# Decimal

[![Build Status](https://api.travis-ci.org/PrestaShop/decimal.svg?branch=master)](https://travis-ci.org/PrestaShop/decimal)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/985899efeb83453babcc507def66c90e)](https://www.codacy.com/app/PrestaShop/decimal?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=PrestaShop/decimal&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/985899efeb83453babcc507def66c90e)](https://www.codacy.com/app/PrestaShop/decimal?utm_source=github.com&utm_medium=referral&utm_content=PrestaShop/decimal&utm_campaign=Badge_Coverage)
[![Total Downloads](https://img.shields.io/packagist/dt/prestashop/decimal.svg?style=flat-square)](https://packagist.org/packages/prestashop/decimal)

An object-oriented [BC Math extension](http://php.net/manual/en/book.bc.php) wrapper/shim.

**Decimal** offers an object-oriented implementation of basic math operation with arbitrary precision, using BC Math if available.

## Install

Via Composer

``` bash
$ composer require prestashop/decimal
```

## Usage

### Instantiation

``` php
// create a number from string
$number = new PrestaShop\Decimal\Number('123.456');
echo $number; // echoes '123.456'

// exponent notation
$number = new PrestaShop\Decimal\Number('123456', -3);
echo $number; // echoes '123.456'
```

### Addition
```php
$a = new PrestaShop\Decimal\Number('123.456');
$b = new PrestaShop\Decimal\Number('654.321');

echo $a->plus($b); // echoes '777.777'
```

### Subtraction
```php
$a = new PrestaShop\Decimal\Number('777.777');
$b = new PrestaShop\Decimal\Number('654.321');

echo $a->minus($b); // echoes '123.456'
```

### Multiplication
```php
$a = new PrestaShop\Decimal\Number('777.777');
$b = new PrestaShop\Decimal\Number('654.321');

echo $a->times($b); // echoes '508915.824417'
```

### Division
```php
$a = new PrestaShop\Decimal\Number('777.777');
$b = new PrestaShop\Decimal\Number('654.321');

echo $a->dividedBy($b, 0);  // echoes '1'
echo $a->dividedBy($b, 5);  // echoes '1.18867'
echo $a->dividedBy($b, 10); // echoes '1.1886780341'
echo $a->dividedBy($b, 15); // echoes '1.188678034175886'
```

### Comparison
```php
$a->equals($b);
$a->isLowerThan($b);
$a->isLowerOrEqualThan($b);
$a->isGreaterThan($b);
$a->isGreaterOrEqualThan($b);
```

### Rounding
```php
$a = new PrestaShop\Decimal\Number('123.456');
$a = new PrestaShop\Decimal\Number('-123.456');

// truncate / pad
$a->toPrecision(0); // '123'
$a->toPrecision(1); // '123.4'
$a->toPrecision(2); // '123.45'
$a->toPrecision(3); // '123.456'
$a->toPrecision(4); // '123.4560'
$b->toPrecision(0); // '-123'
$b->toPrecision(1); // '-123.4'
$b->toPrecision(2); // '-123.45'
$b->toPrecision(3); // '-123.456'
$b->toPrecision(4); // '-123.4560'

// ceil (round up)
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '124'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '123.5'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '123.46'
$b->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '-122'
$b->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '-123.3'
$b->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_CEIL); // '-123.44'

// floor (round down)
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '123.4'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '123.45'
$b->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '-124'
$b->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '-123.5'
$b->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_FLOOR); // '-123.46'

// half-up (symmetric half-up)
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '123.5'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '123.46'
$b->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '-123'
$b->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '-123.5'
$b->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_UP); // '-123.46'

// half-down (symmetric half-down)
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '123.4'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '123.46'
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '-123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '-123.4'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_DOWN); // '-123.46'

// half-even
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '123.4'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '123.46'
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '-123'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '-123.4'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '-123.46'

$a = new Decimal\Number('1.1525354556575859505');
$a->toPrecision(0, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1'
$a->toPrecision(1, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.2'
$a->toPrecision(2, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.15'
$a->toPrecision(3, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.152'
$a->toPrecision(4, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.1525'
$a->toPrecision(5, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.15255'
$a->toPrecision(6, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.152535'
$a->toPrecision(7, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.1525354'
$a->toPrecision(8, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.15253546'
$a->toPrecision(9, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN);  // '1.152535456'
$a->toPrecision(10, PrestaShop\Decimal\Operation\Rounding::ROUND_HALF_EVEN); // '1.1525354556'
```

### Dot shifting
```php
$a = new Decimal\Number('123.456789');

// shift 3 digits to the left
$a->toMagnitude(-3); // 0.123456789

// shift 3 digits to the right
$a->toMagnitude(3); // 123456.789
```

### Useful methods
```php
$number = new PrestaShop\Decimal\Number('123.456');
$number->getIntegerPart();    // '123'
$number->getFractionalPart(); // '456'
$number->getPrecision();      // '3' (number of decimals)
$number->getSign();           // '' ('-' if the number was negative)
$number->getExponent();       // '3' (always positive)
$number->getCoefficient();    // '123456'
$number->isPositive();        // true
$number->isNegative();        // false
$number->invert();            // new Decimal\Number('-123.456')
```

## Testing

``` bash
$ composer install
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [All Contributors](https://github.com/prestashop/decimal/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
