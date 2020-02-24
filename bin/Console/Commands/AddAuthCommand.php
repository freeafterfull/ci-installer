<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;
use FreeAfterFull\App\Console\Traits\File;

class AddAuthCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'add:auth';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Add Ion auth to project.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Add Ion auth to project.';

    private $_tmp;

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
        $url = 'https://github.com/benedmunds/CodeIgniter-Ion-Auth/archive/3.zip';

        $this->createDir($tempDir);
        $this->createDir('application/migrations');
        
        $filepath = $this->pullRepo($url, $tempDir);
        $libpath = $this->unzip($filepath, $tempDir);

        $files = [
            'config' => [
                $libpath . '/config',
                'application/config'
            ],
            'controllers' => [
                $libpath . '/controllers',
                'application/controllers'
            ],
            'libraries' => [
                $libpath . '/libraries',
                'application/libraries'
            ],
            'migrations' => [
                $libpath . '/migrations',
                'application/migrations'
            ],
            'models' => [
                $libpath . '/models',
                'application/models'
            ],
            'views' => [
                $libpath . '/views',
                'application/views'
            ],
        ];

        $arg = $this->argument('language');

        if (count($arg) > 0)
        {
            foreach ($arg as $lang) 
            {
                if (!file_exists('application/language/' . $lang))
                {
                    mkdir('application/language/' . $lang);
                }

                $files[$lang] = [
                    $libpath . '/language/' . $lang,
                    'application/language/' . $lang,
                ];
            }
        }
        else
        {
            if (!file_exists('application/language/english'))
            {
                mkdir('application/language/english');
            }

            $files['english'] = [
                $libpath . '/language/english',
                'application/language/english',
            ];
        }

        foreach ($files as $file) {
            $this->moveFile($file[0], $file[1]);
        }

        rename('application/migrations/001_install_ion_auth.php', 'application/migrations/' . date('YmdHis') . '_install_ion_auth.php');

        $this->deleteDir($tempDir);

        return $this->info('Auth has been added.');
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
            ['language', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Select language (separate multiple language with a space).'],
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