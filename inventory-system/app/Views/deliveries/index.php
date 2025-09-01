<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Deliveries</h2>
    <a href="<?= site_url('/deliveries/add') ?>" class="btn btn-primary mb-3">+ Add Delivery</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Note</th>
                <th>Delivered At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($deliveries)): ?>
                <?php foreach ($deliveries as $index => $delivery): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($delivery->product_name) ?></td>
                        <td><?= esc($delivery->quantity) ?></td>
                        <td><?= esc($delivery->note) ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($delivery->delivered_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No deliveries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
