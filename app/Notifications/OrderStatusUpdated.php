<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    public function __construct($order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'order_status',
            'order_id' => $this->order->id,
            'invoice' => $this->order->invoice,
            'status' => $this->newStatus,
            'title' => 'Status Pesanan Diperbarui',
            'message' => 'Pesanan Anda (' . $this->order->invoice . ') kini berstatus: ' . ucwords(str_replace('_', ' ', $this->newStatus)),
            'icon' => 'fa-box',
            'color' => 'text-primary'
        ];
    }
}
