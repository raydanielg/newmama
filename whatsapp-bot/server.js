const { Client, LocalAuth } = require('whatsapp-web.js');
const express = require('express');
const qrcode = require('qrcode-terminal');
const cors = require('cors');
const winston = require('winston');
require('dotenv').config();

// 📝 Setup Logger
const logger = winston.createLogger({
    level: process.env.LOG_LEVEL || 'info',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
    ),
    transports: [
        new winston.transports.Console(),
        new winston.transports.File({ filename: process.env.LOG_FILE || 'logs/whatsapp-bot.log' })
    ]
});

// 🚀 Express App
const app = express();
app.use(express.json());
app.use(cors({
    origin: process.env.ALLOWED_ORIGINS?.split(',') || ['http://localhost', 'http://127.0.0.1:8000']
}));

// 🔐 Simple API Key Middleware
const apiKeyMiddleware = (req, res, next) => {
    const apiKey = req.headers['x-api-key'];
    if (apiKey !== process.env.API_KEY) {
        return res.status(401).json({ 
            status: 'error', 
            message: 'Unauthorized. Invalid or missing API key.' 
        });
    }
    next();
};

// 📊 Stats
let stats = {
    messagesSent: 0,
    messagesFailed: 0,
    lastActivity: null,
    isReady: false
};

// 🔄 Message Queue System
const messageQueue = [];
let isProcessing = false;

// ⏱️ Delay function
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// 📤 Process message queue
async function processQueue() {
    if (isProcessing || messageQueue.length === 0) return;
    
    isProcessing = true;
    
    while (messageQueue.length > 0) {
        const item = messageQueue.shift();
        
        try {
            await sendMessageWithRetry(item.number, item.message, item.retries || 0);
            await delay(parseInt(process.env.DEFAULT_DELAY_MS) || 3000);
        } catch (error) {
            logger.error('Queue processing error:', error);
        }
    }
    
    isProcessing = false;
}

// 📨 Send message with retry logic
async function sendMessageWithRetry(number, message, retries = 0) {
    try {
        const chatId = formatNumber(number);
        
        logger.info(`Sending message to ${chatId}`, { 
            number: chatId, 
            messageLength: message.length,
            attempt: retries + 1 
        });

        const response = await client.sendMessage(chatId, message);
        
        stats.messagesSent++;
        stats.lastActivity = new Date().toISOString();
        
        logger.info('Message sent successfully', { 
            messageId: response.id.id,
            to: chatId 
        });

        // Notify Laravel of success
        notifyLaravel({
            status: 'sent',
            number: number,
            messageId: response.id.id,
            timestamp: new Date().toISOString()
        });

        return { success: true, messageId: response.id.id };
        
    } catch (error) {
        logger.error('Failed to send message', { 
            error: error.message, 
            number,
            retries 
        });

        if (retries < (parseInt(process.env.MAX_RETRIES) || 3)) {
            logger.info(`Retrying... (${retries + 1}/${process.env.MAX_RETRIES || 3})`);
            await delay(parseInt(process.env.RETRY_DELAY_MS) || 5000);
            return sendMessageWithRetry(number, message, retries + 1);
        }

        stats.messagesFailed++;
        
        // Notify Laravel of failure
        notifyLaravel({
            status: 'failed',
            number: number,
            error: error.message,
            timestamp: new Date().toISOString()
        });

        throw error;
    }
}

// 📱 Format number to WhatsApp chat ID
function formatNumber(number) {
    // Remove any non-digit characters except +
    let cleaned = number.replace(/[^\d+]/g, '');
    
    // Remove + if present at start
    if (cleaned.startsWith('+')) {
        cleaned = cleaned.substring(1);
    }
    
    // Add @c.us suffix
    return cleaned + '@c.us';
}

