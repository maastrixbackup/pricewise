<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvailableProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $productName; // Change to $product_id if that is the correct variable
    public $productUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $productName, $productUrl)
    {
        $this->userName = $userName;
        $this->productName = $productName;
        $this->productUrl = $productUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.available_product_notification')
            ->subject('Product Availability Notification')
            ->with([
                'userName' => $this->userName,
                'productName' => $this->productName,
                'productUrl' => $this->productUrl,
            ]);
    }
}
