#!/bin/bash

# 🚀 MamaCare WhatsApp Bot - VPS Auto-Installer
# Usage: bash install.sh

set -e

echo "🤖 MamaCare WhatsApp Bot - VPS Installer"
echo "=========================================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ Please run as root${NC}"
    exit 1
fi

BOT_DIR="/www/wwwroot/mamacare-bot"
LOG_FILE="/var/log/mamacare-bot-install.log"

# Create log file
touch $LOG_FILE
exec 1> >(tee -a $LOG_FILE)
exec 2>&1

echo -e "${YELLOW}📦 Step 1: Installing dependencies...${NC}"

# Update system
apt-get update -y

# Install Node.js 18.x if not present
if ! command -v node &> /dev/null; then
    echo "Installing Node.js 18.x..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y nodejs
fi

echo "✅ Node.js version: $(node -v)"
echo "✅ NPM version: $(npm -v)"

# Install PM2
if ! command -v pm2 &> /dev/null; then
    echo "Installing PM2..."
    npm install -g pm2
fi
echo "✅ PM2 installed"

# Install Chrome dependencies for Puppeteer
echo -e "${YELLOW}📦 Installing Chrome dependencies...${NC}"
apt-get install -y \
    ca-certificates \
    fonts-liberation \
    libappindicator3-1 \
    libasound2 \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libc6 \
    libcairo2 \
    libcups2 \
    libdbus-1-3 \
    libexpat1 \
    libfontconfig1 \
    libgbm1 \
    libgcc1 \
    libglib2.0-0 \
    libgtk-3-0 \
    libnspr4 \
    libnss3 \
    libpango-1.0-0 \
    libpangocairo-1.0-0 \
    libstdc++6 \
    libx11-6 \
    libx11-xcb1 \
    libxcb1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxext6 \
    libxfixes3 \
    libxi6 \
    libxrandr2 \
    libxrender1 \
    libxss1 \
    libxtst6 \
    lsb-release \
    wget \
    xdg-utils

echo -e "${YELLOW}📁 Step 2: Creating directories...${NC}"

# Create bot directory
mkdir -p $BOT_DIR
mkdir -p $BOT_DIR/logs
mkdir -p $BOT_DIR/.wwebjs_auth

# Set permissions
chmod -R 755 $BOT_DIR
chmod -R 777 $BOT_DIR/logs
chmod -R 777 $BOT_DIR/.wwebjs_auth

echo -e "${YELLOW}⚙️ Step 3: Creating .env file...${NC}"

# Create default .env if not exists
if [ ! -f "$BOT_DIR/.env" ]; then
    cat > $BOT_DIR/.env <<EOF
# MamaCare WhatsApp Bot - Production Config
NODE_ENV=production
PORT=3000

# Security
API_KEY=$(openssl rand -hex 32)

# CORS
ALLOWED_ORIGINS=http://localhost,http://127.0.0.1

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

# MamaCare Integration (UPDATE THESE!)
MAMACARE_BASE_URL=http://localhost:8000
MAMACARE_API_KEY=your_laravel_api_key_here
EOF
    echo "✅ .env file created"
    echo -e "${YELLOW}⚠️ IMPORTANT: Edit .env file and update MAMACARE_BASE_URL and API keys!${NC}"
else
    echo "✅ .env file already exists"
fi

echo -e "${YELLOW}🔧 Step 4: Creating systemd service...${NC}"

# Create systemd service
cat > /etc/systemd/system/mamacare-bot.service <<EOF
[Unit]
Description=MamaCare WhatsApp Bot
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=$BOT_DIR
ExecStart=/usr/bin/node server.js
Restart=always
RestartSec=10
Environment=NODE_ENV=production
Environment=PORT=3000
StandardOutput=append:/var/log/mamacare-bot.log
StandardError=append:/var/log/mamacare-bot-error.log

[Install]
WantedBy=multi-user.target
EOF

# Reload systemd
systemctl daemon-reload
systemctl enable mamacare-bot

echo -e "${GREEN}✅ Installation Complete!${NC}"
echo ""
echo "📝 Next Steps:"
echo "1. Upload your bot files to: $BOT_DIR"
echo "2. Install dependencies: cd $BOT_DIR && npm install"
echo "3. Edit .env file: nano $BOT_DIR/.env"
echo "4. Start bot: systemctl start mamacare-bot"
echo "5. Check logs: journalctl -u mamacare-bot -f"
echo ""
echo "🌐 Useful Commands:"
echo "  Start:  systemctl start mamacare-bot"
echo "  Stop:   systemctl stop mamacare-bot"
echo "  Status: systemctl status mamacare-bot"
echo "  Logs:   journalctl -u mamacare-bot -f"
echo ""
echo "⚠️ IMPORTANT: Update .env file before starting!"
echo "   nano $BOT_DIR/.env"
