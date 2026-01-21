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
    protected $allowedFields    = ['ticket_id', 'sender_id', 'message'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
    protected $deletedField  = null;

    public function getMessagesForTicket($ticketId)
    {
        return $this->select('tm.*, u.full_name, u.photo_profile, r.role_name')
            ->from('ticket_messages tm', true)
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getNewMessages($ticketId, $lastMessageId = 0)
    {
        return $this->select('tm.*, u.full_name, u.photo_profile, r.role_name')
            ->from('ticket_messages tm', true)
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.message_id >', $lastMessageId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function addMessage($ticketId, $senderId, $message)
    {
        return $this->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $senderId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getLastMessageId($ticketId)
    {
        $result = $this->select('message_id')
            ->where('ticket_id', $ticketId)
            ->orderBy('message_id', 'DESC')
            ->first();
        
        return $result ? $result['message_id'] : 0;
    }
}