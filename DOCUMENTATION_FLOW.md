# Festival Project: Functional Flow & Architecture Documentation

This document provides a comprehensive overview of the technical implementation and user flow for the History Event section, Cart system, Stripe Checkout integration, and the Administrative CMS.

---

## 1. History Events & Ticket Ordering Flow

### **The Overview Page**
- **Controller:** `EventsController::history()`
- **View:** `app/views/events/history/overview.php`
- **Logic:** The controller fetches all history-specific events via the `EventRepository`. The view displays these events along with content (titles, descriptions, images) managed through the CMS.

### **Ordering Tickets (The Ad-Hoc System)**
Unlike Jazz events which often use pre-defined database IDs, the History section uses an "Ad-Hoc" ticket system to handle various tour slots, languages, and dates dynamically.
- **Form Submission:** Users select a date, time, and language on the history order page.
- **Cart Entry point:** `CartController::add()`
- **Validation (Capacity Check):** 
    - Inside `CartController`, the `validateHistoryCapacity()` helper method is called.
    - It queries the `TicketRepository::countSoldHistoryTickets()` to ensure that the 12-person limit per tour isn't exceeded.
    - If a "Family Ticket" is selected, it counts as 4 spots.
- **Synthetic Tickets:** Since these tickets aren't always pre-generated in a `tickets` table, the controller creates a "Synthetic" `Ticket` model with `id = 0` and populates it with the posted metadata (date, time, language).

---

## 2. Shopping Cart System

### **Cart Storage**
- **Model:** `ShoppingCart` and `CartItem`
- **Storage:** The cart is stored in the PHP `$_SESSION['cart']`. This allows guest users to build a cart before logging in to checkout.
- **Functionality:**
    - `addItem()`: Adds a ticket or increases the quantity if the same ticket (same ID or same metadata) already exists.
    - `calculateTotal()`: Iterates through items to provide a grand total.

### **Management**
- **Controller:** `CartController`
- **Methods:**
    - `index()`: Displays `app/views/tickets/cart.php`.
    - `remove()`: Removes an item by its array index and re-indexes the session array.

---

## 3. Checkout & Stripe Integration

### **Checkout Initiation**
- **Controller:** `OrderController::checkout()`
- **Requirement:** Users must be logged in. If not, they are redirected to `/login`.
- **Flow:** Displays the cart summary and available payment methods (defined in `App\Enums\PaymentMethod`).

### **Stripe Payment Flow**
1.  **Placement:** `OrderController::placeOrder()` validates the request and CSRF token.
2.  **Session Creation:** It calls `StripeService::createCheckoutSession()`. 
    - This converts our `ShoppingCart` items into Stripe-formatted `line_items`.
    - It passes a `success_url` pointing to `/checkout/complete?session_id={CHECKOUT_SESSION_ID}`.
3.  **Redirection:** The user is redirected to the secure Stripe-hosted checkout page.
4.  **Verification:** Upon successful payment, Stripe redirects back to `OrderController::completeCheckout()`.
    - `verifyStripePayment()`: Retrieves the session from Stripe's API to confirm the `payment_status` is actually `'paid'`.

---

## 4. Order Finalization & Fulfillment

### **Database Persistence**
- **Service:** `OrderService::createOrderFromCart()`
- **Process:** 
    - Generates a unique order number (e.g., `ORD-ABC123`).
    - Maps `CartItem` objects to database rows in the `orders` and `order_items` tables.
    - Clears the `$_SESSION['cart']`.

### **Ticket & Invoice Delivery**
- **PDF Generation:** 
    - `TicketPdfService` generates a branded PDF with ticket codes and event details.
    - `InvoicePdfService` generates a formal invoice PDF including VAT calculations (9%) and itemized costs.
- **Emailing:** `MailService` has been updated to support multiple attachments. It sends both the Ticket PDF and the Invoice PDF to the user's registered email address upon successful payment.

---

## 5. Administrative CMS

### **Content Management**
- **Controller:** `AdminController::dashboard()`
- **Service:** `ContentService`
- **Functionality:** 
    - The `ContentService` reads/writes from a JSON-based or Table-based storage (depending on implementation) to update text, headers, and images across the site.
    - **History CMS:** Admins can change the "History" landing page text and images without touching code.

### **Event Management**
- **Storytelling/History Events:** `AdminController::createStoryEvent()` and `updateStoryEvent()` allow admins to manage the schedule.
- **Repositories:** `StoryEventRepository` handles the CRUD operations.

### **Order & User Management**
- **Users:** Admins can search, filter, edit roles (Customer, Employee, Admin), or delete users.
- **Orders:** The dashboard displays a list of all successful transactions. Admins can view order details or trigger `OrderController::emailTickets()` to re-send lost tickets to a customer.

---

## 6. Security & Validations

- **CSRF Protection:** Every POST request (Cart add, Login, CMS save) is protected by a CSRF token validated via `App\Security\Csrf`.
- **Authentication Guards:** Helper methods like `requireAuth()` and `requireAdmin()` ensure that sensitive routes are only accessible to permitted users.
---

## 7. Code Review & Technical Assessment Preparation

This section outlines a logical path for walking through the codebase during an assessment, focusing on architectural challenges and the problem-solving strategies employed.

