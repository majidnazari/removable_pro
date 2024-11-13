<?php
namespace App\GraphQL\Enums;


enum RequestStatus: int
{
    case Active = 1;
    case Refused = 2;
    case Susspend = 3;

}
