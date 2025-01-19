<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\ValidPassword;

class ValidPasswordTest extends TestCase
{
    public function testWeakPasswordFails()
    {
        $rule = new ValidPassword(3);

        $this->assertFalse($this->passesValidation($rule, '12345')); // Zu schwach
        $this->assertFalse($this->passesValidation($rule, 'password')); // Häufiges Passwort
        $this->assertFalse($this->passesValidation($rule, 'testtest5!'));
        $this->assertFalse($this->passesValidation($rule, 'asdfgasdfg'));
    }

    public function testStrongPasswordPasses()
    {
        $rule = new ValidPassword(3);

        $this->assertTrue($this->passesValidation($rule, 'ComplexP@ssw0rd!123')); // Stark genug
    }

    private function passesValidation($rule, $value)
    {
        $result = null;
        $rule->validate('password', $value, function ($message) use (&$result) {
            $result = false;
        });
        return $result === null;
    }
}
