<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">🛒 Product List</h2>
    <?php if (isAdmin()): ?>
        <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    <?php endif; ?>
</div>

<!--  Flash Messages 
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>  
<?php endif; ?>  
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div> 
<?php endif; ?> --> 

<!-- 🔍 Search & Filter -->
<form method="get" action="<?= base_url('products') ?>" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control"
               placeholder="🔍 Search by name or category..."
               value="<?= esc($_GET['search'] ?? '') ?>">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">📂 All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= esc($cat['category']) ?>"
                    <?= (($_GET['category'] ?? '') == $cat['category']) ? 'selected' : '' ?>>
                    <?= esc($cat['category']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Search
        </button>
    </div>
</form>

<!-- 📊 Admin Tools -->
<?php if (isAdmin()): ?>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-gear"></i> Admin Tools
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="<?= base_url('products/exportExcel'); ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>

            <form action="<?= base_url('products/importExcel'); ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                <input type="file" name="excel_file" accept=".xlsx,.xls" class="form-control form-control-sm" required>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Import Excel
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- 🗂️ Product Table -->
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th class="text-end">Price (৳)</th>
                <th class="text-center">Stock</th>
                <th>Image</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr class="<?= ($product['stock'] < 5) ? 'table-warning' : '' ?>">
                        <td><?= $product['id'] ?></td>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['category']) ?></td>
                        <td class="text-end"><?= number_format($product['price'], 2) ?></td>
                        <td class="text-center">
                            <?php if ($product['stock'] < 5): ?>
                                <span class="badge bg-danger"><?= $product['stock'] ?> ⚠️</span>
                            <?php else: ?>
                                <span class="badge bg-success"><?= $product['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="<?= base_url('uploads/' . $product['image']) ?>"
                                     alt="<?= esc($product['name']) ?>"
                                     class="img-thumbnail" style="max-width: 60px;">
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (isAdmin()): ?>
                                <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="<?= base_url('products/delete/' . $product['id']) ?>" 
                                   onclick="return confirm('Are you sure you want to delete this product?');"
                                   class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                                <a href="<?= base_url('stock/adjust/' . $product['id']) ?>" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-box-seam"></i> Stock In/Out
                                </a>
                            <?php else: ?>
                                <span class="text-muted">🔒 Restricted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No products found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- 📑 Pagination -->
<div class="mt-3">
    <?= $pager->links('default', 'bootstrap') ?>
</div>

<?= $this->endSection() ?>
