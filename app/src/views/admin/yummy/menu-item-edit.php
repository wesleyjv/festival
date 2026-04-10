<?php

use App\Security\Csrf;

$isEdit = $menuItem !== null;
$title  = $isEdit ? 'Edit Menu Item' : 'Add Menu Item';

$v = static function (string $key) use ($menuItem): string {
    return htmlspecialchars((string) ($menuItem[$key] ?? ''), ENT_QUOTES);
};

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $isEdit ? 'Edit Menu Item' : 'Add Menu Item' ?> – Admin</title>
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
        <a
            href="/admin/yummy/restaurants/edit?id=<?= (int) $restaurantId ?>#menu-items"
            class="text-decoration-none text-muted small"
        >
            <i class="bi bi-arrow-left me-1"></i>Back to restaurant
        </a>
        <h1 class="h4 mb-0 mt-1"><?= htmlspecialchars($title, ENT_QUOTES) ?></h1>
    </div>
</div>

<div class="container-fluid px-4">

    <?php if (!empty($_GET['yummy_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['yummy_error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width: 620px;">
        <div class="card-body">

            <form method="POST" action="/admin/yummy/menu-items/save">
                <?= Csrf::field() ?>
                <input type="hidden" name="restaurant_id" value="<?= (int) $restaurantId ?>">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="item_id" value="<?= (int) $menuItem['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="name">Name <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        required
                        value="<?= $v('name') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="3"
                    ><?= $v('description') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="image_path">Image path</label>
                    <input
                        type="text"
                        id="image_path"
                        name="image_path"
                        class="form-control"
                        placeholder="/uploads/menu-item.jpg"
                        value="<?= $v('image_path') ?>"
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold" for="display_order">Display order</label>
                    <input
                        type="number"
                        id="display_order"
                        name="display_order"
                        class="form-control"
                        min="0"
                        value="<?= $v('display_order') ?>"
                    >
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i><?= $isEdit ? 'Save changes' : 'Add item' ?>
                    </button>
                    <a
                        href="/admin/yummy/restaurants/edit?id=<?= (int) $restaurantId ?>#menu-items"
                        class="btn btn-outline-secondary btn-sm"
                    >Cancel</a>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
