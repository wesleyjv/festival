<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Available Tickets</h1>
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
            <p class="text-muted fs-5">No tickets available for this event yet.</p>
            <a href="/events/history" class="btn btn-outline-secondary">Back to Overview</a>
        </div>

    <?php else: ?>

        <div class="row g-4">
            <?php foreach ($viewModel->tickets as $ticket): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($ticket->name) ?></h5>
                            <p class="fs-4 mb-4">&euro;<?= number_format($ticket->price, 2) ?></p>

                            <form action="/cart/add" method="POST" class="mt-auto">
                                <input type="hidden" name="ticket_id" value="<?= $ticket->id ?>">
                                <button type="submit" class="btn btn-dark w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>