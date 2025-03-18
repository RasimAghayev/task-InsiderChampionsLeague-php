<?php

namespace App\Infrastructure;
class Autoloader
{
    /**
     * Register the autoloader
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(static function ($class) {
            $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $baseDir = __DIR__ . '/../../';
            $filePath = $baseDir . $classPath . '.php';
            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
            return false;
        });
    }
}