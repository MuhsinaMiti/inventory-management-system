<?php

namespace App\Controllers;

use App\Models\ProductModel;

class DashboardController extends BaseController
{
    public function index()
    {   
        $check = requireAdmin();
        if ($check) return $check;


        $productModel = new ProductModel();

        // Total and low stock products
        $totalProducts = $productModel->countAll();
        $lowStock = $productModel->where('stock <', 5)->countAllResults();

        // Count of products per category
        $categoriesData = $productModel
            ->select('category, COUNT(*) as total')
            ->groupBy('category')
            ->findAll();

        $categories = [];
        $totals = [];

        foreach ($categoriesData as $row) {
            $categories[] = $row['category'];
            $totals[] = $row['total'];
        }

        return view('dashboard/index', [
            'totalProducts' => $totalProducts,
            'lowStock' => $lowStock,
            'categories' => json_encode($categories),
            'totals' => json_encode($totals)
        ]);
    }
}
