<?php
namespace App\GraphQL\Enums;


enum NotifStatus: int
{

    case Read = 1;
    case NotRead = 2;
    
}
