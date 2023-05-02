<?php

namespace Letecode\Commando\Commands\Files;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateRepository extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--model= : The model on which the repository class will be based on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

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
     * Retrieve the stub content from the repository's stub file.
     *
     * @return mixed
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__.'/stubs/files/repository.stub');
    }

    /**
     * Retrieve the stub content from the repository's empty stub file.
     *
     * @return bool|string
     */
    protected function getEmptyStub()
    {
        return file_get_contents(__DIR__.'/stubs/files/empty.repository.stub');
    }

    /**
     * Rewrite actually the content in the file.
     *
     * @param $filename
     * @param $content
     */
    protected function putInFile($filename, $content)
    {
        if (!is_dir(app_path('/Repositories')))
            mkdir(app_path('/Repositories'));
        file_put_contents($filename, $content);
    }

    /**
     * Set the right name and namespace.
     *
     * @param $model
     * @param $namespace
     * @return void
     */
    protected function setModelAndNamespace(&$model, &$namespace)
    {
        $exploded = str_contains($model, '/') ? explode('/', $model) : explode('\\', $model);
        $model = Arr::last($exploded);
        $namespace = '';

        for ($i = 0; $i < count($exploded) - 1; $i++)
            $namespace .= $exploded[$i].'\\';

        $namespace = Str::replaceLast('\\','', $namespace);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        $namespace = 'App';
        if (empty($name)) {
            $this->error('Please the name of the repository is expected.');
        } else {
            $content = null;

            if (is_null($model)) {
                $content = $this->replaceDummyName('Class', $name, $this->getEmptyStub());
            } else {
                if (Str::contains($model, ['\\', '/'])) {
                    $this->setModelAndNamespace($model, $namespace);
                }

                if ($this->modelFileExists($namespace.'\\'.$model)) {
                    $content = $this->replaceModelNamespace($namespace, $this->getStub());
                    $content = $this->replaceModelName($model, $content);
                    $content = $this->replacePropertyName($model, $content);
                    $content = $this->replaceDummyName('Class', $name, $content);
                } else {
                    $this->output->error('The specified model "'.$this->option('model').'" does not exist.');
                }
            }

            if (!is_null($content)) {
                $filename = app_path('Repositories/'.ucfirst($name).'.php');

                if (file_exists($filename)) {
                    do {
                        $input = $this->ask("There is a repository with this name ($name) do you want to replace it ? [o/n] ");
                    } while (strtolower($input) != 'o' && strtolower($input) != 'n');

                    if('o' == strtolower($input)){
                        $this->putInFile($filename, $content);
                        $this->info('Reporitory created successfully.');
                    }
                } else {
                    $this->putInFile($filename, $content);
                    $this->info('Reporitory created successfully.');
                }
            }
        }
    }
}
