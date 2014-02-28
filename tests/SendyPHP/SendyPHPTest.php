<?php

namespace SendyPHP;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class SendyPHPTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var MockPlugin $mocker
     */
    protected $mocker;

    /**
     * @var SendyPHP $instance
     */
    protected $instance;

    public function setUp()
    {
        $this->client = new Client('http://example.com');
        $this->mocker = new MockPlugin();
        $this->client->addSubscriber($this->mocker);
        $this->instance = new SendyPHP($this->client, 'api_key', 'list_id');
    }

    /**
     * @test
     */
    public function testSubscribersCountSuccess()
    {
        $this->mocker->addResponse(new Response(200, [], '5'));
        $result = $this->instance->getSubscribersCount();
        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($result->getResult(), '5');
    }

    /**
     * @test
     */
    public function testSubscribersCountError()
    {
        $this->mocker->addResponse(new Response(200, [], 'API key not passed'));
        $result = $this->instance->getSubscribersCount();
        $this->assertFalse($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'API key not passed');
    }

    /**
     * @test
     */
    public function testSubscribeSuccess()
    {
        $this->mocker->addResponse(new Response(200, [], 'true'));
        $result = $this->instance->subscribe(['email'=>'test@example.com']);
        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'true');
    }
    /**
     * @test
     */
    public function testSubscribeError()
    {
        $this->mocker->addResponse(new Response(200, [], 'Some fields are missing.'));
        $result = $this->instance->subscribe([]);
        $this->assertFalse($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'Some fields are missing.');
    }

    /**
     * @test
     */
    public function testUnsubscribeSuccess()
    {
        $this->mocker->addResponse(new Response(200, [], 'true'));
        $result = $this->instance->unsubscribe('test@example.com');
        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'true');
    }
    /**
     * @test
     */
    public function testUnsubscribeError()
    {
        $this->mocker->addResponse(new Response(200, [], 'Some fields are missing.'));
        $result = $this->instance->unsubscribe('');
        $this->assertFalse($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'Some fields are missing.');
    }

    /**
     * @test
     */
    public function testSubscriberStatusSuccess()
    {
        $this->mocker->addResponse(new Response(200, [], 'Subscribed'));
        $result = $this->instance->getStatus('test@example.com');
        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'Subscribed');
    }

    /**
     * @test
     */
    public function testSubscriberStatusError()
    {
        $this->mocker->addResponse(new Response(200, [], 'No data passed'));
        $result = $this->instance->getStatus('');
        $this->assertFalse($result->isSuccessful());
        $this->assertEquals($result->getResult(), 'No data passed');
    }
}