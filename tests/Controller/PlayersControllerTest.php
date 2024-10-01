<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayersControllerTest extends WebTestCase
{
    use Withcontext;

    public function testAuth()
    {
        $errorNoToken = '{"message":"No API token provided"}';
        $errorBadToken = '{"message":"Bad API token provided"}';

        $client = static::createClient();
        $client->request('GET', '/api/v1/players');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorNoToken, $client->getResponse()->getContent());

        $client->request('GET', '/api/v1/players', [], [], $this->withWrongAuthorizationHeader());

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorBadToken, $client->getResponse()->getContent());

        $client->request('POST', '/api/v1/players');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorNoToken, $client->getResponse()->getContent());

        $client->request('POST', '/api/v1/players', [], [], $this->withWrongAuthorizationHeader());

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorBadToken, $client->getResponse()->getContent());
    }

    public function testGetPlayers()
    {
        $client = static::createClient([], $this->withGoodAuthorizationHeader());
        $client->request('GET', '/api/v1/players');

        $this->assertResponseIsSuccessful();
        $this->isJson();

        $json = json_decode($client->getResponse()->getContent());
        $this->assertCount(2, $json);
        foreach ($json as $player) {
            $this->assertObjectHasProperty('id', $player);
            $this->assertObjectHasProperty('name', $player);
        }
    }

    public function testPostPlayers()
    {
        $this->markTestIncomplete('Need to test all error use case');
    }
}
