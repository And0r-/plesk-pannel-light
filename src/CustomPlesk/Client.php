<?php

namespace CustomPlesk;

use PleskX\Api\Client as BaseClient;

class Client extends BaseClient
{
    public function webspace(): Webspace
    {
        // Rückgabe der überschriebenen Webspace-Klasse
        return new Webspace($this);
    }

    // Weitere Klassen können hier registriert werden
    // Beispiel:
    // public function mail(): CustomMail { return new CustomMail($this); }
}
