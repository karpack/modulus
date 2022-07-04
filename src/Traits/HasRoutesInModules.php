<?php

namespace Karpack\Modulus\Traits;

use Illuminate\Support\Facades\App;
use Symfony\Component\Finder\Finder;

trait HasRoutesInModules
{
    use PathsInFinder;

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        parent::loadRoutes();

        foreach ($this->routeFilesInModules() as $file) {
            require $file;
        }
    }

    /**
     * Returns all the route file paths in the application modules.
     * 
     * @return array
     */
    protected function routeFilesInModules()
    {
        $modulesDir = method_exists($this, 'modulesDirectory')
            ? call_user_func([$this, 'modulesDirectory'])
            : App::path('Modules');

        $routeDirs = $this->allPathsInFinder(
            Finder::create()->directories()->name('/routes/i')->in($modulesDir)
        );
        $routeFilePaths = [];

        foreach ($routeDirs as $routeDir) {
            array_push($routeFilePaths, ...$this->allPathsInFinder(
                Finder::create()->files()->name('*.php')->in($routeDir)
            ));
        }
        return $routeFilePaths;
    }
}
