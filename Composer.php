<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app;

class Composer
{
    public static function postInstall()
    {
        echo "Installing assets...\n";

        define('ASSETS_DIR', __DIR__ . '/assets');

        if (!file_exists(ASSETS_DIR. '/js')) {
            mkdir(ASSETS_DIR . '/js');
        }

        copy(__DIR__ . '/vendor/bower-asset/jquery/dist/jquery.min.js',  ASSETS_DIR . '/js/jquery.min.js');
        copy(__DIR__ . '/vendor/bower-asset/jquery/dist/jquery.slim.min.js',  ASSETS_DIR . '/js/jquery.slim.min.js');
        copy(__DIR__ . '/vendor/bower-asset/bootstrap/dist/css/bootstrap.min.css',  ASSETS_DIR . '/css/bootstrap.min.css');
        copy(__DIR__ . '/vendor/bower-asset/bootstrap/dist/js/bootstrap.min.js',  ASSETS_DIR . '/js/bootstrap.min.js');
        copy(__DIR__ . '/vendor/bower-asset/grapesjs/dist/css/grapes.min.css',  ASSETS_DIR . '/css/grapes.min.css');
        copy(__DIR__ . '/vendor/bower-asset/grapesjs/dist/grapes.min.js',  ASSETS_DIR . '/js/grapes.min.js');
        self::rCopy(__DIR__ . '/vendor/bower-asset/grapesjs/dist/fonts', ASSETS_DIR . '/fonts');
        copy(
            __DIR__ . '/vendor/kaoz70/grapesjs-blocks-bootstrap4/dist/grapesjs-blocks-bootstrap4.min.js',
            ASSETS_DIR . '/js/grapesjs-blocks-bootstrap4.min.js'
        );

        copy(
            __DIR__ . '/vendor/artf/grapesjs-custom-code/dist/grapesjs-custom-code.min.js',
            ASSETS_DIR . '/js/grapesjs-custom-code.min.js'
        );

        self::rmDir(__DIR__ . '/vendor/artf');
        self::rmDir(__DIR__ . '/vendor/bower-asset');
        self::rmDir(__DIR__ . '/vendor/kaoz70');
    }

    private static function rmDir($dir)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    self::rmDir("$dir/$file");
                }
            }
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }

    private static function rCopy($src, $dst) {
        if (file_exists($dst)) self::rmDir($dst);
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file)
                if ($file != "." && $file != "..") self::rCopy("$src/$file", "$dst/$file");
        }
        else if (file_exists($src)) copy($src, $dst);
    }
}