<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(
            ['cn_numbers' => $this->cnNumbers],
            ['cn_numbers' => 'required|array|min:1', 'cn_numbers.*' => 'required|string']
        );

        if ($validator->fails()) {
            throw new ValidationException('Validation failed: ' . json_encode($validator->errors()));
        }

        return true;
    }
}
