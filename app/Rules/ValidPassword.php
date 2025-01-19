<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use ZxcvbnPhp\Zxcvbn;

class ValidPassword implements ValidationRule
{
    protected int $minScore;
    protected string $errorMessage;

    /**
     * Constructor
     *
     * @param int $minScore Minimum score required for the password (0-4).
     */
    public function __construct(int $minScore = 3)
    {
        $this->minScore = $minScore;
        $this->errorMessage = "The password complexity is insufficient. It must meet a minimum score of {$minScore}.";
    }

    /**
     * Validate the password using zxcvbn.
     *
     * @param  string  $attribute The attribute name.
     * @param  mixed   $value     The value being validated.
     * @param  Closure $fail      The callback to indicate failure.
     * @return void
     */
    public function validate($attribute, $value, $fail): void
    {
        $zxcvbn = new Zxcvbn();
        $strength = $zxcvbn->passwordStrength($value);

        if ($strength['score'] < $this->minScore) {
            $fail($this->errorMessage);
        }
    }
}
