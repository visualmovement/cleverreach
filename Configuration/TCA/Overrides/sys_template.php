<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'cleverreach',
    'Configuration/TypoScript/',
    'CleverReach'
);
ExtensionManagementUtility::addStaticFile(
    'cleverreach',
    'Configuration/TypoScript/Form/',
    'CleverReach Form'
);
ExtensionManagementUtility::addStaticFile(
    'cleverreach',
    'Configuration/TypoScript/Powermail/',
    'CleverReach Powermail'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'cleverreach',
    'Configuration/TsConfig/Page/powermail.tsconfig',
    'Powermail'
);
