<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportStatsModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticket_id';

    public function getInProgressTicketsCount($userId)
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['In Progress', 'Processing'])
            ->countAllResults();
    }

    public function getNeedsAttentionCount($userId)
    {
        return $this->builder('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('p.priority_name', ['Urgent', 'High'])
            ->whereIn('s.status_name', ['Open', 'In Progress'])
            ->countAllResults();
    }

    public function getWaitingCustomerReplyCount($userId)
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->where('s.status_name', 'Waiting Customer Reply')
            ->countAllResults();
    }

    public function getIncomingTicketsCount()
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->countAllResults();
    }

    public function getNewTodayCount()
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->where('DATE(t.created_at)', date('Y-m-d'))
            ->countAllResults();
    }

    public function getAgentsOnlineCount()
    {
        return $this->builder('users u')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('r.role_name', 'Support')
            ->where('u.is_active', true)
            ->countAllResults();
    }

    public function getAgentsInMeetingCount()
    {
        return $this->builder('users u')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('r.role_name', 'Support')
            ->where('u.in_meeting', true)
            ->countAllResults();
    }

    public function getRecentTicketsForSupport($limit = 3)
    {
        return $this->builder('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.subject,
                 p.priority_name,
                 s.status_name,
                 c.category_name,
                 u.full_name as customer_name,
                 proj.project_name,
                 t.created_at')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories c', 'c.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getIncomingTicketsForSupport()
    {
        return $this->builder('tickets t')
            ->select('t.*, 
                p.priority_name, p.priority_id,
                s.status_name, 
                cat.category_name, 
                u.full_name as customer_name, u.email as customer_email,
                proj.project_name, proj.project_id,
                d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('p.priority_id', 'DESC')
            ->orderBy('t.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getForwardedTodayCount()
    {
        return $this->builder('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('s.status_name', 'Forwarded')
            ->where('DATE(t.updated_at)', date('Y-m-d'))
            ->countAllResults();
    }

    public function getHighPriorityIncomingCount()
    {
        return $this->builder('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->whereIn('p.priority_name', ['Urgent', 'High'])
            ->countAllResults();
    }

    public function assignTicketToUser($ticketId, $userId)
    {
        return $this->builder()
            ->where('ticket_id', $ticketId)
            ->update([
                'assigned_to' => $userId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
}
