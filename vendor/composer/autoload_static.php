<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit31ebe0f550661000c42d0f84d9828b80
{
    public static $prefixesPsr0 = array (
        'A' => 
        array (
            'AKlump\\' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit31ebe0f550661000c42d0f84d9828b80::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
