<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Powermail\Validator;

/**
 * This file is part of the "cleverreach" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Supseven\Cleverreach\CleverReach\Api;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptinValidator
{
    /**
     * Check if given number is higher than in configuration
     *
     * @param string $value
     * @param string $validationConfiguration
     * @return bool
     */
    public function validate120($value, $validationConfiguration): bool
    {
        $value = trim($value);

        if (!GeneralUtility::validEmail($value)) {
            return false;
        }

        return !GeneralUtility::makeInstance(Api::class)->isReceiverOfGroupAndActive($value);
    }
}
