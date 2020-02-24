<?php

namespace FreeAfterFull\App\Console\Traits;

trait Download
{
    public function createDir($dir)
    {
        if (!file_exists($dir))
        {
            mkdir($dir);
        }
    }

    public function deleteDir($dir)
    {
        if (is_dir($dir)) {
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
    }

    public function pullRepo($url, $dest)
    {
        $file = file_get_contents($url);
        $urls = parse_url($url);
        $filepath = $dest . DIRECTORY_SEPARATOR . basename($urls['path']);
        file_put_contents($filepath, $file);

        return $filepath;
    }

    public function unzip($filepath, $dest)
    {
        $zip = new \ZipArchive();
        if ($zip->open($filepath) === TRUE) {
            $tmp = explode('/', $zip->getNameIndex(0));
            $dirname = $tmp[0];
            $zip->extractTo($dest);
            $zip->close();
        }

        $libpath = $dest . DIRECTORY_SEPARATOR . $dirname;

        return $libpath;
    }
}
