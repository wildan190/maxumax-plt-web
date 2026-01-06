<?php

namespace App\Http\Controllers;

use App\Models\Preorder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreorderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Preorder::query()->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $preorders = $query->paginate(30)->withQueryString();

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')]
        ));

        return view('admin.preorders.index', compact('preorders'));
    }

    public function markPaid(Request $request, $id)
    {
        $pre = Preorder::findOrFail($id);
        $old = $pre->status;
        $pre->status = 'paid';
        $pre->save();

        // decrement stock if product exists and stock available
        $note = 'Marked as paid by admin';
        if ($pre->product) {
            $product = $pre->product;
            if ($product->stock >= $pre->quantity && $product->stock > 0) {
                $product->stock = max(0, $product->stock - $pre->quantity);
                $product->save();
                $note .= '. Stock decremented by ' . $pre->quantity . ' (remaining: ' . $product->stock . ')';
            } else {
                $note .= '. Product stock insufficient or zero; no decrement performed.';
            }
        }

        \App\Models\PreorderHistory::create([
            'preorder_id' => $pre->id,
            'old_status' => $old,
            'new_status' => 'paid',
            'note' => $note,
        ]);

        return back()->with('status', 'Marked as paid');
    }

    public function destroy($id)
    {
        $preorder = Preorder::findOrFail($id);
        // record deletion in history before deleting
        \App\Models\PreorderHistory::create([
            'preorder_id' => $preorder->id,
            'old_status' => $preorder->status,
            'new_status' => 'deleted',
            'note' => 'Deleted by admin',
        ]);

        $preorder->delete();

        return back()->with('status', 'Preorder deleted successfully');
    }

    public function show(Preorder $preorder)
    {
        $preorder->load('product','histories');

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Preorders', 'url' => route('admin.preorders.index')],
            ['label' => '#'.$preorder->id, 'url' => route('admin.preorders.show', $preorder)]
        ));

        return view('admin.preorders.show', compact('preorder'));
    }

    public function history(Request $request)
    {
        $type = $request->query('type', 'all'); // all | preorder | order
        $query = Preorder::query()->with(['product', 'histories'])->orderByDesc('created_at');

        if ($type === 'preorder') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            });
        } elseif ($type === 'order') {
            $query->whereHas('product', function ($q) {
                $q->where('available_for_preorder', false)->where('is_active', true);
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(30)->withQueryString();

        $counts = [
            'all' => Preorder::count(),
            'preorder' => Preorder::whereHas('product', fn($q) => $q->where('available_for_preorder', true))->count(),
            'order' => Preorder::whereHas('product', fn($q) => $q->where('available_for_preorder', false)->where('is_active', true))->count(),
        ];

        page_breadcrumbs(breadcrumbs(
            ['label' => 'Orders History', 'url' => route('admin.orders.history')],
            ['label' => ucfirst($type), 'url' => route('admin.orders.history', ['type' => $type])]
        ));

        return view('admin.orders.history', compact('orders', 'type', 'counts'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'preorders_'.date('Ymd_His').'.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id','name','email','phone','jersey_type','size','long_sleeve','nameset','nameset_text','quantity','unit_price','total_amount','status','created_at']);

            Preorder::orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->nameset ? '1' : '0',
                        $r->nameset_text,
                        $r->quantity,
                        number_format($r->unit_price,2,'.',''),
                        number_format($r->total_amount,2,'.',''),
                        $r->status,
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
