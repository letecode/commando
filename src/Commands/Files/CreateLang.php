<?php

namespace Letecode\Commando\Commands\Files;

class CreateLang extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:lang {name?} {--locale= : The targeted locale. By default is en} {--json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new language file';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->hasArgument('name') ? $this->argument('name') : '';
        $locale = $this->hasOption('locale') ? $this->option('locale') : 'en';

        if ($this->option('json'))
            $this->createJson($locale);
        else {
            if ($this->isCorrectFilename($name)) {
                $this->createLang($name, $locale);
            } else {
                if ($name == '')
                    $this->error('No filename is given.');
                else
                    $this->error('The given filename is not correct.');
            }
        }
    }

    /**
     * Retrieve the stub content from the lang's stub file.
     *
     * @return mixed
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__.'/stubs/files/lang.stub');
    }

    /**
     * Create a locale file within a lang sub-folder.
     *
     * @param $name
     * @param $locale
     * @return void
     */
    protected function createLang($name, $locale)
    {
        if ($this->replaceExistingFile(base_path('lang/'.$locale.'/'.$name.'.php'), "There is already a locale file with this name do you want to replace it ? [y/n]")) {
            if (!is_dir(base_path('lang/'.$locale)))
                mkdir(base_path('lang/'.$locale));

            file_put_contents(base_path('lang/'.$locale.'/'.$name.'.php'), $this->getStub());
            $this->info('Lang file created successfully.');
        }
    }

    /**
     * Create a json locale file.
     *
     * @param $locale
     * @return void
     */
    protected function createJson($locale)
    {
        if ($this->replaceExistingFile(base_path('lang/'.$locale.'.json'), "There is already a locale file with this name do you want to replace it ? [y/n]")) {
            file_put_contents(base_path('lang/'.$locale.'.json'), "{\n \t \n}");
            $this->info('Lang file created successfully.');
        }
    }

}
