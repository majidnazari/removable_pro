<?php

namespace App\Traits;

trait HasStatusEnum
{
    // Define status constants
    const STATUS_BLOCKED = -1;
    const STATUS_NONE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_SUSPENDED = 3;
    const STATUS_NEW = 4;

    // Status mapping for saving to the database
    protected static $statusMap = [
        'Blocked' => self::STATUS_BLOCKED,
        'None' => self::STATUS_NONE,
        'Active' => self::STATUS_ACTIVE,
        'Inactive' => self::STATUS_INACTIVE,
        'Suspended' => self::STATUS_SUSPENDED,
        'New' => self::STATUS_NEW,
    ];

    // Mutator to set the status value as integer
    public function setStatusAttribute($value)
    {
        // Set status to the corresponding integer value from the map
        $this->attributes['status'] = self::$statusMap[$value] ?? self::STATUS_NONE;  // Default to 'None'
    }

    // Accessor to get the status value as string
    public function getStatusAttribute($value)
    {
        // Reverse the map for reading the status as a string
        $statusMap = array_flip(self::$statusMap);

        // Return the corresponding string value for the status
        return $statusMap[$value] ?? 'None';
    }

    // Helper method to convert status string to integer for database
    public static function getStatusValue(string $status): int
    {
        return self::$statusMap[$status] ?? self::STATUS_NONE;  // Default to 'None' (0)
    }
}

