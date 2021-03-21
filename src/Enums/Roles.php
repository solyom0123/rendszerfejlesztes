<?php


namespace App\Enums;


abstract class Roles
{
   public static $ROLE_OWNER = 'ROLE_OWNER';
   public static $ROLE_CUSTOMER = 'ROLE_CUSTOMER';
   public static $ROLE_ADMIN = 'ROLE_ADMIN';
   public static $ROLE_COURIER = 'ROLE_COURIER';

    public static function match($ROLE): ?string
    {
        $role = "";
        switch ($ROLE) {
            case self::$ROLE_OWNER:
                $role = self::$ROLE_OWNER;
                break;
            case self::$ROLE_CUSTOMER:
                $role = self::$ROLE_CUSTOMER;
                break;
            case self::$ROLE_ADMIN:
                $role = self::$ROLE_ADMIN;
                break;
            case self::$ROLE_COURIER:
                $role = self::$ROLE_COURIER;
                break;
        }
        return $role;
    }
    public static function getAll():ArrayCollection{
        return new ArrayCollection([
            self::$ROLE_OWNER,
            self::$ROLE_CUSTOMER,
            self::$ROLE_ADMIN,
            self::$ROLE_COURIER
        ]);
    }
    public static function getFilteredAll():ArrayCollection{
        return new ArrayCollection([
            self::$ROLE_OWNER,
            self::$ROLE_CUSTOMER,
            self::$ROLE_COURIER
        ]);
    }
}