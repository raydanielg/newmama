#!/bin/bash

# 🚀 MamaCare WhatsApp Bot - Quick Deploy Script
# Run this on your VPS after uploading files

set -e

BOT_DIR="/www/wwwroot/mamacare-bot"

echo "🚀 Deploying MamaCare WhatsApp Bot..."

# Check if directory exists
if [ ! -d "$BOT_DIR" ]; then
    echo "❌ Bot directory not found: $BOT_DIR"
    echo "Please run install.sh first or create directory manually"
    exit 1
fi

cd $BOT_DIR

echo "📦 Installing dependencies..."
npm install --production

echo "🔧 Setting permissions..."
chmod -R 755 $BOT_DIR
chmod -R 777 $BOT_DIR/logs 2>/dev/null || true
chmod -R 777 $BOT_DIR/.wwebjs_auth 2>/dev/null || true

echo "🚀 Starting bot with PM2..."

# Check if already running
if pm2 list | grep -q "mamacare-bot"; then
    echo "Restarting existing bot..."
    pm2 restart mamacare-bot
else
    echo "Starting new bot..."
    pm2 start server.js --name "mamacare-bot"
    pm2 save
fi

echo "✅ Bot deployed successfully!"
echo ""
echo "📊 Status:"
pm2 status

echo ""
echo "📝 View logs:"
echo "  pm2 logs mamacare-bot"
echo ""
echo "🌐 Health check:"
echo "  curl http://localhost:3000/health"
