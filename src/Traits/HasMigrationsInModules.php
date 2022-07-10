<?php

namespace Karpack\Modulus\Traits;

use Illuminate\Support\Facades\App;
use Symfony\Component\Finder\Finder;

trait HasMigrationsInModules
{
    use PathsInFinder;

    /**
     * Returns all the migration file paths in the application modules
     * 
     * @return void
     */
    protected function migrationsInModules()
    {
        $modulesDir = method_exists($this, 'modulesDirectory')
            ? call_user_func([$this, 'modulesDirectory'])
            : App::path('Modules');

        return $this->allPathsInFinder(
            Finder::create()->directories()->name('/migrations/i')->in($modulesDir)
        );
    }
}