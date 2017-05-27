<?php

use PHPUnit\Framework\TestCase;
use Juration\Juration;

class BasicTimeStringificationTest extends TestCase
{
    public function testStringValues()
    {
        $this->assertEquals('12 secs', Juration::stringify(12));
        $this->assertEquals('3 mins 4 secs', Juration::stringify(184));
        $this->assertEquals('2 hrs 20 mins', Juration::stringify(8400));
        $this->assertEquals('6 mths 1 day', Juration::stringify(15854400));
    }
}