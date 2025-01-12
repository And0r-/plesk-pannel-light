<?php

namespace CustomPlesk;

use PleskX\Api\Operator\Webspace as BaseWebspace;
use PleskX\Api\Struct\Webspace\Info;

class Webspace extends BaseWebspace
{
    public function create(
        array $properties,
        array $hostingProperties = null,
        string $planName = '',
        bool $transaction = false
    ): Info {
        if (!$transaction) {
            return parent::create($properties, $hostingProperties, $planName);
        }

        $domainCreateInfo = parent::create($properties, null, $planName);

        try {
            if (!empty($hostingProperties)) {
                $packet = $this->client->getPacket();
                $setTag = $packet->addChild($this->wrapperTag)->addChild('set');
                $setTag->addChild('filter')->addChild('name', $properties['name']);

                $valuesTag = $setTag->addChild('values');

                // IP-Adresse hinzufügen
                if (!empty($properties['ip_address'])) {
                    $genSetupTag = $valuesTag->addChild('gen_setup');
                    $genSetupTag->addChild('ip_address', $properties['ip_address']);
                }

                // Hosting-Properties hinzufügen
                $hostingTag = $valuesTag->addChild('hosting')->addChild('vrt_hst');
                foreach ($hostingProperties as $name => $value) {
                    $propertyTag = $hostingTag->addChild('property');
                    $propertyTag->addChild('name', $name);
                    $propertyTag->addChild('value', $value);
                }

                $this->client->request($packet);
            }

            return $domainCreateInfo;
        } catch (\Exception $e) {
            $this->delete('name', $properties['name']);
            error_log("Domain '{$properties['name']}' wurde im Transaktionsmodus gelöscht, da ein Fehler auftrat: {$e->getMessage()}");
            throw $e;
        }
    }
}
