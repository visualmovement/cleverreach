<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Powermail\Validator;

use Supseven\Cleverreach\CleverReach\Api;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "cleverreach" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class OptoutValidator
{

    /**
     * @var \Supseven\Cleverreach\CleverReach\Api
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $api;

    /**
     * Check if given number is higher than in configuration
     *
     * @param string $value
     * @param string $validationConfiguration
     * @return bool
     */
    public function validate121($value, $validationConfiguration): bool
    {
        $value = trim($value);

        if (!GeneralUtility::validEmail($value)) {
            return false;
        }

        return GeneralUtility::makeInstance(Api::class)->isReceiverOfGroupAndActive($value);
    }
}
