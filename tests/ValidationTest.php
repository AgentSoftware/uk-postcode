<?php

use AgentSoftware\UkPostcode\UkPostcode;
use PHPUnit\Framework\Attributes\DataProvider;

class ValidationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Try some valid postcodes.
     */
    public function testValid()
    {
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('GL9 1AH'));
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('gl91ah'));
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('Gl91Ah'));
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('gl  9 1 a    h'));
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('SW95 9DH'));
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('SW1A 2AA'));

        // @todo This postcode doesn't actually exist, but the forma is perfectly valid. Should it pass or fail?
        $this->assertTrue(\AgentSoftware\UkPostcode\UkPostcode::validate('SW50 9DH'));

        // Alternate method
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('GL9 1AH');
        $this->assertTrue($postcode->isValid());
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('gl91ah');
        $this->assertTrue($postcode->isValid());
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('Gl91Ah');
        $this->assertTrue($postcode->isValid());
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('gl  9 1 a    h');
        $this->assertTrue($postcode->isValid());
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('SW95 9DH');
        $this->assertTrue($postcode->isValid());
        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('SW1A 2AA');
        $this->assertTrue($postcode->isValid());

        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('SW50 9DH');
        $this->assertTrue($postcode->isValid());

    }

    /**
     * Try some invalid postcodes.
     */
    public function testInvalid()
    {
        $this->assertFalse(\AgentSoftware\UkPostcode\UkPostcode::validate('postcode'));
        $this->assertFalse(\AgentSoftware\UkPostcode\UkPostcode::validate('P05T C0DE'));

        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('postcode');
        $this->assertFalse($postcode->isValid());

        $postcode = new \AgentSoftware\UkPostcode\UkPostcode('P05T C0DE');
        $this->assertFalse($postcode->isValid());
    }

    /**
     * The special (non-standard) formats should validate.
     */
    #[DataProvider('validSpecialFormats')]
    public function testValidSpecialFormats(string $postcode)
    {
        $this->assertTrue(UkPostcode::validate($postcode), "{$postcode} should be valid");
        $this->assertTrue((new UkPostcode($postcode))->isValid(), "{$postcode} should be valid");
    }

    public static function validSpecialFormats(): array
    {
        return [
            'BF1 forces' => ['BF1 3AB'],
            'BF1 no space' => ['bf13ab'],
            'GIR 0AA' => ['GIR 0AA'],
            'GIR no space' => ['gir0aa'],
            'BFPO short' => ['BFPO 801'],
            'BFPO four digits' => ['BFPO 1234'],
            'BFPO no space' => ['bfpo801'],
            'BFPO c/o' => ['BFPO c/o 123'],
            'overseas territory' => ['ASCN 1ZZ'],
            'overseas territory (Falklands)' => ['FIQQ 1ZZ'],
            'overseas no space' => ['ascn1zz'],
            'Anguilla' => ['AI-2640'],
            'Anguilla lowercase' => ['ai-2640'],
        ];
    }

    /**
     * Near-miss variants of the special formats should be rejected.
     */
    #[DataProvider('invalidSpecialFormats')]
    public function testInvalidSpecialFormats(string $postcode)
    {
        $this->assertFalse(UkPostcode::validate($postcode), "{$postcode} should be invalid");
    }

    public static function invalidSpecialFormats(): array
    {
        return [
            'Anguilla without hyphen' => ['AI 2640'],
            'BFPO too many digits' => ['BFPO 12345'],
            'overseas too few letters' => ['ASC 1ZZ'],
            'overseas too many letters' => ['ASCND 1ZZ'],
            'GIR wrong inward' => ['GIR 1AA'],
        ];
    }

    /**
     * Validate a whole stack of postcodes
     */
    public function testMultiple()
    {
        if (($handle = fopen(__DIR__."/fixtures/postcodes.csv", "r")) !== false) {
            while (($data = fgetcsv($handle, 50, ",", '"', "")) !== false) {
                $postcode = new \AgentSoftware\UkPostcode\UkPostcode($data[ 0 ]);
                $this->assertTrue($postcode->isValid());
            }
            fclose($handle);
        }
    }

}
