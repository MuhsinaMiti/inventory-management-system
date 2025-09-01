<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">Inventory System</a>
        <div class="collapse navbar-collapse">
           

            <ul class="navbar-nav ms-auto">
    <?php if (session()->get('logged_in')): ?>
        <?php if (isAdmin()): ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('products') ?>">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('stock/logs') ?>">Stock Logs</a></li>
        <?php elseif (isViewer()): ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('products') ?>">Products</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
    <?php endif; ?>
</ul>


        </div>
    </nav>
    <div class="container mt-4">
