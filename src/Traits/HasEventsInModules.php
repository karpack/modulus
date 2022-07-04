<?php

namespace Karpack\Modulus\Traits;

use Illuminate\Support\Facades\App;
use Symfony\Component\Finder\Finder;

trait HasEventsInModules
{
    use PathsInFinder;

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        $result = parent::listens();

        foreach ($this->eventListenerMappingFilesInModules() as $mapFilePath) {
            $mappings = require $mapFilePath;

            // We will iterate through all the events in the map file and check
            // whether it has any listeners already registered. If it has, then
            // the new listeners are added to the existing listeners list, instead
            // of overriding it, so that all listeners from different modules will 
            // be called.
            foreach ($mappings as $event => $listener) {
                if (array_key_exists($event, $result)) {
                    $result[$event] = array_merge($result[$event], $listener);
                } else {
                    $result[$event] = $listener;
                }
            }
        }
        return $result;
    }

    /**
     * Returns all the event-listener mapping files in the application modules.
     * 
     * @return array
     */
    protected function eventListenerMappingFilesInModules()
    {
        $modulesDir = method_exists($this, 'modulesDirectory')
            ? call_user_func([$this, 'modulesDirectory'])
            : App::path('Modules');

        $eventDirs = $this->allPathsInFinder(
            Finder::create()->directories()->name('/events/i')->in($modulesDir)
        );
        $eventListenerMappingFilePaths = [];

        foreach ($eventDirs as $eventDir) {
            array_push($eventListenerMappingFilePaths, ...$this->allPathsInFinder(
                Finder::create()->files()->name('map.php')->in($eventDir)
            ));
        }
        return $eventListenerMappingFilePaths;
    }
}
