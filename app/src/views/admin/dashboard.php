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

        <div class="d-none d-md-flex ms-auto align-items-center">
            <span class="text-light small me-3">
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
                                <p class="h4 mb-0">1,248</p>
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
                        Manage registered users. This is a placeholder layout.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> New User
                    </button>
                </div>
            </div>

            <article class="card stat-card">
                <div class="card-body">
                    <form class="row g-2 g-md-3 align-items-end mb-3">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small mb-1" for="userSearch">Search</label>
                            <input type="text" class="form-control form-control-sm" id="userSearch"
                                   placeholder="Name or email">
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="userRoleFilter">Role</label>
                            <select id="userRoleFilter" class="form-select form-select-sm">
                                <option value="">All roles</option>
                                <option>Admin</option>
                                <option>Editor</option>
                                <option>Viewer</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label small mb-1" for="userStatusFilter">Status</label>
                            <select id="userStatusFilter" class="form-select form-select-sm">
                                <option value="">Any status</option>
                                <option>Active</option>
                                <option>Suspended</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-2 d-grid">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="small">
                            <tr>
                                <td>
                                    <div class="fw-semibold">John Doe</div>
                                    <div class="text-muted">Joined Jan 2026</div>
                                </td>
                                <td>john.doe@example.com</td>
                                <td><span class="badge bg-primary-subtle text-primary-emphasis">Admin</span></td>
                                <td><span class="badge bg-success-subtle text-success-emphasis">Active</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold">Jane Smith</div>
                                    <div class="text-muted">Joined Dec 2025</div>
                                </td>
                                <td>jane.smith@example.com</td>
                                <td><span class="badge bg-info-subtle text-info-emphasis">Editor</span></td>
                                <td><span class="badge bg-success-subtle text-success-emphasis">Active</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-semibold">Alex Johnson</div>
                                    <div class="text-muted">Joined Nov 2025</div>
                                </td>
                                <td>alex.johnson@example.com</td>
                                <td><span class="badge bg-secondary-subtle text-secondary-emphasis">Viewer</span></td>
                                <td><span class="badge bg-warning-subtle text-warning-emphasis">Suspended</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Users pagination" class="mt-3">
                        <ul class="pagination pagination-sm justify-content-end mb-0">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </article>
        </section>

        <!-- Events Management Page -->
        <section id="page-events" class="page-section d-none">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 page-title mb-1">Events</h1>
                    <p class="text-muted small mb-0">
                        Manage events and schedules. Placeholder content for now.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> New Event
                    </button>
                </div>
            </div>

            <article class="card stat-card mb-3">
                <div class="card-body">
                    <form class="row g-2 g-md-3 align-items-end mb-3">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small mb-1" for="eventSearch">Search</label>
                            <input type="text" class="form-control form-control-sm" id="eventSearch"
                                   placeholder="Event name">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small mb-1" for="eventStatusFilter">Status</label>
                            <select id="eventStatusFilter" class="form-select form-select-sm">
                                <option value="">Any status</option>
                                <option>Upcoming</option>
                                <option>Ongoing</option>
                                <option>Past</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4 d-grid">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <div class="list-group small">
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Summer Festival 2026</div>
                                <div class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i> July 14, 2026 · 18:00
                                </div>
                            </div>
                            <div class="text-end">
                                <div><span class="badge bg-success-subtle text-success-emphasis">Upcoming</span></div>
                                <button class="btn btn-sm btn-outline-secondary mt-1">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                            </div>
                        </div>

                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Spring Music Night</div>
                                <div class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i> March 2, 2026 · 20:00
                                </div>
                            </div>
                            <div class="text-end">
                                <div><span class="badge bg-warning-subtle text-warning-emphasis">Ongoing</span></div>
                                <button class="btn btn-sm btn-outline-secondary mt-1">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                            </div>
                        </div>

                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">New Year Celebration 2025</div>
                                <div class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i> Dec 31, 2025 · 21:00
                                </div>
                            </div>
                            <div class="text-end">
                                <div><span class="badge bg-secondary-subtle text-secondary-emphasis">Past</span></div>
                                <button class="btn btn-sm btn-outline-secondary mt-1">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <section aria-label="Events calendar placeholder">
                <div class="card stat-card">
                    <div class="card-header bg-white border-0">
                        <h2 class="h6 mb-0">Calendar (placeholder)</h2>
                    </div>
                    <div class="card-body text-center text-muted small">
                        A calendar or schedule view can be integrated here.
                    </div>
                </div>
            </section>
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
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
        showPage(initialHash || 'dashboard');
    })();
</script>
</body>
</html>

