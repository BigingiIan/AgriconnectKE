<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Bid;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test order can be created
     */
    public function test_order_can_be_created()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'amount' => 100.00,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test order belongs to product
     */
    public function test_order_belongs_to_product()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
        ]);

        $this->assertInstanceOf(Product::class, $order->product);
        $this->assertEquals($product->id, $order->product->id);
    }

    /**
     * Test order belongs to buyer
     */
    public function test_order_belongs_to_buyer()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
        ]);

        $this->assertInstanceOf(User::class, $order->buyer);
        $this->assertEquals($buyer->id, $order->buyer->id);
    }

    /**
     * Test order belongs to farmer
     */
    public function test_order_belongs_to_farmer()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
        ]);

        $this->assertInstanceOf(User::class, $order->farmer);
        $this->assertEquals($farmer->id, $order->farmer->id);
    }

    /**
     * Test order can have driver
     */
    public function test_order_can_have_driver()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $driver = User::factory()->create(['role' => 'driver']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'driver_id' => $driver->id,
        ]);

        $this->assertNotNull($order->driver);
        $this->assertEquals($driver->id, $order->driver->id);
    }

    /**
     * Test pending orders scope
     */
    public function test_pending_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $pendingOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'pending',
        ]);

        $paidOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'paid',
        ]);

        $pending = Order::pending()->get();

        $this->assertTrue($pending->contains($pendingOrder));
        $this->assertFalse($pending->contains($paidOrder));
    }

    /**
     * Test paid orders scope
     */
    public function test_paid_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'pending',
        ]);

        $paidOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'paid',
        ]);

        $paid = Order::paid()->get();

        $this->assertTrue($paid->contains($paidOrder));
    }

    /**
     * Test shipped orders scope
     */
    public function test_shipped_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $shippedOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'shipped',
        ]);

        $shipped = Order::shipped()->get();

        $this->assertTrue($shipped->contains($shippedOrder));
    }

    /**
     * Test delivered orders scope
     */
    public function test_delivered_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $deliveredOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'status' => 'delivered',
        ]);

        $delivered = Order::delivered()->get();

        $this->assertTrue($delivered->contains($deliveredOrder));
    }

    /**
     * Test bid orders scope
     */
    public function test_bid_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
        ]);

        $bidOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'bid_id' => $bid->id,
        ]);

        $regularOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'bid_id' => null,
        ]);

        $bidOrders = Order::bidOrders()->get();

        $this->assertTrue($bidOrders->contains($bidOrder));
        $this->assertFalse($bidOrders->contains($regularOrder));
    }

    /**
     * Test regular orders scope
     */
    public function test_regular_orders_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
        ]);

        $bidOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'bid_id' => $bid->id,
        ]);

        $regularOrder = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'bid_id' => null,
        ]);

        $regularOrders = Order::regularOrders()->get();

        $this->assertTrue($regularOrders->contains($regularOrder));
        $this->assertFalse($regularOrders->contains($bidOrder));
    }

    /**
     * Test order amount is decimal
     */
    public function test_order_amount_is_decimal()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'amount' => 150.99,
        ]);

        $this->assertEquals(150.99, (float) $order->amount);
    }

    /**
     * Test order delivery cost is decimal
     */
    public function test_order_delivery_cost_is_decimal()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'delivery_cost' => 250.50,
        ]);

        $this->assertEquals(250.50, (float) $order->delivery_cost);
    }

    /**
     * Test order timestamps
     */
    public function test_order_timestamps()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $order = Order::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
        ]);

        $this->assertNotNull($order->created_at);
        $this->assertNotNull($order->updated_at);
    }

    /**
     * Test order fillable attributes
     */
    public function test_order_fillable_attributes()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $attributes = [
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'amount' => 500.00,
            'quantity' => 5,
            'status' => 'pending',
            'delivery_address' => '123 Main Street',
            'delivery_lat' => -1.2864,
            'delivery_lng' => 36.8172,
        ];

        $order = Order::create($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $order->$key);
        }
    }
}
