<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withParallel()
    ->withPaths([__DIR__.'/packages'])
    ->withRootFiles()
    ->withPhpSets()
    ->withSkip([])
;
