<?php declare(strict_types=1);

namespace NordicTest\EmailAddress;

use Mockery;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Nordic\EmailAddress\EmailAddressInterface;
use Nordic\EmailAddress\EmailAddress;
use Nordic\EmailAddress\NullEmailAddress;
use Nordic\EmailAddress\Doctrine\EmailAddressType;
use Nordic\EmailAddress\EmailAddressFactory;
use PHPUnit\Framework\TestCase;

final class EmailAddressTypeTest extends TestCase
{
    private const VALID_EMAIL = 'test@example.com';
    private const INVALID_EMAIL = 'invalid_email';

    /** @var EmailAddressType */
    private $type;

    /** @var AbstractPlatform|MockInterface */
    private $platform;

    /** @var EmailAddressFactory */
    private $emailAddressFactory;

    protected function setUp(): void
    {
        parent::setUp();

        if (Type::hasType(EmailAddressType::EMAIL_ADDRESS)) {
            Type::overrideType(EmailAddressType::EMAIL_ADDRESS, EmailAddressType::class);
        } else {
            Type::addType(EmailAddressType::EMAIL_ADDRESS, EmailAddressType::class);
        }

        $this->type = Type::getType(EmailAddressType::EMAIL_ADDRESS);
        $this->platform = $this->getPlatformMock();
        $this->emailAddressFactory = new EmailAddressFactory;
    }

    public function testGetName()
    {
        $this->assertEquals('email_address', $this->type->getName());
    }

    public function testEmailAddressConvertsToDatabaseValue()
    {
        $emailAddress = $this->emailAddressFactory->createEmailAddress(self::VALID_EMAIL);

        $expected = (string) $emailAddress;
        $actual = $this->type->convertToDatabaseValue($emailAddress, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    public function testNullEmailAddressConvertsToDatabaseValue()
    {
        $emailAddress = $this->emailAddressFactory->createEmailAddress();

        $expected = null;
        $actual = $this->type->convertToDatabaseValue($emailAddress, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    public function testEmailAddressInterfaceConvertsToDatabaseValue()
    {
        $emailAddress = Mockery::mock(EmailAddressInterface::class);

        $emailAddress
            ->shouldReceive('__toString')
            ->once()
            ->andReturn(self::VALID_EMAIL);

        $expected = self::VALID_EMAIL;
        $actual = $this->type->convertToDatabaseValue($emailAddress, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    public function testNullConvertsToDatabaseValue()
    {
        $expected = null;
        $actual = $this->type->convertToDatabaseValue(null, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidEmailAddressConvertsToDatabaseValue()
    {
        $this->type->convertToDatabaseValue(self::INVALID_EMAIL, $this->platform);
    }

    public function testEmailAddressConvertsToPHPValue()
    {
        $emailAddress = $this->type->convertToPHPValue(self::VALID_EMAIL, $this->platform);

        $this->assertInstanceOf(EmailAddress::class, $emailAddress);
        $this->assertEquals(self::VALID_EMAIL, (string) $emailAddress);
    }

    public function testNullEmailAddressConvertsToPHPValue()
    {
        $emailAddress = $this->type->convertToPHPValue(null, $this->platform);

        $this->assertInstanceOf(NullEmailAddress::class, $emailAddress);
        $this->assertEquals('', (string) $emailAddress);
    }

    /**
     * @expectedException Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidEmailAddressConvertsToPHPValue()
    {
        $this->type->convertToPHPValue(self::INVALID_EMAIL, $this->platform);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getPlatformMock()
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->setMethods(array('getGuidTypeDeclarationSQL'))
            ->getMockForAbstractClass();
    }
}
