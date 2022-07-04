<?php

namespace Karpack\Modulus\Traits;

use Symfony\Component\Finder\Finder;

trait PathsInFinder
{
    /**
     * Returns an array of paths of files in the given finder.
     * 
     * @return array
     */
    protected function allPathsInFinder(Finder $finder)
    {
        $resolvedFiles = [];

        foreach ($finder as $finderFile) {
            $resolvedFiles[] = $finderFile->getRealPath();
        }
        return $resolvedFiles;
    }
}