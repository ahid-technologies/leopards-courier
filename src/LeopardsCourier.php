<?php

namespace Ahid\LeopardsCourier;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ahid\LeopardsCourier\Requests\BookPacketRequest;
use Ahid\LeopardsCourier\Requests\GetAllCitiesRequest;
use Ahid\LeopardsCourier\Exceptions\ValidationException;
use Ahid\LeopardsCourier\Exceptions\LeopardsApiException;
use Ahid\LeopardsCourier\Requests\BatchBookPacketRequest;
use Ahid\LeopardsCourier\Requests\GetTariffDetailsRequest;
use Ahid\LeopardsCourier\Requests\TrackBookedPacketRequest;
use Ahid\LeopardsCourier\Requests\GetShippingChargesRequest;
use Ahid\LeopardsCourier\Requests\CancelBookedPacketsRequest;

class LeopardsCourier
{

    /**
     * Guzzle HTTP Client
     *
     * @var Client
     */
    protected $client;

    /**
     * API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API Password
     *
     * @var string
     */
    protected $apiPassword;

    /**
     * API Base URL
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Response Format
     *
     * @var string
     */
    protected $format = 'json';

    /**
     * LeopardsCourier constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('leopards-courier.api_key');
        $this->apiPassword = config('leopards-courier.api_password');
        $this->baseUrl = config('leopards-courier.environment') === 'production'
            ? 'https://merchantapi.leopardscourier.com/api/'
            : 'https://merchantapistaging.leopardscourier.com/api/';

        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get all cities from the API
     *
     * @return array
     * @throws LeopardsApiException
     */
    public function getAllCities()
    {
        $endpoint = 'getAllCities/format/' . $this->format . '/';

        $request = new GetAllCitiesRequest($this->apiKey, $this->apiPassword);
        $payload = $request->getPayload();

        return $this->makeRequest('POST', $endpoint, $payload);
    }

    /**
     * Book a packet
     *
     * @param array $data
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function bookPacket(array $data)
    {
        $endpoint = 'bookPacket/format/' . $this->format . '/';

        $request = new BookPacketRequest($this->apiKey, $this->apiPassword, $data);
        $payload = $request->getPayload();

        return $this->makeRequest('POST', $endpoint, $payload);
    }

    /**
     * Batch book multiple packets
     *
     * @param array $packets
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function batchBookPacket(array $packets)
    {
        $endpoint = 'batchBookPacket/format/' . $this->format . '/';

        $request = new BatchBookPacketRequest($this->apiKey, $this->apiPassword, $packets);
        $payload = $request->getPayload();

        return $this->makeRequest('POST', $endpoint, $payload);
    }

    /**
     * Cancel one or more booked packets by CN numbers
     *
     * @param array $cnNumbers  Array of CN numbers (e.g. ['XX123456789', 'XX987654321'])
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function cancelBookedPackets(array $cnNumbers)
    {
        $endpoint = 'cancelBookedPackets/format/' . $this->format . '/';

        $request = new CancelBookedPacketsRequest($this->apiKey, $this->apiPassword, $cnNumbers);
        $payload = $request->getPayload();

        return $this->makeRequest('POST', $endpoint, $payload);
    }

    /**
     * Track booked packet(s)
     *
     * @param array $trackNumbers Array of tracking numbers
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function trackBookedPacket(array $trackNumbers)
    {
        $endpoint = 'trackBookedPacket/format/' . $this->format . '/';

        $request = new TrackBookedPacketRequest($this->apiKey, $this->apiPassword, $trackNumbers);
        $payload = $request->getPayload();

        return $this->makeRequest('POST', $endpoint, $payload);
    }

    /**
     * Get tariff details based on shipment parameters
     *
     * @param array $params
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function getTariffDetails(array $params)
    {
        $request = new GetTariffDetailsRequest($this->apiKey, $this->apiPassword, $params);
        $query = $request->getQueryString();

        $endpoint = 'getTariffDetails/format/' . $this->format . '/?' . $query;

        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Get shipping charges for one or more CN numbers
     *
     * @param array $params
     * @return array
     * @throws LeopardsApiException
     * @throws ValidationException
     */
    public function getShippingCharges(array $params)
    {
        $request = new GetShippingChargesRequest($this->apiKey, $this->apiPassword, $params);
        $query = $request->getQueryString();

        $endpoint = 'getShippingCharges/format/' . $this->format . '/?' . $query;

        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Make API request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $payload
     * @return array
     * @throws LeopardsApiException
     */
    protected function makeRequest(string $method, string $endpoint, array $payload = [])
    {
        try {
            $response = $this->client->request($method, $this->baseUrl . $endpoint, [
                'json' => $payload,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if (isset($responseBody['status']) && $responseBody['status'] === 0) {
                throw new LeopardsApiException($responseBody['error'] ?? 'Unknown API error');
            }

            return $responseBody;
        } catch (GuzzleException $e) {
            throw new LeopardsApiException('API request failed: ' . $e->getMessage());
        }
    }

    /**
     * Set the response format
     *
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = in_array($format, ['json', 'xml']) ? $format : 'json';
        return $this;
    }
}
