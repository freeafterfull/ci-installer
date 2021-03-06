<?php

namespace FreeAfterFull\Src;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Installer
{
    public static function postInstall(Event $event = null) {
        $libPath = 'vendor/codeigniter/framework';

        self::editFile(
            $libPath . '/application/config/config.php', 
            [
                '$config[\'base_url\'] = \'\';',
                '$config[\'index_page\'] = \'index.php\';', 
                '$config[\'composer_autoload\'] = FALSE;',
            ],
            [
                '$config[\'base_url\'] = ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] == \'on\') ? \'https\' : \'http\') . \'://\' . @$_SERVER[\'HTTP_HOST\'] . str_replace(basename($_SERVER[\'SCRIPT_NAME\']), \'\', $_SERVER[\'SCRIPT_NAME\']);',
                '$config[\'index_page\'] = \'\';',
                '$config[\'composer_autoload\'] = realpath(APPPATH . \'../vendor/autoload.php\');',
            ]
        );
        self::editFile(
            $libPath . '/index.php',
            '$system_path = \'system\';',
            '$system_path = \'vendor/codeigniter/framework/system\';'
        );
        
        self::moveFiles($libPath . '/application', 'application');
        self::moveFiles($libPath . '/index.php', 'index.php');
        self::moveFiles(__DIR__ . '/pagination.conf.php', 'application/config/pagination.php');
        self::moveFiles(__DIR__ . '/form_validation.conf.php', 'application/config/form_validation.php');
        self::moveFiles(__DIR__ . '/table.conf.php', 'application/config/table.php');
        self::writeMessage($event, 'nessessary files have been moved.');

        self::createHtaccess();
        self::writeMessage($event, '.htaccess has been generated.');

        self::moveFiles('src/composer.conf.json', 'composer.json');
        self::deleteFiles(__DIR__);
    }

    private static function moveFiles($src, $dest){
        rename($src, $dest);
    }

    private static function editFile($file, $find, $replace){
        $config = file_get_contents($file);
        $config = str_replace($find, $replace, $config);

        file_put_contents($file, $config);
    }

    public static function deleteFiles($dir) {
        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
    
        foreach ($objects as $object) {
            if ($object->isDir()) {
                rmdir($object);
            } else {
                unlink($object);
            }
        }
    
        rmdir($dir);
    }

    private static function createHtaccess(){
        $content = "RewriteEngine On\n"
        ."RewriteCond %{REQUEST_FILENAME} !-f\n"
        ."RewriteCond %{REQUEST_FILENAME} !-d\n"
        ."RewriteRule ^(.*)$ index.php/$1 [L]";
        
        file_put_contents('.htaccess', $content);
    }

    private static function writeMessage(Event $event = null, $message = '')
    {
        $e = $event->getIO();
        $e->write("<info>{$message}</info>");
    }
}
