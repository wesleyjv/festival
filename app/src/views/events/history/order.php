<?php 
$extraStylesheets = ['/css/history/history-order.css'];
require __DIR__ . '/../../partials/header.php'; 
?>

<div class="booking-wrapper">
    <div class="container">
        
        <header class="booking-header">
            <h1 class="booking-title">A Stroll through History</h1>
            <p class="booking-subtitle">Guided Historical Walking Tour</p>
            <p class="booking-meta">LAST WEEKEND OF JULY</p>
            
            <?php if (isset($_SESSION['cart_error'])): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= htmlspecialchars($_SESSION['cart_error']) ?>
                    <?php unset($_SESSION['cart_error']); ?>
                </div>
            <?php endif; ?>
        </header>

        <form action="/cart/add" method="POST" class="booking-container js-cart-add-form">
            <?= \App\Security\Csrf::field() ?>
            <!-- Left Column: Selections -->
            <div class="selection-col">

                <!-- Ticket Type -->
                <div class="panel">
                    <h3 class="panel-title">Select Ticket Type</h3>
                    <div class="grid-2">
                        <input type="radio" id="ticket-adm" name="ticket" value="Admission Ticket|17.50" checked style="display:none">
                        <label for="ticket-adm" class="btn-ticket">
                            <span class="t-type">Admission Ticket</span>
                            <span class="t-price">&euro;17.50</span>
                            <span class="t-desc">Per person</span>
                        </label>

                        <input type="radio" id="ticket-family" name="ticket" value="Family Ticket|60.00" style="display:none">
                        <label for="ticket-family" class="btn-ticket">
                            <span class="t-type">Family Ticket</span>
                            <span class="t-price">&euro;60.00</span>
                            <span class="t-desc">Up to 4 persons</span>
                        </label>
                    </div>
                </div>

                <!-- Date -->
                <div class="panel">
                    <h3 class="panel-title">Select Date</h3>
                    <div class="grid-4">
                        <input type="radio" id="date-thu" name="date" value="Thursday" checked style="display:none">
                        <label for="date-thu" class="btn-toggle">Thursday</label>
                        <input type="radio" id="date-fri" name="date" value="Friday" style="display:none">
                        <label for="date-fri" class="btn-toggle">Friday</label>
                        <input type="radio" id="date-sat" name="date" value="Saturday" style="display:none">
                        <label for="date-sat" class="btn-toggle">Saturday</label>
                        <input type="radio" id="date-sun" name="date" value="Sunday" style="display:none">
                        <label for="date-sun" class="btn-toggle">Sunday</label>
                    </div>
                </div>

                <!-- Time -->
                <div class="panel">
                    <h3 class="panel-title">Departure Time</h3>
                    <div class="grid-3">
                        <input type="radio" id="time-10" name="time" value="10:00" checked style="display:none">
                        <label for="time-10" class="btn-toggle">10:00</label>
                        <input type="radio" id="time-13" name="time" value="13:00" style="display:none">
                        <label for="time-13" class="btn-toggle">13:00</label>
                        <input type="radio" id="time-16" name="time" value="16:00" style="display:none">
                        <label for="time-16" class="btn-toggle">16:00</label>
                    </div>
                </div>

                <!-- Language -->
                <div class="panel">
                    <h3 class="panel-title">Language</h3>
                    <div class="grid-4">
                        <input type="radio" id="lang-dutch" name="language" value="Dutch" checked style="display:none">
                        <label for="lang-dutch" class="btn-toggle">Dutch</label>
                        <input type="radio" id="lang-english" name="language" value="English" style="display:none">
                        <label for="lang-english" class="btn-toggle">English</label>
                        <input type="radio" id="lang-mandarin" name="language" value="Mandarin" style="display:none">
                        <label for="lang-mandarin" class="btn-toggle">Mandarin</label>
                        <input type="radio" id="lang-chinese" name="language" value="Chinese" style="display:none">
                        <label for="lang-chinese" class="btn-toggle">Chinese</label>
                    </div>
                </div>

            </div>

            <!-- Right Column: Summary -->
            <div class="summary-col">
                <div class="summary-card">
                    <h2 class="summary-title">Booking Summary</h2>

                    <div class="selected-ticket-box">
                        <div class="st-label">Ticket Type</div>
                        <div class="st-name" id="sum-name">Admission Ticket</div>
                        <div class="st-price" id="sum-price">&euro; 17.50</div>
                    </div>

                    <ul class="summary-list">
                        <li><span>Day</span> <span id="sum-date">Thursday</span></li>
                        <li><span>Time</span> <span id="sum-time">10:00</span></li>
                        <li><span>Language</span> <span id="sum-lang">Dutch</span></li>
                    </ul>

                    <div class="qty-row">
                        <span>Quantity</span>
                        <div class="qty-controls">
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="qty-val" style="width:60px;text-align:center;padding:6px;border-radius:4px;border:1px solid var(--border-color);">
                        </div>
                    </div>

                    <div class="total-box">
                        <span class="total-label">Total Amount</span>
                        <span class="total-val" id="sum-total">&euro;17.50</span>
                    </div>

                    <input type="hidden" name="redirect" value="checkout">
                    <button type="submit" class="btn-complete">✦ Complete Booking ✦</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Simple script to keep the booking summary in sync with the form selections

document.addEventListener('DOMContentLoaded', function() {
    // Root form container and quantity input
    const form = document.querySelector('.booking-container');
    const quantityInput = document.getElementById('quantity');
    
    // Summary elements in the sidebar that will be updated
    const sumName = document.getElementById('sum-name');   // ticket name
    const sumPrice = document.getElementById('sum-price'); // single-ticket price text
    const sumDate = document.getElementById('sum-date');   // selected day
    const sumTime = document.getElementById('sum-time');   // selected time
    const sumLang = document.getElementById('sum-lang');   // selected language
    const sumTotal = document.getElementById('sum-total'); // total amount

    // Update the summary box based on current form state
    function updateSummary() {
        // Get selected ticket info (value format: "Name|Price")
        const selectedTicket = form.querySelector('input[name="ticket"]:checked');
        if (selectedTicket) {
            const [name, price] = selectedTicket.value.split('|'); // split into name and numeric price
            sumName.textContent = name;
            sumPrice.textContent = '€ ' + parseFloat(price).toFixed(2);
            
            // Calculate and display total = price * quantity
            const quantity = parseInt(quantityInput.value) || 1;
            const total = parseFloat(price) * quantity;
            sumTotal.textContent = '€' + total.toFixed(2);
        }

        // Update day, time and language fields from the checked radio inputs
        const selectedDate = form.querySelector('input[name="date"]:checked');
        if (selectedDate) sumDate.textContent = selectedDate.value;

        const selectedTime = form.querySelector('input[name="time"]:checked');
        if (selectedTime) sumTime.textContent = selectedTime.value;

        const selectedLang = form.querySelector('input[name="language"]:checked');
        if (selectedLang) sumLang.textContent = selectedLang.value;
    }

    // Attach listeners to radios and the quantity input so changes update the summary
    form.querySelectorAll('input[type="radio"], #quantity').forEach(input => {
        input.addEventListener('change', updateSummary); // radio/number change
        // For immediate feedback while typing/changing number, listen to 'input' too
        if (input.id === 'quantity') {
            input.addEventListener('input', updateSummary);
        }
    });

    // Run once on load to populate the summary with default selections
    updateSummary();
});
</script>

<?php require __DIR__ . '/../../partials/footer.php'; ?>