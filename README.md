# Letecode/Commando

This package is a collection of artisan commands for speed up development with laravel.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install letecode/commando

```bash
composer require letecode/commando
```

## Usage

### Class command
#### Generate a class file
```bash
php artisan make:class App\Exceptions\DuplicatedPostException
```
or you can use a dot(.) as separator
```bash
php artisan make:class App.Exceptions.DuplicatedPostException --separator=.
```

#### Generate a trait 
```bash
php artisan make:trait App\Traits\MyTrait
```

#### Generate an interface
```bash
php artisan make:interface App\Contracts\Identifiable
```

### File command
#### Generate a generic file 
```bash
php artisan make:file folder.subfolder1.subfolder2.filename --ext=php
```

### Lang command
#### Generate a new locale file 
```bash
php artisan make:lang myFilename --locale=fr
```

#### Generate a new json locale file
```bash
php artisan make:lang --locale=fr --json
```

### Repository command
#### Generate an empty repository file
```bash
php artisan make:repository UserRepository
```
#### Generate a repository based on a model
```bash
php artisan make:repository UserRepository --model=User
```
OR
```bash
php artisan make:repository UserRepository --model=App\Models\User
```

### Service command
#### Generate a service class
```bash
php artisan make:service PayPalPaymentService
```


### View command
#### Generate an empty view 
```bash
php artisan make:view folder.subfolder.view
```

#### Generate a view that extend a layout
```bash
php artisan make:view folder.subfolder.view --layout=app
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
