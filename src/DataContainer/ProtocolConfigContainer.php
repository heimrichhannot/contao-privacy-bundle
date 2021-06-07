<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use HeimrichHannot\PrivacyBundle\Manager\ProtocolManager;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class ProtocolConfigContainer
{
    protected DcaUtil         $dcaUtil;
    protected ModelUtil       $modelUtil;
    protected ProtocolManager $protocolManager;

    public function __construct(ProtocolManager $protocolManager, DcaUtil $dcaUtil, ModelUtil $modelUtil)
    {
        $this->dcaUtil = $dcaUtil;
        $this->modelUtil = $modelUtil;
        $this->protocolManager = $protocolManager;
    }

    /**
     * @Callback(table="tl_privacy_protocol_config", target="fields.dataContainer.options")
     */
    public function getDataContainers()
    {
        return $this->dcaUtil->getDataContainers();
    }

    /**
     * @Callback(table="tl_privacy_protocol_config", target="config.onsubmit")
     */
    public function setDateAdded($dc)
    {
        $this->dcaUtil->setDateAdded($dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_config", target="config.oncopy")
     */
    public function setDateAddedOnCopy($insertId, $dc)
    {
        $this->dcaUtil->setDateAddedOnCopy($insertId, $dc);
    }

    /**
     * @Callback(table="tl_privacy_protocol_config", target="config.onload")
     */
    public function modifyDca(DataContainer $dc)
    {
        $dca = &$GLOBALS['TL_DCA']['tl_privacy_protocol_config'];
        $config = $this->modelUtil->findModelInstanceByPk('tl_privacy_protocol_config', $dc->id);

        if (null !== $config) {
            if ($config->dataContainer) {
                $dca['fields']['fieldMapping'] = $this->protocolManager->getFieldMappingFieldDca('dataContainer');
            }
        }
    }
}
