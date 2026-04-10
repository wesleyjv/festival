<?php

use App\Security\Csrf;

$isEdit = $restaurant !== null;
$action = $isEdit ? '/admin/yummy/restaurants/update' : '/admin/yummy/restaurants/create';
$title  = $isEdit ? 'Edit Restaurant' : 'Add New Restaurant';

$v = static function (string $key) use ($restaurant): string {
    return htmlspecialchars((string) ($restaurant[$key] ?? ''), ENT_QUOTES);
};

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
        .section-title { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin-bottom: 1rem; }
    </style>
</head>
<body>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <a href="/admin?events_tab=yummy#events" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i>Back to restaurants
        </a>
        <h1 class="h4 mb-0 mt-1"><?= htmlspecialchars($title, ENT_QUOTES) ?></h1>
    </div>
</div>

<div class="container-fluid px-4 pb-5">

    <?php if (!empty($_GET['yummy_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['yummy_error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $action ?>">
        <?= Csrf::field() ?>

        <?php if ($isEdit): ?>
            <input type="hidden" name="restaurant_id" value="<?= (int) $restaurant['id'] ?>">
        <?php endif; ?>

        <!-- Basic info -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Basic info</p>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="restaurant_name">Restaurant name <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="restaurant_name"
                        name="restaurant_name"
                        class="form-control"
                        required
                        value="<?= $v('restaurant_name') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="address">Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="form-control"
                        value="<?= $v('address') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="short_description">Short description</label>
                    <input
                        type="text"
                        id="short_description"
                        name="short_description"
                        class="form-control"
                        value="<?= $v('short_description') ?>"
                    >
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold" for="about">About</label>
                    <textarea
                        id="about"
                        name="about"
                        class="form-control"
                        rows="5"
                    ><?= $v('about') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Pricing</p>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="adult_price_cents">Adult price (in cents)</label>
                        <input
                            type="number"
                            id="adult_price_cents"
                            name="adult_price_cents"
                            class="form-control"
                            min="0"
                            value="<?= $v('adult_price_cents') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="child_price_cents">Child price (in cents)</label>
                        <input
                            type="number"
                            id="child_price_cents"
                            name="child_price_cents"
                            class="form-control"
                            min="0"
                            value="<?= $v('child_price_cents') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="child_max_age">Child max age</label>
                        <input
                            type="number"
                            id="child_max_age"
                            name="child_max_age"
                            class="form-control"
                            min="0"
                            value="<?= $v('child_max_age') ?>"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacity & sessions -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Capacity &amp; sessions</p>

                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="seats">Seats</label>
                        <input
                            type="number"
                            id="seats"
                            name="seats"
                            class="form-control"
                            min="0"
                            value="<?= $v('seats') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="session_count">Session count</label>
                        <input
                            type="number"
                            id="session_count"
                            name="session_count"
                            class="form-control"
                            min="0"
                            value="<?= $v('session_count') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="session_duration_minutes">Session duration (min)</label>
                        <input
                            type="number"
                            id="session_duration_minutes"
                            name="session_duration_minutes"
                            class="form-control"
                            min="0"
                            value="<?= $v('session_duration_minutes') ?>"
                        >
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="session_one_start_time">Session 1 start time</label>
                        <input
                            type="time"
                            id="session_one_start_time"
                            name="session_one_start_time"
                            class="form-control"
                            value="<?= $v('session_one_start_time') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="session_two_start_time">Session 2 start time</label>
                        <input
                            type="time"
                            id="session_two_start_time"
                            name="session_two_start_time"
                            class="form-control"
                            value="<?= $v('session_two_start_time') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="session_three_start_time">Session 3 start time</label>
                        <input
                            type="time"
                            id="session_three_start_time"
                            name="session_three_start_time"
                            class="form-control"
                            value="<?= $v('session_three_start_time') ?>"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Rating</p>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="rating">Rating (0–5)</label>
                        <input
                            type="number"
                            id="rating"
                            name="rating"
                            class="form-control"
                            min="0"
                            max="5"
                            step="0.1"
                            value="<?= $v('rating') ?>"
                        >
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold" for="review_count">Review count</label>
                        <input
                            type="number"
                            id="review_count"
                            name="review_count"
                            class="form-control"
                            min="0"
                            value="<?= $v('review_count') ?>"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Images</p>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="restaurant_image_path">Restaurant image path</label>
                    <input
                        type="text"
                        id="restaurant_image_path"
                        name="restaurant_image_path"
                        class="form-control"
                        placeholder="/uploads/restaurant.jpg"
                        value="<?= $v('restaurant_image_path') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="about_image_path">About image path</label>
                    <input
                        type="text"
                        id="about_image_path"
                        name="about_image_path"
                        class="form-control"
                        placeholder="/uploads/about.jpg"
                        value="<?= $v('about_image_path') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="reservation_image_path">Reservation image path</label>
                    <input
                        type="text"
                        id="reservation_image_path"
                        name="reservation_image_path"
                        class="form-control"
                        placeholder="/uploads/reservation.jpg"
                        value="<?= $v('reservation_image_path') ?>"
                    >
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold" for="chef_image_path">Chef image path</label>
                    <input
                        type="text"
                        id="chef_image_path"
                        name="chef_image_path"
                        class="form-control"
                        placeholder="/uploads/chef.jpg"
                        value="<?= $v('chef_image_path') ?>"
                    >
                </div>
            </div>
        </div>

        <!-- Chef -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Chef</p>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="chef_name">Chef name</label>
                    <input
                        type="text"
                        id="chef_name"
                        name="chef_name"
                        class="form-control"
                        value="<?= $v('chef_name') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="chef_title">Chef title</label>
                    <input
                        type="text"
                        id="chef_title"
                        name="chef_title"
                        class="form-control"
                        value="<?= $v('chef_title') ?>"
                    >
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold" for="chef_bio">Chef bio</label>
                    <textarea
                        id="chef_bio"
                        name="chef_bio"
                        class="form-control"
                        rows="4"
                    ><?= $v('chef_bio') ?></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2" style="max-width: 860px;">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save me-1"></i><?= $isEdit ? 'Save changes' : 'Create restaurant' ?>
            </button>
            <a href="/admin/yummy/restaurants" class="btn btn-outline-secondary btn-sm">Cancel</a>
        </div>

    </form>

    <!-- Menu items section (edit mode only) -->
    <?php if ($isEdit): ?>
        <div class="card mt-5" id="menu-items" style="max-width: 860px;">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Menu items</span>
                <a
                    href="/admin/yummy/menu-items/edit?restaurant_id=<?= (int) $restaurant['id'] ?>"
                    class="btn btn-primary btn-sm"
                >
                    <i class="bi bi-plus-lg me-1"></i>Add item
                </a>
            </div>

            <?php if (empty($menuItems)): ?>
                <div class="card-body">
                    <p class="text-muted mb-0">No menu items yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menuItems as $item): ?>
                                <tr>
                                    <td class="text-muted"><?= (int) $item['display_order'] ?></td>
                                    <td><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></td>
                                    <td class="text-muted small"><?= htmlspecialchars($item['description'] ?? '—', ENT_QUOTES) ?></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a
                                                href="/admin/yummy/menu-items/edit?restaurant_id=<?= (int) $restaurant['id'] ?>&item_id=<?= (int) $item['id'] ?>"
                                                class="btn btn-outline-secondary btn-sm"
                                            >
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                            <form
                                                method="POST"
                                                action="/admin/yummy/menu-items/delete"
                                                onsubmit="return confirm('Delete this menu item?')"
                                            >
                                                <?= Csrf::field() ?>
                                                <input type="hidden" name="menu_item_id" value="<?= (int) $item['id'] ?>">
                                                <input type="hidden" name="restaurant_id" value="<?= (int) $restaurant['id'] ?>">
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
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
