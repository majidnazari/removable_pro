<?php
namespace App\GraphQL\Enums;



enum PhysicalCondition: int
{
    case None = 0;
    case Healthy = 1;
    case Handicapped = 2;
    case Dead = 3;


}
