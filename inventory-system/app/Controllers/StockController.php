<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\StockLogModel;

class StockController extends BaseController
{  

    

    public function adjustForm($id)
    {   
       $check = requireAdmin();
    if ($check) return $check;

       if (!isAdmin()) {
        return redirect()->to('/products')->with('error', 'Access denied.');
    }
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        return view('stock/adjust', ['product' => $product]);
    }

    public function adjust($id)
    {    
        $check = requireAdmin(); 
    if ($check) return $check;

     if (!isAdmin()) {
        return redirect()->to('/products')->with('error', 'Access denied.');
    } 
     echo "📦 adjust() is running<br>";

        $type     = $this->request->getPost('type'); // in/out
        $quantity = (int)$this->request->getPost('quantity');
        $note     = $this->request->getPost('note');

        $productModel = new ProductModel();
        $stockLogModel = new StockLogModel();

        $product = $productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }

        if ($type === 'in') {
            $newStock = $product['stock'] + $quantity;
        } elseif ($type === 'out') {
            $newStock = $product['stock'] - $quantity;
            if ($newStock < 0) $newStock = 0;
        } else {
            return redirect()->back()->with('error', 'Invalid stock type');
        }

        // Update product stock
        $productModel->update($id, ['stock' => $newStock]);

        // Add log
        $stockLogModel->save([
            'product_id' => $id,
            'type'       => $type,
            'quantity'   => $quantity,
            'note'       => $note
        ]);

        return redirect()->to('/products')->with('success', 'Stock updated successfully');
    }

    
    public function logs()
{

    $check = requireAdmin(); 
    if ($check) return $check;
    
    $model = new \App\Models\StockLogModel();
    $logs = $model->select('stock_logs.*, products.name AS product_name')
                  ->join('products', 'products.id = stock_logs.product_id')
                  ->orderBy('stock_logs.id', 'DESC')
                  ->findAll();

    return view('stock/logs', ['logs' => $logs]);
}



}
