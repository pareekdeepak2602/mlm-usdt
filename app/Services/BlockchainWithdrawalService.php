<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlockchainWithdrawalService
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.blockchain.base_url', 'http://localhost:3000');
        $this->apiKey = config('services.blockchain.api_key');
        $this->apiSecret = config('services.blockchain.api_secret');
    }

    /**
     * Process withdrawal via blockchain
     */
    public function processWithdrawal($toAddress, $amount)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'X-API-Signature' => $this->generateSignature($toAddress, $amount),
                ])
                ->post($this->baseUrl . '/api/withdraw', [
                    'toAddress' => $toAddress,
                    'amount' => $amount,
                ]);

            $result = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'txHash' => $result['data']['txHash'] ?? null,
                    'message' => $result['message'] ?? 'Withdrawal processed successfully',
                    'data' => $result['data'] ?? []
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $result['message'] ?? 'Blockchain withdrawal failed',
                    'code' => $result['code'] ?? 'unknown_error'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Blockchain withdrawal error: ' . $e->getMessage(), [
                'toAddress' => $toAddress,
                'amount' => $amount
            ]);

            return [
                'success' => false,
                'error' => 'Blockchain service unavailable: ' . $e->getMessage(),
                'code' => 'service_unavailable'
            ];
        }
    }

    /**
     * Generate HMAC signature for authentication
     */
    private function generateSignature($toAddress, $amount)
    {
        $payload = $toAddress . $amount;
        return hash_hmac('sha256', $payload, $this->apiSecret);
    }

    /**
     * Validate if blockchain service is available
     */
   /**
 * Validate if blockchain service is available
 */
public function isServiceAvailable()
{
    try {
        $response = Http::timeout(10)
            ->get($this->baseUrl . '/api/status/health');
        
        if ($response->successful()) {
            $data = $response->json();
            Log::info('Blockchain health response:', $data);
            
            // Check multiple possible success indicators
            return (
                ($data['success'] ?? false) === true ||
                ($data['code'] ?? '') === 'success' ||
                ($data['status'] ?? '') === 'operational'
            );
        }
        
        Log::warning('Blockchain health check failed with status: ' . $response->status());
        return false;
    } catch (\Exception $e) {
        Log::warning('Blockchain health check failed: ' . $e->getMessage());
        return false;
    }
}
    /**
     * Get blockchain service status
     */
    public function getServiceStatus()
    {
        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . '/api/status/status');
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'status' => 'unavailable',
                'error' => 'Service did not respond correctly'
            ];
        } catch (\Exception $e) {
            Log::warning('Blockchain status check failed: ' . $e->getMessage());
            return [
                'status' => 'unavailable',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Simple ping check
     */
    public function ping()
    {
        try {
            $response = Http::timeout(5)
                ->get($this->baseUrl . '/api/status/ping');
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get detailed service health information
     */
    public function getHealthInfo()
    {
        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . '/api/status/health');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'available' => ($data['code'] ?? '') === 'success',
                    'data' => $data['data'] ?? [],
                    'message' => $data['message'] ?? 'Unknown status'
                ];
            }
            
            return [
                'available' => false,
                'data' => [],
                'message' => 'Service responded with error'
            ];
        } catch (\Exception $e) {
            return [
                'available' => false,
                'data' => [],
                'message' => $e->getMessage()
            ];
        }
    }
}