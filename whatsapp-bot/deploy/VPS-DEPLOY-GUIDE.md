# 🚀 MamaCare WhatsApp Bot - VPS Deployment Guide (aaPanel)

## 📋 Requirements

- VPS (Ubuntu 20.04/22.04 au CentOS 7/8)
- RAM: 2GB+ (4GB recommended)
- Storage: 20GB SSD
- aaPanel installed
- Domain name (optional but recommended)

---

## 🛠️ Step 1: Prepare VPS

### 1.1 Install aaPanel (if not installed)

**Ubuntu/Debian:**
```bash
wget -O install.sh http://www.aapanel.com/script/install-ubuntu_6.0_en.sh && bash install.sh
```

**CentOS:**
```bash
yum install -y wget && wget -O install.sh http://www.aapanel.com/script/install_6.0_en.sh && bash install.sh
```

### 1.2 Login to aaPanel
- Open browser: `http://your-vps-ip:8888`
- Use credentials provided after installation

---

## 📦 Step 2: Install Dependencies in aaPanel

### 2.1 Install Node.js
1. Go to **aaPanel** → **App Store**
2. Search for **"Node.js"**
3. Click **Install**
4. Choose version **18.x LTS** (recommended)

### 2.2 Install PM2 (Process Manager)
```bash
# SSH into your VPS
ssh root@your-vps-ip

# Install PM2 globally
npm install -g pm2
```

### 2.3 Install Nginx (if not installed)
1. aaPanel → **App Store**
2. Search for **"Nginx"**
3. Click **Install**

---

## 📁 Step 3: Upload Bot Files

### 3.1 Create Directory
```bash
mkdir -p /www/wwwroot/mamacare-bot
```

### 3.2 Upload Files (3 options)

**Option A: Via aaPanel File Manager**
1. aaPanel → **Files**
2. Navigate to `/www/wwwroot/`
3. Create folder `mamacare-bot`
4. Upload all bot files

**Option B: Via SCP/SSH**
```bash
# From your local machine
scp -r whatsapp-bot/* root@your-vps-ip:/www/wwwroot/mamacare-bot/
```

**Option C: Via Git**
```bash
cd /www/wwwroot/mamacare-bot
git clone https://github.com/your-repo/mamacare-bot.git .
```

---

## ⚙️ Step 4: Configure Environment

### 4.1 Create .env File
```bash
cd /www/wwwroot/mamacare-bot
nano .env
```

**Paste this configuration:**
```env
# VPS Production Configuration
NODE_ENV=production
PORT=3000

# Security (CHANGE THESE!)
API_KEY=your_strong_api_key_here_12345

# CORS - Allow your Laravel domain
ALLOWED_ORIGINS=http://localhost,http://your-laravel-domain.com,https://your-laravel-domain.com

# Rate Limiting
RATE_LIMIT_WINDOW_MS=60000
RATE_LIMIT_MAX_REQUESTS=100

# Message Settings
DEFAULT_DELAY_MS=3000
MAX_RETRIES=3
RETRY_DELAY_MS=5000

# Logging
LOG_LEVEL=info
LOG_FILE=logs/whatsapp-bot.log

# Session
SESSION_PATH=./.wwebjs_auth

# MamaCare Laravel Integration
MAMACARE_BASE_URL=https://your-laravel-domain.com
MAMACARE_API_KEY=your_laravel_api_key_here
```

### 4.2 Install Dependencies
```bash
cd /www/wwwroot/mamacare-bot
npm install --production
```

### 4.3 Create Logs Directory
```bash
mkdir -p /www/wwwroot/mamacare-bot/logs
touch /www/wwwroot/mamacare-bot/logs/whatsapp-bot.log
```

---

## 🚀 Step 5: Start Bot with PM2

### 5.1 Start Bot
```bash
cd /www/wwwroot/mamacare-bot
pm2 start server.js --name "mamacare-bot"
```

### 5.2 Save PM2 Config
```bash
pm2 save
pm2 startup systemd
```

### 5.3 Monitor Bot
```bash
# Check status
pm2 status

# View logs
pm2 logs mamacare-bot

# Restart
pm2 restart mamacare-bot

# Stop
pm2 stop mamacare-bot
```

---

## 🌐 Step 6: Configure Nginx (aaPanel)

### 6.1 Add Website in aaPanel
1. aaPanel → **Website** → **Add Site**
2. **Domain**: `bot.yourdomain.com` (or use IP)
3. **Root Directory**: `/www/wwwroot/mamacare-bot`
4. **PHP**: Pure static (Node.js)

### 6.2 Configure Reverse Proxy
1. aaPanel → **Website** → Click your site → **Config**
2. Find **"Reverse Proxy"** tab
3. Click **Add Reverse Proxy**
4. **Target URL**: `http://127.0.0.1:3000`
5. **Domain**: `bot.yourdomain.com`
6. Save

### 6.3 Alternative: Manual Nginx Config
```bash
nano /www/server/panel/vhost/nginx/bot.yourdomain.com.conf
```

