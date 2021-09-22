<?php

namespace App\Notifications;


use App\Models\Order_banktransfer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class BankTransfer extends Notification implements ShouldBroadcast

{
    use Queueable;
    protected $touser;
    protected $banktransfer;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $touser, Order_banktransfer $order_banktransfer)
    {
        $this->touser = $touser;
        $this->banktransfer = $order_banktransfer;
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
            ->info('รายการโอนเงินค่าขนส่ง')
            ->subtitle('มีรายการโอนเงินค่าขนส่ง ใบรับส่ง : ' . $this->banktransfer->order_header->order_header_no)
            ->routeDetail('order_banktransfers', $this->banktransfer->id)
            ->toArray();
    }
    public function toBroadcast($notifiable)
    {

        return \Mirovit\NovaNotifications\Notification::make()
            ->info('รายการโอนเงินค่าขนส่ง')
            ->subtitle('มีรายการโอนเงินค่าขนส่ง ใบรับส่ง : ' . $this->banktransfer->order_header->order_header_no)
            ->routeDetail('order_banktransfers', $this->banktransfer->id)
            ->toArray();
    }
}
