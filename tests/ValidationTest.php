<?php

class TestValidation extends \PHPUnit\Framework\TestCase {

	/**
	 * Try some valid postcodes.
	 */
	public function testValid()
	{		
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('GL9 1AH'));
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('gl91ah'));
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('Gl91Ah'));
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('gl  9 1 a    h'));
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('SW95 9DH'));
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('SW1A 2AA'));
		
		// @todo This postcode doesn't actually exist, but the forma is perfectly valid. Should it pass or fail?
		$this->assertTrue(\Lukaswhite\UkPostcode\UkPostcode::validate('SW50 9DH'));

		// Alternate method
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('GL9 1AH');
		$this->assertTrue($postcode->isValid());
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('gl91ah');
		$this->assertTrue($postcode->isValid());
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('Gl91Ah');
		$this->assertTrue($postcode->isValid());
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('gl  9 1 a    h');
		$this->assertTrue($postcode->isValid());
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('SW95 9DH');
		$this->assertTrue($postcode->isValid());
		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('SW1A 2AA');
		$this->assertTrue($postcode->isValid());

		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('SW50 9DH');
		$this->assertTrue($postcode->isValid());

	}

	/**
	 * Try some invalid postcodes.
	 */
	public function testInvalid()
	{
		$this->assertFalse(\Lukaswhite\UkPostcode\UkPostcode::validate('postcode'));
		$this->assertFalse(\Lukaswhite\UkPostcode\UkPostcode::validate('P05T C0DE'));	

		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('postcode');
		$this->assertFalse($postcode->isValid());

		$postcode = new \Lukaswhite\UkPostcode\UkPostcode('P05T C0DE');
		$this->assertFalse($postcode->isValid());	
	}

	/**
	 * Validate a whole stack of postcodes
	 */
	public function testMultiple()
	{
		if (($handle = fopen(__DIR__."/fixtures/postcodes.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 50, ",")) !== FALSE) {
				$postcode = new \Lukaswhite\UkPostcode\UkPostcode( $data[ 0 ] );
				$this->assertTrue($postcode->isValid());
	    }
	    fclose($handle);
		}
	}

}