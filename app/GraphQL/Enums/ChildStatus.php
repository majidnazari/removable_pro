<?php
namespace App\GraphQL\Enums;


enum ChildStatus: int
{
    case None = 0;
    case WithFamily = 1;
    case Separated = 2;


}