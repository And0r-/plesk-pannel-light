<?php

namespace CustomPlesk;

use PleskX\Api\Operator\Webspace as BaseWebspace;
use PleskX\Api\Struct\Webspace\Info;

class Webspace extends BaseWebspace
{
    /**
     * Extends the default Plesk Webspace creation method to optionally use transactions.
     * Transactions ensure the domain and hosting properties are handled in separate steps,
     * allowing for rollback if hosting property setup fails.
     *
     * @param array $properties Domain properties for creation.
     * @param array|null $hostingProperties Hosting configuration properties (optional).
     * @param string $planName Name of the service plan (optional).
     * @param bool $transaction Enable transactional creation mode (default: false).
     * @return Info Information about the created domain.
     * @throws \Exception If an error occurs during hosting property setup.
     */
    public function create(
        array $properties,
        array $hostingProperties = null,
        string $planName = '',
        bool $transaction = false
    ): Info {
        if (!$transaction) {
            // Use the original method when transactions are not required.
            return parent::create($properties, $hostingProperties, $planName);
        }

        // Step 1: Create the domain without hosting properties.
        $domainCreateInfo = parent::create($properties, null, $planName);

        try {
            if (!empty($hostingProperties)) {
                // Prepare a packet to update hosting properties for the created domain.
                $packet = $this->client->getPacket();
                $setTag = $packet->addChild($this->wrapperTag)->addChild('set');
                $setTag->addChild('filter')->addChild('name', $properties['name']);

                $valuesTag = $setTag->addChild('values');

                // The IP address must be manually added to gen_setup 
                // because the original method only assigns it when hosting properties are provided.

                if (!empty($properties['ip_address'])) {
                    $genSetupTag = $valuesTag->addChild('gen_setup');
                    $genSetupTag->addChild('ip_address', $properties['ip_address']);
                }

                // Add hosting properties to the domain.
                $hostingTag = $valuesTag->addChild('hosting')->addChild('vrt_hst');
                foreach ($hostingProperties as $name => $value) {
                    $propertyTag = $hostingTag->addChild('property');
                    $propertyTag->addChild('name', $name);
                    $propertyTag->addChild('value', $value);
                }

                // Send the update request.
                $this->client->request($packet);
            }

            return $domainCreateInfo;
        } catch (\Exception $e) {
            // Roll back the domain creation if hosting properties setup fails.
            $this->delete('name', $properties['name']);
            error_log("Domain '{$properties['name']}' was deleted in transactional mode due to an error: {$e->getMessage()}");
            throw $e;
        }
    }
}
