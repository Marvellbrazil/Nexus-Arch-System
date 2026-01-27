// server.js
require('dotenv').config();
const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const mysql = require('mysql2/promise');
const cors = require('cors');

const app = express();
const server = http.createServer(app);

// Middleware
app.use(cors());
app.use(express.json());

// Konfigurasi dari environment variables
const PORT = process.env.WS_PORT || 3000;
const APP_URL = process.env.APP_URL || 'http://localhost:8080';
const DB_CONFIG = {
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'postgres',
    password: process.env.DB_PASSWORD || 'postgres',
    database: process.env.DB_NAME || 'nexus',
    port: process.env.DB_PORT || 5432
};

// WebSocket Server
const io = new Server(server, {
    cors: {
        origin: APP_URL,
        methods: ["GET", "POST"],
        credentials: true
    },
    pingTimeout: 60000,
    pingInterval: 25000
});

// Simpan koneksi user
const connectedUsers = new Map(); // socketId -> userData
const userRooms = new Map(); // userId -> rooms

// API endpoint untuk health check
app.get('/health', (req, res) => {
    res.json({ 
        status: 'online', 
        timestamp: new Date().toISOString(),
        connectedUsers: connectedUsers.size
    });
});

// API endpoint untuk mengirim notifikasi (dari PHP)
app.post('/api/send-notification', async (req, res) => {
    try {
        const { recipient_id, title, message, type, ticket_id, broadcast_role, department_id } = req.body;
        
        if (!title || !message) {
            return res.status(400).json({ success: false, message: 'Title and message are required' });
        }
        
        let recipients = [];
        
        if (recipient_id) {
            recipients.push(recipient_id.toString());
        } else if (broadcast_role) {
            recipients = await getUsersByRole(broadcast_role);
        } else if (department_id) {
            recipients = await getUsersByDepartment(department_id);
        }
        
        if (recipients.length === 0) {
            return res.status(400).json({ success: false, message: 'No recipients found' });
        }
        
        // Simpan ke database
        const notificationIds = [];
        const connection = await mysql.createConnection(DB_CONFIG);
        
        for (const userId of recipients) {
            const [result] = await connection.execute(
                `INSERT INTO notifications (user_id, title, message, notification_type, ticket_id, is_read, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [userId, title, message, type || 'system', ticket_id || null, 0, new Date()]
            );
            
            notificationIds.push(result.insertId);
            
            // Kirim ke user yang online
            const userSocket = Array.from(connectedUsers.entries())
                .find(([socketId, userData]) => userData.user_id == userId);
            
            if (userSocket) {
                const [socketId, userData] = userSocket;
                io.to(socketId).emit('new_notification', {
                    notification_id: result.insertId,
                    title,
                    message,
                    type: type || 'system',
                    ticket_id,
                    created_at: new Date().toISOString()
                });
            }
        }
        
        await connection.end();
        
        res.json({
            success: true,
            message: `Notification sent to ${recipients.length} user(s)`,
            notification_ids: notificationIds
        });
        
    } catch (error) {
        console.error('Error sending notification:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});
// Di server.js, tambahkan route berikut:

// API endpoint untuk update statistik
app.post('/api/stats-update', async (req, res) => {
    try {
        const { user_id, ...stats } = req.body;
        
        if (!user_id) {
            return res.status(400).json({ success: false, message: 'User ID is required' });
        }
        
        // Broadcast ke room user
        const userRoom = `user_${user_id}`;
        io.to(userRoom).emit('ticket_stats_updated', {
            ...stats,
            user_id,
            updated_at: new Date().toISOString()
        });
        
        res.json({
            success: true,
            message: 'Stats update sent'
        });
        
    } catch (error) {
        console.error('Error updating stats:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// API endpoint untuk update profile
app.post('/api/profile-update', async (req, res) => {
    try {
        const { user_id, ...profileData } = req.body;
        
        if (!user_id) {
            return res.status(400).json({ success: false, message: 'User ID is required' });
        }
        
        // Broadcast ke room user
        const userRoom = `user_${user_id}`;
        io.to(userRoom).emit('profile_updated', {
            ...profileData,
            user_id,
            updated_at: new Date().toISOString()
        });
        
        res.json({
            success: true,
            message: 'Profile update sent'
        });
        
    } catch (error) {
        console.error('Error updating profile:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});
// API endpoint untuk mengirim pesan chat
app.post('/api/send-message', async (req, res) => {
    try {
        const { ticket_id, sender_id, message, is_internal = false, sender_role } = req.body;
        
        if (!ticket_id || !sender_id || !message) {
            return res.status(400).json({ success: false, message: 'Missing required fields' });
        }
        
        const connection = await mysql.createConnection(DB_CONFIG);
        
        // Simpan ke database
        const table = is_internal ? 'internal_chat_messages' : 'ticket_messages';
        
        const [result] = await connection.execute(
            `INSERT INTO ${table} (ticket_id, sender_id, message, created_at) 
             VALUES (?, ?, ?, ?)`,
            [ticket_id, sender_id, message, new Date()]
        );
        
        const messageId = result.insertId;
        
        // Broadcast ke room ticket
        const roomName = `ticket_${ticket_id}`;
        io.to(roomName).emit('new_message', {
            message_id: messageId,
            ticket_id,
            sender_id,
            message,
            is_internal,
            sender_role: sender_role || 'User',
            created_at: new Date().toISOString()
        });
        
        await connection.end();
        
        res.json({
            success: true,
            message: 'Message sent successfully',
            message_id: messageId
        });
        
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// API endpoint untuk update status ticket
app.post('/api/ticket-status-update', async (req, res) => {
    try {
        const { ticket_id, status, updated_by } = req.body;
        
        if (!ticket_id || !status) {
            return res.status(400).json({ success: false, message: 'Missing required fields' });
        }
        
        const connection = await mysql.createConnection(DB_CONFIG);
        
        // Update database
        await connection.execute(
            `UPDATE tickets SET 
                status_id = (SELECT status_id FROM statuses WHERE status_name = ?),
                updated_at = ?,
                updated_by = ?
             WHERE ticket_id = ?`,
            [status, new Date(), updated_by || null, ticket_id]
        );
        
        // Broadcast ke room ticket
        const roomName = `ticket_${ticket_id}`;
        io.to(roomName).emit('ticket_status_changed', {
            ticket_id,
            status,
            updated_by,
            updated_at: new Date().toISOString()
        });
        
        await connection.end();
        
        res.json({
            success: true,
            message: 'Ticket status updated'
        });
        
    } catch (error) {
        console.error('Error updating ticket status:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// WebSocket connection handler
io.on('connection', (socket) => {
    console.log('New connection:', socket.id);
    

    // Di dalam io.on('connection', ...) tambahkan:
socket.on('subscribe_profile', (data) => {
    const { user_id } = data;
    const profileRoom = `profile_${user_id}`;
    socket.join(profileRoom);
    console.log(`Socket ${socket.id} subscribed to profile room: ${profileRoom}`);
});

socket.on('update_status', (data) => {
    const { user_id, status, last_active } = data;
    
    // Update user status in connectedUsers
    const userData = connectedUsers.get(socket.id);
    if (userData) {
        userData.status = status;
        userData.last_active = last_active;
        connectedUsers.set(socket.id, userData);
        
        // Broadcast status update to relevant rooms
        const personalRoom = `user_${user_id}`;
        io.to(personalRoom).emit('user_status_update', {
            user_id,
            status,
            last_active
        });
    }
});

// Handler untuk notification read
socket.on('notification_read', (data) => {
    const { notification_id, user_id } = data;
    
    // Broadcast ke user yang bersangkutan
    const userRoom = `user_${user_id}`;
    io.to(userRoom).emit('notification_read', {
        notification_id,
        user_id
    });
});
    // Authentication
    socket.on('authenticate', (data) => {
        try {
            const { user_id, role, department_id } = data;
            
            if (!user_id || !role) {
                socket.emit('error', { message: 'Authentication data incomplete' });
                return;
            }
            
            // Simpan user data
            connectedUsers.set(socket.id, {
                user_id,
                role,
                department_id,
                connected_at: new Date().toISOString()
            });
            
            // Join personal room
            const personalRoom = `user_${user_id}`;
            socket.join(personalRoom);
            
            // Join role-based room
            const roleRoom = `role_${role.toLowerCase()}`;
            socket.join(roleRoom);
            
            // Join department room jika ada
            if (department_id) {
                const deptRoom = `department_${department_id}`;
                socket.join(deptRoom);
            }
            
            // Simpan rooms user
            userRooms.set(user_id.toString(), [
                personalRoom,
                roleRoom,
                ...(department_id ? [`department_${department_id}`] : [])
            ]);
            
            console.log(`User ${user_id} (${role}) authenticated`);
            socket.emit('authenticated', {
                success: true,
                message: 'Authenticated successfully',
                user_id,
                role
            });
            
        } catch (error) {
            console.error('Authentication error:', error);
            socket.emit('error', { message: 'Authentication failed' });
        }
    });
    
    // Join ticket room
    socket.on('join_ticket', (data) => {
        const { ticket_id } = data;
        const roomName = `ticket_${ticket_id}`;
        socket.join(roomName);
        console.log(`Socket ${socket.id} joined ticket room: ${roomName}`);
    });
    
    // Leave ticket room
    socket.on('leave_ticket', (data) => {
        const { ticket_id } = data;
        const roomName = `ticket_${ticket_id}`;
        socket.leave(roomName);
        console.log(`Socket ${socket.id} left ticket room: ${roomName}`);
    });
    
    // Typing indicator
    socket.on('typing', (data) => {
        const { ticket_id, user_id, is_typing } = data;
        const roomName = `ticket_${ticket_id}`;
        socket.to(roomName).emit('user_typing', {
            user_id,
            is_typing,
            ticket_id
        });
    });
    
    // Disconnect handler
    socket.on('disconnect', () => {
        const userData = connectedUsers.get(socket.id);
        if (userData) {
            console.log(`User ${userData.user_id} disconnected`);
            connectedUsers.delete(socket.id);
            
            // Hapus dari userRooms
            if (userData.user_id) {
                userRooms.delete(userData.user_id.toString());
            }
        }
    });
});

// Helper functions
async function getUsersByRole(role) {
    try {
        const connection = await mysql.createConnection(DB_CONFIG);
        const [rows] = await connection.execute(
            `SELECT u.user_id FROM users u 
             JOIN roles r ON r.role_id = u.role_id 
             WHERE r.role_name = ? AND u.is_active = 1`,
            [role]
        );
        await connection.end();
        return rows.map(row => row.user_id.toString());
    } catch (error) {
        console.error('Error getting users by role:', error);
        return [];
    }
}

async function getUsersByDepartment(department_id) {
    try {
        const connection = await mysql.createConnection(DB_CONFIG);
        const [rows] = await connection.execute(
            `SELECT user_id FROM users 
             WHERE department_id = ? AND is_active = 1`,
            [department_id]
        );
        await connection.end();
        return rows.map(row => row.user_id.toString());
    } catch (error) {
        console.error('Error getting users by department:', error);
        return [];
    }
}

// Start server
server.listen(PORT, () => {
    console.log(`WebSocket server running on port ${PORT}`);
    console.log(`CORS enabled for: ${APP_URL}`);
    console.log(`Health check: http://localhost:${PORT}/health`);
});