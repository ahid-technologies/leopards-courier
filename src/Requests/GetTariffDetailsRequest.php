<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;

class GetTariffDetailsRequest
{
    protected string $apiKey;
    protected string $apiPassword;
    protected array $data;

    public function __construct(string $apiKey, string $apiPassword, array $data)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->data = $data;
    }

    /**
     * Build query string for GET request
     *
     * @return string
     * @throws ValidationException
     */
    public function getQueryString(): string
    {
        $this->validate();

        $queryParams = array_merge([
            'api_key' => $this->apiKey,
            'api_password' => $this->apiPassword,
        ], $this->data);

        return http_build_query($queryParams);
    }

    /**
     * Validate required parameters
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        $required = [
            'packet_weight',
            'shipment_type',
            'origin_city',
            'destination_city',
            'cod_amount',
        ];

        foreach ($required as $field) {
            if (empty($this->data[$field])) {
                throw new ValidationException("Field '$field' is required.");
            }
        }

        return true;
    }
}
