<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>



<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome to the Dashboard, <?= session('user') ?>!</h2>

    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text display-6"><?= $totalProducts ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h5 class="card-title">Low Stock</h5>
                    <p class="card-text display-6"><?= $lowStock ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Pie Chart Section -->
<div class="container mt-4">
    <h4 class="text-center mb-3">Number of Products per Category</h4>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <canvas id="categoryPieChart"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('categoryPieChart').getContext('2d');

    const data = {
        labels: <?= $categories ?>,
        datasets: [{
            label: 'Number of Products',
            data: <?= $totals ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#00C49F', '#C70039', '#00BFFF', '#FFD700'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
</script>




<?= $this->endSection() ?>
