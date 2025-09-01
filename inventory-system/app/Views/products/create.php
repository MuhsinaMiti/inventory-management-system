<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">➕ Add New Product</h2>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('products/store') ?>"enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" value="<?= old('name') ?>">
    </div>

    <div class="mb-3">
        <label>Category</label>
        <input type="text" name="category" class="form-control" value="<?= old('category') ?>">
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="text" name="price" class="form-control" value="<?= old('price') ?>">
    </div>

    <div class="mb-3">
        <label>Stock Quantity</label>
        <input type="number" name="stock" class="form-control" value="<?= old('stock') ?>">
    </div>


<div class="mb-3">
        <label for="image" class="form-label">Product Image</label>
        <input type="file" class="form-control" name="image">
    </div>

    <button type="submit" class="btn btn-success">Add Product</button>
    <a href="<?= base_url('products') ?>" class="btn btn-secondary">Cancel</a>

</form>
<?= $this->endSection() ?>
