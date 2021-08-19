<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0441fcbcc6869238da8ab3772125091b
{
    public static $prefixLengthsPsr4 = array (
        'X' => 
        array (
            'Xenonwellz\\Messenger\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Xenonwellz\\Messenger\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit0441fcbcc6869238da8ab3772125091b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0441fcbcc6869238da8ab3772125091b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0441fcbcc6869238da8ab3772125091b::$classMap;

        }, null, ClassLoader::class);
    }
}
