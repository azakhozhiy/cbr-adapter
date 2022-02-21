<?php

namespace App\Packages\OAuth2\Middleware;

use App\Packages\OAuth2\Exception\TokenIsExpiredException;
use App\Packages\OAuth2\Exception\TokenNotFoundException;
use App\Packages\OAuth2\Repository\TokenRepository;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class OAuth2Middleware
{
    /**
     * @throws TokenNotFoundException
     * @throws TokenIsExpiredException
     */
    public function handle(Request $request, Closure $next)
    {
        $logger = app(LoggerInterface::class);
        $accessToken = $request->headers->get('Authorization');

        if (!$accessToken) {
            $logger->info("Token not found in Authorization header.");
            throw new TokenNotFoundException();
        }

        try {
            $token = app(TokenRepository::class)->getByValue($accessToken);
        } catch (ModelNotFoundException $e) {
            $logger->info("Token not found in database.");
            throw new TokenNotFoundException();
        }

        if ($token->isExpired()) {
            $logger->info("Token is expired, change flag value in table.");
            $token->makeExpired();
            $token->save();

            throw new TokenIsExpiredException();
        }


        return $next($request);
    }
}
