<?php
namespace App\Helpers;

class WebSocketHelper
{
    private static $socketUrl = 'http://localhost:3000';
    
    /**
     * Kirim notifikasi ke WebSocket server
     */
    public static function sendNotification($data)
    {
        $url = self::$socketUrl . '/api/send-notification';
        
        $postData = [
            'recipient_id' => $data['recipient_id'] ?? null,
            'title' => $data['title'] ?? '',
            'message' => $data['message'] ?? '',
            'type' => $data['type'] ?? 'system',
            'ticket_id' => $data['ticket_id'] ?? null,
            'broadcast_role' => $data['broadcast_role'] ?? null,
            'department_id' => $data['department_id'] ?? null
        ];
        
        return self::sendToSocket($url, $postData);
    }
    
// Di WebSocketHelper.php, tambahkan method berikut:

/**
 * Kirim update statistik real-time
 */
public static function sendStatsUpdate($userId, $stats)
{
    $url = self::$socketUrl . '/api/stats-update';
    
    $postData = [
        'user_id' => $userId,
        'total_tickets' => $stats['total_tickets'] ?? 0,
        'open_tickets' => $stats['open_tickets'] ?? 0,
        'in_progress_tickets' => $stats['in_progress_tickets'] ?? 0,
        'resolved_tickets' => $stats['resolved_tickets'] ?? 0,
        'cancelled_tickets' => $stats['cancelled_tickets'] ?? 0,
        'tickets_this_month' => $stats['tickets_this_month'] ?? 0,
        'percentages' => $stats['percentages'] ?? []
    ];
    
    return self::sendToSocket($url, $postData);
}

/**
 * Kirim update profile real-time
 */
public static function sendProfileUpdate($userId, $profileData)
{
    $url = self::$socketUrl . '/api/profile-update';
    
    $postData = array_merge(['user_id' => $userId], $profileData);
    
    return self::sendToSocket($url, $postData);
}   


    /**
     * Kirim pesan chat real-time
     */
    public static function sendChatMessage($data)
    {
        $url = self::$socketUrl . '/api/send-message';
        
        $postData = [
            'ticket_id' => $data['ticket_id'],
            'sender_id' => $data['sender_id'],
            'message' => $data['message'],
            'is_internal' => $data['is_internal'] ?? false
        ];
        
        return self::sendToSocket($url, $postData);
    }
    
    /**
     * Update status ticket real-time
     */
    public static function updateTicketStatus($ticketId, $status, $updatedBy)
    {
        $url = self::$socketUrl . '/api/ticket-status-update';
        
        $postData = [
            'ticket_id' => $ticketId,
            'status' => $status,
            'updated_by' => $updatedBy
        ];
        
        return self::sendToSocket($url, $postData);
    }
    
    /**
     * Helper untuk mengirim data ke socket server
     */
    private static function sendToSocket($url, $data)
    {
        // Gunakan curl untuk mengirim data
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Timeout 3 detik
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            log_message('error', 'WebSocket error: ' . $error);
            return false;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Dapatkan WebSocket URL untuk frontend
     */
    public static function getSocketUrl()
    {
        return self::$socketUrl;
    }
}