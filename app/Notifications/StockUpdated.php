<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockUpdated extends Notification
{
    use Queueable;

    protected $product;
    protected $variant;

    public function __construct($product, $variant = null)
    {
        $this->product = $product;
        $this->variant = $variant;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $itemName = $this->product->name . ($this->variant ? ' - ' . $this->variant->name : '');
        return [
            'type' => 'stock_update',
            'product_id' => $this->product->id,
            'title' => 'Stok Tersedia!',
            'message' => 'Hore! Produk incaranmu (' . $itemName . ') kini sudah kembali tersedia. Jangan sampai kehabisan lagi!',
            'icon' => 'fa-box-open',
            'color' => 'text-success'
        ];
    }
}
