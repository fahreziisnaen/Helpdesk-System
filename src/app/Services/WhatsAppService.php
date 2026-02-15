<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;

    public function __construct()
    {
        // Internal docker network URL
        $this->baseUrl = config('services.whatsapp.url', 'http://whatsapp:3000');
    }

    public function getStatus()
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/status");
            return $response->json();
        } catch (\Exception $e) {
            Log::error("WhatsApp Gateway connection failed: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }

    public function getGroups()
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/groups");
            return $response->json();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function sendMessage($message, $to = null)
    {
        $jid = $to;

        // If 'to' is not provided, use the default group ID from settings
        if (!$jid) {
            $jid = Setting::get('whatsapp_group_id');
        } else {
            // If it's a phone number, format it correctly for Baileys
            $jid = $this->formatPhoneNumber($jid);
        }
        
        if (!$jid) {
            Log::warning("WhatsApp JID not set. Skipping message.");
            return false;
        }

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/send", [
                'jid' => $jid,
                'message' => $message
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format a phone number to WhatsApp JID format (number@s.whatsapp.net)
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $phone);

        if (empty($number)) return null;

        // Handle Indonesian numbers starting with 0
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return $number . '@s.whatsapp.net';
    }

    public function resetConnection()
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/reset");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
