<?php require __DIR__ . '/../../partials/header.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

    :root {
        --bg-dark: #1f1813;
        --card-bg: #f8f5eb;
        --text-dark: #2a1f17;
        --text-muted: #827566;
        --primary-brown: #36291e;
        --border-color: #d8cbbc;
        --gold-accent: #c39a3b;
        --btn-gold: #bfa374;
    }

    body {
        background-color: var(--bg-dark);
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    .booking-wrapper {
        background-color: var(--bg-dark);
        background-image: radial-gradient(circle at bottom left, rgba(195, 154, 59, 0.05) 0%, transparent 40%),
                          radial-gradient(circle at bottom right, rgba(195, 154, 59, 0.05) 0%, transparent 40%);
        min-height: 100vh;
        padding: 60px 0 100px;
    }

    /* --- Header Titles --- */
    .booking-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .booking-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .booking-subtitle {
        font-size: 1.1rem;
        font-style: italic;
        color: #d1c8bd;
        margin-bottom: 8px;
    }
    .booking-meta {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* --- Layout Container --- */
    .booking-container {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }
    @media (max-width: 991px) {
        .booking-container {
            flex-direction: column;
        }
    }
    .selection-col {
        flex: 1.5;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .summary-col {
        flex: 1;
        position: sticky;
        top: 30px;
    }

    /* --- Selection Cards --- */
    .panel {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 25px 30px;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    /* Options Grids */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    
    @media (max-width: 768px) {
        .grid-4, .grid-3 { grid-template-columns: 1fr 1fr; }
        .grid-2 { grid-template-columns: 1fr; }
    }

    /* Toggle Buttons */
    .btn-toggle {
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 6px;
        padding: 12px 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-toggle:hover {
        border-color: var(--gold-accent);
    }
    .btn-toggle.active {
        background-color: var(--primary-brown);
        color: #fff;
        border-color: var(--primary-brown);
    }

    /* Ticket Type Buttons */
    .btn-ticket {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px 20px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-ticket.active {
        background-color: var(--primary-brown);
        color: #fff;
        border-color: var(--gold-accent);
        box-shadow: 0 4px 15px rgba(195, 154, 59, 0.2);
    }
    .t-type { font-size: 0.95rem; font-weight: 600; margin-bottom: 8px; }
    .t-price { font-size: 1.6rem; font-weight: 800; margin-bottom: 5px; }
    .t-desc { font-size: 0.75rem; color: var(--text-muted); }
    .btn-ticket.active .t-desc { color: #d1c8bd; }

    /* Style labels when the preceding radio is checked (no-JS selection state) */
    input[type="radio"][name="ticket"]:checked + label.btn-ticket,
    input[type="radio"][name="date"]:checked + label.btn-toggle,
    input[type="radio"][name="time"]:checked + label.btn-toggle,
    input[type="radio"][name="language"]:checked + label.btn-toggle {
        background-color: var(--primary-brown);
        color: #fff;
        border-color: var(--gold-accent);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    input[type="radio"][name="ticket"]:checked + label .t-desc,
    input[type="radio"][name="ticket"]:checked + label .t-type,
    input[type="radio"][name="ticket"]:checked + label .t-price {
        color: #fff;
    }

    /* --- Summary Card --- */
    .summary-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 35px;
        color: var(--text-dark);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .summary-title {
        font-size: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-align: center;
        margin-bottom: 30px;
    }
    .selected-ticket-box {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
    }
    .st-label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; }
    .st-name { font-size: 1.1rem; font-weight: 700; margin-bottom: 5px; }
    .st-price { font-size: 1.1rem; font-weight: 700; color: var(--gold-accent); }

    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0 0 25px 0;
    }
    .summary-list li {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-size: 0.9rem;
    }
    .summary-list li span:first-child { color: var(--text-muted); }
    .summary-list li span:last-child { font-weight: 600; }

    /* Quantity Controls */
    .qty-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .qty-btn {
        background: #fff;
        border: 1px solid var(--border-color);
        width: 30px;
        height: 30px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: bold;
        color: var(--text-dark);
        transition: border-color 0.2s;
    }
    .qty-btn:hover { border-color: var(--text-dark); }
    .qty-val { font-weight: 700; font-size: 1rem; color: var(--text-dark); width: 20px; text-align: center; }

    /* Total Box */
    .total-box {
        background-color: var(--primary-brown);
        border-radius: 8px;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        margin-bottom: 20px;
    }
    .total-label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #d1c8bd; }
    .total-val { font-size: 1.8rem; font-weight: 800; }

    /* Complete Button */
    .btn-complete {
        width: 100%;
        background-color: var(--btn-gold);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 18px;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background-color 0.3s;
        box-shadow: 0 4px 15px rgba(191, 163, 116, 0.3);
    }
    .btn-complete:hover {
        background-color: #a88d60;
    }
    .summary-note {
        text-align: center;
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 15px;
    }
</style>

<div class="booking-wrapper">
    <div class="container">
        
        <header class="booking-header">
            <h1 class="booking-title">A Stroll through History</h1>
            <p class="booking-subtitle">Guided Historical Walking Tour</p>
            <p class="booking-meta">LAST WEEKEND OF JULY</p>
        </header>

        <form action="/cart/add" method="POST" class="booking-container">
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