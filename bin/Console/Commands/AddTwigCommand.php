<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;
use FreeAfterFull\App\Console\Traits\File;

class AddTwigCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'add:twig';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Add Twig template to project.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Add Twig template to project.';

    /**
     * Handle the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $tempDir = __DIR__ . '/tmp';
        $url = 'https://github.com/freeafterfull/Twig-For-Codeigniter-3/archive/master.zip';

        $this->createDir($tempDir);

        $filepath = $this->pullRepo($url, $tempDir);
        $libpath = $this->unzip($filepath, $tempDir);
        
        $files = [
            'config' => [
                $libpath . '/config',
                'application/config'
            ],
            'core' => [
                $libpath . '/core',
                'application/core'
            ],
            'libraries' => [
                $libpath . '/libraries',
                'application/libraries'
            ],
        ];

        foreach ($files as $file) {
            $this->moveFile($file[0], $file[1]);
        }
        
        $path = 'application/config/autoload.php';
        $find = '$autoload[\'libraries\'] = array(';
        $replace = '$autoload[\'libraries\'] = array(\'twig\', ';

        $this->editFile($path, $find, $replace);

        $this->deleteDir($tempDir);

        passthru('composer.phar require twig/twig');

        return $this->info('Twig has been added.');
    }

    /**
     * Command arguments
     *
     * @return array
     */
    protected function arguments()
    {
        // Add arguments as array
        return [];
    }

    /**
     * Command options
     *
     * @return array
     */
    protected function options()
    {
        // Add options as array
        return [];
    }
}