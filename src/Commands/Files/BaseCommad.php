<?php

namespace Letecode\Commando\Commands\Files;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BaseCommand extends Command
{

    /**
     * Replace every Dummy[ClassName] with the right [ClassName] name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replaceDummyName($className, $name, $stub)
    {
        return str_replace('Dummy'.ucfirst($className), ucfirst($name), $stub);
    }

    /**
     * Replace every DummyProperty with the right property name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replacePropertyName($name, $stub)
    {
        return str_replace('DummyProperty', lcfirst(Str::camel($name)), $stub);
    }

    /**
     * Replace every DummyModel with the right model name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replaceModelName($name, $stub)
    {
        return str_replace('DummyModel', ucfirst($name), $stub);
    }

    /**
     * Set the right namespace in the stub.
     *
     * @param $namespace
     * @param $stub
     * @return mixed
     */
    protected function replaceNamespace($namespace, $stub)
    {
        if (!empty($namespace))
            return str_replace('DummyNamespace', 'namespace '.$namespace.';', $stub);
        return str_replace('DummyNamespace', '', $stub);
    }

     /**
     * Replace the namespace of the model.
     *
     * @param $namespace
     * @param $stub
     * @return mixed
     */
    protected function replaceModelNamespace($namespace, $stub)
    {
        return str_replace('DummyModelNamespace', ucfirst($namespace), $stub);
    }

    /**
     * Check if the filename is correct.
     *
     * @param $name
     * @return bool
     */
    protected function isCorrectFilename($name)
    {
        return preg_match('#^[a-zA-Z][\a-zA-Z0-9\._]+$#', $name);
    }

    /**
     * Create a set of folders if necessary.
     *
     * @param $filename
     * @return void
     */
    protected function createFoldersIfNecessary($filename)
    {
        $folder = base_path('');
        for ($i = 0; $i < count($filename) - 1; $i++) {
            if (!is_dir($folder . '/' . $filename[$i])) {
                mkdir($folder . '/' . $filename[$i]);
            }
            $folder .= '/' . $filename[$i];
        }
    }

    /**
     * Check if the filename exists and if it could be replaced.
     *
     * @param $filename
     * @param $question
     * @return bool
     */
    protected function replaceExistingFile($filename, $question)
    {
        $replaceExistingFile = true;

        $otherPath = str_contains($filename, '\\')
            ? str_replace('\\', '/', $filename)
            : str_replace('/', '\\', $filename);

        if (file_exists($filename) || file_exists($otherPath)) {
            do {
                $input = $this->ask($question);
            } while (strtolower($input) != 'y' && strtolower($input) != 'n');

            if (strtolower($input) == 'n')
                $replaceExistingFile = false;
        }
        return $replaceExistingFile;
    }

    /**
     * Get the separator from the option.
     *
     * @return array|string|null
     */
    protected function getSeparator()
    {
        if ($this->option('separator') !== null) {
            if (mb_strlen($this->option('separator')) > 1) {
                $this->error('This is an invalid separator. Please choose a separator between "." and "\" characters.');
                return null;
            } else
                return $this->option('separator');
        }

        return '\\';
    }

    /**
     * Check if a model file exists.
     *
     * @param $model
     * @return bool
     */
    protected function modelFileExists($model)
    {
        return file_exists( base_path(lcfirst($model).'.php')) || file_exists( base_path(lcfirst(str_replace('\\', '/', $model)).'.php'));
    }


}
