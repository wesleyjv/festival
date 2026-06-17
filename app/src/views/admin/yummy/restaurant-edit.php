<?php

use App\Security\Csrf;

$isEdit = $restaurant !== null;
$action = $isEdit ? '/admin/yummy/restaurants/update' : '/admin/yummy/restaurants/create';
$title  = $isEdit ? 'Edit Restaurant' : 'Add New Restaurant';

$v = static function (string $key) use ($restaurant): string {
    return htmlspecialchars((string) ($restaurant[$key] ?? ''), ENT_QUOTES);
};

/** Renders an upload-enabled image field: text input + upload button + dropzone + preview. */
$imageField = static function (string $name, string $label, string $placeholder) use ($v): void {
    $val = $v($name);
    $preview = $val !== '' ? $val : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    ?>
    <div class="mb-3">
        <label class="form-label small fw-semibold" for="<?= $name ?>"><?= htmlspecialchars($label, ENT_QUOTES) ?></label>
        <div class="input-group input-group-sm mb-2">
            <input
                type="text"
                id="<?= $name ?>"
                name="<?= $name ?>"
                class="form-control cms-image-url"
                placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES) ?>"
                value="<?= $val ?>"
            >
            <button type="button" class="btn btn-outline-secondary cms-upload-btn" data-target-input="<?= $name ?>">
                <i class="bi bi-upload me-1"></i>Upload
            </button>
        </div>
        <div class="cms-dropzone mb-2" data-target-input="<?= $name ?>">
            <i class="bi bi-cloud-arrow-up"></i>
            <span>Drag &amp; drop an image here, or click to select a file.</span>
        </div>
        <img
            src="<?= $preview ?>"
            alt=""
            class="border rounded cms-image-preview<?= $val === '' ? ' d-none' : '' ?>"
            style="max-height: 140px; max-width: 100%; object-fit: cover;"
            data-preview-for="<?= $name ?>"
        >
    </div>
    <?php
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

        .cms-dropzone {
            border: 1px dashed #9ca3af;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: #f9fafb;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .cms-dropzone:hover {
            background-color: #eef2ff;
            border-color: #6366f1;
        }

        .cms-dropzone.dragover {
            background-color: #e0f2fe;
            border-color: #0ea5e9;
            color: #0369a1;
        }

        .cms-dropzone i {
            font-size: 1rem;
        }
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
                        class="form-control wysiwyg"
                        rows="5"
                    ><?= $v('about') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Cuisine tags -->
        <div class="card mb-4" style="max-width: 860px;">
            <div class="card-body">
                <p class="section-title">Cuisine tags</p>

                <?php if (empty($cuisineTags)): ?>
                    <p class="text-muted small mb-0">No cuisine tags are available yet.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($cuisineTags as $tag): ?>
                            <?php $tagId = (int) $tag['id']; ?>
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="cuisine_tag_<?= $tagId ?>"
                                    name="cuisine_tags[]"
                                    value="<?= $tagId ?>"
                                    <?= in_array($tagId, $selectedCuisineTagIds, true) ? 'checked' : '' ?>
                                >
                                <label class="form-check-label small" for="cuisine_tag_<?= $tagId ?>">
                                    <?= htmlspecialchars((string) $tag['name'], ENT_QUOTES) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

                <?php $imageField('restaurant_image_path', 'Restaurant image', '/uploads/restaurant.jpg'); ?>

                <?php $imageField('about_image_path', 'About image', '/uploads/about.jpg'); ?>

                <?php $imageField('reservation_image_path', 'Reservation image', '/uploads/reservation.jpg'); ?>

                <?php $imageField('chef_image_path', 'Chef image', '/uploads/chef.jpg'); ?>
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
                        class="form-control wysiwyg"
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

