<?php
namespace App\GraphQL\Enums;


enum RequestStatusSender: int
{

    case Active = 1;
    case Cancel = 2;
    case Susspend = 3;

}
