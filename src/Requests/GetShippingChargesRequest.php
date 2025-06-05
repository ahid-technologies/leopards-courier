<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;

class GetShippingChargesRequest
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

        $queryParams = [
            'api_key' => $this->apiKey,
            'api_password' => $this->apiPassword,
            'cn_numbers' => implode(',', $this->data['cn_numbers']),
        ];

        return http_build_query($queryParams);
    }

    /**
     * Validate CN numbers
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        if (!isset($this->data['cn_numbers']) || !is_array($this->data['cn_numbers'])) {
            throw new ValidationException("'cn_numbers' must be an array of strings.");
        }

        $count = count($this->data['cn_numbers']);

        if ($count === 0) {
            throw new ValidationException("'cn_numbers' array cannot be empty.");
        }

        if ($count > 50) {
            throw new ValidationException("A maximum of 50 CN numbers can be sent at once.");
        }

        return true;
    }
}