<!-- TinyMCE WYSIWYG editor -->
<script src="https://cdn.tiny.cloud/1/rmqh6zpkull0b6qquqsqfol8clwt2hcni7cikkt0vy5f96ij/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        tinymce.init({
            selector: 'textarea.wysiwyg',
            plugins: 'link lists code image media table',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code',
            menubar: false,
            height: 260,
            images_upload_url: '/admin/upload-image',
            automatic_uploads: true,
            images_upload_credentials: true,
            images_upload_handler: function (blobInfo, success, failure, progress) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/upload-image');
                xhr.withCredentials = true;

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        progress(e.loaded / e.total * 100);
                    }
                };

                xhr.onload = function () {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    let json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    if (!json || typeof json.location !== 'string') {
                        failure('Invalid response: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };

                xhr.onerror = function () {
                    failure('Image upload failed due to a XHR transport error.');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('csrf_token', csrfToken);
                xhr.send(formData);
            }
        });

        // Simple image uploader for the restaurant/about/reservation/chef image fields (button + drag & drop)
        function cmsUploadImage(file, form, targetName, onStart, onDone) {
            if (!file || !form || !targetName) return;

            const data = new FormData();
            data.append('file', file, file.name);
            data.append('csrf_token', csrfToken);

            if (typeof onStart === 'function') {
                onStart();
            }

            fetch('/admin/upload-image', {
                method: 'POST',
                body: data,
                credentials: 'include'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Upload failed with status ' + response.status);
                    }
                    return response.json();
                })
                .then(json => {
                    if (!json || typeof json.location !== 'string') {
                        throw new Error('Invalid response from server');
                    }
                    const input = form.querySelector(`input[name="${targetName}"]`);
                    if (input) {
                        input.value = json.location;
                    }
                    const preview = form.querySelector(`img.cms-image-preview[data-preview-for="${targetName}"]`);
                    if (preview) {
                        preview.src = json.location;
                        preview.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    alert('Image upload failed: ' + err.message);
                })
                .finally(() => {
                    if (typeof onDone === 'function') {
                        onDone();
                    }
                });
        }

        document.querySelectorAll('.cms-upload-btn').forEach(button => {
            button.addEventListener('click', () => {
                const targetName = button.getAttribute('data-target-input');
                if (!targetName) return;

                const form = button.closest('form');
                if (!form) return;

                let fileInput = form.querySelector(`input[type="file"][data-file-for="${targetName}"]`);
                if (!fileInput) {
                    fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.accept = 'image/*';
                    fileInput.classList.add('d-none');
                    fileInput.setAttribute('data-file-for', targetName);
                    form.appendChild(fileInput);
                }

                fileInput.onchange = () => {
                    if (!fileInput.files || !fileInput.files[0]) {
                        return;
                    }

                    const file = fileInput.files[0];

                    cmsUploadImage(
                        file,
                        form,
                        targetName,
                        () => {
                            button.disabled = true;
                            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Uploading...';
                        },
                        () => {
                            button.disabled = false;
                            button.innerHTML = '<i class="bi bi-upload me-1"></i>Upload';
                            fileInput.value = '';
                        }
                    );
                };

                fileInput.click();
            });
        });

        document.querySelectorAll('.cms-dropzone[data-target-input]').forEach(zone => {
            const targetName = zone.getAttribute('data-target-input');
            if (!targetName) return;

            zone.addEventListener('click', () => {
                const form = zone.closest('form');
                if (!form) return;
                const relatedButton = form.querySelector(`.cms-upload-btn[data-target-input="${targetName}"]`);
                if (relatedButton) {
                    relatedButton.click();
                }
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                zone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('dragover');
                });
            });

            ['dragleave', 'dragend', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('dragover');
                });
            });

            zone.addEventListener('drop', e => {
                const files = e.dataTransfer && e.dataTransfer.files;
                if (!files || !files[0]) return;

                const file = files[0];
                const form = zone.closest('form');
                if (!form) return;

                const originalHtml = zone.innerHTML;

                cmsUploadImage(
                    file,
                    form,
                    targetName,
                    () => {
                        zone.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Uploading image...';
                    },
                    () => {
                        zone.innerHTML = originalHtml;
                    }
                );
            });
        });
    })();
</script>
</body>
</html>
