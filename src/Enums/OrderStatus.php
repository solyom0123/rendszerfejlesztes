<?php


namespace App\Enums;


use Doctrine\Common\Collections\ArrayCollection;

class Roles
{
    public static $ORDERED = 'ORDERED';
    public static $ACCEPTED = 'ACCEPTED';
    public static $IN_PROGRESS = 'IN_PROGRESS';
    public static $DONE = 'DONE';

    public static function match($STATUS): ?string
    {
        $status= "";
        switch ($STATUS) {
            case self::$ORDERED:
                $status = self::$ORDERED;
                break;
            case self::$ACCEPTED:
                $status = self::$ACCEPTED;
                break;
            case self::$IN_PROGRESS:
                $status = self::$IN_PROGRESS;
                break;
            case self::$DONE:
                $status = self::$DONE;
                break;
        }
        return $status;
    }

    public static function getAll(): ArrayCollection
    {
        return new ArrayCollection([
            self::$ORDERED,
            self::$ACCEPTED,
            self::$IN_PROGRESS,
            self::$DONE
        ]);
    }
    public static function getFilteredAll(): ArrayCollection
    {
        return new ArrayCollection([
            self::$ORDERED,
            self::$ACCEPTED,
            self::$IN_PROGRESS
        ]);
    }
}