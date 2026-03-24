<?php
/** @var array<string,string> $jazzContent */
/** @var \App\Models\JazzEvent[] $jazzCmsArtists */
/** @var array<int, array<string,string>> $jazzArtistContents */
/** @var string $jazzArtistError */
/** @var string $jazzArtistNotice */
$jazzArtistError = $jazzArtistError ?? '';
$jazzArtistNotice = $jazzArtistNotice ?? '';
?>

<div class="tab-pane fade" id="content-jazz" role="tabpanel" aria-labelledby="tab-jazz">

    <p class="text-muted small mb-3">

        Edit the Jazz homepage and artist detail pages visually. Drag images onto the highlighted areas or click to upload.

        Text is edited in place; click <strong>Save</strong> when you are done.

    </p>



    <ul class="nav nav-pills mb-3 gap-2" role="tablist">

        <li class="nav-item" role="presentation">

            <button class="nav-link active" id="jazz-cms-tab-home" data-bs-toggle="tab" data-bs-target="#jazz-cms-pane-home" type="button" role="tab">

                Jazz homepage

            </button>

        </li>

        <li class="nav-item" role="presentation">

            <button class="nav-link" id="jazz-cms-tab-artist" data-bs-toggle="tab" data-bs-target="#jazz-cms-pane-artist" type="button" role="tab">

                Artist detail pages

            </button>

        </li>

    </ul>



    <div class="tab-content">

        <!-- Homepage -->

        <div class="tab-pane fade show active" id="jazz-cms-pane-home" role="tabpanel">

            <form method="post" action="/admin/content/save" class="mb-0" id="jazz-cms-form-home">

                <input type="hidden" name="page" value="jazz">

                <div id="jazz-cms-home-hidden-fields"></div>



                <div class="jazz-cms-visual mb-3">

                    <div class="jazz-cms-visual__toolbar">

                        <i class="bi bi-eye"></i> Homepage preview (hero &amp; intro)

                    </div>

                    <div class="jazz-cms-block jazz-cms-block--hero-dark">

                        <div class="jazz-cms-label jazz-cms-label--on-dark">Hero title — click to edit</div>

                        <div
                            class="jazz-cms-editable jazz-cms-hero-title"
                            contenteditable="true"
                            data-cms-field="hero_title"
                        ><?= nl2br(htmlspecialchars($jazzContent['hero_title'] ?? '', ENT_QUOTES)) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Hero background image</div>

                        <div

                            class="cms-dropzone"

                            data-cms-image="hero_background_image"

                            data-upload-url="/admin/upload-image"

                        >

                            <?php $hb = $jazzContent['hero_background_image'] ?? ''; ?>

                            <?php if ($hb): ?>

                                <img src="<?= htmlspecialchars($hb, ENT_QUOTES) ?>" alt="" class="cms-preview-img">

                            <?php else: ?>

                                <span class="text-muted small">Drop image here or click</span>

                            <?php endif; ?>

                            <div class="cms-dropzone-hint">Drop or click</div>

                            <input type="hidden" name="hero_background_image" value="<?= htmlspecialchars($hb, ENT_QUOTES) ?>">

                        </div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Intro heading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="intro_heading"><?= htmlspecialchars($jazzContent['intro_heading'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Intro subheading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="intro_sub"><?= htmlspecialchars($jazzContent['intro_sub'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Intro body (HTML allowed)</div>

                        <div

                            class="jazz-cms-editable jazz-cms-rich"

                            contenteditable="true"

                            data-cms-field="intro_text"

                            data-cms-rich="1"

                        ><?= $jazzContent['intro_text'] ?? '' ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Intro side image</div>

                        <div

                            class="cms-dropzone"

                            data-cms-image="intro_image"

                            data-upload-url="/admin/upload-image"

                        >

                            <?php $ii = $jazzContent['intro_image'] ?? ''; ?>

                            <?php if ($ii): ?>

                                <img src="<?= htmlspecialchars($ii, ENT_QUOTES) ?>" alt="" class="cms-preview-img">

                            <?php else: ?>

                                <span class="text-muted small">Drop image here or click</span>

                            <?php endif; ?>

                            <div class="cms-dropzone-hint">Drop or click</div>

                            <input type="hidden" name="intro_image" value="<?= htmlspecialchars($ii, ENT_QUOTES) ?>">

                        </div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Artists section heading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="artists_heading"><?= htmlspecialchars($jazzContent['artists_heading'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Artists section subheading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="artists_sub"><?= htmlspecialchars($jazzContent['artists_sub'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Locations heading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="locations_heading"><?= htmlspecialchars($jazzContent['locations_heading'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Locations subheading</div>

                        <div class="jazz-cms-editable" contenteditable="true" data-cms-field="locations_sub"><?= htmlspecialchars($jazzContent['locations_sub'] ?? '', ENT_QUOTES) ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Locations list (HTML)</div>

                        <div

                            class="jazz-cms-editable jazz-cms-rich"

                            contenteditable="true"

                            data-cms-field="locations_text"

                            data-cms-rich="1"

                        ><?= $jazzContent['locations_text'] ?? '' ?></div>

                    </div>

                    <div class="jazz-cms-block">

                        <div class="jazz-cms-label">Locations map image</div>

                        <div

                            class="cms-dropzone"

                            data-cms-image="locations_image"

                            data-upload-url="/admin/upload-image"

                        >

                            <?php $li = $jazzContent['locations_image'] ?? ''; ?>

                            <?php if ($li): ?>

                                <img src="<?= htmlspecialchars($li, ENT_QUOTES) ?>" alt="" class="cms-preview-img">

                            <?php else: ?>

                                <span class="text-muted small">Drop image here or click</span>

                            <?php endif; ?>

                            <div class="cms-dropzone-hint">Drop or click</div>

                            <input type="hidden" name="locations_image" value="<?= htmlspecialchars($li, ENT_QUOTES) ?>">

                        </div>

                    </div>

                </div>

                <div class="text-end">

                    <button type="submit" class="btn btn-primary btn-sm">

                        <i class="bi bi-save me-1"></i>Save Jazz homepage

                    </button>

                </div>

            </form>

        </div>



        <!-- Artist detail pages -->

        <div class="tab-pane fade" id="jazz-cms-pane-artist" role="tabpanel">

            <?php if ($jazzArtistError !== ''): ?>
                <div class="alert alert-danger py-2 small mb-3"><?= htmlspecialchars($jazzArtistError, ENT_QUOTES) ?></div>
            <?php endif; ?>
            <?php if ($jazzArtistNotice !== ''): ?>
                <?php
                if ($jazzArtistNotice === 'created') {
                    $jazzNoticeText = 'Artist created. Choose them in the list below to edit CMS content.';
                } elseif ($jazzArtistNotice === 'deleted') {
                    $jazzNoticeText = 'Artist removed from the database.';
                } else {
                    $jazzNoticeText = $jazzArtistNotice;
                }
                ?>
                <div class="alert alert-success py-2 small mb-3"><?= htmlspecialchars($jazzNoticeText, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h3 class="h6 mb-2">Add jazz artist</h3>
                    <p class="small text-muted mb-3">Creates database rows and a public page at <code>/events/jazz/&lt;id&gt;</code>.</p>
                    <form method="post" action="/admin/jazz/artists/create" class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small mb-0">Artist name *</label>
                            <input type="text" name="artist" class="form-control form-control-sm" required maxlength="255">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small mb-0">Description *</label>
                            <input type="text" name="description" class="form-control form-control-sm" required maxlength="2000" placeholder="Shown on cards and default bio">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-0">Styles</label>
                            <input type="text" name="style" class="form-control form-control-sm" placeholder="e.g. Fusion, Swing">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-0">Venue</label>
                            <input type="text" name="location" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-0">Start</label>
                            <input type="datetime-local" name="start_time" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-0">End</label>
                            <input type="datetime-local" name="end_time" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-0">Price (€)</label>
                            <input type="number" name="price" class="form-control form-control-sm" step="0.01" min="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-0">Seats</label>
                            <input type="number" name="seats" class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-plus-lg me-1"></i>Create artist</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($jazzCmsArtists)): ?>

                <div class="alert alert-light border small">No jazz artists yet. Use the form above to add one.</div>

            <?php else: ?>

                <div class="row g-2 align-items-end mb-3">

                    <div class="col-md-6">

                        <label class="form-label small mb-1" for="jazz-artist-picker">Choose artist</label>

                        <select id="jazz-artist-picker" class="form-select form-select-sm">

                            <option value="">— Select —</option>

                            <?php foreach ($jazzCmsArtists as $ja): ?>

                                <option value="<?= (int) $ja->eventId ?>"><?= htmlspecialchars($ja->artist, ENT_QUOTES) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>



                <?php foreach ($jazzCmsArtists as $ja): ?>

                    <?php

                    $ac = $jazzArtistContents[$ja->eventId] ?? [];

                    $aid = (int) $ja->eventId;

                    ?>

                    <div class="jazz-cms-artist-wrap d-none mb-4" data-artist-id="<?= $aid ?>">

                    <form

                        method="post"

                        action="/admin/content/save"

                        class="jazz-cms-artist-form"

                        id="jazz-cms-artist-form-<?= $aid ?>"

                        data-artist-id="<?= $aid ?>"

                    >

                        <input type="hidden" name="page" value="jazz_<?= $aid ?>">

                        <div id="jazz-cms-artist-hidden-<?= $aid ?>"></div>



                        <div class="jazz-cms-artist-preview">

                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">

                                <div>

                                    <h3 class="h6 mb-0"><?= htmlspecialchars($ja->artist, ENT_QUOTES) ?></h3>

                                    <p class="small text-muted mb-0">/events/jazz/<?= $aid ?></p>

                                </div>

                            </div>



                            <div class="jazz-cms-label">Display name (optional override)</div>

                            <div class="jazz-cms-editable mb-2" contenteditable="true" data-cms-field="artist_name"><?= htmlspecialchars($ac['artist_name'] ?? '', ENT_QUOTES) ?></div>



                            <div class="jazz-cms-label">Banner image</div>

                            <div class="cms-dropzone mb-2" data-cms-image="banner_image" data-upload-url="/admin/upload-image">

                                <?php $bi = $ac['banner_image'] ?? ''; ?>

                                <?php if ($bi): ?>

                                    <img src="<?= htmlspecialchars($bi, ENT_QUOTES) ?>" alt="" class="cms-preview-img">

                                <?php else: ?>

                                    <span class="text-muted small">Drop or click — fallback: event image</span>

                                <?php endif; ?>

                                <div class="cms-dropzone-hint">Drop or click</div>

                                <input type="hidden" name="banner_image" value="<?= htmlspecialchars($bi, ENT_QUOTES) ?>">

                            </div>



                            <div class="jazz-cms-label">Profile image</div>

                            <div class="cms-dropzone mb-2" data-cms-image="profile_image" data-upload-url="/admin/upload-image">

                                <?php $pi = $ac['profile_image'] ?? ''; ?>

                                <?php if ($pi): ?>

                                    <img src="<?= htmlspecialchars($pi, ENT_QUOTES) ?>" alt="" class="cms-preview-img">

                                <?php else: ?>

                                    <span class="text-muted small">Drop or click — fallback: event image</span>

                                <?php endif; ?>

                                <div class="cms-dropzone-hint">Drop or click</div>

                                <input type="hidden" name="profile_image" value="<?= htmlspecialchars($pi, ENT_QUOTES) ?>">

                            </div>



                            <div class="jazz-cms-label">Biography (HTML — leave empty to use database description)</div>

                            <div

                                class="jazz-cms-editable jazz-cms-rich"

                                contenteditable="true"

                                data-cms-field="bio_html"

                                data-cms-rich="1"

                            ><?= $ac['bio_html'] ?? '' ?></div>



                            <div class="row g-2 mt-2">

                                <div class="col-md-6">

                                    <div class="jazz-cms-label">Tracks section title</div>

                                    <div class="jazz-cms-editable" contenteditable="true" data-cms-field="tracks_heading"><?= htmlspecialchars($ac['tracks_heading'] ?? 'Listen to their sounds', ENT_QUOTES) ?></div>

                                </div>

                                <div class="col-md-6">

                                    <div class="jazz-cms-label">Performances section title</div>

                                    <div class="jazz-cms-editable" contenteditable="true" data-cms-field="performances_heading"><?= htmlspecialchars($ac['performances_heading'] ?? 'Upcoming performances', ENT_QUOTES) ?></div>

                                </div>

                            </div>

                            <div class="mt-2">

                                <div class="jazz-cms-label">Price note</div>

                                <div class="jazz-cms-editable" contenteditable="true" data-cms-field="price_note"><?= htmlspecialchars($ac['price_note'] ?? 'Included in passes', ENT_QUOTES) ?></div>

                            </div>



                            <div class="text-end mt-3">

                                <button type="submit" class="btn btn-primary btn-sm">

                                    <i class="bi bi-save me-1"></i>Save this artist

                                </button>

                            </div>

                        </div>

                    </form>

                    <form method="post" action="/admin/jazz/artists/<?= $aid ?>/delete" class="mt-2 pt-2 border-top" onsubmit="return confirm('Delete this artist and all CMS content for them? This cannot be undone.');">

                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete artist from database</button>

                    </form>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>


