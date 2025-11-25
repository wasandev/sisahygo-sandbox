<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiClientAuth
{
    /**
     * ตรวจสอบ X-Api-Key → หา ApiClient → ตรวจ is_active / allowed_ips
     * แล้วผูก customer_id เข้ากับ request
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1) อ่าน API Key จาก Header
        $apiKey = $request->header('X-Api-Key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => [
                    'code'    => 'API_KEY_MISSING',
                    'message' => 'กรุณาส่ง X-Api-Key ใน Header ทุกครั้งที่เรียกใช้ API',
                    'status'  => 401,
                ],
            ], 401);
        }

        // 2) แปลงเป็น hash เพื่อเทียบกับฐานข้อมูล
        $hashedKey = hash('sha256', $apiKey);

        /** @var \App\Models\ApiClient|null $client */
        $client = ApiClient::query()
            ->where('api_key', $hashedKey)
            ->where('is_active', true)
            ->first();

        if (! $client) {
            return response()->json([
                'error' => [
                    'code'    => 'API_KEY_INVALID',
                    'message' => 'API Key ไม่ถูกต้อง หรือถูกระงับการใช้งาน',
                    'status'  => 401,
                ],
            ], 401);
        }

        // 3) ตรวจ IP (ถ้ากำหนด allowed_ips)
        if (! empty($client->allowed_ips)) {
            $allowedIps = array_filter(array_map('trim', explode(',', $client->allowed_ips)));
            $requestIp  = $request->ip();

            if (! in_array($requestIp, $allowedIps, true)) {
                return response()->json([
                    'error' => [
                        'code'    => 'IP_NOT_ALLOWED',
                        'message' => 'ที่อยู่ IP นี้ไม่ได้รับอนุญาตให้ใช้งาน API Key นี้'.$requestIp,
                        'status'  => 403,
                    ],
                ], 403);
            }
        }

        // 4) แนบข้อมูล client / customer_id เข้าไปใน request
        $request->attributes->set('api_client', $client);
        $request->attributes->set('customer_id', $client->customer_id);

        // 5) ไปต่อ
        return $next($request);
    }
}
