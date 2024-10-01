<?php

namespace App\Tests\Controller;

trait Withcontext
{
    public function withWrongAuthorizationHeader(): array
    {
        return ['HTTP_Authorization' => 'ApiKey wrongToken'];
    }

    public function withGoodAuthorizationHeader(): array
    {
        return ['HTTP_Authorization' => 'ApiKey test_token!'];
    }
}
