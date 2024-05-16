<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit328ac89965f76dddb05f4c0b0098349a
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WebSocket\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
            'Phrity\\Util\\' => 12,
            'Phrity\\Net\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WebSocket\\' => 
        array (
            0 => __DIR__ . '/..' . '/textalk/websocket/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Phrity\\Util\\' => 
        array (
            0 => __DIR__ . '/..' . '/phrity/util-errorhandler/src',
        ),
        'Phrity\\Net\\' => 
        array (
            0 => __DIR__ . '/..' . '/phrity/net-uri/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit328ac89965f76dddb05f4c0b0098349a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit328ac89965f76dddb05f4c0b0098349a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit328ac89965f76dddb05f4c0b0098349a::$classMap;

        }, null, ClassLoader::class);
    }
}
