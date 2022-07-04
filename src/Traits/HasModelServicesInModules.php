<?php

namespace Karpack\Modulus\Traits;

use Illuminate\Support\Facades\App;
use Karpack\Contracts\Hexagon\Services\ServiceResolver;
use Symfony\Component\Finder\Finder;

trait HasModelServicesInModules
{
    use PathsInFinder;

    /**
     * Registers all the model service mappings on to the service resolver.
     * 
     * @return void
     */
    protected function registerServices(ServiceResolver $serviceResolver)
    {
        foreach ($this->loadModelServiceMapFilesInModules() as $mapFilePath) {
            $mappings = require $mapFilePath;

            // We will iterate through all the models in the map file and register
            // it to the service resolver
            foreach ($mappings as $model => $serviceContract) {
                $serviceResolver->register($model, $serviceContract);
            }
        }
    }

    /**
     * Returns all the model-service contract mapping files in the application modules.
     * 
     * @return array
     */
    protected function loadModelServiceMapFilesInModules()
    {
        $modulesDir = method_exists($this, 'modulesDirectory')
            ? call_user_func([$this, 'modulesDirectory'])
            : App::path('Modules');

        $serviceDirs = $this->allPathsInFinder(
            Finder::create()->directories()->name('/services/i')->in($modulesDir)
        );
        $modelServiceMapFilePaths = [];

        foreach ($serviceDirs as $serviceDir) {
            array_push($modelServiceMapFilePaths, ...$this->allPathsInFinder(
                Finder::create()->files()->name('map.php')->in($serviceDir)
            ));
        }
        return $modelServiceMapFilePaths;
    }
}
