<?php

namespace App\Services;

use App\Models\WhatsappMessage;
use App\Models\Mother;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.bot_url', 'http://localhost:3000');
        $this->apiKey = config('services.whatsapp.api_key', '');
        $this->timeout = config('services.whatsapp.timeout', 30);
    }

    /**
     * 🚀 Send WhatsApp Message
     */
    public function sendMessage(string $number, string $message, string $type = 'general', array $metadata = []): array
    {
        try {
            // Format number (ensure it has country code)
            $formattedNumber = $this->formatNumber($number);

            // Save to database first
            $whatsappMessage = WhatsappMessage::create([
                'to_number' => $formattedNumber,
                'message' => $message,
                'type' => $type,
                'status' => 'pending',
                'metadata' => $metadata,
                'sent_at' => now(),
            ]);

            // Send to Node.js bot
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/send", [
                'number' => $formattedNumber,
                'message' => $message,
                'priority' => $metadata['priority'] ?? 'normal',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update status
                $whatsappMessage->update([
                    'status' => $data['status'] === 'success' ? 'sent' : 'queued',
                    'external_id' => $data['data']['messageId'] ?? null,
                    'response_data' => $data,
                ]);

                Log::info('WhatsApp message sent', [
                    'message_id' => $whatsappMessage->id,
                    'number' => $formattedNumber,
                    'status' => $data['status'],
                ]);

                return [
                    'success' => true,
                    'status' => $data['status'],
                    'message_id' => $whatsappMessage->id,
                    'data' => $data,
                ];
            }

            // Handle failure
            $whatsappMessage->update([
                'status' => 'failed',
                'response_data' => $response->json(),
            ]);

            Log::error('WhatsApp API error', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send message',
                'response' => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'error' => $e->getMessage(),
                'number' => $number,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 📤 Send Bulk Messages
     */
    public function sendBulk(array $messages, int $delay = 3000): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/send-bulk", [
                'messages' => $messages,
                'delay' => $delay,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp bulk send error', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * ✅ Check Bot Health
     */
    public function checkHealth(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/health");
            
            if ($response->successful()) {
                return [
                    'online' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'online' => false,
                'error' => 'Health check failed',
            ];

        } catch (\Exception $e) {
            return [
                'online' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 🎉 Send Welcome Message to New Mother
     */
    public function sendWelcomeMessage(Mother $mother): array
    {
        $message = $this->buildWelcomeMessage($mother);
        
        return $this->sendMessage(
            $mother->whatsapp_number,
            $message,
            'welcome',
            ['mother_id' => $mother->id, 'priority' => 'high']
        );
    }

    /**
     * 📋 Send Registration Confirmation
     */
    public function sendRegistrationConfirmation(Mother $mother): array
    {
        $mkNumber = $mother->mk_number ?? 'N/A';
        
        $message = "🎉 *Hongera! Usajili Wako Umekamilika*\n\n";
        $message .= "Jina: {$mother->full_name}\n";
        $message .= "MK Number: *{$mkNumber}*\n";
        $message .= "WhatsApp: {$mother->whatsapp_number}\n\n";
        
        $message .= "🔗 *Ingia kwenye Dashboard yako:*\n";
        $message .= config('app.url') . "/mother/login\n\n";
        
        $message .= "🤰 MamaCare AI itakufuatilia kupitia:\n";
        $message .= "• Ushauri wa kiafya\n";
        $message .= "• Vipimo vya afya\n";
        $message .= "• Vipindi vya kliniki\n\n";
        
        $message .= "🚨 *Dharura? Piga 114 mara moja!*\n\n";
        $message .= "Asante kwa kuchagua MamaCare AI ❤️";

        return $this->sendMessage(
            $mother->whatsapp_number,
            $message,
            'registration',
            ['mother_id' => $mother->id, 'priority' => 'high']
        );
    }

    /**
     * 📊 Send Weekly Pregnancy Update
     */
    public function sendWeeklyUpdate(Mother $mother): array
    {
        $weeks = $mother->weeks_pregnant ?? 0;
        $trimester = $mother->trimester ?? 1;
        
        $tips = $this->getWeeklyTips($weeks);
        
        $message = "🤰 *MamaCare - Wiki ya {$weeks}*\n\n";
        $message .= "Habari {$mother->first_name},\n\n";
        $message .= "Uko trimester {$trimester}, wiki {$weeks}.\n\n";
        
        $message .= "💡 *Ushauri wa Wiki:*\n";
        $message .= $tips . "\n\n";
        
        $message .= "🔗 *Angalia Dashboard yako:*\n";
        $message .= config('app.url') . "/mother/dashboard\n\n";
        
        $message .= "🚨 *Dharura? Piga 114*";

        return $this->sendMessage(
            $mother->whatsapp_number,
            $message,
            'weekly_update',
            ['mother_id' => $mother->id, 'weeks' => $weeks]
        );
    }

    /**
     * 🔔 Send Health Alert
     */
    public function sendHealthAlert(Mother $mother, string $alertType, string $message): array
    {
        $formattedMessage = "⚠️ *MamaCare Alert* ⚠️\n\n";
        $formattedMessage .= "Habari {$mother->first_name},\n\n";
        $formattedMessage .= $message . "\n\n";
        $formattedMessage .= "🔗 *Angalia Alerts zako:*\n";
        $formattedMessage .= config('app.url') . "/mother/alerts\n\n";
        $formattedMessage .= "🚨 *Dharura? Piga 114 mara moja!*";

        return $this->sendMessage(
            $mother->whatsapp_number,
            $formattedMessage,
            'health_alert',
            ['mother_id' => $mother->id, 'alert_type' => $alertType, 'priority' => 'high']
        );
    }

    /**
     * 📅 Send Appointment Reminder
     */
    public function sendAppointmentReminder(Mother $mother, $appointment): array
    {
        $message = "📅 *Kumbusho la Kliniki*\n\n";
        $message .= "Habari {$mother->first_name},\n\n";
        $message .= "Una appointment kesho:\n";
        $message .= "📍 {$appointment->clinic_name}\n";
        $message .= "🕐 {$appointment->appointment_date->format('d M Y H:i')}\n\n";
        
        $message .= "🔗 *Angalia Appointments zako:*\n";
        $message .= config('app.url') . "/mother/appointments\n\n";
        
        $message .= "🤰 MamaCare AI";

        return $this->sendMessage(
            $mother->whatsapp_number,
            $message,
            'appointment_reminder',
            [
                'mother_id' => $mother->id,
                'appointment_id' => $appointment->id,
                'priority' => 'high'
            ]
        );
    }

    /**
     * 🔔 Send Daily Reminder
     */
    public function sendDailyReminder(Mother $mother): array
    {
        $message = "🌅 *MamaCare - Habari za Asubuhi!*\n\n";
        $message .= "Habari {$mother->first_name},\n\n";
        
        $message .= "Usisahau leo:\n";
        $message .= "✅ Chukua dawa zako\n";
        $message .= "✅ Weka Daily Log\n";
        $message .= "✅ Pima Blood Pressure\n\n";
        
        $message .= "🔗 *Weka Daily Log:*\n";
        $message .= config('app.url') . "/mother/daily-log\n\n";
        
        $message .= "🤰 Kuwa na siku njema!";

        return $this->sendMessage(
            $mother->whatsapp_number,
            $message,
            'daily_reminder',
            ['mother_id' => $mother->id]
        );
    }

    /**
     * 🎯 Format phone number
     */
    private function formatNumber(string $number): string
    {
        // Remove spaces and non-digit characters
        $number = preg_replace('/[^\d+]/', '', $number);
        
        // Ensure it starts with country code
        if (!str_starts_with($number, '+')) {
            if (str_starts_with($number, '0')) {
                // Remove leading 0 and add +255 for Tanzania
                $number = '+255' . substr($number, 1);
            } elseif (str_starts_with($number, '255')) {
                $number = '+' . $number;
            } else {
                $number = '+' . $number;
            }
        }
        
        return $number;
    }

    /**
     * 🎉 Build welcome message
     */
    private function buildWelcomeMessage(Mother $mother): string
    {
        $mkNumber = $mother->mk_number ?? 'N/A';
        
        return "🎉 *Karibu MamaCare AI!*\n\n" .
               "Hongera {$mother->first_name},\n" .
               "Usajili wako umekamilika!\n\n" .
               "🆔 *MK Number yako:* {$mkNumber}\n" .
               "🔐 *Ingia hapa:* " . config('app.url') . "/mother/login\n\n" .
               "Nitakufuatilia kila hatua ya safari yako ya uzazi.\n\n" .
               "🤰 MamaCare AI - Rafiki wako wa kiafya ❤️";
    }

    /**
     * 💡 Get weekly tips based on pregnancy week
     */
    private function getWeeklyTips(int $weeks): string
    {
        $tips = [
            'first_trimester' => [
                "• Kula chakula chenye folic acid\n• Epuka pombe na sigara\n• Pumzika sana",
                "• Kunywa maji mengi\n• Chukua vidonge vya folic acid\n• Tembea kidogo kila siku",
            ],
            'second_trimester' => [
                "• Ongeza chakula chenye chuma (iron)\n• Anza maswali ya kliniki\n• Sikiliza mpigo wa moyo wa mtoto",
                "• Kula protini zaidi\n• Fuatilia uzito wako\n• Andaa chumba cha mtoto",
            ],
            'third_trimester' => [
                "• Pumzika zaidi\n• Jiandae kwa hospitali\n• Funga mizigo ya mtoto",
                "• Fuatilia mguu kwenda kuvimba\n• Pima BP mara kwa mara\n• Tengeneza mpango wa usafiri",
            ],
        ];

        if ($weeks <= 12) {
            return $tips['first_trimester'][array_rand($tips['first_trimester'])];
        } elseif ($weeks <= 27) {
            return $tips['second_trimester'][array_rand($tips['second_trimester'])];
        } else {
            return $tips['third_trimester'][array_rand($tips['third_trimester'])];
        }
    }

    /**
     * 📊 Get Queue Status
     */
    public function getQueueStatus(): array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])
            ->timeout($this->timeout)
            ->get("{$this->baseUrl}/queue");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get queue status',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
