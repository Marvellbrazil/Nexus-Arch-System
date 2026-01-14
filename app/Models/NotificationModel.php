<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'notification_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'ticket_id', 'title', 'message', 'is_read', 'notification_type'];

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

    public function getNotifications($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function countAllNotifications($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    public function countUnreadNotifications($userId)
    {
        return $this->where(['user_id' => $userId, 'is_read' => false])->countAllResults();
    }

    public function countNotificationsThisWeek($userId)
    {
        return $this->where('user_id', $userId)
            ->where('created_at >=', date('Y-m-d', strtotime('-1 week')))
            ->countAllResults();
    }

    public function createNotification($data)
    {
        return $this->insert($data);
    }

public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)->set(['is_read' => true])->update();
    }

    public function getNotificationsForCustomer($userId)
    {
        return $this->select('notification_id, title, message, created_at, notification_type, is_read, ticket_id')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getNotificationStatsForCustomer($userId)
    {
        return [
            'total_notifications' => $this->where('user_id', $userId)->countAllResults(),
            'unread_notifications' => $this->where(['user_id' => $userId, 'is_read' => false])->countAllResults(),
            'this_week_notifications' => $this->where('user_id', $userId)
                ->where('created_at >=', date('Y-m-d', strtotime('-1 week')))
                ->countAllResults(),
        ];
    }
}
