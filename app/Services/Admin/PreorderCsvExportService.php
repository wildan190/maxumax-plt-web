<?php

namespace App\Services\Admin;

use App\Models\Preorder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreorderCsvExportService
{
    public function streamRetailOrders(): StreamedResponse
    {
        $fileName = 'orders_' . date('Ymd_His') . '.csv';

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
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    public function streamPreorderOnly(): StreamedResponse
    {
        $fileName = 'preorders_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'name', 'email', 'phone', 'address', 'jersey_type', 'size', 'long_sleeve', 'nameset', 'nameset_text', 'quantity', 'unit_price', 'shipping_courier', 'shipping_service', 'shipping_cost', 'tracking_number', 'total_amount', 'status', 'created_at']);

            Preorder::whereHas('product', function ($q) {
                $q->where('available_for_preorder', true);
            })->orderByDesc('created_at')->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->name,
                        $r->email,
                        $r->phone,
                        $r->address,
                        $r->jersey_type,
                        $r->size,
                        $r->long_sleeve ? '1' : '0',
                        $r->nameset ? '1' : '0',
                        $r->nameset_text,
                        $r->quantity,
                        number_format($r->unit_price, 2, '.', ''),
                        $r->shipping_courier_name,
                        $r->shipping_service_name,
                        number_format($r->shipping_cost, 2, '.', ''),
                        $r->tracking_number,
                        number_format($r->total_amount, 2, '.', ''),
                        $r->status,
                        $r->created_at,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