### **Logical Review Order**
1.  **`CartController.php`**: The entry point for user intent (adding tickets).
2.  **`OrderController.php`**: The orchestration layer for payments and fulfillment.
3.  **`OrderService.php` & `StripeService.php`**: The business logic and external integration layer.
4.  **`MailService.php` & `InvoicePdfService.php`**: Supporting infrastructure for user communication.

### **Technical Challenges & Solutions**

#### **Challenge 1: Controller Bloat & "God Methods"**
- **Problem:** Initial methods like `add()` and `completeCheckout()` were exceeding 80+ lines, making them hard to read and test.
- **Solution:** Applied the **Extract Method** refactoring pattern. By moving validation, ad-hoc ticket creation, and payment verification into private helper methods, the primary entry points now serve as clean "orchestrators" of logic rather than implementers of every detail.

#### **Challenge 2: Handling "Ad-Hoc" vs. Standard Tickets**
- **Problem:** History tours are dynamic (multiple languages/times) and don't always have pre-generated IDs in the database.
- **Solution:** Implemented a **Synthetic Ticket Model** approach. The `CartController` recognizes when an ID is missing and creates a temporary Ticket object populated with metadata. This allows the `ShoppingCart` and `StripeService` to treat history tours and jazz concerts as uniform entities.

#### **Challenge 3: Real-time Capacity Guardrails**
- **Problem:** Preventing overbooking on history tours (max 12 people) especially when "Family Tickets" count as 4 spots.
- **Solution:** Integrated a **Pre-Add Validation** hook. Before an item reaches the session cart, the controller calls `validateHistoryCapacity()`, which interacts with the `TicketRepository` to perform atomic calculations. This ensures the database integrity is protected before the user even enters the checkout flow.

#### **Challenge 4: Extensible Multi-Document Fulfillment**
- **Problem:** The original `MailService` was limited to one attachment, but business requirements evolved to require both a Ticket PDF and a VAT Invoice.
- **Solution:** Refactored the `MailService` to accept a **Polymorphic Attachment Array**. This moved the service from a rigid "one-file" utility to a flexible communication tool that can handle any number of generated documents without changing its internal signature repeatedly.

#### **Challenge 5: Secure Payment Verification (Stripe)**
- **Problem:** Trusting a simple redirect from a payment provider is a security risk.
- **Solution:** Implemented a **Server-Side Verification Loop**. The `completeCheckout` method does not assume success based on the URL; it uses the `StripeService` to fetch the session directly from Stripe's API and verifies the `payment_status` server-to-server before creating the order in the database.

---

## 8. End-to-End Flow: History Overview to Checkout Confirmation

This section details the chronological journey of a user from browsing history tours to receiving their order confirmation.

### **Step 1: The Overview Page**
- **Action:** User visits `/events/history`.
- **Controller Method:** `EventsController::history()`
- **Logic:** Fetches history-specific content and events via `EventRepository::getHistoryEvents()`.
- **Outcome:** Displays the list of available tours to the user.

### **Step 2: The Booking Form**
- **Action:** User selects a tour and clicks to book, navigating to `/events/history/order`.
- **Controller Method:** `TicketController::historyTickets()`
- **Outcome:** Renders the dynamic booking form where users select dates, times, and languages.

### **Step 3: Adding to Cart**
- **Action:** User submits the booking form to `/cart/add`.
- **Controller Method:** `CartController::add()` -> `handleAdHocTicket()`
- **Logic:** 
    - Validates capacity via `TicketRepository::countSoldHistoryTickets()`.
    - Creates a **Synthetic Ticket** object (ID = 0) populated with metadata from the POST request.
    - Adds the ticket to the `ShoppingCart` session.
- **Outcome:** User is redirected to the checkout page.

### **Step 4: Checkout Overview**
- **Action:** User lands on `/checkout`.
- **Controller Method:** `OrderController::checkout()`
- **Logic:** `CheckoutViewModel` prepares the cart summary and available payment methods.
- **Outcome:** User reviews their order before proceeding to payment.

### **Step 5: Payment Redirection**
- **Action:** User clicks "Place Order" (submits to `/checkout/place-order`).
- **Controller Method:** `OrderController::placeOrder()`
- **Logic:** Calls `StripeService::createCheckoutSession()`, which maps cart items to Stripe line items and generates a unique checkout URL.
- **Outcome:** User is redirected to the secure Stripe Checkout page.

### **Step 6: Completion & Fulfillment**
- **Action:** After payment, Stripe redirects back to `/checkout/complete?session_id=...`.
- **Controller Method:** `OrderController::completeCheckout()`
- **Logic:** 
    - Verifies payment status via `StripeService::retrieveSession()`.
    - Finalizes the transaction via `OrderService::createOrderFromCart()`, which persists the order and creates permanent ticket rows in the `tickets` table.
    - Triggers `MailService::sendWithAttachment()` to email the generated Ticket and Invoice PDFs.
- **Outcome:** Session cart is cleared, and user is redirected to the confirmation page.

### **Step 7: Order Confirmation**
- **Action:** User lands on `/checkout/confirmation`.
- **Controller Method:** `OrderController::confirmation()`
- **Outcome:** Displays a final success message with the order number and total amount.

