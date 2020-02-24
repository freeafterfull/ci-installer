<?php

namespace FreeAfterFull\App\Console\Commands;

use mysqli;

use FreeAfterFull\App\Console\Traits\File;
use FreeAfterFull\App\Console\CommandAbstract;

use FreeAfterFull\App\Console\Traits\Download;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCrudCommand extends CommandAbstract
{
    use Download, File;

    /**
     * Command name
     *
     * @var string
     */
    protected $command = 'create:crud';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Create CRUD app to project.';
    
    /**
     * Command help
     *
     * @var string
     */
    protected $help = 'Create CRUD app to project.';

    /**
     * Handle the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $crud = $this->argument('crud');
        $className = ucfirst($crud);
        $modelName = $className . '_model';
        
        $fields = $this->_connectDB($crud);
        unset($fields[0]);

        $labels = [];
        $rules = [];
        $table_header = [];
        $table_rows = [];
        $form_values = [];
        $form_control = [];
        $form_input = [];

        foreach ($fields as $field) {
            $labels[] = "'{$field}' => '{$field}',";
            $rules[] = "[
                'field' => '{$field}',
                'label' => \$this->_labels['{$field}'],
                'rules' => 'required',
            ],";
            $table_header[] = "\$this->_labels['{$field}'],";
            $table_rows[] = "\$item->{$field},";
            $form_values[] = "'{$field}' => \$this->_values['{$field}'] ?? set_value('{$field}'),";
            $form_control[] = "'{$field}' => [
                'label' => form_label(\$this->_labels['{$field}'], '{$field}'),
                'input' => form_input('{$field}', \$values['{$field}'], \$attr),
                'error' => \$this->form_validation->error('{$field}'),
            ],";
            $form_input[] = "<?= \$form['{$field}']['label'] ?>\n<?= \$form['{$field}']['input'] ?>\n<?= \$form['{$field}']['error'] ?>";
        }

        // Create Controller
        $ctrl_dest = 'application/controllers/' . $className . '.php';
        
        copy('bin/Console/pages/crud_controller.txt', $ctrl_dest);
        
        $ctrl_find = [
            '#class#',
            '#controller#',
            '#labels#',
            '#rules#',
            '#table_header#',
            '#table_rows#',
            '#form_values#',
            '#form_control#',
        ];
        $ctrl_replace = [
            $className,
            $crud,
            implode("\n\t\t\t", $labels),
            implode("\n\t\t\t", $rules),
            implode("\n\t\t\t", $table_header),
            implode("\n\t\t\t\t", $table_rows),
            implode("\n\t\t\t", $form_values),
            implode("\n\t\t\t", $form_control),
        ];

        $this->editFile($ctrl_dest, $ctrl_find, $ctrl_replace);

        // Create Model
        $model_dest = 'application/models/' . $className . '_model.php';
        
        copy('bin/Console/pages/model.txt', $model_dest);
        
        $model_find = [
            '#class#',
            '#table#',
        ];
        $model_replace = [
            $className,
            $crud,
        ];

        $this->editFile($model_dest, $model_find, $model_replace);

        // Create View
        $viewDir = 'application/views/' . $crud;

        $this->createDir($viewDir);

        $this->movefile('bin/Console/pages/crud', $viewDir);

        $view_find = [
            '#controller#',
            '#form_input#',
        ];
        $view_replace = [
            $crud,
            implode("\n", $form_input),
        ];

        $this->editFile($viewDir . '/index.txt', $view_find, $view_replace);
        $this->editFile($viewDir . '/form.txt', $view_find, $view_replace);

        rename($viewDir . '/index.txt', $viewDir . '/index.php');
        rename($viewDir . '/form.txt', $viewDir . '/form.php');

        return $this->info($className . ' CRUD has been added.');
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
            ['crud', InputArgument::REQUIRED, 'CRUD name.'],
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

    private function _connectDB($table)
    {
        define('BASEPATH', 1);
        require_once 'application/config/database.php';

        $config = $db['default'];
        $fields = [];

        $link = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);

        if ($link->connect_errno)
        {
            die($link->connect_error);
        }

        $sql = "SELECT * FROM {$table}";

        if ($res = $link->query($sql))
        {
            while($fieldInfo = $res->fetch_field())
            {
                $fields[] = $fieldInfo->name;
            }

            $res->free_result();
        }

        return $fields;
    }
}