<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitebb8b3c1fc567b8521c2edfb14dd3e80
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitebb8b3c1fc567b8521c2edfb14dd3e80::$classMap;

        }, null, ClassLoader::class);
    }
}
