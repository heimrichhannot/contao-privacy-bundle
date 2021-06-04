<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use HeimrichHannot\PrivacyBundle\HeimrichHannotPrivacyBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface, RoutingPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(HeimrichHannotPrivacyBundle::class)->setLoadAfter([
                'formhybrid',
                'HeimrichHannot\ContaoExporterBundle\HeimrichHannotContaoExporterBundle',
                'notification_center',
                ContaoCoreBundle::class,
            ]),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@HeimrichHannotPrivacyBundle/Resources/config/services.yml');
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver->resolve(__DIR__.'/../Resources/config/routing.yml')->load(__DIR__.'/../Resources/config/routing.yml');
    }
}
