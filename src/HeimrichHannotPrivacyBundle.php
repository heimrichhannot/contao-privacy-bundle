<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\PrivacyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotPrivacyBundle extends Bundle
{
    const OPT_IN_OUT_TOKEN_PARAM = 'ptoken';
    const OPT_ACTION_PARAM = 'paction';
}
