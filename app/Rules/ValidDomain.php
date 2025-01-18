<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

/**
 * ValidDomain
 *
 * Validates that a given domain name is syntactically correct and meets the minimum level requirement.
 * Supports both ASCII and Internationalized Domain Names (IDNs).
 */
class ValidDomain implements ValidationRule
{
    /**
     * The minimum number of levels (labels) the domain must have.
     *
     * @var int
     */
    protected int $minLevels;

    /**
     * Create a new rule instance.
     *
     * @param int $minLevels Minimum number of levels (labels) required. Default is 2.
     */
    public function __construct(int $minLevels = 2)
    {
        $this->minLevels = $minLevels;
    }

    /**
     * Validate the domain name.
     *
     * @param  string  $attribute The name of the attribute being validated.
     * @param  mixed   $value     The value of the attribute.
     * @param  Closure $fail      The callback to invoke on failure.
     * @return void
     */
    public function validate($attribute, $value, $fail): void
    {
        $value = trim($value);

        // Convert the domain to Punycode (ASCII) to handle IDNs.
        $punycode = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        if ($punycode === false) {
            $fail("The {$attribute} must be a valid domain name.");
            return;
        }

        // Check the number of levels in the domain
        $levels = substr_count($punycode, '.') + 1;
        if ($levels < $this->minLevels) {
            $fail("The {$attribute} must have at least {$this->minLevels} level(s).");
            return;
        }

        // Validate the domain's syntax
        if (!filter_var($punycode, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $fail("The {$attribute} must be a valid domain name.");
        }
    }
}
