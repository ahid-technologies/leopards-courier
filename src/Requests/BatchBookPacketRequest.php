<?php

namespace Ahid\LeopardsCourier\Requests;

use Ahid\LeopardsCourier\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

class BatchBookPacketRequest
{
    protected string $apiKey;
    protected string $apiPassword;
    protected array $packets;

    public function __construct(string $apiKey, string $apiPassword, array $packets)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->packets = $packets;
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
            'packets' => $this->packets,
        ];
    }

    /**
     * Validate each packet in the batch
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validate(): bool
    {
        if (empty($this->packets) || !is_array($this->packets)) {
            throw new ValidationException('Packets data must be a non-empty array.');
        }

        foreach ($this->packets as $index => $packet) {
            $validator = Validator::make($packet, [
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
                'special_instructions' => 'nullable|string',
                'shipment_type' => 'nullable|string',
                'custom_data' => 'nullable|array',
                'return_address' => 'nullable|string',
                'return_city' => 'nullable|integer',
                'is_vpc' => 'nullable|in:0,1',
            ]);

            if ($validator->fails()) {
                throw new ValidationException(
                    "Validation failed for packet at index $index: " . json_encode($validator->errors())
                );
            }
        }

        return true;
    }
}
