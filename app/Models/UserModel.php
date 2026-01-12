<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = [
        'username',
        'full_name',
        'email',
        'password',
        'role_id',
        'department_id',
        'phone_number',
        'photo_profile',
        'is_active',
        'last_login',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{user_id}]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,user_id,{user_id}]',
        'password' => 'required|min_length[6]',
        'role_id' => 'required|integer',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already registered'
        ]
    ];

    // Hash password before insert/update
    protected function beforeInsert(array $data)
    {
        $data = $this->hashPassword($data);
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['password'])) {
            $data = $this->hashPassword($data);
        }
        return $data;
    }

    private function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUsersWithRole($limit = null, $offset = 0)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*, r.role_name, d.department_name');
        $builder->join('roles r', 'r.role_id = u.role_id', 'left');
        $builder->join('departments d', 'd.department_id = u.department_id', 'left');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        $builder->orderBy('u.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function countUsersByRole()
    {
        $builder = $this->db->table('users u');
        $builder->select('r.role_name, COUNT(*) as count');
        $builder->join('roles r', 'r.role_id = u.role_id');
        $builder->groupBy('r.role_id', 'r.role_name');

        return $builder->get()->getResultArray();
    }

    public function searchUsers($keyword, $roleFilter = null, $statusFilter = null)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*, r.role_name, d.department_name');
        $builder->join('roles r', 'r.role_id = u.role_id', 'left');
        $builder->join('departments d', 'd.department_id = u.department_id', 'left');

        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('u.username', $keyword);
            $builder->orLike('u.full_name', $keyword);
            $builder->orLike('u.email', $keyword);
            $builder->groupEnd();
        }

        if (!empty($roleFilter)) {
            $builder->where('u.role_id', $roleFilter);
        }

        if (!empty($statusFilter)) {
            if ($statusFilter === 'active') {
                $builder->where('u.is_active', 1);
            } elseif ($statusFilter === 'inactive') {
                $builder->where('u.is_active', 0);
            }
        }

        $builder->orderBy('u.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}