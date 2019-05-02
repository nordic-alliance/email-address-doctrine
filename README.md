# Email Address Doctrine type

![PHP version][ico-php-version]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

The **nordic/email-address-doctrine** package provides the ability to use [nordic/email-address][link-nordic-email-address] as a [Doctrine field type][link-doctrine-field-type].

## Install

Via Composer

```bash
$ composer require nordic/email-address-doctrine
```

### Configuration

To configure Doctrine to use **nordic/email-address** as a field type, you'll need to set up the following in your bootstrap:

```php
<?php

use Doctrine\DBAL\Types\Type;
use Nordic\EmailAddress\Doctrine\EmailAddressType;

Type::addType(EmailAddressType::EMAIL_ADDRESS, EmailAddressType::CLASS);

// or
Type::addType('email_address', 'Nordic\EmailAddress\Doctrine\EmailAddressType');
```

## Usage

Now you can annotate properties in your entities:

```php
use Doctrine\ORM\Mapping as ORM;
use Nordic\EmailAddress\EmailAddressInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="contacts")
 */
class Contact
{
    /**
     * @ORM\Column(type="email_address")
     * @var EmailAddressInterface
     */
    private $emailAddress;

    public function getEmailAddress(): EmailAddressInterface
    {
        return $this->emailAddress;
    }
}
```

## Credits

- [Serhii Diahovchenko][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-php-version]: https://img.shields.io/travis/php-v/nordic-alliance/email-address-doctrine.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nordic-alliance/email-address-doctrine/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nordic-alliance/email-address-doctrine.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nordic-alliance/email-address-doctrine.svg?style=flat-square

[link-travis]: https://travis-ci.org/nordic-alliance/email-address
[link-code-quality]: https://scrutinizer-ci.com/g/nordic-alliance/email-address
[link-scrutinizer]: https://scrutinizer-ci.com/g/nordic-alliance/email-address/code-structure
[link-author]: https://github.com/DyaGa
[link-contributors]: ../../contributors

[link-nordic-email-address]: https://github.com/nordic-alliance/email-address
[link-doctrine-field-type]: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html