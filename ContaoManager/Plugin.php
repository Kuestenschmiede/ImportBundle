<?php

namespace con4gis\ImportBundle\ContaoManager;

use con4gis\ApiBundle\Con4gisApiBundle;
use con4gis\CoreBundle\con4gisCoreBundle;
use con4gis\ExportBundle\con4gisExportBundle;
use con4gis\ImportBundle\con4gisImportBundle;
use con4gis\MapsBundle\con4gisMapsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements RoutingPluginInterface, BundlePluginInterface, ConfigPluginInterface
{

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Resources/config/routing.yml')
            ->load(__DIR__.'/../Resources/config/routing.yml')
            ;
    }


    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @param ParserInterface $parser
     *
     * @return ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(con4gisImportBundle::class)
                ->setLoadAfter([con4gisCoreBundle::class, con4gisExportBundle::class])
        ];
    }


    /**
     * Allows a plugin to load container configuration.
     *
     * @param LoaderInterface $loader
     * @param array           $managerConfig
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@con4gisImportBundle/Resources/config/config.yml');
        $loader->load('@con4gisImportBundle/Resources/config/services.yml');
    }
}