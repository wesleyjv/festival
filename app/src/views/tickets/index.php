<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Available Tickets</h1>
            <p class="text-muted mb-0">
                <?= $viewModel->ticketCount ?> ticket<?= $viewModel->ticketCount !== 1 ? 's' : '' ?> available
            </p>
        </div>
        <a href="/events/history" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <?php if ($viewModel->ticketCount === 0): ?>

        <div class="text-center py-5">
            <i class="bi bi-ticket text-muted" style="font-size:3rem"></i>
            <p class="text-muted fs-5 mt-3 mb-4">No tickets available for this event yet.</p>
            <a href="/events/history" class="btn btn-outline-secondary">Back to Overview</a>
        </div>

    <?php else: ?>

        <div class="row g-4">
            <?php foreach ($viewModel->tickets as $ticket): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2">
                                    <i class="bi bi-ticket-perforated me-1"></i>Ticket
                                </span>
                            </div>

                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($ticket->name) ?></h5>

                            <p class="fw-bold fs-3 mb-0 mt-auto pt-3">
                                &euro;<?= number_format($ticket->price, 2) ?>
                            </p>
                            <p class="text-muted small mb-4">per person</p>

                            <form action="/cart/add" method="POST" class="js-cart-add-form">
                                <?= \App\Security\Csrf::field() ?>
                                <input type="hidden" name="ticket_id" value="<?= $ticket->id ?>">
                                <button type="submit" class="btn btn-dark w-100 rounded-3">
                                    <i class="bi bi-bag-plus me-1"></i>Add to Cart
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