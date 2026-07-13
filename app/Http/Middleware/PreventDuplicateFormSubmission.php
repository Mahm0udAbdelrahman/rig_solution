<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateFormSubmission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->shouldInspectRequest($request)) {
            return $next($request);
        }

        $submissionToken = trim((string) $request->input('_submission_token', ''));
        if ($submissionToken === '') {
            return $next($request);
        }

        $sessionKey = 'duplicate_form_submission_tokens';
        $usedTokens = (array) $request->session()->get($sessionKey, []);

        if (in_array($submissionToken, $usedTokens, true)) {
            if ($request->expectsJson() || $request->ajax()) {
                return new JsonResponse([
                    'message' => 'This form has already been submitted. Please wait for the first request to finish.',
                ], 409);
            }

            return new RedirectResponse(url()->previous() ?: '/');
        }

        $response = $next($request);

        if ($this->shouldPersistToken($response)) {
            $usedTokens[] = $submissionToken;
            $request->session()->put($sessionKey, array_values(array_unique($usedTokens)));
        }

        return $response;
    }

    private function shouldInspectRequest(Request $request): bool
    {
        if (!$request->isMethod('post') && !$request->isMethod('put') && !$request->isMethod('patch')) {
            return false;
        }

        return $request->is('dashboard/work-flow/*') || $request->is('dashboard/inspection/*');
    }

    private function shouldPersistToken(Response $response): bool
    {
        $statusCode = (int) $response->getStatusCode();

        return $statusCode >= 200 && $statusCode < 400;
    }
}
