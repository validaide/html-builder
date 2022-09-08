<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ValidaideHtmlBuilderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
dd($config);
        // you now have these 2 config keys
        // $config['twitter']['client_id'] and $config['twitter']['client_secret']
    }

    public function getAlias(): string
    {
        return 'validaide_html_builder';
    }
}