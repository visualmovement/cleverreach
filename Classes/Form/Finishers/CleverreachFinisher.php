<?php
declare(strict_types=1);
namespace Supseven\Cleverreach\Form\Finishers;

/**
 * This file is part of the "cleverreach" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Supseven\Cleverreach\CleverReach\Api;
use Supseven\Cleverreach\Domain\Model\Receiver;
use Supseven\Cleverreach\Service\ConfigurationService;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;

class CleverreachFinisher extends AbstractFinisher
{

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var ConfigurationService
     */
    protected $configurationService;

    /**
     * @var array
     */
    protected $defaultOptions = [
    ];

    /**
     * @param Api $api
     * @param ConfigurationService $configurationService
     */
    public function __construct(Api $api, ConfigurationService $configurationService)
    {
        $this->api = $api;
        $this->configurationService = $configurationService;
    }

    /**
     * Executes this finisher
     * @see AbstractFinisher::execute()
     *
     * @throws FinisherException
     */
    protected function executeInternal()
    {
        $formValues = $this->getFormValues();

        $configuration = $this->configurationService->getConfiguration();

        $groupId = isset($this->options['groupId']) && \strlen($this->options['groupId']) > 0 ? $this->options['groupId'] : $configuration['groupId'];
        $formId = isset($this->options['formId']) && \strlen($this->options['formId']) > 0 ? $this->options['formId'] : $configuration['formId'];

        if (empty($groupId) || empty($formId)) {
            throw new FinisherException('Form ID or Group ID not set.');
        }

        $email = null;
        $attributes = [];
        
        // default value: always subscribe
        $actionIsTriggered = true;

        foreach ($formValues as $identifier => $value) {
            $element = $this->finisherContext->getFormRuntime()->getFormDefinition()->getElementByIdentifier($identifier);

            if ($element !== null) {
                $properties = $element->getProperties();
                
                if ($properties['cleverreachTrigger'] === true) {
                    if ($value != true) {
                        $actionIsTriggered = false;
                    }
                }

                if (isset($properties['cleverreachField'])) {
                    switch ($properties['cleverreachField']) {
                        case 'email':
                            $email = $value;
                            break;
                        case 'formId':
                            $formId = $value;
                            break;
                        case 'groupId':
                            $groupId = $value;
                            break;
                        default:
                            $attributes[$properties['cleverreachField']] = $value;
                    }
                }
            }
        }

        if (isset($this->options['mode']) && $email != '') {
            if (strtolower($this->options['mode']) === Api::MODE_OPTIN && $actionIsTriggered) {
                $receiver = new Receiver($email, $attributes);
                $this->api->addReceiversToGroup($receiver, $groupId);
                $this->api->sendSubscribeMail($email, $formId, $groupId);
            } elseif (strtolower($this->options['mode']) === Api::MODE_OPTOUT) {
                $this->api->sendUnsubscribeMail($email, $formId, $groupId);
            }
        }
    }

    /**
     * Returns the values of the submitted form
     *
     * @return []
     */
    protected function getFormValues(): array
    {
        return $this->finisherContext->getFormValues();
    }

    /**
     * Returns a form element object for a given identifier.
     *
     * @param string $elementIdentifier
     * @return FormElementInterface|null
     */
    protected function getElementByIdentifier(string $elementIdentifier)
    {
        return $this
            ->finisherContext
            ->getFormRuntime()
            ->getFormDefinition()
            ->getElementByIdentifier($elementIdentifier);
    }
}
