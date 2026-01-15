<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketMessageModel extends Model
{
    protected $table            = 'ticket_messages';
    protected $primaryKey       = 'message_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ticket_id', 'sender_id', 'message', 'is_internal'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getMessagesForTicket($ticketId)
    {
        return $this->builder('ticket_messages tm')
            ->select('tm.*, u.full_name, u.photo_profile, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('tm.ticket_id', $ticketId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getRecentActivityForProject($projectId, $userId)
    {
        return $this->builder('ticket_messages tm')
            ->select('tm.*, t.subject, u.full_name, u.photo_profile')
            ->join('tickets t', 't.ticket_id = tm.ticket_id')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('t.project_id', $projectId)
            ->where('t.customer_id', $userId)
            ->orderBy('tm.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }
}
