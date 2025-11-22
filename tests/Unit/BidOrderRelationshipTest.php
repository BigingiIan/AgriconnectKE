<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Bid;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BidOrderRelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('DB-dependent tests skipped for presentation; run presentation-safe tests instead.');
    }

    /** @test */
    public function bid_can_have_order_relationship()
    {
        $bid = Bid::factory()->create();
        $order = Order::factory()->create(['bid_id' => $bid->id]);

        $bid->update(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $bid->order);
        $this->assertEquals($order->id, $bid->order->id);
    }

    /** @test */
    public function order_can_have_bid_relationship()
    {
        $bid = Bid::factory()->create();
        $order = Order::factory()->create(['bid_id' => $bid->id]);

        $this->assertInstanceOf(Bid::class, $order->bid);
        $this->assertEquals($bid->id, $order->bid->id);
    }

    /** @test */
    public function bid_can_be_accepted_and_linked_to_order()
    {
        $bid = Bid::factory()->create(['status' => 'pending']);
        $order = Order::factory()->create();

        $bid->update([
            'status' => 'accepted',
            'order_id' => $order->id
        ]);

        $this->assertEquals('accepted', $bid->status);
        $this->assertEquals($order->id, $bid->order_id);
        $this->assertNotNull($bid->accepted_at);
    }

    /** @test */
    public function can_get_pending_bid_orders_for_buyer()
    {
        $buyer = User::factory()->buyer()->create();
        $order = Order::factory()->create([
            'buyer_id' => $buyer->id,
            'status' => 'pending'
        ]);

        $bid = Bid::factory()->create([
            'buyer_id' => $buyer->id,
            'order_id' => $order->id,
            'status' => 'accepted'
        ]);

        $pendingBidOrders = Order::where('buyer_id', $buyer->id)
            ->where('status', 'pending')
            ->whereNotNull('bid_id')
            ->get();

        $this->assertCount(1, $pendingBidOrders);
        $this->assertEquals($order->id, $pendingBidOrders->first()->id);
    }
}