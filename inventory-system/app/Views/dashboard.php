<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <h1 class="mb-4">Welcome to the Dashboard, <?= session()->get('user') ?>!</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text fs-3">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title">Low Stock</h5>
                    <p class="card-text fs-3">0</p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
