<?php


namespace NW\WebService\References\Templates;


class Template extends AbstractTemplate
{
    /**
     * @var int
     */
    private $complaintIdField;
    /**
     * @var int
     */
    private $complaintNumberField;
    /**
     * @var int
     */
    private $creatorIdField;
    /**
     * @var string
     */
    private $creatorNameField;
    /**
     * @var int
     */
    private $expertIdField;
    /**
     * @var string
     */
    private $expertNameField;
    /**
     * @var int
     */
    private $clientIdField;
    /**
     * @var string
     */
    private $clientNameField;
    /**
     * @var int
     */
    private $consumptionIdField;
    /**
     * @var string
     */
    private $consumptionNumberField;
    /**
     * @var string
     */
    private $agreementNumberField;
    /**
     * @var string
     */
    private $dateField;
    /**
     * @var
     */
    private $differencesField;

    /**
     * @param int $complaintId
     */
    public function setComplaintIdField(int $complaintId): void
    {
        $this->complaintIdField = [
            'name' => 'COMPLAINT_ID',
            'value' => $complaintId
        ];
    }

    /**
     * @param string $complaintNumber
     */
    public function setComplaintNumberField(string $complaintNumber): void
    {
        $this->complaintNumberField = [
            'name' => 'COMPLAINT_NUMBER',
            'value' => $complaintNumber
        ];

    }

    /**
     * @param int $creatorId
     */
    public function setCreatorIdField(int $creatorId): void
    {
        $this->creatorIdField = [
            'name' => 'CREATOR_ID',
            'value' => $creatorId
        ];
    }

    /**
     * @param string $creatorName
     */
    public function setCreatorNameField(string $creatorName): void
    {
        $this->creatorNameField = [
            'name' => 'CREATOR_NAME',
            'value' => $creatorName
        ];
    }

    /**
     * @param string $agreementNumber
     */
    public function setAgreementNumberField(string $agreementNumber): void
    {
        $this->agreementNumberField = [
            'name' => 'AGREEMENT_NUMBER',
            'value' => $agreementNumber
        ];
    }

    /**
     * @param int $clientId
     */
    public function setClientIdField(int $clientId): void
    {
        $this->clientIdField = [
            'name' => 'CLIENT_ID',
            'value' => $clientId
        ];
    }

    /**
     * @param string $clientName
     */
    public function setClientNameField(string $clientName): void
    {
        $this->clientNameField = [
            'name' => 'CLIENT_NAME',
            'value' => $clientName
        ];
    }

    /**
     * @param string $expertName
     */
    public function setExpertNameField(string $expertName): void
    {
        $this->expertNameField = [
            'name' => 'EXPERT_NAME',
            'value' => $expertName
        ];
    }

    /**
     * @param int $expertId
     */
    public function setExpertIdField(int $expertId): void
    {
        $this->expertIdField = [
            'name' => 'EXPERT_ID',
            'value' => $expertId
        ];
    }

    /**
     * @param $differences
     */
    public function setDifferencesField($differences): void
    {
        $this->differencesField = [
            'name' => 'DIFFERENCES',
            'value' => $differences
        ];
    }

    /**
     * @param string $date
     */
    public function setDateField(string $date): void
    {
        $this->dateField = [
            'name' => 'DATE',
            'value' => $date
        ];
    }

    /**
     * @param string $consumptionNumber
     */
    public function setConsumptionNumberField(string $consumptionNumber): void
    {
        $this->consumptionNumberField = [
            'name' => 'CONSUMPTION_NUMBER',
            'value' => $consumptionNumber
        ];
    }

    /**
     * @param int $consumptionId
     */
    public function setConsumptionIdField(int $consumptionId): void
    {
        $this->consumptionIdField = [
            'name' => 'CONSUMTION_ID',
            'value' => $consumptionId
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
    }

}