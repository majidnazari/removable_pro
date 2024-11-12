<?php
namespace App\GraphQL\Enums;


enum Status: int
{

    case Balocked = -1;
    case None = 0;
    case Active = 1;
    case Inactive = 2;
    case Susspend = 3;
    case New = 4;

}
