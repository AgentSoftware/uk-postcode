<?php

declare(strict_types=1);

namespace AgentSoftware\UkPostcode;

use Stringable;

/**
 * Represents a UK Postcode; for validating, formatting and querying.
 */
class UkPostcode implements Stringable
{
    // Permitted letters depend upon their position in the postcode.
    private const ALPHA_1 = '[abcdefghijklmnoprstuwyz]';    // Character 1
    private const ALPHA_2 = '[abcdefghklmnopqrstuvwxy]';    // Character 2
    private const ALPHA_3 = '[abcdefghjkpmnrstuvwxy]';      // Character 3
    private const ALPHA_4 = '[abehmnprvwxy]';               // Character 4
    private const ALPHA_5 = '[abdefghjlnpqrstuwxyz]';       // Character 5
    private const BFPO_5 = '[abdefghjlnpqrst]{1}';          // BFPO character 5
    private const BFPO_6 = '[abdefghjlnpqrstuwzyz]{1}';     // BFPO character 6

    // Anguilla's fixed postcode. It has no capture groups, so format() handles it as a special case.
    private const PATTERN_ANGUILLA = '/^ai-2640$/';

    /**
     * The regular expressions describing every accepted postcode format.
     *
     * @var list<string>
     */
    private const PATTERNS = [
        // BF1 type postcodes
        '/^(bf1)([[:space:]]{0,})([0-9]{1}' . self::BFPO_5 . self::BFPO_6 . ')$/',
        // AN NAA, ANN NAA, AAN NAA and AANN NAA with a space
        '/^(' . self::ALPHA_1 . '{1}' . self::ALPHA_2 . '{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}' . self::ALPHA_5 . '{2})$/',
        // ANA NAA
        '/^(' . self::ALPHA_1 . '{1}[0-9]{1}' . self::ALPHA_3 . '{1})([[:space:]]{0,})([0-9]{1}' . self::ALPHA_5 . '{2})$/',
        // AANA NAA
        '/^(' . self::ALPHA_1 . '{1}' . self::ALPHA_2 . '{1}[0-9]{1}' . self::ALPHA_4 . ')([[:space:]]{0,})([0-9]{1}' . self::ALPHA_5 . '{2})$/',
        // The special postcode GIR 0AA
        '/^(gir)([[:space:]]{0,})(0aa)$/',
        // Standard BFPO numbers
        '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/',
        // c/o BFPO numbers
        '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/',
        // Overseas Territories
        '/^([a-z]{4})([[:space:]]{0,})(1zz)$/',
        // Anguilla
        self::PATTERN_ANGUILLA,
    ];

    /**
     * Create an instance from a string representation of the postcode; it's fairly
     * lax about the format. So in other words, it'll accept any of the following:
     *
     *   GL9 1AH
     *   gl91ah
     *   Gl91Ah
     *   gl  9 1 a    h
     */
    public function __construct(public readonly string $postcode)
    {
    }

    /**
     * Utility (static) function; validate the supplied postcode.
     */
    public static function validate(string $postcode): bool
    {
        // Need to do an assignment, because we're going to pass by reference.
        $toCheck = $postcode;

        // The format function validates the postcode.
        return self::format($toCheck);
    }

    /**
     * Utility (static) function; format the supplied postcode in place.
     *
     * @param string $toCheck Rewritten to the reformatted postcode when valid.
     */
    public static function format(string &$toCheck): bool
    {
        // Load up the string to check, converting into lowercase.
        $postcode = str_replace(' ', '', strtolower($toCheck));

        // Check the string against every accepted postcode format.
        foreach (self::PATTERNS as $regexp) {
            if (! preg_match($regexp, $postcode, $matches)) {
                continue;
            }

            // Anguilla is a fixed literal with no capture groups; there's nothing to reassemble.
            if ($regexp === self::PATTERN_ANGUILLA) {
                $toCheck = 'AI-2640';

                return true;
            }

            // Reassemble as outward code + space + inward code, in uppercase.
            $postcode = strtoupper($matches[1] . ' ' . $matches[3]);

            // Take account of the special BFPO c/o format.
            $postcode = preg_replace('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

            // Return with the reformatted valid postcode in uppercase.
            $toCheck = $postcode;

            return true;
        }

        return false;
    }

    /**
     * Validates this postcode.
     */
    public function isValid(): bool
    {
        return self::validate($this->postcode);
    }

    /**
     * Get the outcode; that is, the first part of the postcode.
     */
    public function getOutcode(): string
    {
        // Format the postcode first.
        $formatted = strtoupper(str_replace(' ', '', $this->postcode));

        // The outcode is the postcode less the last three characters.
        return substr($formatted, 0, strlen($formatted) - 3);
    }

    /**
     * Returns the inward code.
     */
    public function getInwardCode(): string
    {
        // Format the postcode first.
        $formatted = strtoupper(str_replace(' ', '', $this->postcode));

        // Get the last three characters.
        return substr($formatted, strlen($formatted) - 3);
    }

    /**
     * Get the postcode sector, e.g. GL9 1
     */
    public function getSector(): string
    {
        // The sector is simply outcode + space + first digit of the inward code.
        return sprintf('%s %s', $this->getOutcode(), substr($this->getInwardCode(), 0, 1));
    }

    /**
     * Formats this postcode.
     */
    public function formatted(): string
    {
        $postcode = $this->postcode;
        self::format($postcode);

        return $postcode;
    }

    /**
     * Returns a string representation of this postcode.
     */
    public function __toString(): string
    {
        return $this->formatted();
    }
}
