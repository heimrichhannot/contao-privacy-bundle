<?php

namespace HeimrichHannot\Privacy\Manager;

use Contao\ContentElement;
use Contao\System;

/**
 * Class ProtocolManager
 * @package HeimrichHannot\Privacy
 *
 * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
 */
class ProtocolManager
{
    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function addEntryFromContentElement($type, $archive, array $data, ContentElement $element, $packageName = '')
    {
        System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntryFromContentElement(
            $type, $archive, $data, $element, $packageName
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function addEntryFromModule($type, $archive, array $data, $module, $packageName = '')
    {
        System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntryFromModule(
            $type, $archive, $data, $module, $packageName
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function addEntry($type, $archive, array $data, $packageName = '', $skipFields = ['id', 'tstamp', 'dateAdded', 'pid', 'type'])
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->addEntry(
            $type, $archive, $data, $packageName, $skipFields
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function updateReferenceEntity($protocolArchive, $data, $editableFields, $context)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->updateReferenceEntity(
            $protocolArchive, $data, $editableFields, $context
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function deleteReferenceEntity($protocolArchive, $data)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->deleteReferenceEntity(
            $protocolArchive, $data
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getSelectorFieldDca($label = null)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getSelectorFieldDca(
            $label
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getArchiveFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getArchiveFieldDca();
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getTypeFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getTypeFieldDca();
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getDescriptionFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getDescriptionFieldDca();
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getFieldMappingFieldDca($tableField)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getFieldMappingFieldDca(
            $tableField
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getTextualFieldMappingFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getTextualFieldMappingFieldDca();
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getNotificationFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getNotificationFieldDca();
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Manager\ProtocolManager
     */
    public function getActivationJumpToFieldDca()
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Manager\ProtocolManager::class)->getActivationJumpToFieldDca();
    }
}
