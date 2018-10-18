<?php

use Lukaswhite\UkPostcode\UkPostcode;

class TestUkPostcodeClass extends \PHPUnit\Framework\TestCase {

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

	public function testToString( )
	{
		$postcode = new UkPostcode('sw11 5ds');		
		$this->assertEquals('SW11 5DS', $postcode->__toString());		
		$this->assertEquals('SW11 5DS', ( string ) $postcode );		
	}


}