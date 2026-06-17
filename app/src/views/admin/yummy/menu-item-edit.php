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
                        class="form-control wysiwyg"
                        rows="3"
                    ><?= $v('description') ?></textarea>
                </div>

                <?php
                $imagePathVal = $v('image_path');
                $imagePathPreview = $imagePathVal !== '' ? $imagePathVal : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
                ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="image_path">Image</label>
                    <div class="input-group input-group-sm mb-2">
                        <input
                            type="text"
                            id="image_path"
                            name="image_path"
                            class="form-control cms-image-url"
                            placeholder="/uploads/menu-item.jpg"
                            value="<?= $imagePathVal ?>"
                        >
                        <button type="button" class="btn btn-outline-secondary cms-upload-btn" data-target-input="image_path">
                            <i class="bi bi-upload me-1"></i>Upload
                        </button>
                    </div>
                    <div class="cms-dropzone mb-2" data-target-input="image_path">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Drag &amp; drop an image here, or click to select a file.</span>
                    </div>
                    <img
                        src="<?= $imagePathPreview ?>"
                        alt=""
                        class="border rounded cms-image-preview<?= $imagePathVal === '' ? ' d-none' : '' ?>"
                        style="max-height: 140px; max-width: 100%; object-fit: cover;"
                        data-preview-for="image_path"
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

        // Simple image uploader for the menu item image field (button + drag & drop)
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
