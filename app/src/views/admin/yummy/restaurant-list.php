<?php

use App\Security\Csrf;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Yummy Restaurants – Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars(Csrf::getToken(), ENT_QUOTES, 'UTF-8') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .page-header { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 16px 24px; margin-bottom: 24px; }
    </style>
</head>
<body>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <a href="/admin?events_tab=yummy#events" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i>Back to restaurants
        </a>
        <h1 class="h4 mb-0 mt-1">Yummy Restaurants</h1>
    </div>
    <a href="/admin/yummy/restaurants/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Add new restaurant
    </a>
</div>

<div class="container-fluid px-4">

    <?php if (!empty($_GET['yummy_notice'])): ?>
        <?php $notice = htmlspecialchars($_GET['yummy_notice'], ENT_QUOTES); ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($notice === 'created'): ?>
                Restaurant created successfully.
            <?php elseif ($notice === 'updated'): ?>
                Restaurant updated successfully.
            <?php elseif ($notice === 'deleted'): ?>
                Restaurant deleted successfully.
            <?php else: ?>
                <?= $notice ?>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['yummy_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['yummy_error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <?php if (empty($restaurants)): ?>
                <p class="text-muted p-4 mb-0">No restaurants found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Adult price</th>
                                <th>Rating</th>
                                <th>Active</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($restaurants as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['restaurant_name'], ENT_QUOTES) ?></td>
                                    <td class="text-muted small"><?= htmlspecialchars($row['address'] ?? '—', ENT_QUOTES) ?></td>
                                    <td>
                                        <?php if ($row['adult_price_cents'] !== null): ?>
                                            €<?= htmlspecialchars(number_format((int) $row['adult_price_cents'] / 100, 2), ENT_QUOTES) ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['rating'] !== null ? htmlspecialchars((string) $row['rating'], ENT_QUOTES) : '—' ?></td>
                                    <td>
                                        <?php if ((int) $row['active'] === 1): ?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2 flex-wrap justify-content-end">

                                            <a
                                                href="/admin/yummy/restaurants/edit?id=<?= (int) $row['id'] ?>"
                                                class="btn btn-outline-secondary btn-sm"
                                            >
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>

                                            <a
                                                href="/admin/yummy/restaurants/edit?id=<?= (int) $row['id'] ?>#menu-items"
                                                class="btn btn-outline-info btn-sm"
                                            >
                                                <i class="bi bi-list-ul me-1"></i>Menu Items
                                            </a>

                                            <form method="POST" action="/admin/yummy/restaurants/<?= (int) $row['id'] ?>/toggle-active">
                                                <?= Csrf::field() ?>
                                                <input type="hidden" name="restaurant_id" value="<?= (int) $row['id'] ?>">
                                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                                    <?= (int) $row['active'] === 1 ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="/admin/yummy/restaurants/<?= (int) $row['id'] ?>/delete"
                                                onsubmit="return confirm('Delete this restaurant? This cannot be undone.')"
                                            >
                                                <?= Csrf::field() ?>
                                                <input type="hidden" name="restaurant_id" value="<?= (int) $row['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash me-1"></i>Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
