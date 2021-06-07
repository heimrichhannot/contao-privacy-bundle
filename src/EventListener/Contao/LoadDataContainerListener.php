<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\EventListener\Contao;

use Contao\Config;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\DataContainer;
use Contao\StringUtil;
use HeimrichHannot\PrivacyBundle\DataContainer\ProtocolEntryContainer;
use HeimrichHannot\PrivacyBundle\Manager\ProtocolManager;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

/**
 * @Hook("loadDataContainer")
 */
class LoadDataContainerListener
{
    protected static $callbacks;
    protected static $setCallbacks = [];

    /**
     * @var ContainerUtil
     */
    protected $containerUtil;

    /**
     * @var ModelUtil
     */
    protected $modelUtil;

    /**
     * @var ProtocolManager
     */
    protected $protocolManager;

    public function __construct(ContainerUtil $containerUtil, ModelUtil $modelUtil, ProtocolManager $protocolManager)
    {
        $this->containerUtil = $containerUtil;
        $this->modelUtil = $modelUtil;
        $this->protocolManager = $protocolManager;
    }

    public function __invoke(string $table): void
    {
        if ($this->containerUtil->isBackend()) {
            // css
            $GLOBALS['TL_CSS']['contao-privacy-bundle'] = 'bundles/heimrichhannotprivacy/css/contao-privacy-bundle.be.min.css';
        }

        $this->initProtocolCallbacks($table);
    }

    public function initProtocolCallbacks($table)
    {
        if (null === static::$callbacks) {
            static::$callbacks = StringUtil::deserialize(Config::get('privacyProtocolCallbacks'), true);

            foreach (static::$callbacks as $callback) {
                static::$setCallbacks[] = $callback['table'];
            }
        }

        $callbacks = static::$callbacks;

        if (!\in_array($table, static::$setCallbacks)) {
            return;
        }

        foreach ($callbacks as $callback) {
            if ($table !== $callback['table']) {
                continue;
            }

            $dca = &$GLOBALS['TL_DCA'][$callback['table']];

            if (!isset($dca['config'][$callback['callback']])) {
                $dca['config'][$callback['callback']] = [];
            }

            $createEntryFunc = function ($data) use ($callback) {
                // restrict to scope
                if (ProtocolEntryContainer::CMS_SCOPE_BOTH === $callback['cmsScope'] || TL_MODE === $callback['cmsScope']) {
                    $this->protocolManager->addEntry($data['type'], $callback['archive'], $data);
                }
            };

            switch ($callback['callback']) {
                case 'oncreate_callback':
                    $dca['config'][$callback['callback']]['addPrivacyProtocolEntry'] =
                        function ($table, $id, $data, DataContainer $dc) use ($callback, $createEntryFunc) {
                            $instance = $dc->activeRecord ?: $this->modelUtil->findModelInstanceByPk($callback['table'], $id);

                            $entryData = $instance->row();
                            $entryData['dataContainer'] = $callback['table'];
                            $entryData['type'] = ProtocolEntryContainer::TYPE_CREATE;

                            $createEntryFunc($entryData);
                        };

                    break;

                case 'onversion_callback':
                    $dca['config'][$callback['callback']]['addPrivacyProtocolEntry'] =
                        function ($table, $id, DataContainer $dc) use ($callback, $createEntryFunc) {
                            $instance = $dc->activeRecord ?: $this->modelUtil->findModelInstanceByPk($callback['table'], $id);

                            $entryData = $instance->row();
                            $entryData['dataContainer'] = $callback['table'];
                            $entryData['type'] = ProtocolEntryContainer::TYPE_UPDATE;

                            $createEntryFunc($entryData);
                        };

                    break;

                case 'ondelete_callback':
                    $dca['config'][$callback['callback']]['addPrivacyProtocolEntry'] =
                        function (DataContainer $dc, $id) use ($callback, $createEntryFunc) {
                            $instance = $dc->activeRecord ?: $this->modelUtil->findModelInstanceByPk($callback['table'], $id);

                            $entryData = $instance->row();
                            $entryData['dataContainer'] = $callback['table'];
                            $entryData['type'] = ProtocolEntryContainer::TYPE_DELETE;

                            $createEntryFunc($entryData);
                        };

                    break;
            }
        }
    }
}
