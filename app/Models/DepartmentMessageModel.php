<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentMessageModel extends Model
{
    protected $table = 'ticket_messages';
    protected $primaryKey = 'message_id';
    protected $allowedFields = ['ticket_id', 'sender_id', 'message', 'is_department_chat', 'department_id', 'is_internal'];
    
    public function getDepartmentMessages($ticketId, $departmentId)
    {
        return $this->select('tm.*, u.full_name, u.role_id, r.role_name, d.department_name as sender_department')
            ->from('ticket_messages tm', true)
            ->join('users u', 'u.user_id = tm.sender_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.is_department_chat', 1)
            ->where('tm.department_id', $departmentId)
            ->orderBy('tm.created_at', 'ASC')
            ->findAll();
    }
    
    public function getLatestDepartmentMessage($ticketId, $departmentId)
    {
        return $this->select('tm.*, u.full_name')
            ->from('ticket_messages tm', true)
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.is_department_chat', 1)
            ->where('tm.department_id', $departmentId)
            ->orderBy('tm.created_at', 'DESC')
            ->first();
    }
}