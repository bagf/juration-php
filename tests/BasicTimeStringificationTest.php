<?php

use PHPUnit\Framework\TestCase;
use Juration\Juration;

class BasicTimeStringificationTest extends TestCase
{
    public function testStringValues()
    {
        $this->assertEquals('12 seconds', Juration::stringify(12));
    }
}