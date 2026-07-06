<?php

use AgentSoftware\UkPostcode\UkPostcode;
use PHPUnit\Framework\Attributes\DataProvider;

class UkPostcodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test the class instantiation
     */
    public function testInstantiate()
    {
        $postcode = new UkPostcode('GL9 1AH');
        $this->assertInstanceOf(UkPostcode::class, $postcode);
        $this->assertEquals('GL9 1AH', $postcode->postcode);
    }

    /**
     * Test the getOutcode() function
     */
    public function testOutcode()
    {
        $postcode = new UkPostcode('GL9 1AH');
        $this->assertEquals('GL9', $postcode->getOutcode());
    }

    /**
     * Test the getInwardCode() function
     */
    public function testGetInwardCode()
    {
        $postcode = new UkPostcode('GL9 1AH');
        $this->assertEquals('1AH', $postcode->getInwardCode());

        $postcode = new UkPostcode('sw11 5ds');
        $this->assertEquals('5DS', $postcode->getInwardCode());

    }

    /**
     * Test the getSector() function
     */
    public function testGetSector()
    {
        $postcode = new UkPostcode('GL9 1AH');
        $this->assertEquals('GL9 1', $postcode->getSector());

        $postcode = new UkPostcode('sw11 5ds');
        $this->assertEquals('SW11 5', $postcode->getSector());

    }

    /**
    public function testGetPostTown()
    {
        $postcode = new UkPostcode('sw11 5ds');
        var_dump($postcode->getPostTown());

        $postcode = new UkPostcode('m4 4at');
        var_dump($postcode->getPostTown());

        $postcode = new UkPostcode('hd1 4sq');
        var_dump($postcode->getPostTown());
    }
    **/

    public function testFormatting()
    {
        $postcode = new UkPostcode('sw11 5ds');
        $this->assertEquals('SW11 5DS', $postcode->formatted());

        $postcode = new UkPostcode('sw115ds');
        $this->assertEquals('SW11 5DS', $postcode->formatted());

        $postcode = new UkPostcode('m45as');
        $this->assertEquals('M4 5AS', $postcode->formatted());

        $postcode = new UkPostcode('sw1a 2aa');
        $this->assertEquals('SW1A 2AA', $postcode->formatted());


    }

    public function testToString()
    {
        $postcode = new UkPostcode('sw11 5ds');
        $this->assertEquals('SW11 5DS', $postcode->__toString());
        $this->assertEquals('SW11 5DS', ( string ) $postcode);
    }

    /**
     * The special (non-standard) formats should be normalised correctly.
     */
    #[DataProvider('specialFormats')]
    public function testFormatsSpecialPostcodes(string $input, string $expected)
    {
        $this->assertEquals($expected, (new UkPostcode($input))->formatted());
    }

    public static function specialFormats(): array
    {
        return [
            'BF1 forces' => ['bf13ab', 'BF1 3AB'],
            'GIR 0AA' => ['gir0aa', 'GIR 0AA'],
            'BFPO' => ['bfpo801', 'BFPO 801'],
            'BFPO c/o' => ['bfpoc/o12', 'BFPO c/o 12'],
            'overseas territory keeps its code' => ['ascn1zz', 'ASCN 1ZZ'],
            'overseas territory (Falklands)' => ['fiqq 1zz', 'FIQQ 1ZZ'],
            'Anguilla' => ['ai-2640', 'AI-2640'],
            'Anguilla with surrounding space' => [' AI-2640 ', 'AI-2640'],
        ];
    }

    /**
     * Overseas territories must not all collapse onto Anguilla's code.
     */
    public function testOverseasTerritoriesAreDistinct()
    {
        $this->assertEquals('ASCN 1ZZ', (new UkPostcode('ASCN 1ZZ'))->formatted());
        $this->assertEquals('FIQQ 1ZZ', (new UkPostcode('FIQQ 1ZZ'))->formatted());
        $this->assertNotEquals(
            (new UkPostcode('ASCN 1ZZ'))->formatted(),
            (new UkPostcode('FIQQ 1ZZ'))->formatted()
        );
    }

    /**
     * The static format() helper rewrites its argument by reference and reports validity.
     */
    public function testFormatRewritesByReference()
    {
        $postcode = 'sw115ds';
        $this->assertTrue(UkPostcode::format($postcode));
        $this->assertEquals('SW11 5DS', $postcode);

        $invalid = 'not a postcode';
        $this->assertFalse(UkPostcode::format($invalid));
        $this->assertEquals('not a postcode', $invalid, 'Invalid input is left untouched');
    }

    /**
     * The query methods operate on the raw value and don't blow up on odd input.
     */
    public function testQueryMethodsAcceptUnspacedInput()
    {
        $postcode = new UkPostcode('sw115ds');
        $this->assertEquals('SW11', $postcode->getOutcode());
        $this->assertEquals('5DS', $postcode->getInwardCode());
        $this->assertEquals('SW11 5', $postcode->getSector());
    }

    public function testQueryMethodsOnEmptyStringDoNotError()
    {
        $postcode = new UkPostcode('');
        $this->assertEquals('', $postcode->getOutcode());
        $this->assertEquals('', $postcode->getInwardCode());
    }
}
