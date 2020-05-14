<?php declare(strict_types=1);


namespace BiiiiiigMonster\Throttle;

use BiiiiiigMonster\Throttle\Annotation\Mapping\Throttle;
use Swoft\Stdlib\Helper\ArrayHelper;
use Swoft\Stdlib\Helper\StringHelper;

class ThrottleRegister
{
    /**
     * @var array
     */
    private static $throttle = [];

    /**
     * @param string $className
     * @param string $method
     * @param Throttle $throttle
     */
    public static function register(string $className,string $method,Throttle $throttle): void
    {
        [$maxAccepts,$duration] = StringHelper::explode($throttle->getRate(),'/',2);
        $value = substr($duration,0,-1);
        $unit = substr($duration,-1);
        $ttl = $value * ArrayHelper::get(['s'=>1,'m'=>60,'h'=>60*60,'d'=>60*60*24],$unit,1);

        $throttleConfig = [$throttle->getPrefix(),$maxAccepts,$ttl];
        self::$throttle[$className][$method][$throttle->getKey()] = $throttleConfig;
    }

    /**
     * @param string $className
     * @param string $method
     * @return array
     */
    public static function get(string $className,string $method): array
    {
        return self::$throttle[$className][$method] ?? [];
    }
}
