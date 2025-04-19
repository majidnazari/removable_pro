<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class GraphQLStatusCodeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only intercept GraphQL JSON responses
        if (
            $request->is('graphql') &&
            $response->status() === 200 &&
            str_contains($response->headers->get('Content-Type'), 'application/json')
        ) {
            $data = json_decode($response->getContent(), true);

            if (!empty($data['errors'])) {
                // Log the errors for debugging
//               Log::info("GraphQL Error Response: ". json_encode($data['errors']));

                // Determine the status code based on errors
                $statusCode = $this->determineStatusCode($data['errors']);

                return response()->json($data, $statusCode);
            }
        }

        return $response;
    }

    protected function determineStatusCode(array $errors): int
    {
        foreach ($errors as $error) {
            $message = strtolower($error['message'] ?? '');
            $reason = strtolower($error['extensions']['reason'] ?? '');
            $debugMessage = strtolower($error['extensions']['debugMessage'] ?? '');

            // Token Expired Error (Unauthenticated)
            if (
                str_contains($message, 'unauthenticated')
            ) {
                return 401; // Unauthorized (Token Expired)
            }

            // Client Authentication Failed (Invalid Client)
            if (
                str_contains($message, 'invalid_client') &&
                str_contains($reason, 'client authentication failed')
            ) {
                return 401; // Unauthorized (Passport not configured correctly)
            }

            // Incorrect Username or Password
            if (
                str_contains($message, 'authentication exception') &&
                str_contains($reason, 'incorrect username or password')
            ) {
                return 401; // Unauthorized (Incorrect Username or Password)
            }

            // Authentication Errors (General case)
            if (
                str_contains($message, 'authentication exception') ||
                str_contains($reason, 'authentication')
            ) {
                return 401; // Unauthorized
            }

            // Authorization Errors (Forbidden)
            if (
                str_contains($message, 'unauthorized') ||
                str_contains($reason, 'unauthorized') ||
                str_contains($reason, 'forbidden')
            ) {
                return 403; // Forbidden
            }

            // Validation Errors
            if (
                str_contains($message, 'validation') ||
                isset($error['extensions']['validation'])
            ) {
                return 422; // Unprocessable Entity (Validation Errors)
            }

            // Not Found Errors
            if (
                str_contains($message, 'not found') ||
                str_contains($reason, 'not found') ||
                str_contains($debugMessage, 'not found')
            ) {
                return 404; // Not Found
            }

            // Conflict Errors (Resource already exists, etc.)
            if (
                str_contains($message, 'conflict') ||
                str_contains($reason, 'conflict')
            ) {
                return 409; // Conflict
            }

            // Rate Limit Errors (Too many requests)
            if (
                str_contains($message, 'throttle') ||
                str_contains($reason, 'throttled') ||
                str_contains($message, 'rate limit')
            ) {
                return 429; // Too Many Requests
            }

            // Internal Server Errors (Fallback)
            if (
                str_contains($message, 'server error') ||
                str_contains($reason, 'server error')
            ) {
                return 500; // Internal Server Error
            }
        }

        return 500; // Default to 500 if no specific condition matched
    }
}
