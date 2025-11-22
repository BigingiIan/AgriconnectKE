<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\User;
use App\Models\Bid;
use App\Models\Order;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Test product can be created with required fields
     */
    public function test_product_can_be_created()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'name' => 'Tomatoes',
            'price' => 50.00,
        ]);

        $this->assertDatabaseHas('products', [
            'farmer_id' => $farmer->id,
            'name' => 'Tomatoes',
            'price' => 50.00,
        ]);
    }

    /**
     * Test product belongs to farmer
     */
    public function test_product_belongs_to_farmer()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $this->assertInstanceOf(User::class, $product->farmer);
        $this->assertEquals($farmer->id, $product->farmer->id);
    }

    /**
     * Test product has many bids
     */
    public function test_product_has_many_bids()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $bids = Bid::factory()->count(3)->create(['product_id' => $product->id]);

        $this->assertEquals(3, $product->bids()->count());
        $this->assertTrue($product->bids->contains($bids[0]));
    }

    /**
     * Test product has many orders
     */
    public function test_product_has_many_orders()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Order::factory()->count(2)->create([
            'product_id' => $product->id,
            'farmer_id' => $farmer->id,
            'buyer_id' => $buyer->id,
        ]);

        $this->assertEquals(2, $product->orders()->count());
    }

    /**
     * Test product available scope
     */
    public function test_product_available_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $availableProduct = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => true,
            'quantity' => 10,
        ]);

        $unavailableProduct = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => false,
            'quantity' => 0,
        ]);

        $available = Product::available()->get();

        $this->assertTrue($available->contains($availableProduct));
        $this->assertFalse($available->contains($unavailableProduct));
    }

    /**
     * Test product with bids scope
     */
    public function test_product_with_bids_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $biddingProduct = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'accepts_bids' => true,
        ]);

        $nonBiddingProduct = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'accepts_bids' => false,
        ]);

        $biddable = Product::withBids()->get();

        $this->assertTrue($biddable->contains($biddingProduct));
        $this->assertFalse($biddable->contains($nonBiddingProduct));
    }

    /**
     * Test product pending bids
     */
    public function test_product_pending_bids()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Bid::factory()->create(['product_id' => $product->id, 'status' => 'pending']);
        Bid::factory()->create(['product_id' => $product->id, 'status' => 'pending']);
        Bid::factory()->create(['product_id' => $product->id, 'status' => 'accepted']);

        $pendingBids = $product->pendingBids()->get();

        $this->assertEquals(2, $pendingBids->count());
    }

    /**
     * Test product accepted bids
     */
    public function test_product_accepted_bids()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Bid::factory()->create(['product_id' => $product->id, 'status' => 'pending']);
        Bid::factory()->create(['product_id' => $product->id, 'status' => 'accepted']);
        Bid::factory()->create(['product_id' => $product->id, 'status' => 'accepted']);

        $acceptedBids = $product->acceptedBids()->get();

        $this->assertEquals(2, $acceptedBids->count());
    }

    /**
     * Test product image URL attribute
     */
    public function test_product_image_url_attribute()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $productWithImage = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'image' => 'products/tomatoes.jpg',
        ]);

        $productWithoutImage = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'image' => null,
        ]);

        $this->assertStringContainsString('storage/products/tomatoes.jpg', $productWithImage->image_url);
        $this->assertStringContainsString('default-product.jpg', $productWithoutImage->image_url);
    }

    /**
     * Test product filter by search
     */
    public function test_product_filter_by_search()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        Product::factory()->create([
            'farmer_id' => $farmer->id,
            'name' => 'Tomatoes',
            'description' => 'Fresh red tomatoes',
        ]);

        Product::factory()->create([
            'farmer_id' => $farmer->id,
            'name' => 'Potatoes',
            'description' => 'Yellow potatoes',
        ]);

        $results = Product::filter(['search' => 'Tomatoes'])->get();

        $this->assertEquals(1, $results->count());
        $this->assertEquals('Tomatoes', $results->first()->name);
    }

    /**
     * Test product filter by category
     */
    public function test_product_filter_by_category()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        Product::factory()->create([
            'farmer_id' => $farmer->id,
            'category' => 'vegetables',
        ]);

        Product::factory()->create([
            'farmer_id' => $farmer->id,
            'category' => 'fruits',
        ]);

        $vegetables = Product::filter(['category' => 'vegetables'])->get();

        $this->assertEquals(1, $vegetables->count());
        $this->assertEquals('vegetables', $vegetables->first()->category);
    }

    /**
     * Test product price is decimal
     */
    public function test_product_price_is_decimal()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'price' => 99.99,
        ]);

        $this->assertEquals(99.99, (float) $product->price);
    }

    /**
     * Test product fillable attributes
     */
    public function test_product_fillable_attributes()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $attributes = [
            'farmer_id' => $farmer->id,
            'name' => 'Maize',
            'description' => 'High quality maize',
            'price' => 25.50,
            'quantity' => 100,
            'category' => 'grains',
            'is_available' => true,
            'accepts_bids' => true,
        ];

        $product = Product::create($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $product->$key);
        }
    }

    /**
     * Test product availability casting
     */
    public function test_product_availability_casting()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => true,
            'accepts_bids' => true,
        ]);

        $this->assertIsBool($product->is_available);
        $this->assertIsBool($product->accepts_bids);
    }
}
