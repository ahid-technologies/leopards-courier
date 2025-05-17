<?php

namespace Ahid\LeopardsCourier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAllCities()
 * @method static array bookPacket(array $data)
 * @method static array batchBookPacket(array $packets)
 * @method static array cancelBookedPackets(array $cnNumbers)
 * @method static array trackBookedPacket(array $trackNumbers)
 * @method static array getTariffDetails(array $params)
 * @method static array getShippingCharges(array $params)
 * @method static \Ahid\LeopardsCourier\LeopardsCourier setFormat(string $format)
 *
 * @see \Ahid\LeopardsCourier\LeopardsCourier
 */
class LeopardsCourier extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'leopards-courier';
    }
}
