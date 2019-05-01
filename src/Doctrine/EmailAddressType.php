<?php declare(strict_types = 1);

namespace Nordic\EmailAddress\Doctrine;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Nordic\EmailAddress\EmailAddress;
use Nordic\EmailAddress\EmailAddressInterface;
use Nordic\EmailAddress\NullEmailAddress;

/**
 * Doctrine email address datatype
 */
class EmailAddressType extends StringType
{
    public const EMAIL_ADDRESS = 'email_address';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::EMAIL_ADDRESS;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof NullEmailAddress) {
            return null;
        }

        if ($value instanceof EmailAddressInterface) {
            return (string) $value;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', EmailAddressInterface::class, 'email address string']);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Nordic\EmailAddress\InvalidEmailAddressException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?EmailAddressInterface
    {
        if ($value === null) {
            return new NullEmailAddress;
        }

        if ($value instanceof EmailAddressInterface) {
            return $value;
        }

        return new EmailAddress($value);
    }
}
