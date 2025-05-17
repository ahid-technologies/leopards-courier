# Ahid Leopards Courier

A Laravel package for integrating with Leopards Courier API services.

## Installation

You can install the package via composer:

```bash
composer require ahid/leopards-courier
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Ahid\LeopardsCourier\AhidLeopardsServiceProvider"
```

Add your Leopards Courier API credentials to your `.env` file:

```
LEOPARDS_API_KEY=your_api_key
LEOPARDS_API_PASSWORD=your_api_password
LEOPARDS_API_ENVIRONMENT=staging # or production
```

## Usage

### Get All Cities

```php
use Ahid\LeopardsCourier\Facades\LeopardsCourier;

// Get all cities
$cities = LeopardsCourier::getAllCities();
```

### Book a Packet

```php
use Ahid\LeopardsCourier\Facades\LeopardsCourier;

// Book a packet
$bookingData = [
    'booked_packet_weight' => 2000, // Weight in grams
    'booked_packet_no_piece' => 1,
    'booked_packet_collect_amount' => 2500,
    'booked_packet_order_id' => 'ORD-12345',
    'origin_city' => 'self', // or city ID
    'destination_city' => 789, // City ID from getAllCities
    'shipment_name_eng' => 'self',
    'shipment_email' => 'self',
    'shipment_phone' => 'self',
    'shipment_address' => 'self',
    'consignment_name_eng' => 'John Doe',
    'consignment_email' => 'john@example.com',
    'consignment_phone' => '03001234567',
    'consignment_address' => '123 Main St, Lahore',
    'special_instructions' => 'Handle with care'
];

$booking = LeopardsCourier::bookPacket($bookingData);
```

## Available Methods

-   `getAllCities()`: Get a list of all available cities with origin and destination status
-   `bookPacket(array $data)`: Book a new shipment

## Error Handling

The package throws `LeopardsApiException` when the API returns an error. You can catch this exception to handle API errors:

```php
use Ahid\LeopardsCourier\Exceptions\LeopardsApiException;

try {
    $cities = LeopardsCourier::getAllCities();
} catch (LeopardsApiException $e) {
    // Handle API error
    echo $e->getMessage();
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
