<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Services\DeliveryService;
use Tests\TestCase;

class DeliveryServiceTest extends TestCase
{
    private $deliveryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deliveryService = new DeliveryService();
    }

    /**
     * Test delivery cost calculation with valid coordinates
     */
    public function test_delivery_cost_calculation_with_coordinates()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.3000,
            'longitude' => 36.8300,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertGreater($cost, 100); // Base cost + distance
        $this->assertIsNumeric($cost);
    }

    /**
     * Test delivery cost calculation returns default if coordinates missing
     */
    public function test_delivery_cost_default_without_coordinates()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create();

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertEquals(200, $cost); // Default cost
    }

    /**
     * Test delivery cost includes base cost
     */
    public function test_delivery_cost_includes_base_cost()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.2864, // Same location
            'longitude' => 36.8172,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertEquals(100, $cost); // Base cost only for same location
    }

    /**
     * Test distance calculation is positive
     */
    public function test_distance_calculation_is_positive()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        $buyer = User::factory()->create([
            'latitude' => 1,
            'longitude' => 1,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertGreater($cost, 0);
    }

    /**
     * Test distance calculation between known locations
     */
    public function test_distance_calculation_between_cities()
    {
        // Approximate coordinates
        $nairobi = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $kisumu = User::factory()->create([
            'latitude' => -0.1022,
            'longitude' => 34.7617,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($kisumu, $nairobi);

        // Should be higher than base cost due to distance
        $this->assertGreater($cost, 100);
    }

    /**
     * Test delivery cost increases with distance
     */
    public function test_delivery_cost_increases_with_distance()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        $nearBuyer = User::factory()->create([
            'latitude' => 0.1,
            'longitude' => 0.1,
        ]);

        $farBuyer = User::factory()->create([
            'latitude' => 1,
            'longitude' => 1,
        ]);

        $nearCost = $this->deliveryService->calculateDeliveryCost($nearBuyer, $farmer);
        $farCost = $this->deliveryService->calculateDeliveryCost($farBuyer, $farmer);

        $this->assertGreater($farCost, $nearCost);
    }

    /**
     * Test delivery cost is numeric
     */
    public function test_delivery_cost_is_numeric()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.3000,
            'longitude' => 36.8300,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertIsNumeric($cost);
        $this->assertGreater($cost, 0);
    }

    /**
     * Test delivery service calculates cost for multiple orders
     */
    public function test_delivery_service_multiple_orders()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyers = User::factory()->count(3)->create();

        $costs = [];
        foreach ($buyers as $buyer) {
            $costs[] = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);
        }

        // All costs should be numeric
        foreach ($costs as $cost) {
            $this->assertIsNumeric($cost);
        }
    }

    /**
     * Test delivery cost calculation is consistent
     */
    public function test_delivery_cost_calculation_consistency()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.3000,
            'longitude' => 36.8300,
        ]);

        $cost1 = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);
        $cost2 = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertEquals($cost1, $cost2);
    }

    /**
     * Test delivery cost rate per km
     */
    public function test_delivery_cost_rate_per_km()
    {
        // Using coordinates that create a known distance
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        $buyer = User::factory()->create([
            'latitude' => 0.009, // Approximately 1 km away
            'longitude' => 0,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        // Should be base cost (100) + approximately 50 * distance
        $this->assertGreater($cost, 100);
    }

    /**
     * Test delivery service with null latitude
     */
    public function test_delivery_cost_with_null_latitude_farmer()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => null,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.3000,
            'longitude' => 36.8300,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertEquals(200, $cost); // Default cost
    }

    /**
     * Test delivery service with null longitude
     */
    public function test_delivery_cost_with_null_longitude_buyer()
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $buyer = User::factory()->create([
            'latitude' => -1.3000,
            'longitude' => null,
        ]);

        $cost = $this->deliveryService->calculateDeliveryCost($buyer, $farmer);

        $this->assertEquals(200, $cost); // Default cost
    }
}
