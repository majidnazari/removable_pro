<?php
namespace App\GraphQL\Enums;


enum RequestStatusReceiver: int
{

    case Active = 1;
    case Refused = 2;
    case Suspend = 3;
   
}
