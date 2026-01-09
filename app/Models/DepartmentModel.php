<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    protected $allowedFields = ['department_name', 'description', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
}