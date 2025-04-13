<?php

namespace App\Exceptions;

use Exception;

class CustomValidationException extends Exception
{
   // protected string $message;
    protected string $endUserMessage;
    protected int $statusCode;

    public function __construct(string $message, string $endUserMessage, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->endUserMessage = $endUserMessage;
       // $this->message = $message;
        $this->statusCode = $statusCode;
    }

    // public function getsMessage(): string
    // {
    //     return $this->message;
    // }
    public function getEndUserMessage(): string
    {
        return $this->endUserMessage;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
