<?php

namespace App\Notifications;

use App\Models\Branchrec_order;
use App\Models\Delivery_detail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderPaid extends Notification implements ShouldQueue
{
    use Queueable;
    protected $touser;
    protected $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $touser, Branchrec_order $order)
    {
        $this->touser = $touser;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return \Mirovit\NovaNotifications\Notification::make()
            ->title('แจ้งการรับชำระเงินปลายทาง')
            ->info('แจ้งการรับชำระเงินปลายทาง')
            ->subtitle($this->order->order_header_no . ' ยืนยันการจัดส่งและรับชำระค่าขนส่งแล้ว')
            ->routeDetail('order_headers', $this->order->id)
            ->toArray();
    }
}
