
// [Optional Feature] This controller is reserved for future use: to track internal stock usage separately from deliveries.



<?php

require_once 'BaseController.php'; 

class UsagesController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $query = $this->db->query("
            SELECT usages.*, products.name 
            FROM usages 
            JOIN products ON usages.product_id = products.id
            ORDER BY used_at DESC
        ");
        $usages = $query->fetchAll();
        include 'views/usages/index.php';
    }

    public function create()
    {
        $products = $this->db->query("SELECT id, name, stock FROM products")->fetchAll();
        include 'views/usages/create.php';
    }

    public function store()
    {
        $product_id = $_POST['product_id'];
        $quantity   = $_POST['quantity'];
        $note       = $_POST['note'];

        // Get current stock
        $product = $this->db->prepare("SELECT stock FROM products WHERE id = ?");
        $product->execute([$product_id]);
        $stock = $product->fetchColumn();

        if ($quantity > $stock) {
            echo "Error: Not enough stock available.";
            return;
        }

        // Insert usage
        $stmt = $this->db->prepare("INSERT INTO usages (product_id, quantity, note) VALUES (?, ?, ?)");
        $stmt->execute([$product_id, $quantity, $note]);

        // Reduce stock
        $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")
                 ->execute([$quantity, $product_id]);

        header("Location: index.php?page=usages");
        exit;
    }
}
