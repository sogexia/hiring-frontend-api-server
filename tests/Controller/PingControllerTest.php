<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/ping');

        $this->assertResponseIsSuccessful();
        $this->isJson();

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($json->ping, 'pong');
    }
}
