<?php

use PHPUnit\Framework\TestCase;
use Juration\Juration;

class BasicTimeParsingTest extends TestCase
{
    public function testSeconds()
    {
        $this->assertEquals(1, Juration::parse('1 sec'));
        $this->assertEquals(3, Juration::parse('2s 1second'));
        $this->assertEquals(2, Juration::parse('2seconds'));
        /**
         * @todo this breaks it
         */
        //$this->assertEquals(1, Juration::parse('1.5s'));
    }

    public function testMinutes()
    {
        $this->assertEquals(60, Juration::parse('1 m'));
        $this->assertEquals(180, Juration::parse('2mins 1min'));
        $this->assertEquals(120, Juration::parse('2minutes'));
        $this->assertEquals(150, Juration::parse('1.5m'));
    }

    public function testHours()
    {
        $this->assertEquals(3600, Juration::parse('1 hr'));
        $this->assertEquals(10800, Juration::parse('2h 1hr'));
        $this->assertEquals(7200, Juration::parse('2hours'));
        $this->assertEquals(5400, Juration::parse('1.5hs'));
    }

    public function testDays()
    {
        $this->assertEquals(86400, Juration::parse('1 d'));
        $this->assertEquals(259200, Juration::parse('2dys 1dy'));
        $this->assertEquals(172800, Juration::parse('2days'));
        $this->assertEquals(129600, Juration::parse('1.5ds'));
    }

    public function testWeeks()
    {
        $this->assertEquals(604800, Juration::parse('1 w'));
        $this->assertEquals(1844000, Juration::parse('2wks 1wk'));
        $this->assertEquals(1209600, Juration::parse('2weeks'));
        $this->assertEquals(907200, Juration::parse('1.5ws'));
    }

    public function testMonths()
    {
        $this->assertEquals(2628000, Juration::parse('1 mo'));
        $this->assertEquals(7884000, Juration::parse('2mons 1mth'));
        $this->assertEquals(5256000, Juration::parse('2months'));
        $this->assertEquals(3942000, Juration::parse('1.5mths'));
    }

    public function testYears()
    {
        $this->assertEquals(31536000, Juration::parse('1 y'));
        $this->assertEquals(94608000, Juration::parse('2yrs 1yr'));
        $this->assertEquals(63072000, Juration::parse('2years'));
        $this->assertEquals(47304000, Juration::parse('1.5ys'));
    }
}