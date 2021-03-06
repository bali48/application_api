<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7b673e3027829b1715cd248444d27e66
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'CoreLib\\' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static $classMap = array (
        'CoreLib\\Module' => __DIR__ . '/../..' . '/Module.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit7b673e3027829b1715cd248444d27e66::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit7b673e3027829b1715cd248444d27e66::$classMap;

        }, null, ClassLoader::class);
    }
}
