<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\Util;

use Contao\Model;
use HeimrichHannot\UtilsBundle\Dca\DcaUtil;

class ProtocolUtil
{
    /**
     * @var DcaUtil
     */
    protected $dcaUtil;

    public function __construct(DcaUtil $dcaUtil)
    {
        $this->dcaUtil = $dcaUtil;
    }

    public function findReferenceEntity($table, $field, $fieldValue)
    {
        $modelClass = Model::getClassFromTable($table);

        if (class_exists($modelClass)) {
            return $modelClass::findOneBy(["$table.$field=?"], [$fieldValue]);
        }

        return false;
    }

    public function getMappedPrivacyProtocolFieldValues($data, $mapping)
    {
        if (\is_object($data)) {
            $data = (array) $data;
        }

        foreach ($mapping as $mappingData) {
            $data[$mappingData['protocolField']] = $data[$mappingData['entityField']];
        }

        return $data;
    }

    public function getMappedPrivacyProtocolField($entityField, $mapping)
    {
        foreach ($mapping as $mappingData) {
            if ($mappingData['entityField'] === $entityField) {
                return $mappingData['protocolField'];
            }
        }

        return $entityField;
    }

    public function getFieldsAsOptions()
    {
        return $this->dcaUtil->getFields('tl_privacy_protocol_entry');
    }
}
