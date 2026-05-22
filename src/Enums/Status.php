<?php

/**
 * Statuts métiers compatibles PHP ancien.
 */
class Status
{
    const PENDING = 'PENDING';
    const ASSIGNED = 'ASSIGNED';
    const RESOLVED = 'RESOLVED';

    public static function isValid($status)
    {
        return in_array($status, [self::PENDING, self::ASSIGNED, self::RESOLVED], true);
    }
}
