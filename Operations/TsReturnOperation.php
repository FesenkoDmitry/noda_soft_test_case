<?php


namespace NW\WebService\References\Operations;

use NW\WebService\References\Exceptions\DataValidationException;
use NW\WebService\References\Exceptions\PersonNotFoundException;
use NW\WebService\References\Exceptions\TemplateEmptyFieldsException;
use NW\WebService\References\Factories\ClientFactory;
use NW\WebService\References\Factories\EmployeeFactory;
use NW\WebService\References\Factories\SellerFactory;
use NW\WebService\References\Templates\Template;
use NW\WebService\References\Utils\Utils;
use NW\WebService\References\DTO\SellerDTO;
use NW\WebService\References\DTO\ClientDTO;
use NW\WebService\References\DTO\EmployeeDTO;

class TsReturnOperation extends AbstractOperation
{

    /**
     * @var array
     */
    private $data;
    /**
     * @var SellerDTO|null
     */
    private $reseller;
    /**
     * @var ClientDTO|null
     */
    private $client;
    /**
     * @var EmployeeDTO|null
     */
    private $creator;
    /**
     * @var EmployeeDTO|null
     */
    private $expert;
    /**
     * @var int
     */
    private $notificationType;
    /**
     * @var int
     */
    private $previousStatusId;
    /**
     * @var int
     */
    private $currentStatusId;
    /**
     * @var int
     */
    private $complaintId;
    /**
     * @var string
     */
    private $complaintNumber;
    /**
     * @var int
     */
    private $consumptionId;
    /**
     * @var string
     */
    private $consumptionNumber;
    /**
     * @var string
     */
    private $agreementNumber;
    /**
     * @var string
     */
    private $date;
    /**
     * @var array|null
     */
    private $differences;

    /**
     * @return array
     * @throws DataValidationException
     */
    public function doOperation(): array
    {
        $this->data = Utils::getDataFromRequest();
        $this->fillProperties();
        $this->validateProperties();
        $template = $this->makeTemplate();

        $result = [
            'notificationEmployeeByEmail' => false,
            'notificationClientByEmail' => false,
            'notificationClientBySms' => [
                'isSent' => false,
                'message' => '',
            ],
        ];

        $this->sendEmployeeNotification($template);
        $result['notificationEmployeeByEmail'] = true;
        $sendResult = $this->sendClientNotification($template);
        $result['notificationClientByEmail'] = $sendResult['notificationClientByEmail'] ?? false;
        $result['notificationClientBySms']['isSent'] = $sendResult['notificationClientBySms']['isSent'] ?? false;
        $result['notificationClientBySms']['message'] = $sendResult['notificationClientBySms']['message'] ?? '';

        return $result;
    }

    /**
     * @return Template
     */
    private function makeTemplate(): Template
    {
        $template = new Template();
        $template->setComplaintIdField($this->complaintId);
        $template->setComplaintNumberField($this->complaintNumber);
        $template->setCreatorIdField($this->creator->getId());
        $template->setCreatorNameField($this->creator->getFullName());
        $template->setExpertIdField($this->expert->getId());
        $template->setExpertNameField($this->expert->getFullName());
        $template->setClientIdField($this->client->getId());
        $template->setClientNameField($this->client->getFullName());
        $template->setConsumptionIdField($this->consumptionId);
        $template->setConsumptionNumberField($this->consumptionNumber);
        $template->setAgreementNumberField($this->agreementNumber);
        $template->setDateField($this->date);
        $template->setDifferencesField($this->differences);

        return $template;
    }

