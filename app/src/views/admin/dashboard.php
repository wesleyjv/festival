<?php

// Simple standalone admin CMS dashboard view.
// This is intentionally self-contained so you can open it via the /admin route
// without needing any additional layout templates.

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin CMS Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />

    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 56px;
        }

        body {
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        .top-navbar {
            height: var(--navbar-height);
            z-index: 1030;
        }

        .sidebar {
            width: var(--sidebar-width);
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 1020;
            background-color: #1f2933;
            color: #e5e7eb;
        }

        .sidebar .nav-link {
            color: #9ca3af;
            border-radius: 0.375rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar .nav-link:hover {
            color: #f9fafb;
            background-color: rgba(148, 163, 184, 0.2);
        }

        .sidebar .nav-link.active {
            color: #f9fafb;
            background: linear-gradient(90deg, #2563eb, #4f46e5);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        @media (min-width: 768px) {
            .main-wrapper {
                margin-left: var(--sidebar-width);
                padding-top: var(--navbar-height);
            }
        }

        @media (max-width: 767.98px) {
            .main-wrapper {
                padding-top: calc(var(--navbar-height) + 0.5rem);
            }
        }

        .stat-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .page-title {
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            border-bottom-width: 1px;
        }

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
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top top-navbar px-3">
        <a class="navbar-brand fw-semibold" href="#">
            <i class="bi bi-grid-1x2-fill me-1"></i> Admin CMS
        </a>

        <button
            class="navbar-toggler ms-auto"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar"
            aria-controls="mobileSidebar"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="d-none d-md-flex ms-auto align-items-center gap-2">
            <a href="/" class="btn btn-sm btn-outline-light">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Site
            </a>
            <span class="text-light small">
              <i class="bi bi-person-circle me-1"></i> Admin
            </span>
        </div>
    </nav>
</header>

<!-- Desktop Sidebar -->
<nav
    class="d-none d-md-block position-fixed sidebar px-3 py-4 overflow-auto"
    aria-label="Main navigation"
>
    <div class="mb-4 small text-uppercase text-secondary fw-semibold">
        Navigation
    </div>
    <ul class="nav nav-pills flex-column gap-1" id="sidebarNavDesktop">
        <li class="nav-item">
            <button
                class="nav-link active w-100 text-start"
                data-page="dashboard"
                type="button"
            >
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </button>
        </li>
        <li class="nav-item">
            <button
                class="nav-link w-100 text-start"
                data-page="users"
                type="button"
            >
                <i class="bi bi-people"></i>
                <span>Users</span>
            </button>
        </li>
        <li class="nav-item">
            <button
                class="nav-link w-100 text-start"
                data-page="events"
                type="button"
            >
                <i class="bi bi-calendar-event"></i>
                <span>Events</span>
            </button>
        </li>
        <li class="nav-item">
            <button
                class="nav-link w-100 text-start"
                data-page="orders"
                type="button"
            >
                <i class="bi bi-receipt"></i>
                <span>Orders</span>
            </button>
        </li>
        <li class="nav-item">
            <button
                class="nav-link w-100 text-start"
                data-page="content"
                type="button"
            >
                <i class="bi bi-file-earmark-text"></i>
                <span>Content</span>
            </button>
        </li>
    </ul>
</nav>

<!-- Mobile Sidebar (Offcanvas) -->
<div
    class="offcanvas offcanvas-start bg-dark text-light"
    tabindex="-1"
    id="mobileSidebar"
    aria-labelledby="mobileSidebarLabel"
>
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">
            <i class="bi bi-grid-1x2-fill me-1"></i> Admin CMS
        </h5>
        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="offcanvas"
            aria-label="Close"
        ></button>
    </div>
        <div class="offcanvas-body pt-3">
        <div class="mb-3 small text-uppercase text-secondary fw-semibold">
            Navigation
        </div>
        <ul class="nav nav-pills flex-column gap-1" id="sidebarNavMobile">
            <li class="nav-item">
                <button
                    class="nav-link active w-100 text-start"
                    data-page="dashboard"
                    type="button"
                    data-bs-dismiss="offcanvas"
                >
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link w-100 text-start"
                    data-page="users"
                    type="button"
                    data-bs-dismiss="offcanvas"
                >
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link w-100 text-start"
                    data-page="events"
                    type="button"
                    data-bs-dismiss="offcanvas"
                >
                    <i class="bi bi-calendar-event"></i>
                    <span>Events</span>
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link w-100 text-start"
                    data-page="orders"
                    type="button"
                    data-bs-dismiss="offcanvas"
                >
                    <i class="bi bi-receipt"></i>
                    <span>Orders</span>
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link w-100 text-start"
                    data-page="content"
                    type="button"
                    data-bs-dismiss="offcanvas"
                >
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Content</span>
                </button>
            </li>
        </ul>
    </div>
    </div>

<!-- Main Content Wrapper -->
<div class="main-wrapper">
    <main class="container-fluid py-3 py-md-4">
        <!-- Dashboard Page -->
        <section id="page-dashboard" class="page-section">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Dashboard</h1>
                    <p class="text-muted small mb-0">
                        Quick overview of key metrics.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="row g-3 g-md-4 mb-4">
                <div class="col-md-4">
                    <article class="card stat-card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h6 text-muted text-uppercase mb-1">Total Users</h2>
                                <p class="h4 mb-0"><?= $totalUsers ?></p>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up-right me-1"></i>12% vs last month
                                </small>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="card stat-card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h6 text-muted text-uppercase mb-1">Total Events</h2>
                                <p class="h4 mb-0">32</p>
                                <small class="text-muted">
                                    <i class="bi bi-circle me-1 text-warning"></i>Upcoming this month
                                </small>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="card stat-card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h6 text-muted text-uppercase mb-1">Total Orders</h2>
                                <p class="h4 mb-0">3,587</p>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up-right me-1"></i>7% vs last week
                                </small>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-receipt-cutoff"></i>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <section aria-label="Recent activity">
                <div class="card stat-card">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0">Recent Activity</h2>
                        <a href="#" class="small text-primary text-decoration-none">View all</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-semibold">New user registered</span>
                                    <div class="text-muted">john.doe@example.com</div>
                                </div>
                                <span class="text-muted">5 min ago</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-semibold">Order #3490 paid</span>
                                    <div class="text-muted">2 tickets for "Summer Fest"</div>
                                </div>
                                <span class="text-muted">34 min ago</span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-semibold">Event updated</span>
                                    <div class="text-muted">"Music Night" date changed</div>
                                </div>
                                <span class="text-muted">1 hr ago</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </section>

        <!-- Users Management Page -->
        <section id="page-users" class="page-section d-none">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Users</h1>
                    <p class="text-muted small mb-0">
                        Manage registered users &mdash; <?= $totalUsers ?> total.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-plus-lg me-1"></i> New User
                    </button>
                </div>
            </div>

            <?php if ($userSaved): ?>
                <div class="alert alert-success alert-sm py-2 mb-3">
                    <small>User saved successfully.</small>
                </div>
            <?php endif; ?>
            <?php if ($userError): ?>
                <div class="alert alert-danger alert-sm py-2 mb-3">
                    <small>
                        <?php if ($userError === 'email_exists'): ?>Email is already in use.
                        <?php elseif ($userError === 'invalid_id'): ?>Cannot delete that user.
                        <?php else: ?>Invalid data. Please check all fields.
                        <?php endif; ?>
                    </small>
                </div>
            <?php endif; ?>

            <article class="card stat-card">
                <div class="card-body">
                    <form method="get" action="/admin" class="row g-2 g-md-3 align-items-end mb-3">
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($userSort, ENT_QUOTES) ?>">
                        <input type="hidden" name="dir"  value="<?= htmlspecialchars($userDir,  ENT_QUOTES) ?>">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small mb-1" for="userSearch">Search</label>
                            <input type="text" class="form-control form-control-sm" id="userSearch" name="search"
                                   placeholder="Name or email" value="<?= htmlspecialchars($userSearch, ENT_QUOTES) ?>">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="userRoleFilter">Role</label>
                            <select id="userRoleFilter" name="role" class="form-select form-select-sm">
                                <option value="">All roles</option>
                                <option value="admin"    <?= $userRole === 'admin'    ? 'selected' : '' ?>>Admin</option>
                                <option value="employee" <?= $userRole === 'employee' ? 'selected' : '' ?>>Employee</option>
                                <option value="customer" <?= $userRole === 'customer' ? 'selected' : '' ?>>Customer</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="userSortSelect">Sort by</label>
                            <select id="userSortSelect" name="sort" class="form-select form-select-sm">
                                <option value="id"         <?= $userSort === 'id'         ? 'selected' : '' ?>>ID</option>
                                <option value="name"       <?= $userSort === 'name'       ? 'selected' : '' ?>>Name</option>
                                <option value="email"      <?= $userSort === 'email'      ? 'selected' : '' ?>>Email</option>
                                <option value="role"       <?= $userSort === 'role'       ? 'selected' : '' ?>>Role</option>
                                <option value="created_at" <?= $userSort === 'created_at' ? 'selected' : '' ?>>Registered</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-2 d-grid">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <a href="/admin?search=<?= urlencode($userSearch) ?>&role=<?= urlencode($userRole) ?>&sort=name&dir=<?= ($userSort === 'name' && $userDir === 'ASC') ? 'DESC' : 'ASC' ?>#users"
                                       class="text-decoration-none text-muted small text-uppercase">
                                        User <i class="bi bi-arrow-down-up"></i>
                                    </a>
                                </th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">
                                    <a href="/admin?search=<?= urlencode($userSearch) ?>&role=<?= urlencode($userRole) ?>&sort=created_at&dir=<?= ($userSort === 'created_at' && $userDir === 'ASC') ? 'DESC' : 'ASC' ?>#users"
                                       class="text-decoration-none text-muted small text-uppercase">
                                        Registered <i class="bi bi-arrow-down-up"></i>
                                    </a>
                                </th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="small">
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($u->name, ENT_QUOTES) ?></div>
                                    <div class="text-muted">#<?= $u->id ?></div>
                                </td>
                                <td><?= htmlspecialchars($u->email, ENT_QUOTES) ?></td>
                                <td>
                                    <?php if ($u->role === 'admin'): ?>
                                        <span class="badge bg-primary-subtle text-primary-emphasis">Admin</span>
                                    <?php elseif ($u->role === 'employee'): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis">Employee</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $u->createdAt ? htmlspecialchars(date('M j, Y', strtotime($u->createdAt)), ENT_QUOTES) : '—' ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                            data-id="<?= $u->id ?>"
                                            data-name="<?= htmlspecialchars($u->name, ENT_QUOTES) ?>"
                                            data-email="<?= htmlspecialchars($u->email, ENT_QUOTES) ?>"
                                            data-role="<?= htmlspecialchars($u->role, ENT_QUOTES) ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if ($u->id !== (int) ($_SESSION['user_id'] ?? 0)): ?>
                                    <form method="post" action="/admin/users/<?= $u->id ?>/delete" class="d-inline"
                                          onsubmit="return confirm('Delete user <?= htmlspecialchars(addslashes($u->name), ENT_QUOTES) ?>?')">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>

            <!-- Create User Modal -->
            <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="/admin/users/create">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createUserModalLabel">New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="create-name">Name</label>
                                    <input type="text" class="form-control form-control-sm" id="create-name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="create-email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="create-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="create-password">Password</label>
                                    <input type="password" class="form-control form-control-sm" id="create-password" name="password" required minlength="6">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="create-role">Role</label>
                                    <select class="form-select form-select-sm" id="create-role" name="role">
                                        <option value="customer">Customer</option>
                                        <option value="employee">Employee</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Create User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" id="editUserForm" action="/admin/users/0/update">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="edit-name">Name</label>
                                    <input type="text" class="form-control form-control-sm" id="edit-name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="edit-email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="edit-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold" for="edit-role">Role</label>
                                    <select class="form-select form-select-sm" id="edit-role" name="role">
                                        <option value="customer">Customer</option>
                                        <option value="employee">Employee</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Management Page -->
        <section id="page-events" class="page-section d-none">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Events</h1>
                    <p class="text-muted small mb-0">
                        Manage storytelling events for the Stories page.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createStoryEventModal">
                        <i class="bi bi-plus-lg me-1"></i> New Story Event
                    </button>
                </div>
            </div>

            <?php if ($storySaved): ?>
                <div class="alert alert-success alert-sm py-2 mb-3">
                    <small>Story event saved successfully.</small>
                </div>
            <?php endif; ?>
            <?php if ($storyError): ?>
                <div class="alert alert-danger alert-sm py-2 mb-3">
                    <small>
                        <?php if ($storyError === 'invalid_id'): ?>Invalid event ID.
                        <?php else: ?>Invalid data. Please check all required fields.
                        <?php endif; ?>
                    </small>
                </div>
            <?php endif; ?>

            <article class="card stat-card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover small">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Day</th>
                                <th scope="col">Event date</th>
                                <th scope="col">Time slot</th>
                                <th scope="col">Location</th>
                                <th scope="col">Price</th>
                                <th scope="col">Language</th>
                                <th scope="col">Category</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($storyEvents as $e): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($e['title'] ?? '', ENT_QUOTES) ?>
                                        </div>
                                        <div class="text-muted">#<?= (int)($e['id'] ?? 0) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($e['day'] ?? '', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($e['event_date'] ?? '', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($e['time_slot'] ?? '', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($e['location'] ?? '', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars((string)($e['price'] ?? ''), ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($e['language'] ?? '', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($e['category'] ?? '', ENT_QUOTES) ?></td>
                                    <td class="text-end">
                                        <button
                                            class="btn btn-sm btn-outline-secondary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStoryEventModal"
                                            data-id="<?= (int)($e['id'] ?? 0) ?>"
                                            data-title="<?= htmlspecialchars($e['title'] ?? '', ENT_QUOTES) ?>"
                                            data-day="<?= htmlspecialchars($e['day'] ?? '', ENT_QUOTES) ?>"
                                            data-event_date="<?= htmlspecialchars($e['event_date'] ?? '', ENT_QUOTES) ?>"
                                            data-time_slot="<?= htmlspecialchars($e['time_slot'] ?? '', ENT_QUOTES) ?>"
                                            data-location="<?= htmlspecialchars($e['location'] ?? '', ENT_QUOTES) ?>"
                                            data-age_group="<?= htmlspecialchars($e['age_group'] ?? '', ENT_QUOTES) ?>"
                                            data-language="<?= htmlspecialchars($e['language'] ?? '', ENT_QUOTES) ?>"
                                            data-price="<?= htmlspecialchars((string)($e['price'] ?? ''), ENT_QUOTES) ?>"
                                            data-category="<?= htmlspecialchars($e['category'] ?? '', ENT_QUOTES) ?>"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="post"
                                              action="/admin/story-events/<?= (int)($e['id'] ?? 0) ?>/delete"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete story event <?= htmlspecialchars(addslashes($e['title'] ?? ''), ENT_QUOTES) ?>?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($storyEvents)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No story events found. Use "New Story Event" to add one.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>

            <!-- Create Story Event Modal -->
            <div class="modal fade" id="createStoryEventModal" tabindex="-1" aria-labelledby="createStoryEventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" action="/admin/story-events/create">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createStoryEventModalLabel">New Story Event</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold" for="create-story-title">Title</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-title" name="title" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="create-story-day">Day</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-day" name="day" placeholder="Thursday" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="create-story-date">Event date</label>
                                        <input type="date" class="form-control form-control-sm" id="create-story-date" name="event_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="create-story-time">Time slot</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-time" name="time_slot" placeholder="16:00-17:00" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="create-story-location">Location</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-location" name="location" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="create-story-age">Age group</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-age" name="age_group" placeholder="All ages">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="create-story-language">Language</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-language" name="language" placeholder="EN/NL">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="create-story-price">Price</label>
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm" id="create-story-price" name="price">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold" for="create-story-category">Category / description</label>
                                        <input type="text" class="form-control form-control-sm" id="create-story-category" name="category">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Create Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Story Event Modal -->
            <div class="modal fade" id="editStoryEventModal" tabindex="-1" aria-labelledby="editStoryEventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" id="editStoryEventForm" action="/admin/story-events/0/update">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStoryEventModalLabel">Edit Story Event</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold" for="edit-story-title">Title</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-title" name="title" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="edit-story-day">Day</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-day" name="day" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="edit-story-date">Event date</label>
                                        <input type="date" class="form-control form-control-sm" id="edit-story-date" name="event_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="edit-story-time">Time slot</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-time" name="time_slot" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="edit-story-location">Location</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-location" name="location" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold" for="edit-story-age">Age group</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-age" name="age_group">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="edit-story-language">Language</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-language" name="language">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold" for="edit-story-price">Price</label>
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm" id="edit-story-price" name="price">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold" for="edit-story-category">Category / description</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-story-category" name="category">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Orders Overview Page -->
        <section id="page-orders" class="page-section d-none">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Orders</h1>
                    <p class="text-muted small mb-0">
                        Overview of ticket and product orders. Placeholder content.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </button>
                </div>
            </div>

            <article class="card stat-card mb-3">
                <div class="card-body">
                    <form class="row g-2 g-md-3 align-items-end mb-3">
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="orderSearch">Search</label>
                            <input type="text" id="orderSearch" class="form-control form-control-sm"
                                   placeholder="Order ID or email">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="orderStatusFilter">Status</label>
                            <select id="orderStatusFilter" class="form-select form-select-sm">
                                <option value="">Any status</option>
                                <option>Paid</option>
                                <option>Pending</option>
                                <option>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="orderDateFilter">Date</label>
                            <input type="date" id="orderDateFilter" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-6 col-md-3 d-grid">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                            <tr>
                                <th scope="col">Order</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Event</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="small">
                            <tr>
                                <td>
                                    <div class="fw-semibold">#3490</div>
                                    <div class="text-muted">Feb 10, 2026</div>
                                </td>
                                <td>john.doe@example.com</td>
                                <td>Summer Festival 2026</td>
                                <td>$120.00</td>
                                <td><span class="badge bg-success-subtle text-success-emphasis">Paid</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold">#3489</div>
                                    <div class="text-muted">Feb 9, 2026</div>
                                </td>
                                <td>jane.smith@example.com</td>
                                <td>Spring Music Night</td>
                                <td>$60.00</td>
                                <td><span class="badge bg-warning-subtle text-warning-emphasis">Pending</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold">#3488</div>
                                    <div class="text-muted">Feb 8, 2026</div>
                                </td>
                                <td>alex.johnson@example.com</td>
                                <td>New Year Celebration 2025</td>
                                <td>$180.00</td>
                                <td><span class="badge bg-danger-subtle text-danger-emphasis">Cancelled</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>

            <section aria-label="Orders summary">
                <div class="row g-3 g-md-4">
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="small text-muted text-uppercase mb-1">Today</div>
                                <div class="h5 mb-0">$1,240.00</div>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up-right me-1"></i>4% vs yesterday
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="small text-muted text-uppercase mb-1">This week</div>
                                <div class="h5 mb-0">$7,940.00</div>
                                <small class="text-muted">
                                    <i class="bi bi-circle me-1 text-warning"></i>On track
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="small text-muted text-uppercase mb-1">Refunds</div>
                                <div class="h5 mb-0">$320.00</div>
                                <small class="text-danger">
                                    <i class="bi bi-arrow-down-right me-1"></i>1.2% of revenue
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- Content Management Page -->
        <section id="page-content" class="page-section d-none">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Content</h1>
                    <p class="text-muted small mb-0">
                        Manage homepage, Story, Yummy, History, and Jazz page copy.
                    </p>
                </div>
            </div>

            <?php if (!empty($_GET['saved'])): ?>
                <div class="alert alert-success alert-sm py-2">
                    <small class="mb-0">Content saved successfully.</small>
                </div>
            <?php endif; ?>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-homepage" data-bs-toggle="tab" data-bs-target="#content-homepage" type="button" role="tab">
                        Homepage
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-stories" data-bs-toggle="tab" data-bs-target="#content-stories" type="button" role="tab">
                        Story
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-yummy" data-bs-toggle="tab" data-bs-target="#content-yummy" type="button" role="tab">
                        Yummy
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-history" data-bs-toggle="tab" data-bs-target="#content-history" type="button" role="tab">
                        History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-jazz" data-bs-toggle="tab" data-bs-target="#content-jazz" type="button" role="tab">
                        Jazz
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Homepage -->
                <div class="tab-pane fade show active" id="content-homepage" role="tabpanel" aria-labelledby="tab-homepage">
                    <form method="post" action="/admin/content/save" class="card stat-card mb-3">
                        <div class="card-body">
                            <input type="hidden" name="page" value="homepage">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero title (supports HTML)</label>
                                <textarea name="hero_title" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($homepageContent['hero_title'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero subtitle</label>
                                <textarea name="hero_subtitle" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($homepageContent['hero_subtitle'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">CTA heading</label>
                                <textarea name="cta_heading" class="form-control wysiwyg" rows="2"><?= htmlspecialchars($homepageContent['cta_heading'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">CTA text</label>
                                <textarea name="cta_text" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($homepageContent['cta_text'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i>Save Homepage Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Stories -->
                <div class="tab-pane fade" id="content-stories" role="tabpanel" aria-labelledby="tab-stories">
                    <form method="post" action="/admin/content/save" class="card stat-card mb-3">
                        <div class="card-body">
                            <input type="hidden" name="page" value="stories">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero background image URL</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input
                                        type="text"
                                        name="hero_image"
                                        class="form-control cms-image-url"
                                        value="<?= htmlspecialchars($storiesContent['hero_image'] ?? '/img/storytelling-hero.jpg', ENT_QUOTES) ?>"
                                        placeholder="/uploads/your-hero-image.jpg"
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary cms-upload-btn"
                                        data-target-input="hero_image"
                                    >
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                </div>
                                <div
                                    class="cms-dropzone mb-2"
                                    data-target-input="hero_image"
                                >
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <span>
                                        Drag &amp; drop an image here, or click to select a file.
                                    </span>
                                </div>
                                <small class="text-muted d-block mb-1">
                                    Paste an image URL, use <strong>Upload</strong>, or drag-and-drop into the box above to upload a new hero image.
                                </small>
                                <img
                                    src="<?= htmlspecialchars($storiesContent['hero_image'] ?? '/img/storytelling-hero.jpg', ENT_QUOTES) ?>"
                                    alt="Hero preview"
                                    class="border rounded cms-image-preview"
                                    style="max-height: 140px; max-width: 100%; object-fit: cover;"
                                    data-preview-for="hero_image"
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Featured storyteller image URL</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input
                                        type="text"
                                        name="featured_image"
                                        class="form-control cms-image-url"
                                        value="<?= htmlspecialchars($storiesContent['featured_image'] ?? '/img/featured-storyteller.jpg', ENT_QUOTES) ?>"
                                        placeholder="/uploads/your-featured-image.jpg"
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary cms-upload-btn"
                                        data-target-input="featured_image"
                                    >
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                </div>
                                <div
                                    class="cms-dropzone mb-2"
                                    data-target-input="featured_image"
                                >
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <span>
                                        Drag &amp; drop an image here, or click to select a file.
                                    </span>
                                </div>
                                <small class="text-muted d-block mb-1">
                                    Paste an image URL, use <strong>Upload</strong>, or drag-and-drop into the box above to upload a new featured storyteller image.
                                </small>
                                <img
                                    src="<?= htmlspecialchars($storiesContent['featured_image'] ?? '/img/featured-storyteller.jpg', ENT_QUOTES) ?>"
                                    alt="Featured storyteller preview"
                                    class="border rounded cms-image-preview"
                                    style="max-height: 140px; max-width: 100%; object-fit: cover;"
                                    data-preview-for="featured_image"
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero title</label>
                                <textarea name="hero_title" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($storiesContent['hero_title'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero description</label>
                                <textarea name="hero_description" class="form-control wysiwyg" rows="5"><?= htmlspecialchars($storiesContent['hero_description'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Additional information text</label>
                                <textarea name="info_paragraph" class="form-control wysiwyg" rows="4"><?= htmlspecialchars($storiesContent['info_paragraph'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i>Save Story Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Yummy -->
                <div class="tab-pane fade" id="content-yummy" role="tabpanel" aria-labelledby="tab-yummy">
                    <form method="post" action="/admin/content/save" class="card stat-card mb-3">
                        <div class="card-body">
                            <input type="hidden" name="page" value="yummy">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero background image URL</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input
                                        type="text"
                                        name="hero_image"
                                        class="form-control cms-image-url"
                                        value="<?= htmlspecialchars($yummyContent['hero_image'] ?? '/assets/yummy/image/yummy-hero.jpg', ENT_QUOTES) ?>"
                                        placeholder="/uploads/your-yummy-hero-image.jpg"
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary cms-upload-btn"
                                        data-target-input="hero_image"
                                    >
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                </div>
                                <div
                                    class="cms-dropzone mb-2"
                                    data-target-input="hero_image"
                                >
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <span>
                                        Drag &amp; drop an image here, or click to select a file.
                                    </span>
                                </div>
                                <small class="text-muted d-block mb-1">
                                    Paste an image URL, use <strong>Upload</strong>, or drag-and-drop into the box above to upload a new Yummy hero image.
                                </small>
                                <img
                                    src="<?= htmlspecialchars($yummyContent['hero_image'] ?? '/assets/yummy/image/yummy-hero.jpg', ENT_QUOTES) ?>"
                                    alt="Yummy hero preview"
                                    class="border rounded cms-image-preview"
                                    style="max-height: 140px; max-width: 100%; object-fit: cover;"
                                    data-preview-for="hero_image"
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Intro heading</label>
                                <textarea name="intro_heading" class="form-control wysiwyg" rows="2"><?= htmlspecialchars($yummyContent['intro_heading'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Intro text</label>
                                <textarea name="intro_text" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($yummyContent['intro_text'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i>Save Yummy Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- History -->
                <div class="tab-pane fade" id="content-history" role="tabpanel" aria-labelledby="tab-history">
                    <form method="post" action="/admin/content/save" class="card stat-card mb-3">
                        <div class="card-body">
                            <input type="hidden" name="page" value="history">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero title</label>
                                <textarea name="hero_title" class="form-control wysiwyg" rows="2"><?= htmlspecialchars($historyContent['hero_title'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero description</label>
                                <textarea name="hero_description" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($historyContent['hero_description'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i>Save History Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Jazz -->
                <div class="tab-pane fade" id="content-jazz" role="tabpanel" aria-labelledby="tab-jazz">
                    <form method="post" action="/admin/content/save" class="card stat-card mb-3">
                        <div class="card-body">
                            <input type="hidden" name="page" value="jazz">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Hero background image URL</label>
                                <div class="input-group input-group-sm mb-2">
                                    <input
                                        type="text"
                                        name="hero_image"
                                        class="form-control cms-image-url"
                                        value="<?= htmlspecialchars($jazzContent['hero_image'] ?? '/assets/jazz/img/jazz-hero.jpg', ENT_QUOTES) ?>"
                                        placeholder="/uploads/your-jazz-hero-image.jpg"
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary cms-upload-btn"
                                        data-target-input="hero_image"
                                    >
                                        <i class="bi bi-upload me-1"></i>Upload
                                    </button>
                                </div>
                                <div
                                    class="cms-dropzone mb-2"
                                    data-target-input="hero_image"
                                >
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <span>
                                        Drag &amp; drop an image here, or click to select a file.
                                    </span>
                                </div>
                                <small class="text-muted d-block mb-1">
                                    Paste an image URL, use <strong>Upload</strong>, or drag-and-drop into the box above to upload a new Jazz hero image.
                                </small>
                                <img
                                    src="<?= htmlspecialchars($jazzContent['hero_image'] ?? '/assets/jazz/img/jazz-hero.jpg', ENT_QUOTES) ?>"
                                    alt="Jazz hero preview"
                                    class="border rounded cms-image-preview"
                                    style="max-height: 140px; max-width: 100%; object-fit: cover;"
                                    data-preview-for="hero_image"
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Intro heading</label>
                                <textarea name="intro_heading" class="form-control wysiwyg" rows="2"><?= htmlspecialchars($jazzContent['intro_heading'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Intro text</label>
                                <textarea name="intro_text" class="form-control wysiwyg" rows="3"><?= htmlspecialchars($jazzContent['intro_text'] ?? '', ENT_QUOTES) ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save me-1"></i>Save Jazz Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- TinyMCE WYSIWYG editor -->
<script src="https://cdn.tiny.cloud/1/rmqh6zpkull0b6qquqsqfol8clwt2hcni7cikkt0vy5f96ij/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    (function () {
        const pageSections = document.querySelectorAll('.page-section');

        function showPage(page) {
            const targetId = 'page-' + page;

            pageSections.forEach(section => {
                if (section.id === targetId) {
                    section.classList.remove('d-none');
                } else {
                    section.classList.add('d-none');
                }
            });

            [document.getElementById('sidebarNavDesktop'), document.getElementById('sidebarNavMobile')]
                .forEach(nav => {
                    if (!nav) return;
                    nav.querySelectorAll('.nav-link').forEach(link => {
                        const linkPage = link.getAttribute('data-page');
                        if (linkPage === page) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                });
        }

        document.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const page = btn.getAttribute('data-page');
                showPage(page);
            });
        });

        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash) {
                showPage(hash);
            }
        });

        const initialHash = window.location.hash.replace('#', '');
        const hasUserParams = new URLSearchParams(window.location.search).get('search') !== null
            || new URLSearchParams(window.location.search).get('role') !== null
            || new URLSearchParams(window.location.search).get('sort') !== null
            || new URLSearchParams(window.location.search).get('user_saved') !== null
            || new URLSearchParams(window.location.search).get('user_error') !== null;
        const hasStoryParams = new URLSearchParams(window.location.search).get('story_saved') !== null
            || new URLSearchParams(window.location.search).get('story_error') !== null;
        let defaultPage = 'dashboard';
        if (hasUserParams) {
            defaultPage = 'users';
        } else if (hasStoryParams) {
            defaultPage = 'events';
        }
        showPage(initialHash || defaultPage);

        // Populate edit user modal
        document.getElementById('editUserModal').addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            document.getElementById('edit-name').value  = btn.getAttribute('data-name');
            document.getElementById('edit-email').value = btn.getAttribute('data-email');
            document.getElementById('edit-role').value  = btn.getAttribute('data-role');
            document.getElementById('editUserForm').action = '/admin/users/' + btn.getAttribute('data-id') + '/update';
        });

        // Populate edit story event modal
        const editStoryModalEl = document.getElementById('editStoryEventModal');
        if (editStoryModalEl) {
            editStoryModalEl.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                if (!btn) return;
                document.getElementById('edit-story-title').value      = btn.getAttribute('data-title') || '';
                document.getElementById('edit-story-day').value        = btn.getAttribute('data-day') || '';
                document.getElementById('edit-story-date').value       = btn.getAttribute('data-event_date') || '';
                document.getElementById('edit-story-time').value       = btn.getAttribute('data-time_slot') || '';
                document.getElementById('edit-story-location').value   = btn.getAttribute('data-location') || '';
                document.getElementById('edit-story-age').value        = btn.getAttribute('data-age_group') || '';
                document.getElementById('edit-story-language').value   = btn.getAttribute('data-language') || '';
                document.getElementById('edit-story-price').value      = btn.getAttribute('data-price') || '';
                document.getElementById('edit-story-category').value   = btn.getAttribute('data-category') || '';

                const form = document.getElementById('editStoryEventForm');
                form.action = '/admin/story-events/' + (btn.getAttribute('data-id') || '0') + '/update';
            });
        }

        // Initialize TinyMCE for all WYSIWYG fields
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
                xhr.send(formData);
            }
        });

        function cmsUploadImage(file, form, targetName, onStart, onDone) {
            if (!file || !form || !targetName) return;

            const data = new FormData();
            data.append('file', file, file.name);

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

        // Simple image uploader for non-WYSIWYG "image frame" fields (button + drag & drop)
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

        // Drag & drop support for CMS image fields
        document.querySelectorAll('.cms-dropzone').forEach(zone => {
            const targetName = zone.getAttribute('data-target-input');
            if (!targetName) return;

            zone.addEventListener('click', () => {
                // Delegate to the existing upload button for the same target, if present
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

