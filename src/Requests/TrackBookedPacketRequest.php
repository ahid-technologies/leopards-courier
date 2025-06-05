<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

class TrackBookedPacketRequest
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiPassword;

    /**
     * @var array
     */
    protected $trackNumbers;

    /**
     * TrackBookedPacketRequest constructor.
     *
     * @param string $apiKey
     * @param string $apiPassword
     * @param array $trackNumbers
     */
    public function __construct(string $apiKey, string $apiPassword, array $trackNumbers)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->trackNumbers = $trackNumbers;
    }

    /**
     * Get request payload
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
            'track_numbers' => implode(',', $this->trackNumbers),
        ];
    }

    /**
     * Validate tracking numbers
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        $validator = Validator::make(
            ['track_numbers' => $this->trackNumbers],
            ['track_numbers' => 'required|array|min:1', 'track_numbers.*' => 'required|string']
        );

        if ($validator->fails()) {
            throw new ValidationException('Validation failed: ' . json_encode($validator->errors()));
        }

        return true;
    }
}
