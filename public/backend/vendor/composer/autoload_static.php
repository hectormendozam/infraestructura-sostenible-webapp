<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbd6e59aa5fbd6a5d93dd7c71901d88e3
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'ECOTRACK\\MYAPI\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ECOTRACK\\MYAPI\\' => 
        array (
            0 => __DIR__ . '/../..' . '/myapi',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbd6e59aa5fbd6a5d93dd7c71901d88e3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbd6e59aa5fbd6a5d93dd7c71901d88e3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbd6e59aa5fbd6a5d93dd7c71901d88e3::$classMap;

        }, null, ClassLoader::class);
    }
}
