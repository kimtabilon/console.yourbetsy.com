<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\ResellersProfiles;

class VerifyResellers extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $resellersprofiles;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ResellersProfiles $resellersprofiles)
    {
        $this->resellersprofiles = $resellersprofiles;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "";
        $body = "";
        $show_btn = "";
        $regards = "";
        switch ($this->resellersprofiles->action_type) {
            case 101:
                $subject = "Account Creation";
                $body = [
                    "Thank you for submitting your application! Ito na ang first step para ipakita ang creativity mo to the world!",
                    "We will be reviewing your submission - please give us 5 to 10 working days to get back to you, abangan ang susunod naming email!",
                    "In the meantime,  gawa lang ng gawa, because #makersgonnamake ! We are cheering for you like all the UAAP Pep Squads combined."
                ];
                $regards = [
                    "Korean Heart,",
                    "The Betsy Team"
                ];

                $show_btn = "hide";
                break;
            case 0:
                $subject = "Account Creation - Successful";
                $body = [
                    "Success! You are now a certified Betsy vendor! It’s time to show the world how creative you can be.",
                    "Set up your account and let’s start selling!"
                ];
                $regards = [
                    "Rooting for you,",
                    "The Betsy Team"
                ];
                $show_btn = "show";
                
                break;
            case 2:
                $subject = "Account Creation - Declined";
                $body = [
                    "Oh nose! Pending muna ang vendor application mo.",
                    "Please make sure you have submitted all the requirements so we can do a re-evaluation." ,
                    "Aabangan namin ang submission mo!",
                ];
                $show_btn = "hide";
                $regards = [
                    "Cheers,",
                    "The Betsy Team"
                ];
                break;
            case 3:
                $subject = "Account - Suspension";
                $body = ["We are sorry to inform you that your account has been suspended. Should you want to reactivate your account or submit an appeal, please schedule a call with Betsy Customer Service for more information. "];
                $show_btn = "hide";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 4:
                $subject = "Account - Disable";
                $body = ["We are sorry to inform you that your account has been suspended. Should you want to reactivate your account or submit an appeal, please schedule a call with Betsy Customer Service for more information. "];
                $show_btn = "hide";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 100:
                $subject = "Update Profile Request - Successful";
                $body = ["Your Betsy profile has been successfully updated."];
                $show_btn = "show";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 200:
                $subject = "Update Profile Request - Declined";
                $body = ["Your request to update your Betsy profile has been declined. Please email admin@yourbetsy.com for more details."];
                $show_btn = "hide";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 300:
                $subject = "Account Creation - Successful";
                // $body = ["You are successfuly added as child vendor of ".$this->resellersprofiles->parent];
                $body = ["Success! You have created a secondary user account!"];
                $show_btn = "show";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 400:
                $subject = "Account Creation - Reactivate";
                $body = ["Your account has been reactivated."];
                $show_btn = "show";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
                break;
            case 500:
                $subject = "Your password has been recently changed.";
                $body = ["This is a confirmation that you made recently changes to your password. If you did not process this request kindly contact us immediately."];
                $show_btn = "show";
                $regards = [
                    "Thank you!",
                    "Cheers from the Betsy Team"
                ];
            break;
            default:
                # code...
                break;
        }
        return $this->subject($subject)
                    ->markdown("mail.verify-reseller")
                    ->with([
                        'body' => $body,
                        'show_btn' => $show_btn,
                        "regards" => $regards,
                        /* 'message' => $this */
                    ]);
    }
}
