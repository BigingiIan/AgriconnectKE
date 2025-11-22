<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_buyers' => User::where('role', 'buyer')->count(),
            'total_drivers' => User::where('role', 'driver')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
        ];

        // Sales Chart Data (Filtered)
        $salesData = Order::where('status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesChart = [
            'labels' => $salesData->pluck('date'),
            'data' => $salesData->pluck('total'),
        ];

        // User Growth Chart Data (Filtered)
        $userGrowthData = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $userGrowthChart = [
            'labels' => $userGrowthData->pluck('date'),
            'data' => $userGrowthData->pluck('count'),
        ];

        // Top Products (Filtered)
        $topProductsData = Order::where('orders.status', 'paid')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(orders.quantity) as total_sold')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topProductsChart = [
            'labels' => $topProductsData->pluck('name'),
            'data' => $topProductsData->pluck('total_sold'),
        ];

        // Delivery Central Stats
        $deliveryStats = [
            'active_drivers' => User::where('role', 'driver')->where('is_available', true)->count(),
            'ongoing_deliveries' => Order::where('status', 'shipped')->count(),
            'active_deliveries_list' => Order::where('status', 'shipped')
                ->with(['driver', 'buyer'])
                ->latest()
                ->take(10)
                ->get()
        ];

        return view('admin.dashboard', compact('stats', 'salesChart', 'userGrowthChart', 'topProductsChart', 'deliveryStats', 'startDate', 'endDate'));
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function products()
    {
        return view('admin.products');
    }

    public function orders()
    {
        return view('admin.orders');
    }

    public function trackDrivers()
    {
        $drivers = User::where('role', 'driver')
            ->with('driverLocation')
            ->get();
            
        return view('admin.track-drivers', compact('drivers'));
    }

    public function systemStats()
    {
        return view('admin.system-stats');
    }
}