<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $productName;
    public $email;

    public function __construct($userName, $productName, $email)
    {
        $this->userName = $userName;
        $this->productName = $productName;
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('emails.admin_product_notification')
                    ->subject('New Product Notification Request')
                    ->with([
                        'userName' => $this->userName,
                        'productName' => $this->productName,
                        'email' => $this->email,
                        'adminPanelLink' => route('admin.notify_products'),
                    ]);
    }
}
