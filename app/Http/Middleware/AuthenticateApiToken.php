<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $tokenHash = hash('sha256', $plainToken);
        $token = DB::table('user_api_tokens')
            ->where('token_hash', $tokenHash)
            ->where(function ($query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::query()->find($token->user_id);
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        DB::table('user_api_tokens')
            ->where('id', $token->id)
            ->update(['last_used_at' => now()]);

        $request->setUserResolver(fn (): User => $user);
        Auth::setUser($user);

        return $next($request);
    }
}
