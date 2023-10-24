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

class TsReturnOperation extends AbstractOperation
{

    /**
     * @return array
     * @throws DataValidationException
     * @throws PersonNotFoundException
     * @throws TemplateEmptyFieldsException
     */
    public function doOperation(): array
    {
        $data = Utils::getDataFromRequest();

        $this->checkRequestData($data);

        $resellerId = (int)$data['resellerId'];
        $reseller = SellerFactory::getById($resellerId);
        if ($reseller === null) {
            throw new PersonNotFoundException('Seller', $resellerId);
        }

        $clientId = (int)$data['clientId'];
        $client = ClientFactory::getById($clientId);
        if ($client === null || $client->getType() !== PERSON_TYPE_CUSTOMER || $client->getSeller()->getId() !== $resellerId) {
            throw new PersonNotFoundException('Client', $clientId);
        }

        $creatorId = (int)$data['creatorId'];
        $creator = EmployeeFactory::getById($creatorId);
        if ($creator === null) {
            throw new PersonNotFoundException('Employee (creator)', $creatorId);
        }

        $expertId = (int)$data['expertId'];
        $expert = EmployeeFactory::getById($expertId);

        if ($expert === null) {
            throw new PersonNotFoundException('Employee (expert)', $creatorId);
        }

        $notificationType = (int)$data['notificationType'];
        $previousStatusId = (int)$data['differences']['from'];
        $currentStatusId = (int)$data['differences']['to'];
        $differences = '';
        if ($notificationType === NOTIFICATION_TYPE_NEW) {
            $differences = __('NewPositionAdded', null, $resellerId);
        } elseif ($notificationType === NOTIFICATION_TYPE_CHANGE && !empty($data['differences'])) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => STATUSES[$previousStatusId],
                'TO' => STATUSES[$currentStatusId],
            ], $resellerId);
        }

        $template = new Template();
        $template->setComplaintIdField((int)$data['complaintId']);
        $template->setComplaintNumberField((string)$data['complaintNumber']);
        $template->setCreatorIdField($creatorId);
        $template->setCreatorNameField($creator->getFullName());
        $template->setExpertIdField($expertId);
        $template->setExpertNameField($expert->getFullName());
        $template->setClientIdField($clientId);
        $template->setClientNameField($client->getFullName());
        $template->setConsumptionIdField((int)$data['consumptionId']);
        $template->setConsumptionNumberField((string)$data['consumptionNumber']);
        $template->setAgreementNumberField((string)$data['agreementNumber']);
        $template->setDateField((string)$data['date']);
        $template->setDifferencesField($differences);

        // Если хоть одна переменная для шаблона не задана, то не отправляем уведомления
        $emptyFields = $template->getEmptyFields();
        if (!$emptyFields){
            throw new TemplateEmptyFieldsException($emptyFields);
        }

        $result = [
            'notificationEmployeeByEmail' => false,
            'notificationClientByEmail' => false,
            'notificationClientBySms' => [
                'isSent' => false,
                'message' => '',
            ],
        ];

        $emailFrom = getResellerEmailFrom($resellerId);
        // Получаем email сотрудников из настроек
        $emails = getEmailsByPermit($resellerId, 'tsGoodsReturn');
        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $emailTo) {
                MessagesClient::sendMessage([
                    0 => $this->makeEmailMessage($emailFrom, $emailTo, $template, $resellerId),
                ], $resellerId, NOTIFICATION_EVENT_CHANGE_RETURN_STATUS);
                $result['notificationEmployeeByEmail'] = true;

            }
        }

        // Шлём клиентское уведомление, только если произошла смена статуса
        if ($notificationType === NOTIFICATION_TYPE_CHANGE) {
            $emailTo = $client->getEmail();
            if (!empty($emailFrom) && !empty($emailTo)) {
                MessagesClient::sendMessage([
                    0 => $this->makeEmailMessage($emailFrom, $emailTo, $template, $resellerId),
                ], $resellerId, $client->getId(), NOTIFICATION_EVENT_CHANGE_RETURN_STATUS, (int)$data['differences']['to']);
                $result['notificationClientByEmail'] = true;
            }

            $clientMobile = $client->getMobile();
            if (!empty($clientMobile)) {
                $res = NotificationManager::send($resellerId, $clientId, NOTIFICATION_TYPE_CHANGE, $previousStatusId, $template->toArray());
                if ($res) {
                    $result['notificationClientBySms']['isSent'] = true;
                    $result['notificationClientBySms']['message'] = $res; // не уверен, что метод send возвращает отправленное сообщение, как предположение
                }
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @throws DataValidationException
     */
    private function checkRequestData(array $data): void
    {
        if (empty($data)) {
            throw new DataValidationException('data');
        }

        if (empty($notificationType)) {
            throw new DataValidationException('notificationType');
        }

        if (empty($data['differences']['from'])){
            throw new DataValidationException('differences[from]');
        }

        if (empty($data['differences']['to'])){
            throw new DataValidationException('differences[to]');
        }
    }

    /**
     * @param string $emailFrom
     * @param string $emailTo
     * @param Template $template
     * @param int $sellerId
     * @param string $type
     * @return array
     */
    private function makeEmailMessage(string $emailFrom, string $emailTo, Template $template, int $sellerId, string $type = 'Employee'):array
    {
        $subjectType = $type == 'Employee' ? 'complaintEmployeeEmailSubject' : 'complaintClientEmailSubject';
        $messageType = $type == 'Employee' ? 'complaintEmployeeEmailBody' : 'complaintClientEmailBody';
        return [ // MessageTypes::EMAIL
            'emailFrom' => $emailFrom,
            'emailTo' => $emailTo,
            'subject' => __($subjectType, $template->toArray(), $sellerId),
            'message' => __($messageType, $template->toArray(), $sellerId),
        ];
    }
}