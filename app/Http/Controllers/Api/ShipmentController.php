<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order_header;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * GET /api/v1/shipments
     * ดึงรายการใบรับส่งสินค้าตามเงื่อนไข + แบ่งหน้า
     */
    public function index(Request $request)
    {
        // มาจาก ApiClientAuth
        $customerId = $request->attributes->get('customer_id');

        $query = Order_header::query();

        if (! is_null($customerId)) {
            $query->where('customer_id', $customerId);
        }

        // filters ต่าง ๆ จาก query string
        if ($request->filled('from_date')) {
            $query->whereDate('order_header_date', '>=', $request->get('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('order_header_date', '<=', $request->get('to_date'));
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->get('order_status'));
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->get('order_type'));
        }

        if ($request->filled('tracking_no')) {
            $query->where('tracking_no', $request->get('tracking_no'));
        }

        if ($request->filled('order_header_no')) {
            $query->where('order_header_no', $request->get('order_header_no'));
        }

        $perPage   = (int) $request->get('per_page', 50);
        $shipments = $query->orderBy('order_header_date', 'desc')->paginate($perPage);

        $data = $shipments->getCollection()->map(function (Order_header $order) {
            return [
                'tracking_no'       => $order->id,
                'order_header_no'   => $order->order_header_no,
                'order_header_date' => optional($order->order_header_date)->format('Y-m-d'),
                'order_status'      => $order->order_status,
                'order_type'        => $order->order_type,
                'order_amount'      => (float) $order->order_amount,
                'branch_id'         => $order->branch->name,
                'branch_rec_id'     => $order->to_branch->name,
                'customer'          => $order->customer->name,
                'customer_rec'      => $order->to_customer->name,

            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'per_page'     => $shipments->perPage(),
                'total'        => $shipments->total(),
                'last_page'    => $shipments->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/shipments/{tracking_no}
     * ดึงรายละเอียดใบรับส่งสินค้า 1 ใบ พร้อม history สถานะ
     */
    public function show(Request $request, string $trackingNo)
    {
        $customerId = $request->attributes->get('customer_id');

        $order = Order_header::with(['order_statuses' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->when($customerId, function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            })
            ->where('id', $trackingNo)
            ->firstOrFail();

        // map history จาก order_statuses
        $history = $order->order_statuses->map(function ($status) {
            return [
                'status'     => $status->status,
                'changed_at' => optional($status->created_at)->toIso8601String(),
                'user_id'    => $status->user_id,
            ];
        });

        $responseData = [
            'tracking_no'       => $order->id,
            'order_header_no'   => $order->order_header_no,
            'order_header_date' => optional($order->order_header_date)->format('Y-m-d'),
            'order_status'      => $order->order_status,
            'order_type'        => $order->order_type,
            'order_amount'      => (float) $order->order_amount,
            'branch'            => $order->branch->name,
            'branch_rec'        => $order->to_branch->name,
            'customer'          => $order->customer->name,
            'customer_rec'      => $order->to_customer->name,


            'history' => $history,
        ];

        return response()->json([
            'data' => $responseData,
        ]);
    }
}
