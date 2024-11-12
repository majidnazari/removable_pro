<?php
namespace App\GraphQL\Enums;

enum ChildKind: int
{
    case None = 0;
    case DirectChild = 1;
    case MotherChild = 2;
    case FatherChild = 3;

}