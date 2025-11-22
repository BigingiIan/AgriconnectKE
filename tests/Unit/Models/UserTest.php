<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Bid;
use App\Models\Order;
use App\Models\Notification;
use App\Models\DriverLocation;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test user can be created with required fields
     */
    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'John Farmer',
            'email' => 'farmer@test.com',
            'role' => 'farmer',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'farmer@test.com',
            'role' => 'farmer',
        ]);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext_password',
        ]);

        $this->assertNotEquals('plaintext_password', $user->password);
    }

    /**
     * Test farmer user has products relationship
     */
    public function test_farmer_has_many_products()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $products = Product::factory()->count(3)->create(['farmer_id' => $farmer->id]);

        $this->assertEquals(3, $farmer->products()->count());
        $this->assertTrue($farmer->products->contains($products[0]));
    }

    /**
     * Test buyer user has bids relationship
     */
    public function test_buyer_has_many_bids()
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create();
        $bids = Bid::factory()->count(2)->create(['buyer_id' => $buyer->id, 'product_id' => $product->id]);

        $this->assertEquals(2, $buyer->bids()->count());
    }

    /**
     * Test buyer user has orders relationship
     */
    public function test_buyer_has_many_orders()
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Order::factory()->count(2)->create([
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(2, $buyer->orders()->count());
    }

    /**
     * Test farmer receives orders from buyers
     */
    public function test_farmer_has_many_orders_from_buyers()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Order::factory()->count(3)->create([
            'farmer_id' => $farmer->id,
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(3, $farmer->farmerOrders()->count());
    }

    /**
     * Test driver has assigned orders
     */
    public function test_driver_has_many_assigned_orders()
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Order::factory()->count(2)->create([
            'driver_id' => $driver->id,
            'farmer_id' => $farmer->id,
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(2, $driver->driverOrders()->count());
    }

    /**
     * Test user can receive notifications
     */
    public function test_user_has_many_notifications()
    {
        $user = User::factory()->create();
        Notification::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertEquals(3, $user->notifications()->count());
    }

    /**
     * Test user can have driver location
     */
    public function test_driver_has_one_location()
    {
        $driver = User::factory()->create(['role' => 'driver']);
        $location = DriverLocation::factory()->create(['driver_id' => $driver->id]);

        $this->assertNotNull($driver->driverLocation);
        $this->assertEquals($location->id, $driver->driverLocation->id);
    }

    /**
     * Test user with different roles
     */
    public function test_user_roles()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $driver = User::factory()->create(['role' => 'driver']);

        $this->assertEquals('admin', $admin->role);
        $this->assertEquals('farmer', $farmer->role);
        $this->assertEquals('buyer', $buyer->role);
        $this->assertEquals('driver', $driver->role);
    }

    /**
     * Test user location coordinates
     */
    public function test_user_location_coordinates()
    {
        $user = User::factory()->create([
            'latitude' => -1.2864,
            'longitude' => 36.8172,
        ]);

        $this->assertEquals(-1.2864, $user->latitude);
        $this->assertEquals(36.8172, $user->longitude);
    }

    /**
     * Test user availability status
     */
    public function test_user_availability_status()
    {
        $availableDriver = User::factory()->create([
            'role' => 'driver',
            'is_available' => true,
        ]);

        $unavailableDriver = User::factory()->create([
            'role' => 'driver',
            'is_available' => false,
        ]);

        $this->assertTrue($availableDriver->is_available);
        $this->assertFalse($unavailableDriver->is_available);
    }

    /**
     * Test user fillable attributes
     */
    public function test_user_fillable_attributes()
    {
        $attributes = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'role' => 'buyer',
            'phone' => '254712345678',
            'address' => '123 Main St',
            'latitude' => -1.2864,
            'longitude' => 36.8172,
            'is_available' => true,
        ];

        $user = User::create($attributes);

        foreach ($attributes as $key => $value) {
            if ($key !== 'password') {
                $this->assertEquals($value, $user->$key);
            }
        }
    }

    /**
     * Test user password is hidden
     */
    public function test_user_password_is_hidden()
    {
        $user = User::factory()->create();
        $user_array = $user->toArray();

        $this->assertArrayNotHasKey('password', $user_array);
    }
}
