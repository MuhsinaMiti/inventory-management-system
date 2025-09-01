<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2 class="mb-4">✏️ Edit Product</h2>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('products/update/' . $product['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" value="<?= old('name', $product['name']) ?>">
    </div>

    <div class="mb-3">
        <label>Category</label>
        <input type="text" name="category" class="form-control" value="<?= old('category', $product['category']) ?>">
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="text" name="price" class="form-control" value="<?= old('price', $product['price']) ?>">
    </div>

    <div class="mb-3">
        <label>Stock Quantity</label>
        <input type="number" name="stock" class="form-control" value="<?= old('stock', $product['stock']) ?>">
    </div>

    <!-- Optional: Show existing image -->
<?php if (!empty($product['image'])): ?>
    <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="<?= base_url('uploads/' . $product['image']) ?>" alt="Image" width="100">
    </div>
<?php endif; ?>

<!-- File input -->
<div class="mb-3">
    <label for="image" class="form-label">Change Image (optional)</label>
    <input type="file" name="image" class="form-control" accept="image/*">

</div>



    <button type="submit" class="btn btn-success">Update Product</button>
    <a href="<?= base_url('products') ?>" class="btn btn-secondary">Cancel</a>

</form>
<?= $this->endSection() ?>
