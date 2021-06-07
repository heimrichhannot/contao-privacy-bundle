<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle\EventListener\Contao;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\System;
use Firebase\JWT\JWT;
use HeimrichHannot\PrivacyBundle\HeimrichHannotPrivacyBundle;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;

/**
 * @Hook("replaceInsertTags")
 */
class ReplaceInsertTagsListener
{
    /**
     * @var UrlUtil
     */
    protected $urlUtil;

    public function __construct(UrlUtil $urlUtil)
    {
        $this->urlUtil = $urlUtil;
    }

    public function __invoke(string $tag)
    {
        $tagArray = explode('::', $tag);

        switch ($tagArray[0]) {
            case 'privacy_opt_url':
                $dataString = $tagArray[1];
                $data = [];

                foreach (explode('#', $dataString) as $fieldValuePairString) {
                    $fieldValuePair = explode(':', $fieldValuePairString);

                    if (2 !== \count($fieldValuePair)) {
                        continue;
                    }

                    $data[$fieldValuePair[0]] = $fieldValuePair[1];
                }

                $jumpTo = isset($tagArray[2]) ? $tagArray[2] : null;
                $url = $this->urlUtil->getJumpToPageObject($jumpTo)->getAbsoluteUrl();

                $token = [
                    'data' => $data,
                ];

                if (isset($tagArray[3]) && $tagArray[3]) {
                    $token['referenceFieldValue'] = $tagArray[3];
                }

                $jwt = JWT::encode($token, System::getContainer()->getParameter('contao.encryption_key'));

                return $this->urlUtil->addQueryString(HeimrichHannotPrivacyBundle::OPT_ACTION_PARAM.'=opt&'.
                    HeimrichHannotPrivacyBundle::OPT_IN_OUT_TOKEN_PARAM.'='.$jwt, $url);
        }

        return false;
    }
}
