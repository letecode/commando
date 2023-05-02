<?php

namespace Letecode\Commando\Commands\Files;

use Illuminate\Console\Command;

class CreateView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {name} {--layout= : The layout that the view will extend}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve the stub content from the view's stub file.
     *
     * @return mixed
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__.'/stubs/files/view.stub');
    }

    /**
     * Fill the right layout name in the stub.
     *
     * @param $layout
     * @param $stub
     * @return mixed
     */
    protected function replaceLayout($layout, $stub)
    {
        return str_replace(
            'Layout',
            $layout,
            $stub
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = explode('.', $this->argument('name'));
        $content = '';
        if (preg_match('#^[a-zA-Z._\-0-9]+$#', $this->argument('name'))) {
            $folder = resource_path('/views');
            for ($i = 0; $i < count($path) - 1; $i++) {
                if (!is_dir($folder . '/' . $path[$i])) {
                    mkdir($folder . '/' . $path[$i]);
                }
                $folder .= '/' . $path[$i];
            }
            $fileIndex = count($path) - 1;

            if (!empty($this->option('layout')))
                $content = $this->replaceLayout($this->option('layout'), $this->getStub());

            $filename = $folder . '/' . $path[$fileIndex] . '.blade.php';
            $replaceIfNecessary = true;
            if (file_exists($filename)) {
                do {
                    $input = $this->ask("There is a view with this name do you want to replace it ? [y/n] ");
                } while (strtolower($input) != 'y' && strtolower($input) != 'n');

                if('n' == strtolower($input))
                    $replaceIfNecessary = false;
            }

            if ($replaceIfNecessary) {
                file_put_contents($filename, $content);
                $this->info('View created successfully.');
            }
        } else
            $this->info('Invalid view name. Only alphanumeric characters and dashes are supported.');
    }
}
