<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Items;

class Item extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $items;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        switch ($this->items->email_type) {
            case 'itemVerification':
                $subject = "Item Verification";
                /* $body = [
                    "Your Item ".$this->items->sku." was sucessfuly verified!"
                ]; */
                $body = [
                    "The product has been successfully verified."
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            case 'itemDeclined':
                $subject = "Item Verification";
                /* $body = [
                    "Sorry your Item ".$this->items->sku." was declined!",
                ]; */
                $body = [
                    "The product has been declined and cannot be sold on Betsy."
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            case 'ItemResubmit':
                $subject = "Item Verification";
                $body = [
                    "Sorry your Item ".$this->items->sku." was declined",
                    "Here's the reason for item declined",
                    "* List of declined reasons here *"
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            case 'itemSuspended':
                $subject = "Item Suspension";
                $body = [
                    "Sorry your Item ".$this->items->sku." was suspended",
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            case 'itemDisabled':
                $subject = "Item Disabled";
                $body = [
                    "Sorry your Item ".$this->items->sku." was disabled",
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            case 'itemReactivate':
                $subject = "Item Reactivation";
                $body = [
                    "Your Item ".$this->items->sku." was Reactivated",
                ];

                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                break;
            
            default:
                # code...
                break;
        }

        

        return $this->subject($subject)
                    ->markdown("mail.item")
                    ->with([
                        'body' => $body,
                        "regards" => $regards
                    ]);
    }
}
