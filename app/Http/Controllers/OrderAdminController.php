<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        // Only show orders (available_for_preorder = false, is_active = true)
        $query = Preorder::query()
            ->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                  ->where('is_active', true);
            })
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $orders = $query->paginate(30)->withQueryString();

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')]
        ));

        return view('admin.orders.index', compact('orders'));
    }

    public function markPaid(Request $request, Preorder $order)
    {
        if ($order->status !== 'confirmed') {
            return back()->with('error', 'Order harus dikonfirmasi admin terlebih dahulu sebelum ditandai sebagai paid');
        }
        $old = $order->status;
        $order->status = 'paid';
        $order->save();

        // decrement stock if product exists and stock available
        $note = 'Marked as paid by admin';
        if ($order->product) {
            $product = $order->product;
            if ($product->stock >= $order->quantity && $product->stock > 0) {
                $product->stock = max(0, $product->stock - $order->quantity);
                $product->save();
                $note .= '. Stock decremented by '.$order->quantity.' (remaining: '.$product->stock.')';
            } else {
                $note .= '. Product stock insufficient or zero; no decrement performed.';
            }
        }

        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $old,
            'new_status' => 'paid',
            'note' => $note,
        ]);

        return back()->with('status', 'Marked as paid');
    }

    public function confirm(Request $request, Preorder $order)
    {
        $old = $order->status;

        if ($order->status === 'paid') {
            return back()->with('status', 'Order sudah paid');
        }

        if ($order->status === 'confirmed') {
            return back()->with('status', 'Order sudah dikonfirmasi');
        }

        $order->status = 'confirmed';
        $order->save();

        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $old,
            'new_status' => 'confirmed',
            'note' => 'Confirmed by admin',
        ]);

        return back()->with('status', 'Order dikonfirmasi');
    }

    public function destroy(Preorder $order)
    {
        // record deletion in history before deleting
        \App\Models\PreorderHistory::create([
            'preorder_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => 'deleted',
            'note' => 'Deleted by admin',
        ]);

        $order->delete();

        return back()->with('status', 'Order deleted successfully');
    }

    public function show(Preorder $order)
    {
        $order->load('product', 'histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => '#'.$order->order_number, 'url' => route('admin.orders.show', $order)]
        ));

        return view('admin.orders.show', compact('order'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'orders_'.date('Ymd_His').'.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'order_number', 'name', 'email', 'phone', 'address', 'jersey_type', 'size', 'long_sleeve', 'quantity', 'unit_price', 'total_amount', 'currency', 'status', 'notes', 'created_at']);

            Preorder::whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)
                  ->where('is_active', true);
            })->orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->order_number,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->address,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->quantity,
                        number_format($r->unit_price, 2, '.', ''),
                        number_format($r->total_amount, 2, '.', ''),
                        $r->currency,
                        $r->status,
                        $r->notes,
                        $r->created_at,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }
}
