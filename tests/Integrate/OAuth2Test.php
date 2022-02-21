<?php

namespace Tests\Integrate;

use App\Packages\OAuth2\Models\Token;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class OAuth2Test extends TestCase
{
    public function test_authorize(): void
    {
        $response = $this->json('POST', '/api/oauth2/authorize', [
            'username' => $this->user->username,
            'password' => 'test'
        ]);

        /** @var Token $token */
        $token = Token::query()
            ->whereHas('user', fn(Builder $q) => $q->whereKey($this->user->getKey()))
            ->where('is_expired', false)
            ->firstOrFail();

        $response->assertStatus(200)
            ->assertJson([
                'token' => $token->value,
                'expiredAt' => $token->expired_at
            ]);
    }

    public function test_not_authorize(): void{
        $response = $this->json('POST', '/api/oauth2/authorize', [
            'username' => 'asdasdasdasdas',
            'password' => 'test'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 401,
                'data' => [
                    'message' => 'Incorrect username or password.',
                    'code' => 104
                ]
            ]);
    }
}
