<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;
use FreeAfterFull\App\Console\Traits\File;

class AddMigrationCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'add:migration';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Add natanfelles/codeigniter-migrate to project.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Add natanfelles/codeigniter-migrate to project.';

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
        $url = 'https://github.com/natanfelles/codeigniter-migrate/archive/master.zip';

        $this->createDir($tempDir);
        $this->createDir('assets');
        $this->createDir('application/migrations');
        
        $filepath = $this->pullRepo($url, $tempDir);
        $libpath = $this->unzip($filepath, $tempDir);

        $files = [
            'controllers' => [
                $libpath . '/application/controllers',
                'application/controllers'
            ],
            'helpers' => [
                $libpath . '/application/helpers',
                'application/helpers'
            ],
            'views' => [
                $libpath . '/application/views',
                'application/views'
            ],
            'assets' => [
                $libpath . '/assets',
                'assets'
            ],
        ];

        foreach ($files as $file) {
            $this->moveFile($file[0], $file[1]);
        }

        $path = 'application/config/migration.php';
        $find = '$config[\'migration_enabled\'] = FALSE;';
        $replace = '$config[\'migration_enabled\'] = TRUE;';

        $this->editFile($path, $find, $replace);

        $this->deleteDir($tempDir);

        return $this->info('Codeigniter migration has been added.');
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