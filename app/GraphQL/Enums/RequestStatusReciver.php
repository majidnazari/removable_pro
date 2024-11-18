<?php
namespace App\GraphQL\Enums;


enum RequestStatusReciver: int
{

    case Active = 1;
    case Refused = 2;
    case Suspend = 3;
   
}
