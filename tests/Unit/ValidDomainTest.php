<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\ValidDomain;

class ValidDomainTest extends TestCase
{
    public function testValidDomainsPass()
    {
        $rule = new ValidDomain();

        // Keine Fehler sollten auftreten
        $this->assertTrue($this->passesValidation($rule, 'example.com'));
        $this->assertTrue($this->passesValidation($rule, 'sub.example.com'));
        $this->assertTrue($this->passesValidation($rule, 'müller.de'));
    }

    public function testInvalidDomainsFail()
    {
        $rule = new ValidDomain();

        // Es sollten Fehler auftreten
        $this->assertFalse($this->passesValidation($rule, 'AAAAääääsdf')); // No TLD
        $this->assertFalse($this->passesValidation($rule, 'invalid_domain')); // Invalid characters
        $this->assertFalse($this->passesValidation($rule, '-example.com')); // Starts with a hyphen
        $this->assertFalse($this->passesValidation($rule, 'example..com')); // Double dot
    }

    public function testCustomMinLevels()
    {
        $rule = new ValidDomain(3);

        // Es sollten Fehler auftreten für Domains, die weniger als 3 Levels haben
        $this->assertFalse($this->passesValidation($rule, 'example.com')); // 2 Levels, fail
        $this->assertTrue($this->passesValidation($rule, 'sub.sub.example.com')); // 3 Levels, pass
    }

    private function passesValidation($rule, $value)
    {
        $result = null;
        $rule->validate('domain', $value, function ($message) use (&$result) {
            $result = false;
        });
        return $result === null;
    }
}
