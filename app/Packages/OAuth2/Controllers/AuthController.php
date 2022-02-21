<?php

namespace App\Packages\OAuth2\Controllers;

use App\Models\User;
use App\Packages\OAuth2\Exception\WrongAuthDataException;
use App\Packages\OAuth2\Factory\TokenFactory;
use App\Packages\OAuth2\Mapping\Results\AuthorizeResult;
use App\Packages\OAuth2\Models\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Psr\Log\LoggerInterface;

class AuthController extends Controller
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function authorize(Request $request, TokenFactory $tokenFactory): JsonResponse
    {
        $username = $request->get('username');
        $password = $request->get('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            /** @var User $user */
            $user = Auth::user();
            $this->logger->info("User is authorized.", $user->toArray());

            $this->logger->info("Make all old user tokens expire.");
            $countUpdated = Token::query()
                ->whereHas('user', fn(Builder $q) => $q->whereKey($user->getKey()))
                ->update(['is_expired' => true]);
            $this->logger->info("Number of tokens updated: {$countUpdated}.");

            $token = $tokenFactory->createByUser($user);
            if ($token->save()) {
                $this->logger->info("Access token created successfully for user: {$user->username}.");
            }

            return response()->json(AuthorizeResult::createByToken($token));
        }

        throw new WrongAuthDataException();
    }
}
