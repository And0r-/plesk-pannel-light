<?php

namespace App\Modules\DomainManager\Services;

use CustomPlesk\Client;
use App\Helpers\ResponseHelper;

class DomainService
{
    private $pleskClient, $responseHelper;

    // Dependency injection for:
    //  - The configured Plesk client
    //  - ResponseHelper to return consistent responses and log them in debug mode
    public function __construct(Client $pleskClient, ResponseHelper $responseHelper)
    {
        $this->pleskClient = $pleskClient;
        $this->responseHelper = $responseHelper;
    }

    public function createDomain(array $data)
    {
        try {
            $serverIp = $this->getFirstIp();

            // create Domain
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

            return $this->responseHelper->success('Domain successfully created', $response, 201);
        } catch (\Exception $e) {
            // Attempt to map a Plesk error to a corresponding form field
            $mappedErrors = $this->mapPleskErrorToField($e->getMessage());
            if (!empty($mappedErrors)) {
                return $this->responseHelper->validationError($mappedErrors, 'Domain creation failed');
            }


            return $this->responseHelper->pleskServerError('Domain creation failed', $e);
        }
    }

    public function getDomains()
    {
        try {
            $domains = $this->pleskClient->webspace()->getAll();
            return $this->responseHelper->success('', $domains);
        } catch (\Exception $e) {
            return $this->responseHelper->PleskServerError(
                'Domain list failed',
                $e
            );
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

            return $this->responseHelper->success('Status successfully updated', $response);
        } catch (\Exception $e) {
            return $this->responseHelper->pleskServerError('Failed to update domain status', $e);
        }
    }


    /**
     * Plesk errors will be displayed above the form.
     * We try to map the error to a specific form field so it can be shown directly on the relevant field.
     * Note: `plesk_error_id` is not unique.
     * We do not compare the complete error string, as Plesk may change the error message format.
     * It is better to have some false positives than no matches after an update.
     * 
     * example errors:
     * {"error":"Domain creation failed","plesk_error_id":2204,"plesk_error_message":"System user setting was failed. Error: Your password is not complex enough. According to the server policy, the minimal password strength is Strong (recommended)."} -> password
     * {"error":"Domain creation failed","plesk_error_id":1019,"plesk_error_message":"Domain name is invalid"} -> domain
     * {"error":"Domain creation failed","plesk_error_id":1007,"plesk_error_message":"Incorrect name asdfsdafsafsaf.ch. This domain name already exists."} -> domain
     * {"error":"Domain creation failed","plesk_error_id":2204,"plesk_error_message":"Unable to update hosting preferences. System user update is failed: The user andreashabegger.ch_6w4g6duu7l4 already exists. Incorrect fields: \"login\"."} -> ftp_user
     * 
     */
    private function mapPleskErrorToField(string $pleskErrorMessage): array
    {
        $mappedErrors = [];

        if (stripos($pleskErrorMessage, 'password') !== false) {
            $mappedErrors['password'] = $pleskErrorMessage;
        }

        if (stripos($pleskErrorMessage, 'domain name') !== false) {
            $mappedErrors['domain'] = $pleskErrorMessage;
        }

        if (stripos($pleskErrorMessage, 'user update') !== false) {
            $mappedErrors['ftp_user'] = $pleskErrorMessage;
        }

        return $mappedErrors;
    }

    /**
     * A dropdown should be implemented for the IP address selection when more than one IP is available.
     * but for now, this is sufficient.
     */
    private function getFirstIp()
    {
        $ipAddresses = $this->pleskClient->ip()->get();
        if (empty($ipAddresses)) {
            throw new \Exception('No IP address is available.');
        }
        return $ipAddresses[0]->ipAddress;
    }
}
