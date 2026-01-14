<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketAttachmentModel extends Model
{
    protected $table            = 'ticket_attachments';
    protected $primaryKey       = 'attachment_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

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

    public function getAttachmentsForTicket($ticketId)
    {
        return $this->builder('ticket_attachments ta')
            ->select('ta.*, u.full_name')
            ->join('users u', 'u.user_id = ta.uploaded_by')
            ->where('ta.ticket_id', $ticketId)
            ->get()
            ->getResultArray();
    }

    public function createAttachment($data)
    {
        return $this->insert($data);
    }
}
