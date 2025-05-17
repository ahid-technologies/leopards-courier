<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;

class CancelBookedPacketsRequest
{
    protected string $apiKey;
    protected string $apiPassword;
    protected array $cnNumbers;

    public function __construct(string $apiKey, string $apiPassword, array $cnNumbers)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->cnNumbers = $cnNumbers;
    }

    /**
     * Get payload for API
     *
     * @return array
     * @throws ValidationException
     */
    public function getPayload(): array
    {
        $this->validate();

        return [
            'api_key' => $this->apiKey,
            'api_password' => $this->apiPassword,
            'cn_numbers' => implode(',', $this->cnNumbers),
        ];
    }

    /**
     * Validate the CN numbers array
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        if (empty($this->cnNumbers)) {
            throw new ValidationException('At least one CN number is required.');
        }

        foreach ($this->cnNumbers as $cn) {
            if (!preg_match('/^[A-Z]{2}\d{8,10}$/', $cn)) {
                throw new ValidationException("Invalid CN format: $cn");
            }
        }

        return true;
    }
}
