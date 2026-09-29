<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Preorder::query()
            ->selectRaw('
                COALESCE(NULLIF(email, ""), phone) as customer_key,
                MIN(name) as name,
                email,
                phone,
                MIN(currency) as currency,
                COUNT(*) as order_count,
                SUM(CASE WHEN status IN ("paid","confirmed","shipped","delivered","completed") THEN total_amount ELSE 0 END) as total_spend,
                MAX(created_at) as last_order
            ')
            ->groupByRaw('COALESCE(NULLIF(email, ""), phone)')
            ->orderByDesc('last_order');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(20)->withQueryString();

        $totalCustomers  = (int) Preorder::selectRaw('COUNT(DISTINCT COALESCE(NULLIF(email,""), phone)) as cnt')->value('cnt');
        $repeatCustomers = Preorder::selectRaw('COALESCE(NULLIF(email,""), phone) as k, COUNT(*) as c')
            ->groupBy('k')->havingRaw('c > 1')->get()->count();
        $newCustomers = (int) Preorder::selectRaw('COUNT(DISTINCT COALESCE(NULLIF(email,""), phone)) as cnt')
            ->where('created_at', '>=', now()->subDays(30))->value('cnt');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Customers', 'url' => route('admin.customers.index')]
        ));

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'repeatCustomers', 'newCustomers'));
    }

    public function show(Request $request)
    {
        $email = $request->input('email');
        $phone = $request->input('phone');

        $orders = Preorder::with('product')
            ->where(function ($q) use ($email, $phone) {
                if ($email) {
                    $q->where('email', $email);
                }
                if ($phone) {
                    $q->orWhere('phone', $phone);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        if ($orders->isEmpty()) {
            abort(404, 'Customer not found.');
        }

        $first = $orders->first();

        $allOrders = Preorder::where(function ($q) use ($email, $phone) {
            if ($email) {
                $q->where('email', $email);
            }
            if ($phone) {
                $q->orWhere('phone', $phone);
            }
        })->get(['status', 'total_amount', 'currency', 'created_at']);

        $totalSpend = $allOrders
            ->whereIn('status', ['paid', 'confirmed', 'shipped', 'delivered', 'completed'])
            ->sum(fn ($o) => (float) $o->total_amount);

        $customer = [
            'name'           => $first->name,
            'email'          => $first->email,
            'phone'          => $first->phone,
            'address'        => $first->address,
            'currency'       => $first->currency ?? 'MYR',
            'total_orders'   => $allOrders->count(),
            'total_spend'    => $totalSpend,
            'pending_orders' => $allOrders->whereIn('status', ['pending', 'confirmed'])->count(),
            'first_order_at' => $allOrders->min('created_at') ? Carbon::parse($allOrders->min('created_at')) : null,
            'last_order_at'  => $allOrders->max('created_at') ? Carbon::parse($allOrders->max('created_at')) : null,
        ];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Customers', 'url' => route('admin.customers.index')],
            ['label' => $customer['name'], 'url' => '']
        ));

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
