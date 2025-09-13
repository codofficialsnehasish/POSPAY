<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    public function sendSMS($phone_number, $otp = 0)
    {
        $url = 'https://msg.myctrlbox.com/API/WebSMS/Http/v2.3.6/api.php';

        try {
            $response = Http::get($url, [
                'username' => 'SAVEKART',
                'api_key' => '5e21da7302d31fc78b8ab4358bbba76b',
                'sender' => 'SVKART',
                'dlt_template' => '1707160741710488132',
                'dlt_principal' => '1701160656747132020',
                'to' => $phone_number,
                'message' => 'Your OTP is '.$otp.'. Please do not share this OTP with anyone. Thank you, Savekart.',
            ]);

            if ($response->status() == 200) {
                return $response;
            } else {
                // return "Failed to send SMS. Status code: " . $response->status();
                return $response;
            }
        } catch (\Exception $e) {
            // return "An error occurred while sending the SMS: " . $e->getMessage();
            return false;
        }
    }
}
