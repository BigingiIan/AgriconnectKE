<?php

namespace Tests\Unit\Models;

use App\Models\Bid;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Tests\TestCase;

class BidTest extends TestCase
{
    /**
     * Test bid can be created
     */
    public function test_bid_can_be_created()
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'amount' => 100.00,
        ]);

        $this->assertDatabaseHas('bids', [
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'amount' => 100.00,
        ]);
    }

    /**
     * Test bid belongs to product
     */
    public function test_bid_belongs_to_product()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $bid = Bid::factory()->create(['product_id' => $product->id, 'buyer_id' => $buyer->id]);

        $this->assertInstanceOf(Product::class, $bid->product);
        $this->assertEquals($product->id, $bid->product->id);
    }

    /**
     * Test bid belongs to buyer
     */
    public function test_bid_belongs_to_buyer()
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $farmer = User::factory()->create(['role' => 'farmer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);
        $bid = Bid::factory()->create(['product_id' => $product->id, 'buyer_id' => $buyer->id]);

        $this->assertInstanceOf(User::class, $bid->buyer);
        $this->assertEquals($buyer->id, $bid->buyer->id);
    }

    /**
     * Test pending bids scope
     */
    public function test_pending_bids_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $pendingBid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $acceptedBid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'accepted',
        ]);

        $pending = Bid::pending()->get();

        $this->assertTrue($pending->contains($pendingBid));
        $this->assertFalse($pending->contains($acceptedBid));
    }

    /**
     * Test accepted bids scope
     */
    public function test_accepted_bids_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $acceptedBid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'accepted',
        ]);

        $accepted = Bid::accepted()->get();

        $this->assertTrue($accepted->contains($acceptedBid));
        $this->assertEquals(1, $accepted->count());
    }

    /**
     * Test rejected bids scope
     */
    public function test_rejected_bids_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $rejectedBid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'rejected',
        ]);

        $rejected = Bid::rejected()->get();

        $this->assertTrue($rejected->contains($rejectedBid));
    }

    /**
     * Test for product scope
     */
    public function test_for_product_scope()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product1 = Product::factory()->create(['farmer_id' => $farmer->id]);
        $product2 = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bid1 = Bid::factory()->create(['product_id' => $product1->id, 'buyer_id' => $buyer->id]);
        $bid2 = Bid::factory()->create(['product_id' => $product2->id, 'buyer_id' => $buyer->id]);

        $bidsForProduct1 = Bid::forProduct($product1->id)->get();

        $this->assertTrue($bidsForProduct1->contains($bid1));
        $this->assertFalse($bidsForProduct1->contains($bid2));
    }

    /**
     * Test bid can be accepted
     */
    public function test_bid_can_be_accepted()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => true,
        ]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $this->assertTrue($bid->canBeAccepted());
    }

    /**
     * Test bid cannot be accepted if product not available
     */
    public function test_bid_cannot_be_accepted_if_product_unavailable()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => false,
        ]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $this->assertFalse($bid->canBeAccepted());
    }

    /**
     * Test bid cannot be accepted if not pending
     */
    public function test_bid_cannot_be_accepted_if_not_pending()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => true,
        ]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'rejected',
        ]);

        $this->assertFalse($bid->canBeAccepted());
    }

    /**
     * Test bid can be accepted (action)
     */
    public function test_bid_accept_action()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create([
            'farmer_id' => $farmer->id,
            'is_available' => true,
        ]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'status' => 'pending',
        ]);

        $result = $bid->accept();

        $this->assertTrue($result);
        $this->assertEquals('accepted', $bid->fresh()->status);
    }

    /**
     * Test bid amount is decimal
     */
    public function test_bid_amount_is_decimal()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bid = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'amount' => 150.75,
        ]);

        $this->assertEquals(150.75, (float) $bid->amount);
    }

    /**
     * Test bid has been processed
     */
    public function test_bid_has_been_processed()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $bidWithOrder = Bid::factory()->create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
        ]);

        Order::factory()->create([
            'bid_id' => $bidWithOrder->id,
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
        ]);

        $this->assertTrue($bidWithOrder->hasBeenProcessed());
    }

    /**
     * Test bid fillable attributes
     */
    public function test_bid_fillable_attributes()
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $product = Product::factory()->create(['farmer_id' => $farmer->id]);

        $attributes = [
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'amount' => 200.50,
            'status' => 'pending',
        ];

        $bid = Bid::create($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $bid->$key);
        }
    }
}
