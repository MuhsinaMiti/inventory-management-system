<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProductModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductController extends BaseController
{
    public function index()
    {
        $model = new \App\Models\ProductModel();

        $search   = $this->request->getGet('search');
        $category = $this->request->getGet('category');

        $builder = $model;

        if ($search) {
            $builder = $builder->groupStart()
                               ->like('name', $search)
                               ->orLike('category', $search)
                               ->groupEnd();
        }

        if ($category) {
            $builder = $builder->where('category', $category);
        }

        $products = $builder->paginate(5);

        $categories = $model->distinct()->select('category')->findAll();

        return view('products/index', [
            'products'   => $products,
            'pager'      => $model->pager,
            'title'      => 'Product List',
            'categories' => $categories
        ]);
    }

    public function create()
    {
        if (!isAdmin()) {
            return redirect()->to('/products')->with('error', 'Access denied.');
        }

        return view('products/create', ['title' => 'Add Product']);
    }

    public function store()
    {
        if (!isAdmin()) {
            return redirect()->to('/products')->with('error', 'Access denied.');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'name'     => 'required',
            'price'    => 'required|decimal',
            'stock'    => 'required|integer',
            'image'    => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = null;
        $predictedCategory = "Uncategorized"; // default

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);

            // ✅ Send image to FastAPI
            $fastApiUrl = "http://127.0.0.1:8000/classify-image/";
            $filePath   = ROOTPATH . 'public/uploads/' . $imageName;

            $curl = curl_init();
            $cfile = new \CURLFile($filePath, mime_content_type($filePath), basename($filePath));

            curl_setopt_array($curl, [
                CURLOPT_URL => $fastApiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ["file" => $cfile],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
    log_message('error', 'FastAPI error: ' . $err);
} else {
    $result = json_decode($response, true);

    // Log the raw response for debugging
    log_message('debug', 'FastAPI raw response: ' . $response);

    if (isset($result['predictions'][0]['mapped_category'])) {
    // Use FastAPI's mapped category directly
    $predictedCategory = $result['predictions'][0]['mapped_category'];
} elseif (isset($result['predictions'][0]['imagenet_label'])) {
    // Fallback: use our PHP mapCategory if only imagenet_label exists
    $predictedCategory = $this->mapCategory($result['predictions'][0]['imagenet_label']);
}

}

        }

        $productModel = new \App\Models\ProductModel();
        $productModel->save([
            'name'     => $this->request->getPost('name'),
            'category' => $predictedCategory, // ✅ Auto-filled category
            'price'    => $this->request->getPost('price'),
            'stock'    => $this->request->getPost('stock'),
            'image'    => $imageName
        ]);

        log_message('debug', 'FastAPI predicted label: ' . ($result['predictions'][0]['label'] ?? 'none'));

        return redirect()->to('/products')->with('success', 'Product added successfully!');
    }



    public function edit($id)
    {
        if (!isAdmin()) {
            return redirect()->to('/products')->with('error', 'Access denied.');
        }
        $productModel = new \App\Models\ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }

        return view('products/edit', [
            'title' => 'Edit Product',
            'product' => $product
        ]);
    }

    public function update($id)
    {
        if (!isAdmin()) {
            return redirect()->to('/products')->with('error', 'Access denied.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'name'     => 'required',
            'category' => 'required',
            'price'    => 'required|decimal',
            'stock'    => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $productModel = new \App\Models\ProductModel();
        $product      = $productModel->find($id);

        $image = $this->request->getFile('image');
        $imageName = $product['image']; // default to old image

        if ($image && $image->isValid() && !$image->hasMoved()) {
            if (!empty($product['image']) && file_exists(ROOTPATH . 'public/uploads/' . $product['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $product['image']);
            }

            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $newName);
            $imageName = $newName;
        }

        $productModel->update($id, [
            'name'     => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'price'    => $this->request->getPost('price'),
            'stock'    => $this->request->getPost('stock'),
            'image'    => $imageName
        ]);

        return redirect()->to('/products')->with('success', 'Product updated successfully!');
    }

    public function delete($id)
    {
        if (!isAdmin()) {
            return redirect()->to('/products')->with('error', 'Access denied.');
        }

        $model = new \App\Models\ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        if (!empty($product['image']) && file_exists(ROOTPATH . 'public/uploads/' . $product['image'])) {
            unlink(ROOTPATH . 'public/uploads/' . $product['image']);
        }

        $model->delete($id);

        return redirect()->to('/products')->with('success', 'Product deleted successfully');
    }

    // --------- Export Excel ----------------------
    public function export_excel()
    {
        $productModel = new ProductModel();
        $products = $productModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Price');
        $sheet->setCellValue('D1', 'Stock');
        $sheet->setCellValue('E1', 'Image Path');

        $row = 2;
        foreach ($products as $p) {
            $sheet->setCellValue('A' . $row, $p['id'] ?? '');
            $sheet->setCellValue('B' . $row, $p['name'] ?? '');
            $sheet->setCellValue('C' . $row, $p['price'] ?? '');
            $sheet->setCellValue('D' . $row, $p['stock'] ?? '');
            $sheet->setCellValue('E' . $row, $p['image'] ?? '');
            $row++;
        }

        $filename = 'products_' . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) { ob_end_clean(); }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // --------------- Import Excel -------------------------
    public function import_excel()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $file = $this->request->getFile('excel_file');
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File upload failed.');
        }

        $spreadsheet = IOFactory::load($file->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $productModel = new ProductModel();

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (!empty($row[1])) {
                $productModel->insert([
                    'name'  => $row[1],
                    'price' => $row[2] ?? 0,
                    'stock' => $row[3] ?? 0,
                    'image' => $row[4] ?? ''
                ]);
            }
        }

        return redirect()->back()->with('success', 'Products imported successfully.');
    }
}
