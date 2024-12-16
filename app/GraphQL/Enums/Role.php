<?php
namespace App\GraphQL\Enums;


enum Role: int
{
    case Admin = 1;
    case Supporter = 2;
    case User = 3;
  
}
