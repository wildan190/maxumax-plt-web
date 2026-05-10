<?php

namespace App\Services\Admin;

use App\Models\Preorder;
use App\Models\PreorderHistory;
use App\Services\DelyvaService;
use App\Services\EasyParcelService;

class PreorderAutoShipmentAfterPaidService
{
    /**
     * Attempt automatic courier booking after mark-paid. Reloads preorder if tracking updated.
     *
     * @return ?string Extra status message fragment (shown after "Marked as paid")
     */
    public function run(Preorder $preorder): ?string
    {
        if (!empty($preorder->tracking_number) || empty($preorder->shipping_service_id)) {
            return null;
        }

        $autoBookingMsg = null;

        try {
            $weight = max(1, (int) $preorder->quantity * 0.5);
            $addr = (string) ($preorder->address ?? '');
            $segments = preg_split('/,\s*/', $addr);
            $postal = null;
            $state = null;
            $city = null;
            foreach ($segments as $i => $seg) {
                if (stripos($seg, 'Postal ') === 0) {
                    $postal = trim(substr($seg, 7));
                    $state = $segments[$i - 1] ?? null;
                    $city = $segments[$i - 2] ?? null;
                    break;
                }
            }
            $isDelyva = !empty($preorder->shipping_courier_name) && stripos($preorder->shipping_courier_name, 'delyva') !== false;
            if ($isDelyva) {
                $delyva = new DelyvaService();
                $origin = [
                    'name' => config('app.name'),
                    'address1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                    'postcode' => '88000',
                    'state' => 'Sabah',
                    'city' => 'Kota Kinabalu',
                    'country' => 'MY',
                    'phone' => $preorder->phone ?? '',
                    'email' => $preorder->email ?? null,
                ];
                $destination = [
                    'name' => $preorder->name,
                    'address1' => $preorder->address ?? '',
                    'postcode' => $postal,
                    'state' => $state,
                    'city' => $city,
                    'country' => 'MY',
                    'phone' => $preorder->phone ?? '',
                    'email' => $preorder->email ?? null,
                ];
                $items = [
                    [
                        'name' => 'Jersey',
                        'quantity' => $preorder->quantity,
                        'weight' => ['unit' => 'kg', 'value' => $weight],
                    ]
                ];
                $meta = [
                    'reference' => $preorder->order_number,
                    'cod' => ['amount' => 0, 'currency' => $preorder->currency],
                    'price' => ['amount' => $preorder->shipping_cost ?? 0, 'currency' => $preorder->currency],
                ];
                $created = $delyva->createOrder($origin, $destination, $items, $meta);
                $orderId = $created['data']['id'] ?? null;
                if ($orderId) {
                    $serviceCode = $preorder->shipping_service_id;
                    $delyva->processOrder($orderId, $serviceCode);
                    $details = $delyva->getOrder($orderId);
                    $consignmentNo = $details['data']['consignmentNo'] ?? null;
                    if ($consignmentNo) {
                        $preorder->tracking_number = $consignmentNo;
                        $preorder->shipping_status = 'shipped';
                        $preorder->save();
                        PreorderHistory::create([
                            'preorder_id' => $preorder->id,
                            'old_status' => $preorder->status,
                            'new_status' => $preorder->status,
                            'note' => 'Auto-booked via Delyva. Consignment: ' . $consignmentNo,
                        ]);
                        $autoBookingMsg = 'Auto-booked shipment (Consignment: ' . $consignmentNo . ')';
                    }
                }
            } else {
                $easyParcel = new EasyParcelService();
                $orderData = [
                    'weight' => $weight,
                    'content' => 'Jersey',
                    'value' => $preorder->total_amount,
                    'service_id' => $preorder->shipping_service_id,
                    'order_number' => $preorder->order_number,
                    'pick_name' => config('app.name'),
                    'pick_company' => config('app.name'),
                    'pick_contact' => $preorder->phone ?? '',
                    'pick_mobile' => $preorder->phone ?? '',
                    'pick_addr1' => 'Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens',
                    'pick_code' => '88000',
                    'pick_state' => 'Sabah',
                    'pick_province' => 'Sabah',
                    'pick_country' => 'MY',
                    'send_name' => $preorder->name,
                    'send_contact' => $preorder->phone ?? '',
                    'send_mobile' => $preorder->phone ?? '',
                    'send_addr1' => $preorder->address ?? '',
                    'send_code' => $postal,
                    'send_state' => $state,
                    'send_province' => $state,
                    'send_country' => 'MY',
                    'send_email' => $preorder->email,
                ];
                $result = $easyParcel->submitOrder($orderData);
                if (isset($result['api_status']) && $result['api_status'] === 'Success') {
                    $shipment = $result['result'][0] ?? [];
                    $awb = $shipment['awb'] ?? null;
                    if ($awb) {
                        $preorder->tracking_number = $awb;
                        $preorder->shipping_status = 'shipped';
                        $preorder->save();
                        PreorderHistory::create([
                            'preorder_id' => $preorder->id,
                            'old_status' => $preorder->status,
                            'new_status' => $preorder->status,
                            'note' => 'Auto-booked via EasyParcel. AWB: ' . $awb,
                        ]);
                        $autoBookingMsg = 'Auto-booked shipment (AWB: ' . $awb . ')';
                    } else {
                        $autoBookingMsg = 'Booking success without AWB';
                    }
                } else {
                    $autoBookingMsg = 'Booking failed: ' . json_encode($result);
                }
            }
        } catch (\Throwable $e) {
            $autoBookingMsg = 'Booking error: ' . $e->getMessage();
        }

        return $autoBookingMsg;
    }
}
