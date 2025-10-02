    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dpmptsp Kota Kupang - <?= ucfirst($page) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
        --sidebar-bg: #1b1a2e;
        --accent: #0d6efd;
        }
        body {
        font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        background: #f5f7fb;
        }
        .sidebar {
        background: var(--sidebar-bg);
        color: #cfd6e3;
        min-height: 100vh;
        width: 260px;
        transition: width 0.3s;
        }
        .sidebar.minimized {
        width: 70px;
        }
        .sidebar .nav-link {
        color: rgba(255,255,255,0.9);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        }
        .sidebar .nav-link.active {
        background: rgba(255,255,255,0.04);
        font-weight: 600;
        }
        .brand {
        padding: 1rem;
        font-weight: 700;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #fff;
        }
        .brand img { margin-bottom: 0.5rem; }
        .topbar {
        background: var(--accent);
        color: #fff;
        padding: 0.6rem 1rem;
        }
        .content {
        padding: 1.5rem;
        }
    </style>
    </head>
    <body>
    <div class="d-flex">
