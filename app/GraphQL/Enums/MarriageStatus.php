<?php
namespace App\GraphQL\Enums;

enum MarriageStatus: int
{
    case None = 0;
    case Related = 1;
    case NotRelated = 2;
    case Susspend = 3;

}