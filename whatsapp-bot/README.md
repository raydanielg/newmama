# 🤖 MamaCare WhatsApp Bot

Node.js WhatsApp bridge kwa ajili ya MamaCare AI - inatumia `whatsapp-web.js` ku-connect na WhatsApp Web.

## 🎯 Features

- ✅ **Send Messages** - SMS kwa WhatsApp
- ✅ **Queue System** - Messages zinaenda taratibu
- ✅ **Auto-Retry** - Ikiwa imeshindwa, inajaribu tena
- ✅ **Status Tracking** - Inajua kama msg imefika
- ✅ **Bulk Messages** - Kutuma kwa watu wengi
- ✅ **Auto-Replies** - Majibu ya haraka kwa queries
- ✅ **Health Check** - Kuangalia kama bot iko sawa

## 🚀 Installation

### 1. Dependencies

```bash
cd whatsapp-bot
npm install
```

### 2. Configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Edit `.env` file:

```env
# Bot Configuration
PORT=3000
API_KEY=your_secret_api_key_here

# MamaCare Laravel URL
MAMACARE_BASE_URL=http://localhost:8000
MAMACARE_API_KEY=your_laravel_api_key

# Rate Limiting
RATE_LIMIT_MAX_REQUESTS=30
DEFAULT_DELAY_MS=3000
```

### 3. Start Bot

```bash
npm start
# Au kwa development:
npm run dev
```

### 4. Scan QR Code

- Bot itaonyesha **QR code** terminal
- Scan kwa WhatsApp yako (Linked Devices → Link a Device)

## 📡 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `POST /send` | POST | Tuma message moja |
| `POST /send-bulk` | POST | Tuma bulk messages |
| `GET /health` | GET | Angalia status |
| `GET /queue` | GET | Angalia queue |
| `GET /stats` | GET | Statistics |

### Example: Send Message

```bash
curl -X POST http://localhost:3000/send \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_secret_key" \
  -d '{
    "number": "2557XXXXXXXX",
    "message": "Habari! Karibu MamaCare 🤰",
    "priority": "high"
  }'
```

## 🔗 Laravel Integration

### 1. Environment Variables (.env)

```env
WHATSAPP_ENABLED=true
WHATSAPP_BOT_URL=http://localhost:3000
WHATSAPP_API_KEY=your_secret_api_key_here
```

### 2. Usage in Laravel

```php
use App\Services\WhatsAppService;

// Send message
$whatsApp = new WhatsAppService();
$whatsApp->sendMessage('2557XXXXXXXX', 'Habari Mama! 👋');

// Send to mother after registration
$whatsApp->sendRegistrationConfirmation($mother);

// Weekly pregnancy update
$whatsApp->sendWeeklyUpdate($mother);

// Health alert
$whatsApp->sendHealthAlert($mother, 'bp_high', 'BP yako ni kubwa kidogo');

// Appointment reminder
$whatsApp->sendAppointmentReminder($mother, $appointment);
```

## 📱 Auto-Replies

Bot inajibu automatically kwa keywords:

| Keyword | Reply |
|---------|-------|
| `mk` | MK Number yako |
| `login` | Login link |
| `dashboard` | Dashboard link |
| `help` | Commands list |
| `emergency` | Emergency numbers |

## ⚠️ Important Notes

1. **Don't Spam** - Delay kati ya messages (default 3 sec)
2. **Rate Limiting** - Max 30 requests per minute
3. **QR Code** - Lazima u-scan kila session mpya
4. **Session** - Session inahifadhiwa (`.wwebjs_auth`)

## 🛠️ Troubleshooting

### Bot won't start

```bash
# Delete session and restart
rm -rf .wwebjs_auth
npm start
```

### QR Code not showing

```bash
# Install qrcode-terminal manually
npm install qrcode-terminal
```

### Messages not sending

1. Check if `WHATSAPP_BOT_URL` iko sawa
2. Check API keys zinafanana
3. Check bot logs: `npm start`

## 📊 Logs

Logs ziko kwenye:
- `logs/whatsapp-bot.log`
- Console (real-time)

## 🔄 Webhook Flow

```
Mother Registers
      ↓
Laravel sends API request
      ↓
Node.js Bot sends WhatsApp msg
      ↓
WhatsApp delivers msg
      ↓
Bot notifies Laravel (webhook)
      ↓
Database updated with status
```

## 🎉 Test It!

1. Jiunge kama mama: `/join`
2. Check WhatsApp yako - umepokea msg!
3. Reply na `help` kuona auto-reply

## 🔒 Security

- Tumia strong API keys
- Keep `.env` file secret
- Don't commit `.wwebjs_auth`
- Use HTTPS kwa production

## 📞 Support

Kwa msaada zaidi:
- Email: support@mamacare.ai
- WhatsApp: 2557XXXXXXXX
