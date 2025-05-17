<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

class BookPacketRequest
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
    protected $data;

    /**
     * BookPacketRequest constructor.
     *
     * @param string $apiKey
     * @param string $apiPassword
     * @param array $data
     */
    public function __construct(string $apiKey, string $apiPassword, array $data)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->data = $data;
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

        return array_merge(
            [
                'api_key' => $this->apiKey,
                'api_password' => $this->apiPassword,
            ],
            $this->data
        );
    }

    /**
     * Validate request data
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        $rules = [
            // Required fields
            'booked_packet_weight' => 'required|integer',
            'booked_packet_no_piece' => 'required|integer',
            'booked_packet_collect_amount' => 'required|integer',
            'origin_city' => 'required',
            'destination_city' => 'required',
            'shipment_id' => 'required|integer',
            'shipment_name_eng' => 'required|string',
            'shipment_email' => 'required|string',
            'shipment_phone' => 'required|string',
            'shipment_address' => 'required|string',
            'special_instructions' => 'required|string',
            'consignment_name_eng' => 'required|string',
            'consignment_phone' => 'required|string',
            'consignment_address' => 'required|string',

            // Optional fields
            'booked_packet_vol_weight_w' => 'nullable|integer',
            'booked_packet_vol_weight_h' => 'nullable|integer',
            'booked_packet_vol_weight_l' => 'nullable|integer',
            'booked_packet_order_id' => 'nullable|string',
            'consignment_email' => 'nullable|string',
            'consignment_phone_two' => 'nullable|string',
            'consignment_phone_three' => 'nullable|string',
            'shipment_type' => 'nullable|string',
            'custom_data' => 'nullable|array',
            'return_address' => 'nullable|string',
            'return_city' => 'nullable|integer',
            'is_vpc' => 'nullable|in:0,1',
        ];

        $validator = Validator::make($this->data, $rules);

        if ($validator->fails()) {
            throw new ValidationException('Validation failed: ' . json_encode($validator->errors()));
        }

        return true;
    }
}
