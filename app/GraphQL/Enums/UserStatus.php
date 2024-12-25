<?php
namespace App\GraphQL\Enums;


enum UserStatus: int
{

    case Balocked = -1;
    case None = 0;
    case Active = 1;
    case Inactive = 2;
    case Suspend = 3;
    case New = 4;

}
