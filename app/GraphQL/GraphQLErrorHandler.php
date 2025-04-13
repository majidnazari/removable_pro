<?php

namespace App\GraphQL;

use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use App\Exceptions\CustomValidationException;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class GraphQLErrorHandler
{
    public static function handle(Error $error)
    {
        $originalError = $error->getPrevious();

        if ($originalError instanceof CustomValidationException) {
            return [
                'message' => $originalError->getMessage(),
                'end_user_message' => $originalError->getEndUserMessage(),
                'status_code' => $originalError->getStatusCode(),
            ];
        }

        // fallback for other errors
        return FormattedError::createFromException($error);
    }
}
