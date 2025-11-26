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

       // $query = Order_header::query()->with('order_details'); // 👈 โหลดรายละเอียดสินค้า (order_details) มาพร้อมกัน;
        $query = Order_header::query()->with([
                'order_statuses' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'order_details', // 👈 โหลดรายการสินค้าในใบรับส่งด้วย
            ]);

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


        if ($request->filled('id')) {
            $query->where('id', $request->get('tracking_no'));
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
                'branch'            => $order->branch->name,
                'branch_rec'        => $order->to_branch->name,
                'customer'          => $order->customer->name,
                'customer_rec'      => $order->to_customer->name,

            // 👇 รายการสินค้าในใบส่งแต่ละใบ
            'items'             => $order->order_details->map(function ($d) {
                return [

                    'product_name' => $d->product->name ?? null,
                    'unit'         => $d->unit->name ?? null,
                    'price'        => (float) ($d->price ?? 0),
                    'amount'       => (float) ($d->amount ?? 0),
                    'remark'       => $d->remark ?? null,
                ];
            })->values(),
             // ---------- ประวัติสถานะการจัดส่ง (order_statuses) ----------
            'history'           => $order->order_statuses->map(function ($row) {
                return [
                    'status'      => $row->status,
                    'changed_at'  => optional($row->created_at)->toIso8601String(),
                ];
            })->values(),
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

         $order = order_header::with([
                'order_statuses' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'order_details', // 👈 โหลดรายการสินค้าในใบรับส่งด้วย
            ])
            ->when($customerId, function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            })
            ->where('id', $trackingNo)
            ->firstOrFail();


        $data = [
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
            'remark'            => $order->remark,


            // ---------- รายการสินค้าในใบรับส่ง (order_details) ----------
            'items'             => $order->order_details->map(function ($d) {
                return [
                    'id'           => $d->id,
                    'product_name' => $d->product->name ?? null,
                    'unit'         => $d->unit->name ?? null,
                    'price'        => (float) ($d->price ?? 0),
                    'amount'       => (float) ($d->amount ?? 0),
                    'remark'       => $d->remark ?? null,
                ];
            })->values(),
            // ---------- ประวัติสถานะการจัดส่ง (order_statuses) ----------
            'history'           => $order->order_statuses->map(function ($row) {
                return [
                    'status'      => $row->status,
                    'changed_at'  => optional($row->created_at)->toIso8601String(),
                ];
            })->values(),
        ];

        return response()->json([
            'data' => $data,
        ]);
    }
}
