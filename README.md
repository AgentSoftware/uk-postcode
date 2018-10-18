# UK Postcode

[![CircleCI](https://circleci.com/gh/lukaswhite/uk-postcode.svg?style=svg)](https://circleci.com/gh/lukaswhite/uk-postcode)

A PHP class that represents a UK Postcode.

It allows you to:

- validate a postcode
- format a postcode correctly
- get the outcode, inward code or postcode sector

For example, suppose you request users provide their postcode when filling out their address; there's a very strong possibility they'll enter something like `sw1a2aa`. This class not only allows you to check that it's a valid UK postcode, but also format it correctly &mdash; in this example, that means `SW1A 2AA`.

## Installation

Via Composer:

```bash
composer require lukaswhite\uk-postcode
```

### Create an instance

```php
use Lukaswhite\UkPostcode\UkPostcode;

$postcode = new UkPostcode('sw1a2aa');
```


### Validate a Postcode
        
```php
if ($postcode->isValid()) {
	// do something...
}
```

Alternatively, use the static method:

```php
if (UkPostcode::validate('sw1a2aa')) {
	// do something...
}
```

### Format a Postcode

```php
$postcode = new UkPostcode('sw1a2aa');

print $postcode->formatted();

// outputs "SW1A 2AA"
```

### Outcodes

The outcode is the first part of a UK postcode. To illustrate:

```php
$postcode = new UkPostcode('sw1a2aa');
print $postcode->getOutcode();
// outputs "SW1A"

$postcode = new Lukaswhite\UkPostcodes\UkPostcode('GL9 1AH');
print $postcode->getOutcode();
// outputs "GL9"

$postcode = new UkPostcode('gl91ah');
print $postcode->getOutcode();
// outputs "GL9"
```

### Inward Codes

The inward code is the bit after the outcode. To illustrate:

```php
$postcode = new UkPostcode('sw1a2aa');
print $postcode->getInwardCode();
// outputs "2AA"

$postcode = new Lukaswhite\UkPostcodes\UkPostcode('GL9 1AH');
print $postcode->getInwardCode();
// outputs "1AH"

$postcode = new UkPostcode('gl91ah');
print $postcode->getInwardCode();
// outputs "1AH"
```

### Sectors

The sector is the outcode, followed by the first digit of the inward code. To illustrate:

```php
$postcode = new UkPostcode('sw1a2aa');
print $postcode->getSector();
// outputs "SW1A 2"

$postcode = new Lukaswhite\UkPostcodes\UkPostcode('GL9 1AH');
print $postcode->getSector();
// outputs "GL9 1"

$postcode = new UkPostcode('gl91ah');
print $postcode->getSector();
// outputs "GL9 1"
```

### Miscellany

* The class implements the magic `__toString()` method, which will return a formatted version of the postcode.
