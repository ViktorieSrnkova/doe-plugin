<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaf603b36d34fe7e4770a4ce70ae0cb1e
{
    public static $files = array (
        '4c09aa81aba2d286cf38f22ce150beae' => __DIR__ . '/../..' . '/src/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaf603b36d34fe7e4770a4ce70ae0cb1e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaf603b36d34fe7e4770a4ce70ae0cb1e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaf603b36d34fe7e4770a4ce70ae0cb1e::$classMap;

        }, null, ClassLoader::class);
    }
}