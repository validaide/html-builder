<?php

declare(strict_types=1);

use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Core\Configuration\Option;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);
    $parameters->set(Option::PATHS, [
        __DIR__ . '/lib'
    ]);

    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_74);

    $services = $containerConfigurator->services();
    $services->set(NameImportingPostRector::class);
};
