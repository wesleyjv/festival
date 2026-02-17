<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <!-- Hero -->
    <div class="text-center mb-5">
        <div class="bg-light rounded-4 p-4 p-md-5 shadow-sm">
            <h1 class="fw-bold mb-2">
                <i class="bi bi-ticket-perforated me-2"></i>Available Tickets
            </h1>
            <p class="text-muted mb-0">
                Choose your tickets and add them to your cart to secure your spot.
            </p>
        </div>
    </div>

    <!-- Info bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tickets</h3>
            <p class="text-muted mb-0">
                <?= count($tickets) ?> ticket<?= count($tickets) !== 1 ? 's' : '' ?> available
            </p>
        </div>
        <a href="/events/history" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i>Back to Overview
        </a>
    </div>

    <?php if (empty($tickets)): ?>
        <div class="text-center py-5">
            <i class="bi bi-ticket-perforated text-muted fs-1"></i>
            <h5 class="text-muted mt-3">No tickets available for this event yet</h5>
            <p class="text-muted">Check back soon for upcoming tickets.</p>
            <a href="/events/history" class="btn btn-outline-dark mt-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Overview
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($tickets as $ticket): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">

                            <!-- Ticket name -->
                            <div class="mb-3">
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars($ticket->name) ?></h5>
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle me-1"></i>Available
                                </span>
                            </div>

                            <!-- Price -->
                            <div class="d-flex align-items-center mb-3 flex-grow-1">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center p-2 me-2">
                                    <i class="bi bi-currency-euro"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Price</small>
                                    <span class="fw-semibold">&euro;<?= number_format($ticket->price, 2) ?></span>
                                </div>
                            </div>

                            <!-- Add to cart -->
                            <form action="/cart/add" method="POST">
                                <input type="hidden" name="ticket_id" value="<?= $ticket->id ?>">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>