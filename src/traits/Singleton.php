<?php
namespace GM\traits;

trait Singleton
{
    private static $instance;

    /**
     * get instance
     *
     * @param [type] ...$args
     *
     * @return static
     */
    static function instance(...$args)
    {
        $className = md5(get_called_class() . serialize($args));
        if(!isset(self::$instance[$className])){
            self::$instance[$className] = new static(...$args);
        }
        return self::$instance[$className];
    }
}