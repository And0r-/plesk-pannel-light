<?php

namespace CustomPlesk;

use PleskX\Api\Client as BaseClient;

class Client extends BaseClient
{
    public function webspace(): Webspace
    {
        return new Webspace($this);
    }
}
