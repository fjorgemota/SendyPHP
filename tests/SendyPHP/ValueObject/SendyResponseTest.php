<?php

namespace SendyPHP\ValueObject;

class SendyResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSuccessfulOnError()
    {
        $failedInstance = new SendyResponse(false, "Error");
        $this->assertFalse($failedInstance->isSuccessful());
    }

    /**
     * @test
     */
    public function testSuccessfulOnSuccess()
    {
        $successfulInstance = new SendyResponse(true, "Success");
        $this->assertTrue($successfulInstance->isSuccessful());
    }

    /**
     * @test
     */
    public function testResponse()
    {
        $instance = new SendyResponse(true, 'test 123');
        $this->assertEquals($instance->getResult(), 'test 123');
    }

}
