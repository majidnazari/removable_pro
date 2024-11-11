<?php
namespace App\GraphQL\Enums;



use Nuwave\Lighthouse\Support\Contracts\EnumValue;

// class ChildStatus implements EnumValue
// {
//     // This method is used to convert the integer value into a string that GraphQL understands.
//     public static function getValues(): array
//     {
//         return [
//             0  => 'None',
//             1  => 'WithFamily',
//             2  => 'Separated',
//             3  => 'Suspended',
//             4  => 'New',
//         ];
//     }

//     // Convert a status integer to the corresponding string
//     public static function fromValue($value)
//     {
//         $values = self::getValues();
//         return $values[$value] ?? null;
//     }

//     // Convert a status string to the corresponding integer for storing in the database
//     public static function toValue($value)
//     {
//         $values = array_flip(self::getValues());
//         return $values[$value] ?? null;
//     }
// }
