<?php

declare(strict_types=1);

use App\Security\Csrf;

/**
 * @var string $pageKey
 * @var string $pageLabel
 * @var list<array<string, mixed>> $fields
 * @var array<string, string> $content
 * @var bool $isFirstTabPane
 */
$tabId = 'cms-pane-' . preg_replace('/[^a-z0-9\-]/i', '-', $pageKey);
$tabBtnId = 'cms-tab-' . preg_replace('/[^a-z0-9\-]/i', '-', $pageKey);
$fadeClass = $isFirstTabPane ? 'show active' : '';
?>
<div class="tab-pane fade <?= $fadeClass ?>" id="<?= htmlspecialchars($tabId, ENT_QUOTES) ?>" role="tabpanel" aria-labelledby="<?= htmlspecialchars($tabBtnId, ENT_QUOTES) ?>">
    <form method="post" action="/admin/content/save" class="card stat-card mb-3" data-cms-page="<?= htmlspecialchars($pageKey, ENT_QUOTES) ?>">
        <div class="card-body">
            <?= Csrf::field() ?>
            <input type="hidden" name="page" value="<?= htmlspecialchars($pageKey, ENT_QUOTES) ?>">
            <?php foreach ($fields as $field): ?>
                <?php
                $name = (string) ($field['name'] ?? '');
                $label = (string) ($field['label'] ?? $name);
                $type = (string) ($field['type'] ?? 'text');
                $val = (string) ($content[$name] ?? '');
                ?>
                <?php if ($type === 'wysiwyg'): ?>
                    <?php $rows = (int) ($field['rows'] ?? 4); ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><?= htmlspecialchars($label, ENT_QUOTES) ?></label>
                        <textarea name="<?= htmlspecialchars($name, ENT_QUOTES) ?>" class="form-control wysiwyg" rows="<?= max(2, $rows) ?>"><?= htmlspecialchars($val, ENT_QUOTES) ?></textarea>
                    </div>
                <?php elseif ($type === 'image'): ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><?= htmlspecialchars($label, ENT_QUOTES) ?></label>
                        <div class="input-group input-group-sm mb-2">
                            <input
                                type="text"
                                name="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                                class="form-control cms-image-url"
                                value="<?= htmlspecialchars($val, ENT_QUOTES) ?>"
                                placeholder="/uploads/your-image.jpg"
                            >
                            <button
                                type="button"
                                class="btn btn-outline-secondary cms-upload-btn"
                                data-target-input="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                            >
                                <i class="bi bi-upload me-1"></i>Upload
                            </button>
                        </div>
                        <div
                            class="cms-dropzone mb-2"
                            data-target-input="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                        >
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span>Drag &amp; drop an image here, or click to select a file.</span>
                        </div>
                        <img
                            src="<?= htmlspecialchars($val !== '' ? $val : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==', ENT_QUOTES) ?>"
                            alt=""
                            class="border rounded cms-image-preview<?= $val === '' ? ' d-none' : '' ?>"
                            style="max-height: 140px; max-width: 100%; object-fit: cover;"
                            data-preview-for="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                        >
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><?= htmlspecialchars($label, ENT_QUOTES) ?></label>
                        <input type="text" name="<?= htmlspecialchars($name, ENT_QUOTES) ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($val, ENT_QUOTES) ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-save me-1"></i>Save <?= htmlspecialchars($pageLabel, ENT_QUOTES) ?>
                </button>
            </div>
        </div>
    </form>
</div>
