<?php

namespace Letecode\Commando\Commands\Files;

use Illuminate\Support\Str;

class CreateClass extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:class {filename}
                            {--separator=\\ : Character used to separate file and its parent(s) folder(s).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new class or trait file';

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isCorrectFilename($this->argument('filename'))) {
            $path = base_path($this->argument('filename') . '.php');

            if ($this->replaceExistingFile($path, 'There is already a file with this name do you want to replace it ? [y/n]')) {
                $filename = str_contains($this->argument('filename'), '/')
                    ? explode('/', $this->argument('filename'))
                    : explode('\\', $this->argument('filename'));

                $this->createFoldersIfNecessary($filename);
                $stub = file_get_contents(__DIR__.'/../stubs/files/class.stub');
                $stub = $this->replaceDummyName('Class', $filename[count($filename) - 1], $stub);
                $namespace = '';

                for ($i = 0; $i < count($filename) - 1; $i++)
                    $namespace .= ucfirst($filename[$i]).'\\';

                $stub = $this->replaceNamespace(Str::replaceLast('\\', '', $namespace), $stub);

                file_put_contents($path, $stub);

                $this->info('Class created successfully');
            }
        } else
            $this->error('The filename is not correct.');
    }


}
