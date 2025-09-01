<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class DeliveriesController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {   
        $check = requireAdmin();
        if ($check) return $check;


        $query = $this->db->query("SELECT d.*, p.name AS product_name 
                                   FROM deliveries d 
                                   JOIN products p ON d.product_id = p.id 
                                   ORDER BY d.delivered_at DESC");

        $data['deliveries'] = $query->getResult();

        return view('deliveries/index', $data);
    }

    public function add()
    {    

        $check = requireAdmin(); 
    if ($check) return $check;

        $productQuery = $this->db->query("SELECT id, name FROM products");
        $data['products'] = $productQuery->getResult();

        return view('deliveries/add', $data);
    }

   public function store()
{
    $check = requireAdmin(); 
    if ($check) return $check;

    if (!$this->validate([
        'product_id' => 'required|is_natural_no_zero',
        'quantity' => 'required|is_natural',
        'note' => 'permit_empty|string|max_length[255]',
    ])) {
        return redirect()->back()->withInput()->with('error', 'Invalid data submitted.');
    }

    $product_id = $this->request->getPost('product_id');
    $quantity = $this->request->getPost('quantity');
    $note = $this->request->getPost('note');

    // Check if enough stock is available
    $product = $this->db->query("SELECT stock FROM products WHERE id = ?", [$product_id])->getRow();
    if ($product && $product->stock < $quantity) {
        return redirect()->back()->withInput()->with('error', 'Not enough stock available for this delivery.');
    }

    // Insert delivery record
    $this->db->query("INSERT INTO deliveries (product_id, quantity, note) VALUES (?, ?, ?)", [
        $product_id, $quantity, $note
    ]);

    // Reduce stock
    $this->db->query("UPDATE products SET stock = stock - ? WHERE id = ?", [
        $quantity, $product_id
    ]);

    return redirect()->to('/deliveries')->with('success', 'Delivery recorded and stock updated.');
}


}
