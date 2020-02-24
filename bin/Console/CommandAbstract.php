<?php

namespace FreeAfterFull\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandAbstract extends Command
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
	 * Configuring the Command
	 */
    protected function configure()
    {
        $this->setName($this->command)
             ->setDescription($this->description)
             ->setHelp($this->help);

        $this->addArguments();
        $this->addOptions();
    }

    /**
     * Access to the output stream to write messages to the console
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle($input, $output);
    }

    /**
     * Access argument to command
     *
     * @param string $name
     */
    protected function argument($name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * Access option to command
     *
     * @param string $name
     */
    protected function option($name)
    {
        return $this->input->getOption($name);
    }

    /**
     * Add arguments to the command
     */
    protected function addArguments()
    {
        foreach ($this->arguments() as $argument) {
            $this->addArgument($argument[0], $argument[1], $argument[2]);
        }
    }

    /**
     * Add options to the command
     */
    protected function addOptions()
    {
        foreach ($this->options() as $option) {
            $this->addOption($option[0], $option[1], $option[2], $option[3], $option[4]);
        }
    }

    /**
     * Output info message to the console
     *
     * @param string $value
     */
    protected function info($value)
    {
        return $this->output->writeln('<info>' . $value . '</info>');
    }

    /**
     * Output error message to the console
     *
     * @param string $value
     */
    protected function error($value)
    {
        return $this->output->writeln('<error>' . $value . '</error>');
    }
}