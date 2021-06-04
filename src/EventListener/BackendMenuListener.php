<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\EventListener;

use Contao\CoreBundle\Event\MenuEvent;
use HeimrichHannot\PrivacyBundle\Controller\BackendModule\BackendOptInModuleController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @ServiceTag("kernel.event_listener", event="contao.backend_menu_build")
 */
class BackendMenuListener
{
    protected $router;
    protected $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        $contentNode = $tree->getChild('privacy');

        $node = $factory
            ->createItem('privacy_backend_opt_in')
            ->setUri($this->router->generate(BackendOptInModuleController::class))
            ->setLabel($GLOBALS['TL_LANG']['MOD']['privacy_opt_in'][0])
            ->setLinkAttribute('title', $GLOBALS['TL_LANG']['MOD']['privacy_opt_in'][0])
            ->setCurrent(BackendOptInModuleController::class === $this->requestStack->getCurrentRequest()->get('_controller'))
        ;

        $contentNode->addChild($node);
    }
}
