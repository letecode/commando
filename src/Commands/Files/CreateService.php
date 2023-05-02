<?php

namespace Letecode\Commando\Commands\Files;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateService extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

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
     * Retrieve the stub content from the repository's empty stub file.
     *
     * @return bool|string
     */
    protected function getEmptyStub()
    {
        return file_get_contents(__DIR__.'/stubs/files/empty.service.stub');
    }



    /**
     * Rewrite actually the content in the file.
     *
     * @param $filename
     * @param $content
     */
    protected function putInFile($filename, $content)
    {
        if (!is_dir(app_path('/Services')))
            mkdir(app_path('/Services'));

        file_put_contents($filename, $content);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (empty($name)) {
            $this->error('Please the name of the service is expected.');
        } else {
            $content = $this->replaceDummyName('Class', $name, $this->getEmptyStub());

            if (!is_null($content)) {
                $filename = app_path('Services/'.ucfirst($name).'.php');

                if (file_exists($filename)) {
                    do {
                        $input = $this->ask("There is a service with this name ($name) do you want to replace it ? [y/n] ");
                    } while (strtolower($input) != 'y' && strtolower($input) != 'n');

                    if ('y' == strtolower($input)) {
                        $this->putInFile($filename, $content);
                        $this->info('Service created successfully.');
                    }
                } else {
                    $this->putInFile($filename, $content);
                    $this->info('Service created successfully.');
                }
            }
        }
    }
}
