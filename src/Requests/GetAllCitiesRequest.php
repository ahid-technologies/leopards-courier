<?php

namespace Ahid\LeopardsCourier\Requests;

class GetAllCitiesRequest
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
     * GetAllCitiesRequest constructor.
     *
     * @param string $apiKey
     * @param string $apiPassword
     */
    public function __construct(string $apiKey, string $apiPassword)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
    }

    /**
     * Get request payload
     *
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'api_key' => $this->apiKey,
            'api_password' => $this->apiPassword,
        ];
    }
}
