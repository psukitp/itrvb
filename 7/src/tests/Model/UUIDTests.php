<?php

namespace UnitTests\Model;

use my\Exceptions\InvalidArgumentException;
use my\Model\UUID;
use PHPUnit\Framework\TestCase;

class UUIDTests extends TestCase
{
    public function testIncorrectUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect UUID');

        $uuid = 'lol-kek-roflrofl';
        $uuid = new UUID($uuid);
    }

    public function testToString(): void
    {
        $myUuid = 'b4b87940-e4f0-4fd6-a88d-8b5211a45a4d';
        $uuid = new UUID($myUuid);

        $this->assertEquals($myUuid, $uuid);
    }

    public function testGenerateUuid(): void
    {
        $myUuid = UUID::random();
        $uuid = new UUID($myUuid);

        $this->assertEquals($myUuid, $uuid);
    }
}