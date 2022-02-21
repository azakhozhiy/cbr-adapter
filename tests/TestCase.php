<?php

namespace Tests;

use App\Models\User;
use App\Packages\OAuth2\Factory\TokenFactory;
use App\Packages\OAuth2\Models\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected Token $token;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->username = 'test';
        $user->password = bcrypt('test');
        $user->save();

        $this->user = $user;

        $tokenFactory = $this->app->make(TokenFactory::class);
        $token = $tokenFactory->createByUser($user);
        $token->save();

        $this->token = $token;
    }

    public function secureRequest(
        string $method,
        string $uri,
        array $data = [],
        array $headers = []
    ): TestResponse {
        return $this->json($method, $uri, $data, array_merge(['Authorization' => $this->token->value],$headers));
    }
}
