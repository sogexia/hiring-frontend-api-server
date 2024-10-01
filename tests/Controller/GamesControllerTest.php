<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GamesControllerTest extends WebTestCase
{
    use Withcontext;

    public function testAuth()
    {
        $errorNoToken = '{"message":"No API token provided"}';
        $errorBadToken = '{"message":"Bad API token provided"}';

        $client = static::createClient();
        $client->request('GET', '/api/v1/games');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorNoToken, $client->getResponse()->getContent());

        $client->request('GET', '/api/v1/games', [], [], $this->withWrongAuthorizationHeader());

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorBadToken, $client->getResponse()->getContent());

        $client->request('POST', '/api/v1/games');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorNoToken, $client->getResponse()->getContent());

        $client->request('POST', '/api/v1/games', [], [], $this->withWrongAuthorizationHeader());

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->isJson();
        $this->assertJsonStringEqualsJsonString($errorBadToken, $client->getResponse()->getContent());
    }

    public function testGetGames()
    {
        $client = static::createClient([], ['HTTP_Authorization' => 'ApiKey test_token!']);
        $client->request('GET', '/api/v1/games');

        $this->assertResponseIsSuccessful();
        $this->isJson();

        $json = json_decode($client->getResponse()->getContent());
        $this->assertCount(3, $json);
        foreach ($json as $game) {
            $this->assertObjectHasProperty('id', $game);
            $this->assertObjectHasProperty('scores', $game);
            foreach ($game->scores as $score) {
                $this->assertObjectHasProperty('playerId', $score);
                $this->assertObjectHasProperty('score', $score);
            }
        }
    }

    public function testPostGames()
    {
        $this->markTestIncomplete('Need to test all error use case');
    }
}
