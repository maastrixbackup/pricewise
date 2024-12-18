<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealSavedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailId;
    public $postalCode;
    public $houseNo;
    public $address;
    public $validTill;
    public $dealUrl;

    /**
     * Create a new message instance.
     *
     * @param array $dealDetails
     */
    public function __construct($emailId, $postalCode, $houseNo, $address, $validTill, $dealUrl)
    {
        $this->emailId = $emailId;
        $this->postalCode = $postalCode;
        $this->houseNo = $houseNo;
        $this->address = $address;
        $this->validTill = $validTill;
        $this->dealUrl = $dealUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.exclusive_deal_saved')
            ->subject('Exclusive Deal Saved Successfully')
            ->with([
                'emailId' => $this->emailId,
                'postalCode'=>$this->postalCode,
                'houseNo'=>$this->houseNo,
                'address'=>$this->address,
                'validTill'=>$this->validTill,
                'dealsUrl'=>$this->dealUrl
            ]);
    }
}
