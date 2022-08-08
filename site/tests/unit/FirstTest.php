<?php


namespace tests\unit;


use PHPUnit\Framework\TestCase;

class FirstTest extends TestCase
{
    public function testFirst()
    {
        $this->assertTrue(true);
    }

    public function testSecond()
    {
        $this->assertTrue(true);
    }

    public function testThird()
    {
        $this->assertFalse(true);
    }
}