Paste this:
```nginx
server {
    listen 80;
    server_name bot.yourdomain.com;
    
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

Restart Nginx:
```bash
/etc/init.d/nginx restart
```

---

## 🔒 Step 7: SSL Certificate (HTTPS)

### 7.1 Apply SSL in aaPanel
1. aaPanel → **Website** → Click your site
2. Find **"SSL"** tab
3. Choose **"Let's Encrypt"**
4. Check **"Apply"**
5. Click **"Save"**

### 7.2 Force HTTPS (Optional)
1. In same SSL tab
2. Check **"Force HTTPS"**
3. Save

---

## 🔥 Step 8: Firewall Configuration

### 8.1 Open Ports in aaPanel
1. aaPanel → **Security** → **Firewall**
2. Add rules:
   - **Port 3000**: Allow (for localhost)
   - **Port 80**: Allow (HTTP)
   - **Port 443**: Allow (HTTPS)
   - **Port 8888**: Allow (aaPanel - if not already)

### 8.2 Or via SSH
```bash
# Ubuntu/Debian (UFW)
ufw allow 3000
ufw allow 80
ufw allow 443
ufw reload

# CentOS (Firewalld)
firewall-cmd --permanent --add-port=3000/tcp
firewall-cmd --permanent --add-port=80/tcp
firewall-cmd --permanent --add-port=443/tcp
firewall-cmd --reload
```

---

## ✅ Step 9: Test Deployment

### 9.1 Test Bot Health
```bash
curl http://bot.yourdomain.com/health
# or
curl http://your-vps-ip:3000/health
```

Expected response:
```json
{
  "status": "ok",
  "ready": true,
  "uptime": 123.456
}
```

### 9.2 Test Send Message
```bash
curl -X POST http://bot.yourdomain.com/send \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_strong_api_key_here_12345" \
  -d '{
    "number": "2557XXXXXXXX",
    "message": "Test from VPS! 🚀"
  }'
```

### 9.3 Scan QR Code
```bash
pm2 logs mamacare-bot
```
- Look for QR code in terminal
- Scan with WhatsApp (Linked Devices)

---

## 🔄 Step 10: Update Laravel .env

Update your Laravel `.env`:
```env
WHATSAPP_ENABLED=true
WHATSAPP_BOT_URL=https://bot.yourdomain.com
WHATSAPP_API_KEY=your_strong_api_key_here_12345
WHATSAPP_TIMEOUT=30
WHATSAPP_DEFAULT_DELAY=3000
WHATSAPP_MAX_RETRIES=3
```

---

## 📊 Step 11: Monitoring (aaPanel)

### 11.1 Enable Monitoring
1. aaPanel → **Monitor**
2. Enable **"Resource Monitor"**
3. Check **"Process Monitor"**

### 11.2 Log Rotation
```bash
# Install logrotate if not present
apt-get install logrotate

# Create config
nano /etc/logrotate.d/mamacare-bot
```

Paste:
```
/www/wwwroot/mamacare-bot/logs/*.log {
    daily
    rotate 7
    compress
    delaycompress
    missingok
    notifempty
    create 644 root root
}
```

---

## 🆘 Troubleshooting

### Bot won't start
```bash
# Check logs
pm2 logs mamacare-bot

# Check Node.js version
node -v

# Reinstall dependencies
cd /www/wwwroot/mamacare-bot
rm -rf node_modules
npm install
```

### QR Code not showing
```bash
# Delete session and restart
rm -rf /www/wwwroot/mamacare-bot/.wwebjs_auth
pm2 restart mamacare-bot
pm2 logs mamacare-bot
```

### Nginx 502 Error
```bash
# Check if bot is running
pm2 status

# Check port
netstat -tlnp | grep 3000

# Restart everything
pm2 restart mamacare-bot
/etc/init.d/nginx restart
```

### Permission Denied
```bash
# Fix permissions
chown -R root:root /www/wwwroot/mamacare-bot
chmod -R 755 /www/wwwroot/mamacare-bot
chmod -R 777 /www/wwwroot/mamacare-bot/logs
chmod -R 777 /www/wwwroot/mamacare-bot/.wwebjs_auth
```

---

## 🎉 Success!

Your WhatsApp Bot is now live on VPS! 🚀

**URLs:**
- Bot API: `https://bot.yourdomain.com`
- Health Check: `https://bot.yourdomain.com/health`
- aaPanel: `http://your-vps-ip:8888`

**Commands:**
```bash
# Check status
pm2 status

# View logs
pm2 logs mamacare-bot

# Restart
pm2 restart mamacare-bot
```

**Next Steps:**
1. Scan QR code to authenticate WhatsApp
2. Test sending message
3. Update Laravel to use new bot URL
4. Setup monitoring alerts

---

## 📞 Support

Kwa msaada zaidi:
- aaPanel Docs: https://www.aapanel.com
- WhatsApp Web.js: https://wwebjs.dev
- PM2 Docs: https://pm2.keymetrics.io
