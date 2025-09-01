<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Adjust Stock for: <?= esc($product['name']) ?></h2>

<form method="post" action="<?= base_url('stock/adjust/' . $product['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-select" required>
            <option value="in">Stock In</option>
            <option value="out">Stock Out</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Note (optional)</label>
        <input type="text" name="note" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Update Stock</button>
    <a href="<?= base_url('products') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>
