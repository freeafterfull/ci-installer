<?php

namespace FreeAfterFull\App\Console\Commands;

use FreeAfterFull\App\Console\CommandAbstract;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FreeAfterFull\App\Console\Traits\Download;

class CreateEncryptionKeyCommand extends CommandAbstract
{
    use Download;
    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'create:key';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Generate an encryption key.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Generate an encryption key.';

    /**
     * Handle the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $key = $this->_generateKey();
        $file = 'application/config/config.php';
        $find = '$config[\'encryption_key\'] = \'\'';
        $replace = '$config[\'encryption_key\'] = \'' . $key . '\'';
        
        $this->editFile($file, $find, $replace);

        return $this->info('Encryption key has been generated.');
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

    private function _generateKey()
    {
        $string = microtime();
        $key = hash('ripemd128', $string);

        return $key;
    }
}