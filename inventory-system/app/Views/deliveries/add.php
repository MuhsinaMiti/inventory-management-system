<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Add Delivery</h2>

    <form action="<?= site_url('/deliveries/store') ?>" method="post">
        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                <option value="">Select a product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product->id ?>"><?= esc($product->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note (optional)</label>
            <textarea name="note" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Save Delivery</button>
        <a href="<?= site_url('/deliveries') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>
