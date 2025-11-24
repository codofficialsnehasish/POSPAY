<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    public function sendSMS($phone_number, $link)
    {
        $url = "https://sms.cell24x7.in/smsReceiver/sendSMS";

        // Your message
        $msg_body = "Thanks for shopping at pospay. To view invoice, offers, give feedback, click ".$link." . Team Agiltas";

        try {
            $response = Http::asForm()->post($url, [
                'user'   => 'agiltas',
                'pwd'    => 'apiagiltas',
                'sender' => 'AGILTS',
                'mobile' => $phone_number,
                'msg'    => $msg_body,
                'mt'     => 0,
            ]);

            if ($response->successful()) {
                return $response->body(); // API returns plain text
            } else {
                return $response->body(); // or ->status()
            }
        } catch (\Exception $e) {
            return false;
        }
    }

}
