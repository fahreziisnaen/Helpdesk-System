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

    public function sendMessage($message)
    {
        $groupId = Setting::get('whatsapp_group_id');
        
        if (!$groupId) {
            Log::warning("WhatsApp Group ID not set. Skipping message.");
            return false;
        }

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/send", [
                'jid' => $groupId,
                'message' => $message
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
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
