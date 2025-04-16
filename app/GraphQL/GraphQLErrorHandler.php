<?php

namespace App\GraphQL;

use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use App\Exceptions\CustomValidationException;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Exceptions\ValidationException;

use Nuwave\Lighthouse\Execution\ErrorHandler;
use Closure;
use Log;

// class GraphQLErrorHandler
// {
//     public static function handle(Error $error)
//     {
//         $originalError = $error->getPrevious();

//         // ✅ Handle custom app-level validation exception
//         if ($originalError instanceof CustomValidationException) {
//             Log::info("inside of custom error " . json_encode($originalError->getMessage()));
//             return [
//                 'message' => $originalError->getMessage(),
//                 'end_user_message' => $originalError->getEndUserMessage(),
//                 'status_code' => $originalError->getStatusCode(),
//             ];
//         }

//         // ✅ Handle Laravel's native validation exception
//         if ($originalError instanceof ValidationException) {
//             Log::info("inside of ValidationException error: " . json_encode($originalError->errors()));



//             $messages = collect($originalError->errors())
//                 ->flatMap(fn ($messages) => $messages)
//                 ->values()
//                 ->implode(' ');

//             return [
//                 'message' => $messages,
//                 'end_user_message' => 'لطفاً تمام فیلدها را به‌درستی پر کنید.',
//                 'status' => 422,
//                 'extensions' => [
//                     'validation' => $originalError->errors(),
//                     'code' => 'VALIDATION_ERROR',
//                 ],
//             ];
//         }

//         // fallback for unhandled exceptions
//         return FormattedError::createFromException($error);
//     }
// }

class GraphQLErrorHandler implements ErrorHandler
{
    public function __invoke(?Error $error, Closure $next): ?array
    {
        $formattedError = $next($error);

        $previous = $error?->getPrevious();
        
        // Log the previous error to see what type it is
        Log::info('Previous exception type: ' . get_class($previous));

        if ($previous instanceof CustomValidationException) {
            Log::info("inside of custom error " . json_encode($previous->getMessage()));
            return [
                'message' => $previous->getMessage(),
                'end_user_message' => $previous->getEndUserMessage(),
                'status_code' => $previous->getStatusCode(),
            ];
        }

        // This will catch validation issues from Lighthouse validators
        if ($previous instanceof ValidationException ) {
            $extensions = $previous->getExtensions();
           
            if (isset($extensions['validation'])) {
                Log::info("GraphQL Validation Error", $extensions['validation']);
            }

            return [
                'message' => "Validation Error!",
                'end_user_message' => 'لطفاً تمام فیلدها را به‌درستی پر کنید.',
                'status' => 422,
                'extensions' => $extensions,
            ];
        }

        return $formattedError;
    }
}