<?php

namespace App\Modules\DomainManager\Services;

use CustomPlesk\Client;
use Illuminate\Support\Facades\Log;

class DomainService
{
    private $pleskClient;

    // Dependency injection for the configured Plesk client
    public function __construct(Client $pleskClient)
    {
        $this->pleskClient = $pleskClient;
    }

    public function createDomain(array $data)
    {
        try {
            // IP-Adresse holen
            $ipAddresses = $this->pleskClient->ip()->get();
            if (empty($ipAddresses)) {
                throw new \Exception('No IP address is available.');
            }
            $serverIp = $ipAddresses[0]->ipAddress;

            // Domain erstellen
            $webspace = $this->pleskClient->webspace();
            $response = $webspace->create(
                [
                    'name' => $data['domain'],
                    'ip_address' => $serverIp,
                ],
                [
                    'ftp_login' => $data['ftp_user'],
                    'ftp_password' => $data['password'],
                ],
                '',
                true
            );

            return response()->json(['message' => 'Domain successfully created', 'data' => $response]);
        } catch (\Exception $e) {
            Log::error('Domain creation failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Domain creation failed',
                'plesk_error_id' => $e->getCode(),
                'plesk_error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getDomains()
    {
        try {
            $domains = $this->pleskClient->webspace()->getAll();
            return response()->json(['data' => $domains]);
        } catch (\Exception $e) {
            Log::error('Error fetching domains: ' . $e->getMessage());
            return response()->json([
                'error' => 'Domain list failed',
                'plesk_error_id' => $e->getCode(),
                'plesk_error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus($domainId, string $status)
    {
        try {
            $response = null;

            if ($status === 'active') {
                $response = $this->pleskClient->webspace()->enable('id', $domainId);
            } elseif ($status === 'disabled') {
                $response = $this->pleskClient->webspace()->disable('id', $domainId);
            }

            return response()->json([
                'message' => 'Status successfully updated',
                'data' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating domain status: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update domain status',
                'plesk_error_id' => $e->getCode(),
                'plesk_error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
