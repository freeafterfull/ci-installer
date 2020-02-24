<?php

namespace FreeAfterFull\App\Console\Traits;

trait File
{
    public function moveFile($src, $dest)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $destination = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($file->isDir())
            {
                if (!file_exists($destination)) 
                {
                    mkdir($destination);
                }
            }
            else
            {
                if (!file_exists($destination)) 
                {
                    copy($file, $destination);
                }
            }
        }
    }

    public function editFile($path, $find, $replace){
        $file = file_get_contents($path);
        $content = str_replace($find, $replace, $file);

        file_put_contents($path, $content);
    }
}