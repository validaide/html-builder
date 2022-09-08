<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('html_builder');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('cache_serializer_path')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}