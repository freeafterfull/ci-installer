<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;
use FreeAfterFull\App\Console\Traits\File;

class CreateMigrationCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'create:migration';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Generate a migration file.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Generate a migration file.';

    /**
     * Handle the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $table = $this->argument('table');
        $class = 'Migration_add_' . $table;
        $file = date('YmdHis') . '_add_' . $table . '.php';
        $src = 'bin/Console/pages/migration.txt';
        $dest = "application/migrations/{$file}";

        $this->createDir('application/migrations');
        
        copy($src, $dest);

        $find = ['#class#', '#table#'];
        $replace = [$class, $table];

        $this->editFile($dest, $find, $replace);

        return $this->info($file . ' has been generated.');
    }

    /**
     * Command arguments
     *
     * @return array
     */
    protected function arguments()
    {
        // Add arguments as array
        return [
            ['table', InputArgument::REQUIRED, 'Migration table name.'],
        ];
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