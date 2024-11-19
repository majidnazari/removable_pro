<?php
namespace App\GraphQL\Enums;


enum MergeStatus: int
{

    
    case Active = 1;
    case Inactive = 2;
    case Susspend = 3;
    case Complete = 4;

}
