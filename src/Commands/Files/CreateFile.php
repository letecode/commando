<?php

namespace Letecode\Commando\Commands\Files;

use Illuminate\Support\Str;

class CreateFile extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:file {filename} {--ext= : The file extension. By default is php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new file';

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
            $extension = $this->getExtension();
            $path = base_path(str_replace(['.', "\\", "\\\\"], '/', $this->argument('filename')).'.'.$extension);

            if ($this->replaceExistingFile($path, 'There is already a file with this name do you want to replace it ? [y/n]')) {
                $filename = explode('.', $this->argument('filename'));

                $this->createFoldersIfNecessary($filename);

                file_put_contents($path, '');
                $this->info('File created successfully');
            }
        } else
            $this->error('The filename is not correct.');
    }

    /**
     * Get the file extension specified by the option.
     * PHP is considered as the default extension.
     *
     * @return string
     */
    protected function getExtension()
    {
        if ($this->hasOption('ext') && $this->option('ext') !== null)
            if (Str::startsWith($this->option('ext'), '.'))
                return Str::replaceFirst('.', '', $this->option('ext'));
            else
                return $this->option('ext');
        return 'php';
    }
}
