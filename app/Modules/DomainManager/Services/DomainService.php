<?php

namespace App\Modules\DomainManager\Services;

use CustomPlesk\Client;
use Illuminate\Support\Facades\Log;

class DomainService
{
    private $pleskClient;

    public function __construct()
    {
        $config = config('plesk'); // Konfiguration aus Laravel .env
        $this->pleskClient = new Client($config['host']);
        $this->pleskClient->setSecretKey($config['secret_key']);
    }

    public function createDomain(array $data)
    {
        try {
            // IP-Adresse holen
            $ipAddresses = $this->pleskClient->ip()->get();
            if (empty($ipAddresses)) {
                throw new \Exception('Keine IP-Adresse verfügbar.');
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

            return response()->json(['message' => 'Domain erfolgreich erstellt', 'data' => $response]);
        } catch (\Exception $e) {
            Log::error('Domain-Erstellung fehlgeschlagen: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDomains()
    {
        try {
            $domains = $this->pleskClient->webspace()->getAll();
            return response()->json(['data' => $domains]);
        } catch (\Exception $e) {
            Log::error('Fehler beim Abrufen der Domains: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
