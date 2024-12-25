<?php
namespace App\GraphQL\Enums;


enum ConfirmMemoryStatus: int
{
    case Accept = 1;
    case Reject = 2;
    case Suspend = 3;

}
