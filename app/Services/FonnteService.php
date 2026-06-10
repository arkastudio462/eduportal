<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
    }

    public function send(string $target, string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->baseUrl.'/send', [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error('Fonnte API error: '.$response->body());

                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Fonnte send error: '.$e->getMessage());

            return false;
        }
    }
}
