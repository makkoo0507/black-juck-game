<?php
namespace blackJack;

require_once(__DIR__.'/config.php');

class FactoryStateClass
{
    public static function create($state)
    {
        $className = Config::STATE_CLASSES[$state];
        return new $className();
    }
}

$class = FactoryStateClass::create(Config::STATE['hit']);
var_dump($class);
