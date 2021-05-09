<?php


namespace App\Enums;


use Doctrine\Common\Collections\ArrayCollection;

class OrderStatus
{
    public static $ORDERED = 'ORDERED';
    public static $ACCEPTED = 'ACCEPTED';
    public static $IN_PROGRESS = 'IN_PROGRESS';
    public static $DONE = 'DONE';

    public static $DELIVERY_STATUS_ASSIGNED = 'DELIVERY_STATUS_ASSIGNED';
    public static $DELIVERY_STATUS_CANCELLED = 'DELIVERY_STATUS_CANCELLED';
    public static $DELIVERY_STATUS_IN_PROGRESS = 'DELIVERY_STATUS_IN_PROGRESS';
    public static $DELIVERY_STATUS_FINISHED = 'DELIVERY_STATUS_FINISHED';
    public static $DELIVERY_STATUS_FAILED = 'DELIVERY_STATUS_FAILED';

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
            case self::$DELIVERY_STATUS_CANCELLED:
                $status = self::$DELIVERY_STATUS_CANCELLED;
                break;
            case self::$DELIVERY_STATUS_FINISHED:
                $status = self::$DELIVERY_STATUS_FINISHED;
                break;
            case self::$DELIVERY_STATUS_IN_PROGRESS:
                $status = self::$DELIVERY_STATUS_IN_PROGRESS;
                break;
            case self::$DELIVERY_STATUS_FAILED:
                $status = self::$DELIVERY_STATUS_FAILED;
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
            self::$DONE,
            self::$DELIVERY_STATUS_IN_PROGRESS,
            self::$DELIVERY_STATUS_CANCELLED,
            self::$DELIVERY_STATUS_FAILED,
            self::$DELIVERY_STATUS_FINISHED
        ]);
    }
    public static function getFilteredAll(): ArrayCollection
    {
        return new ArrayCollection([
            self::$ORDERED,
            self::$ACCEPTED,
            self::$IN_PROGRESS,
            self::$DELIVERY_STATUS_IN_PROGRESS,
            self::$DELIVERY_STATUS_CANCELLED,
            self::$DELIVERY_STATUS_FAILED,
            self::$DELIVERY_STATUS_FINISHED
        ]);
    }
}