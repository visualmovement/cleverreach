<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Form\Validator;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Supseven\Cleverreach\CleverReach\Api;
use Supseven\Cleverreach\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Validator for email addresses
 *
 * @api
 */
class OptinValidator extends AbstractValidator
{
    /**
     * Checks if the given value is already in the list
     *
     * @param mixed $value The value that should be validated
     * @api
     */
    public function isValid($value)
    {
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        $configuration = $configurationService->getConfiguration();

        $groupId = isset($this->options['groupId']) && \strlen($this->options['groupId']) > 0 ? $this->options['groupId'] : $configuration['groupId'];

        if (empty($groupId)) {
            $this->addError('Group ID not set.', 1534719428);

            return;
        }

        $api = GeneralUtility::makeInstance(Api::class);

        if ($api->isReceiverOfGroupAndActive($value, $groupId)) {
            $this->addError(
                $this->translateErrorMessage(
                    'validator.alreadyInList',
                    'cleverreach'
                ),
                1534719423
            );
        }
    }
}
