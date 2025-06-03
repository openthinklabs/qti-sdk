<?php

namespace qtismtest\runtime\storage\binary;

use DateTime;
use DateTimeZone;
use qtism\common\storage\BinaryStreamAccess;
use qtism\common\storage\BinaryStreamAccessException;
use qtism\common\storage\MemoryStream;
use qtismtest\QtiSmTestCase;

/**
 * Class BinaryStreamAccessTest
 */
class BinaryStreamAccessTest extends QtiSmTestCase
{
    private $emptyStream;

    public function setUp(): void
    {
        parent::setUp();

        $this->emptyStream = new MemoryStream();
        $this->emptyStream->open();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->emptyStream);
    }

    /**
     * Get an open empty stream
     *
     * @return MemoryStream
     */
    public function getEmptyStream(): MemoryStream
    {
        return $this->emptyStream;
    }

    public function testReadTinyInt(): void
    {
        $stream = new MemoryStream("\x00\x01\x0A");
        $stream->open();

        $reader = new BinaryStreamAccess($stream);
        $tinyInt = $reader->readTinyInt();
        $this::assertIsInt($tinyInt);
        $this::assertEquals(0, $tinyInt);

        $tinyInt = $reader->readTinyInt();
        $this::assertIsInt($tinyInt);
        $this::assertEquals(1, $tinyInt);

        $tinyInt = $reader->readTinyInt();
        $this::assertIsInt($tinyInt);
        $this::assertEquals(10, $tinyInt);

        try {
            // EOF reached.
            $tinyInt = $reader->readTinyInt();
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::TINYINT, $e->getCode());
        }
    }

    public function testWriteTinyInt(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeTinyInt(0);
        $access->writeTinyInt(1);
        $access->writeTinyInt(255);
        $stream->rewind();

        $reader = new BinaryStreamAccess($stream);

        $val = $reader->readTinyInt();
        $this::assertIsInt($val);
        $this::assertEquals(0, $val);

        $val = $reader->readTinyInt();
        $this::assertIsInt($val);
        $this::assertEquals(1, $val);

        $val = $reader->readTinyInt();
        $this::assertIsInt($val);
        $this::assertEquals(255, $val);
    }

    public function testReadDateTime(): void
    {
        $date = new DateTime('2013:09:04 09:37:09', new DateTimeZone('Europe/Luxembourg'));
        $stream = new MemoryStream(pack('l', $date->getTimestamp()));
        $stream->open();
        $access = new BinaryStreamAccess($stream);

        $date = $access->readDateTime();
        $this::assertEquals(1378280229, $date->getTimestamp());

        try {
            // EOF
            $date = $access->readDateTime();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::DATETIME, $e->getCode());
        }
    }

    public function testWriteDateTime(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeDateTime(new DateTime('2013:09:04 09:37:09', new DateTimeZone('Europe/Luxembourg')));
        $stream->rewind();

        $date = $access->readDateTime();
        $this::assertEquals(1378280229, $date->getTimestamp());
    }

    public function testReadShort(): void
    {
        $stream = new MemoryStream(pack('S', 0) . pack('S', 1) . pack('S', 65535));
        $stream->open();
        $reader = new BinaryStreamAccess($stream);

        $short = $reader->readShort();
        $this::assertIsInt($short);
        $this::assertEquals(0, $short);

        $short = $reader->readShort();
        $this::assertIsInt($short);
        $this::assertEquals(1, $short);

        $short = $reader->readShort();
        $this::assertIsInt($short);
        $this::assertEquals(65535, $short);

        // go beyond EOF.
        try {
            $short = $reader->readShort();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::SHORT, $e->getCode());
        }

        // try to read on a closed stream.
        try {
            $stream = $this->getEmptyStream();
            $stream->close();
            $reader = new BinaryStreamAccess($stream);
            $short = $reader->readShort();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::NOT_OPEN, $e->getCode());
        }
    }

    public function testWriteShort(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeShort(0);
        $access->writeShort(1);
        $access->writeShort(65535);
        $stream->rewind();

        $val = $access->readShort();
        $this::assertIsInt($val);
        $this::assertEquals(0, $val);

        $val = $access->readShort();
        $this::assertIsInt($val);
        $this::assertEquals(1, $val);

        $val = $access->readShort();
        $this::assertIsInt($val);
        $this::assertEquals(65535, $val);
    }

    public function testReadInt(): void
    {
        $stream = new MemoryStream(pack('l', 0) . pack('l', 1) . pack('l', -1) . pack('l', 2147483647) . pack('l', -2147483648));
        $stream->open();
        $reader = new BinaryStreamAccess($stream);

        $int = $reader->readInteger();
        $this::assertIsInt($int);
        $this::assertEquals(0, $int);

        $int = $reader->readInteger();
        $this::assertIsInt($int);
        $this::assertEquals(1, $int);

        $int = $reader->readInteger();
        $this::assertIsInt($int);
        $this::assertEquals(-1, $int);

        $int = $reader->readInteger();
        $this::assertIsInt($int);
        $this::assertEquals(2147483647, $int);

        $int = $reader->readInteger();
        $this::assertIsInt($int);
        $this::assertEquals(-2147483648, $int);

        // reach EOF.
        try {
            $int = $reader->readInteger();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::INT, $e->getCode());
        }
    }

    public function testWriteInt(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeInteger(0);
        $access->writeInteger(1);
        $access->writeInteger(-1);
        $access->writeInteger(2147483647);
        $access->writeInteger(-2147483648);
        $stream->rewind();

        $val = $access->readInteger();
        $this::assertIsInt($val);
        $this::assertEquals(0, $val);

        $val = $access->readInteger();
        $this::assertIsInt($val);
        $this::assertEquals(1, $val);

        $val = $access->readInteger();
        $this::assertIsInt($val);
        $this::assertEquals(-1, $val);

        $val = $access->readInteger();
        $this::assertIsInt($val);
        $this::assertEquals(2147483647, $val);

        $val = $access->readInteger();
        $this::assertIsInt($val);
        $this::assertEquals(-2147483648, $val);
    }

    public function testReadBool(): void
    {
        $stream = new MemoryStream("\x00\x01");
        $stream->open();
        $reader = new BinaryStreamAccess($stream);

        $bool = $reader->readBoolean();
        $this::assertIsBool($bool);
        $this::assertFalse($bool);

        $bool = $reader->readBoolean();
        $this::assertIsBool($bool);
        $this::assertTrue($bool);

        try {
            $bool = $reader->readBoolean();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::BOOLEAN, $e->getCode());
        }
    }

    public function testWriteBool(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeBoolean(true);
        $access->writeBoolean(false);
        $stream->rewind();

        $val = $access->readBoolean();
        $this::assertIsBool($val);
        $this::assertTrue($val);

        $val = $access->readBoolean();
        $this::assertIsBool($val);
        $this::assertFalse($val);
    }

    public function testReadString(): void
    {
        $stream = new MemoryStream(pack('S', 0) . '' . pack('S', 1) . 'A' . pack('S', 6) . 'binary');
        $stream->open();
        $reader = new BinaryStreamAccess($stream);

        $string = $reader->readString();
        $this::assertIsString($string);
        $this::assertEquals('', $string);

        $string = $reader->readString();
        $this::assertIsString($string);
        $this::assertEquals('A', $string);

        $string = $reader->readString();
        $this::assertIsString($string);
        $this::assertEquals('binary', $string);

        try {
            $reader->readString();
            $this::assertTrue(false);
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::STRING, $e->getCode());
        }
    }

    public function testWriteString(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeString('');
        $access->writeString('A');
        $access->writeString('binary');
        $stream->rewind();

        $val = $access->readString();
        $this::assertIsString($val);
        $this::assertEquals('', $val);

        $val = $access->readString();
        $this::assertIsString($val);
        $this::assertEquals('A', $val);

        $val = $access->readString();
        $this::assertIsString($val);
        $this::assertEquals('binary', $val);
    }

    public function testReadFloat(): void
    {
        $stream = new MemoryStream(pack('d', 0.0) . pack('d', -M_PI) . pack('d', M_2_PI));
        $stream->open();
        $reader = new BinaryStreamAccess($stream);

        $float = $reader->readFloat();
        $this::assertIsFloat($float);
        $this::assertEquals(round(0.0, 3), round($float, 3));

        $float = $reader->readFloat();
        $this::assertIsFloat($float);
        $this::assertEquals(round(-M_PI, 3), round($float, 3));

        $float = $reader->readFloat();
        $this::assertIsFloat($float);
        $this::assertEquals(round(M_2_PI, 3), round($float, 3));

        try {
            $float = $reader->readFloat();
        } catch (BinaryStreamAccessException $e) {
            $this::assertEquals(BinaryStreamAccessException::FLOAT, $e->getCode());
        }
    }

    public function testWriteFloat(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);

        $access->writeFloat(0.0);
        $access->writeFloat(-M_PI);
        $access->writeFloat(M_2_PI);
        $stream->rewind();

        $val = $access->readFloat();
        $this::assertIsFloat($val);
        $this::assertEquals(round(0.0, 3), round($val, 3));

        $val = $access->readFloat();
        $this::assertIsFloat($val);
        $this::assertEquals(round(-M_PI, 3), round($val, 3));

        $val = $access->readFloat();
        $this::assertIsFloat($val);
        $this::assertEquals(round(M_2_PI, 3), round($val, 3));
    }

    public function testWriteIntegerClosedStream(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $stream->close();

        $this->expectException(BinaryStreamAccessException::class);
        $this->expectExceptionMessage('Writing a integer from a closed binary stream is not permitted.');

        $access->writeInteger(1);
    }

    public function testWriteFloatClosedStream(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $stream->close();

        $this->expectException(BinaryStreamAccessException::class);
        $this->expectExceptionMessage('Writing a double precision float from a closed binary stream is not permitted.');

        $access->writeFloat(1.);
    }

    public function testWriteStringMaxLengthExceeded(): void
    {
        $substitute = mb_substitute_character();
        $string = str_repeat('a', 2 ** 17);

        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $access->writeString($string);
        $stream->rewind();

        // The written string should be 2^16 - 1 long anyway (force by implementation to not break).
        $this::assertSame(str_repeat('a', 2 ** 16 - 1), $access->readString());
        $this::assertSame($substitute, mb_substitute_character());
    }

    public function testWriteStringMaxLengthWithMultiByteExceeded(): void
    {
        $substitute = mb_substitute_character();
        $string = str_repeat('a', 2 ** 16 - 2) . '🤦';

        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $access->writeString($string);
        $stream->rewind();

        // The written string will be 2^16 - 2 long as the multi-byte character at the end exceeds the 2^16 - 1 limit
        $this::assertSame(str_repeat('a', 2 ** 16 - 2), $access->readString());
        $this::assertSame($substitute, mb_substitute_character());
    }

    public function testReadBinary(): void
    {
        $stream = new MemoryStream(pack('S', 4) . 'test');
        $stream->open();
        $access = new BinaryStreamAccess($stream);

        $this::assertEquals('test', $access->readBinary());
    }

    /**
     * @depends testReadBinary
     */
    public function testWriteBinary(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $access->writeBinary('test');
        $stream->rewind();
        $read = $access->readBinary();

        $this::assertEquals('test', $read);
    }

    /**
     * @depends testWriteBinary
     */
    public function testWriteBinaryClosedStream(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $stream->close();

        $this->expectException(BinaryStreamAccessException::class);
        $this->expectExceptionMessage('Writing a string from a closed binary stream is not permitted.');

        $access->writeBinary('test');
    }

    public function testWriteDurationCloseStream(): void
    {
        $stream = $this->getEmptyStream();
        $access = new BinaryStreamAccess($stream);
        $stream->close();

        $this->expectException(BinaryStreamAccessException::class);
        $this->expectExceptionMessage('Writing a datetime from a closed binary stream is not permitted.');

        $access->writeDateTime(new DateTime());
    }
}
