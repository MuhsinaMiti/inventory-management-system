<?php

namespace App\Models;

use CodeIgniter\Model;

class StockLogModel extends Model
{
    protected $table      = 'stock_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = ['product_id', 'type', 'quantity', 'note'];

    //  This line disables automatic timestamp saving
    protected $useTimestamps = false;
}
