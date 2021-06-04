<?php

namespace HeimrichHannot\Privacy\Util;

use Contao\System;

/**
 * Class ProtocolUtil
 * @package HeimrichHannot\Privacy
 *
 * @deprecated Use service \HeimrichHannot\PrivacyBundle\Util\ProtocolUtil
 */
class ProtocolUtil
{
    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Util\ProtocolUtil
     */
    public function findReferenceEntity($table, $field, $fieldValue)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Util\ProtocolUtil::class)->addEntryFromContentElement(
            $table, $field, $fieldValue
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Util\ProtocolUtil
     */
    public function getMappedPrivacyProtocolFieldValues($data, $mapping)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Util\ProtocolUtil::class)->getMappedPrivacyProtocolFieldValues(
            $data, $mapping
        );
    }

    /**
     * @deprecated Use service \HeimrichHannot\PrivacyBundle\Util\ProtocolUtil
     */
    public function getMappedPrivacyProtocolField($entityField, $mapping)
    {
        return System::getContainer()->get(\HeimrichHannot\PrivacyBundle\Util\ProtocolUtil::class)->getMappedPrivacyProtocolField(
            $entityField, $mapping
        );
    }
}
