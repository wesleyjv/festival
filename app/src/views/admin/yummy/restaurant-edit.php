<?php

use App\Security\Csrf;

$isEdit  = $restaurant !== null;
$action  = $isEdit ? '/admin/yummy/restaurants/update' : '/admin/yummy/restaurants/create';
$title   = $isEdit ? 'Edit Restaurant' : 'Add New Restaurant';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $isEdit ? 'Edit Restaurant' : 'Add Restaurant' ?> – Admin</title>
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
        <a href="/admin/yummy/restaurants" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i>Back to restaurants
        </a>
        <h1 class="h4 mb-0 mt-1"><?= $title ?></h1>
    </div>
</div>

<div class="container-fluid px-4">

    <?php if (!empty($_GET['yummy_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['yummy_error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width: 720px;">
        <div class="card-body">

            <form method="POST" action="<?= $action ?>">
                <?= Csrf::field() ?>

                <?php if ($isEdit): ?>
                    <input type="hidden" name="restaurant_id" value="<?= (int) $restaurant['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="restaurant_name">Restaurant name</label>
                    <input
                        type="text"
                        id="restaurant_name"
                        name="restaurant_name"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($restaurant['restaurant_name'] ?? '', ENT_QUOTES) ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($restaurant['description'] ?? '', ENT_QUOTES) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="address">Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="form-control"
                        value="<?= htmlspecialchars($restaurant['address'] ?? '', ENT_QUOTES) ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="price">Price (€)</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="<?= htmlspecialchars($restaurant['price'] !== null ? number_format((float) $restaurant['price'], 2, '.', '') : '', ENT_QUOTES) ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="rating">Rating (0–5)</label>
                    <input
                        type="number"
                        id="rating"
                        name="rating"
                        class="form-control"
                        min="0"
                        max="5"
                        step="0.1"
                        value="<?= htmlspecialchars($restaurant['rating'] !== null ? (string) $restaurant['rating'] : '', ENT_QUOTES) ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="image_path">Image path</label>
                    <input
                        type="text"
                        id="image_path"
                        name="image_path"
                        class="form-control"
                        placeholder="/uploads/your-image.jpg"
                        value="<?= htmlspecialchars($restaurant['image_path'] ?? '', ENT_QUOTES) ?>"
                    >
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i><?= $isEdit ? 'Save changes' : 'Create restaurant' ?>
                    </button>
                    <a href="/admin/yummy/restaurants" class="btn btn-outline-secondary btn-sm">Cancel</a>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
