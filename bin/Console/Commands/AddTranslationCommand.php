<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;
use FreeAfterFull\App\Console\Traits\File;

class AddTranslationCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'add:translation';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Add Codeigniter translation to project.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Add Codeigniter translation to project.';

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
        $url = 'https://github.com/bcit-ci/codeigniter3-translations/archive/develop.zip';

        $this->createDir($tempDir);
        
        $filepath = $this->pullRepo($url, $tempDir);
        $libpath = $this->unzip($filepath, $tempDir);

        $files = [
            'core' => [
                $libpath . '/core',
                'application/core'
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

        foreach ($files as $file) {
            $this->moveFile($file[0], $file[1]);
        }

        $this->deleteDir($tempDir);

        return $this->info('Translation has been added.');
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