    /**
     * @return array|null
     */
    private function getDifferences()
    {
        $differences = null;
        if ($this->notificationType === NOTIFICATION_TYPE_NEW) {
            $differences = __('NewPositionAdded', null, $this->reseller->getId());
        } elseif ($this->notificationType === NOTIFICATION_TYPE_CHANGE && !empty($data['differences'])) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => STATUSES[$this->previousStatusId],
                'TO' => STATUSES[$this->currentStatusId],
            ], $this->reseller->getId());
        }

        return $differences;
    }

    /**
     * @throws DataValidationException
     */
    private function validateProperties(): void
    {
        $reflector = new \ReflectionClass($this);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            if (empty($this->{$property->getName()})) {
                throw new DataValidationException($property);
            }
        }
    }

    /**
     */
    private function fillProperties(): void
    {
        $this->notificationType = (int)$this->data['notificationType'];
        $this->previousStatusId = (int)$this->data['differences']['from'];
        $this->currentStatusId = (int)$this->data['differences']['to'];
        $this->complaintId = (int)$this->data['complaintId'];
        $this->complaintNumber = (string)$this->data['complaintNumber'];
        $this->consumptionId = (int)$this->data['consumptionId'];
        $this->consumptionNumber = (string)$this->data['consumptionNumber'];
        $this->agreementNumber = (string)$this->data['agreementNumber'];
        $this->date = (string)$this->data['date'];
        $this->differences = $this->getDifferences();

        $resellerId = (int)$this->data['resellerId'];
        $this->reseller = SellerFactory::getById($resellerId);

        $clientId = (int)$this->data['clientId'];
        $this->client = ClientFactory::getById($clientId);

        $creatorId = (int)$this->data['creatorId'];
        $this->creator = EmployeeFactory::getById($creatorId);

        $expertId = (int)$this->data['expertId'];
        $this->expert = EmployeeFactory::getById($expertId);
    }

    /**
     * @param \NW\WebService\References\Templates\Template $template
     */
    private function sendEmployeeNotification(Template $template): void
    {
        $emailFrom = Utils::getResellerEmailFrom();
        // Получаем email сотрудников из настроек
        $emails = Utils::getEmailsByPermit($this->reseller->getId(), 'tsGoodsReturn');
        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $emailTo) {
                MessagesClient::sendMessage([
                    0 => $this->makeEmailMessage($emailFrom, $emailTo, $template, $this->reseller->getId()),
                ], $this->reseller->getId(), NOTIFICATION_EVENT_CHANGE_RETURN_STATUS);
            }
        }
    }

    /**
     * @param \NW\WebService\References\Templates\Template $template
     * @return array
     */
    private function sendClientNotification(Template $template): array
    {
        $result = [];
        if ($this->notificationType === NOTIFICATION_TYPE_CHANGE) {
            $emailTo = $this->client->getEmail();
            if (!empty($emailFrom) && !empty($emailTo)) {
                MessagesClient::sendMessage([
                    0 => $this->makeEmailMessage($emailFrom, $emailTo, $template, $this->reseller->getId()),
                ], $this->reseller->getId(), $this->client->getId(), NOTIFICATION_EVENT_CHANGE_RETURN_STATUS, $this->previousStatusId);
                $result['notificationClientByEmail'] = true;
            }

            $clientMobile = $this->client->getMobile();
            if (!empty($clientMobile)) {
                $res = NotificationManager::send($this->reseller->getId(), $this->client->getId(), NOTIFICATION_TYPE_CHANGE, $this->previousStatusId, $template->toArray());
                if ($res) {
                    $result['notificationClientBySms']['isSent'] = true;
                    $result['notificationClientBySms']['message'] = $res; // не уверен, что метод send возвращает отправленное сообщение, как предположение
                }
            }
        }

        return $result;
    }

    /**
     * @param string $emailFrom
     * @param string $emailTo
     * @param Template $template
     * @param int $sellerId
     * @param string $type
     * @return array
     */
    private function makeEmailMessage(string $emailFrom, string $emailTo, Template $template, string $type = 'Employee'): array
    {
        $subjectType = $type == 'Employee' ? 'complaintEmployeeEmailSubject' : 'complaintClientEmailSubject';
        $messageType = $type == 'Employee' ? 'complaintEmployeeEmailBody' : 'complaintClientEmailBody';
        return [ // MessageTypes::EMAIL
            'emailFrom' => $emailFrom,
            'emailTo' => $emailTo,
            'subject' => __($subjectType, $template->toArray(), $this->reseller->getId()),
            'message' => __($messageType, $template->toArray(), $this->reseller->getId()),
        ];
    }
}