// 🔔 Notify Laravel about message status
async function notifyLaravel(data) {
    try {
        const baseUrl = process.env.MAMACARE_BASE_URL || 'http://localhost:8000';
        
        // This will be implemented in Laravel webhook
        const response = await fetch(`${baseUrl}/api/webhooks/whatsapp-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-API-Key': process.env.MAMACARE_API_KEY || ''
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            logger.warn('Failed to notify Laravel', { status: response.status });
        }
    } catch (error) {
        logger.error('Error notifying Laravel:', error.message);
    }
}

// 🤖 WhatsApp Client Setup
const client = new Client({
    authStrategy: new LocalAuth({
        dataPath: process.env.SESSION_PATH || './.wwebjs_auth'
    }),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--single-process',
            '--disable-gpu'
        ]
    }
});

// 📱 QR Code Event
client.on('qr', (qr) => {
    logger.info('QR Code received. Scan to authenticate.');
    qrcode.generate(qr, { small: true });
    stats.isReady = false;
});

// ✅ Ready Event
client.on('ready', () => {
    logger.info('WhatsApp Client is ready! 🚀');
    stats.isReady = true;
    console.log('\n✅ WhatsApp Bot tayari!\n📡 API inasikiliza port:', process.env.PORT || 3000);
});

// 🔌 Disconnected Event
client.on('disconnected', (reason) => {
    logger.warn('WhatsApp Client disconnected:', reason);
    stats.isReady = false;
});

// ❌ Auth Failure
client.on('auth_failure', (msg) => {
    logger.error('Auth failure:', msg);
    stats.isReady = false;
});

// 📩 Incoming Message
client.on('message', async (msg) => {
    logger.info('Received message', { 
        from: msg.from, 
        body: msg.body.substring(0, 50) 
    });
    
    // Auto-reply for MamaCare
    if (msg.body.toLowerCase().includes('mamacare') || msg.body.toLowerCase().includes('help')) {
        await msg.reply(
            "🤰 *MamaCare AI*\n\n" +
            "Karibu! Ninaweza kukusaidia na:\n" +
            "• Ufuatiliaji wa mimba\n" +
            "• Vipimo vya afya\n" +
            "• Ushauri wa kiafya\n\n" +
            "Ingia kwenye dashboard yako:\n" +
            "🔗 http://localhost:8000/mother/login\n\n" +
            "*Dharura? Piga 114* 🚨"
        );
    }
});

// 🌐 API Routes

// Health Check
app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        ready: stats.isReady,
        uptime: process.uptime(),
        stats: {
            messagesSent: stats.messagesSent,
            messagesFailed: stats.messagesFailed,
            lastActivity: stats.lastActivity
        },
        timestamp: new Date().toISOString()
    });
});

// Send Single Message
app.post('/send', apiKeyMiddleware, async (req, res) => {
    const { number, message, priority = 'normal' } = req.body;
    
    if (!number || !message) {
        return res.status(400).json({
            status: 'error',
            message: 'Missing required fields: number, message'
        });
    }
    
    if (!stats.isReady) {
        return res.status(503).json({
            status: 'error',
            message: 'WhatsApp client not ready. Please scan QR code first.'
        });
    }
    
    try {
        // Add to queue for normal priority, send immediately for high priority
        if (priority === 'high') {
            const result = await sendMessageWithRetry(number, message);
            res.json({
                status: 'success',
                message: 'Message sent immediately (high priority)',
                data: result
            });
        } else {
            messageQueue.push({ number, message, retries: 0 });
            processQueue();
            
            res.json({
                status: 'queued',
                message: 'Message queued for delivery',
                position: messageQueue.length
            });
        }
        
    } catch (error) {
        logger.error('Error sending message:', error);
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
});

// Send Bulk Messages
app.post('/send-bulk', apiKeyMiddleware, async (req, res) => {
    const { messages, delay = 3000 } = req.body;
    
    if (!Array.isArray(messages) || messages.length === 0) {
        return res.status(400).json({
            status: 'error',
            message: 'Missing or invalid messages array'
        });
    }
    
    if (!stats.isReady) {
        return res.status(503).json({
            status: 'error',
            message: 'WhatsApp client not ready'
        });
    }
    
    // Add all to queue
    messages.forEach(msg => {
        messageQueue.push({
            number: msg.number,
            message: msg.message,
            retries: 0
        });
    });
    
    processQueue();
    
    res.json({
        status: 'queued',
        message: `${messages.length} messages queued`,
        totalQueue: messageQueue.length
    });
});

// Get Queue Status
app.get('/queue', apiKeyMiddleware, (req, res) => {
    res.json({
        queueLength: messageQueue.length,
        isProcessing,
        stats
    });
});

// 📊 Stats Endpoint
app.get('/stats', apiKeyMiddleware, (req, res) => {
    res.json({
        ...stats,
        memory: process.memoryUsage(),
        uptime: process.uptime()
    });
});

// 🏠 Root
app.get('/', (req, res) => {
    res.json({
        name: 'MamaCare WhatsApp Bot',
        version: '1.0.0',
        status: stats.isReady ? 'ready' : 'initializing',
        endpoints: [
            'POST /send - Send single message',
            'POST /send-bulk - Send bulk messages',
            'GET /health - Health check',
            'GET /queue - Queue status',
            'GET /stats - Bot statistics'
        ]
    });
});

// 🚀 Start Server
const PORT = process.env.PORT || 3000;

// Initialize WhatsApp client
client.initialize();

// Start Express server
app.listen(PORT, () => {
    logger.info(`WhatsApp Bot API running on port ${PORT}`);
    console.log(`\n🤖 MamaCare WhatsApp Bot\n📡 Port: ${PORT}\n⏳ Waiting for WhatsApp authentication...\n`);
});

// Graceful shutdown
process.on('SIGINT', async () => {
    logger.info('Shutting down gracefully...');
    await client.destroy();
    process.exit(0);
});

process.on('SIGTERM', async () => {
    logger.info('Shutting down gracefully...');
    await client.destroy();
    process.exit(0);
});
