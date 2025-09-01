<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>📦 Stock Logs</h2>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Change</th>
                <th>Quantity</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($logs)) : ?>
                <?php foreach ($logs as $log) : ?>
                    <tr>
                        <td><?= $log['id'] ?></td>
                        <td><?= esc($log['product_name']) ?></td>
                        <td>
                        <?= ($log['type'] === 'in') ? '➕ Added' : '➖ Removed' ?>
                        </td>
                        <td><?= $log['quantity'] ?></td>
                        <td><?= esc($log['note']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No stock logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= base_url('products') ?>" class="btn btn-secondary">← Back to Products</a>
</div>

<?= $this->endSection() ?>
