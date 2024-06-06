<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

class SuccessPage extends Component
{
    #[Title('Success page')]

    public function render()
    {
        $order_latest = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        header('Content-type: text/html; charset=utf-8');

        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'; //Put your secret key in there

        if (!empty($_GET)) {
            $partnerCode = $_GET["partnerCode"];
            $accessKey = $_GET["accessKey"];
            $orderId = $_GET["orderId"];
            $localMessage = $_GET["localMessage"];
            $message = $_GET["message"];
            $transId = $_GET["transId"];
            $orderInfo = $_GET["orderInfo"];
            $amount = $_GET["amount"];
            $errorCode = $_GET["errorCode"];
            $responseTime = $_GET["responseTime"];
            $requestId = $_GET["requestId"];
            $extraData = $_GET["extraData"];
            $payType = $_GET["payType"];
            $orderType = $_GET["orderType"];
            $extraData = $_GET["extraData"];
            $m2signature = $_GET["signature"]; //MoMo signature

            //Checksum
            $rawHash = "partnerCode=" . $partnerCode . "&accessKey=" . $accessKey . "&requestId=" . $requestId . "&amount=" . $amount . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
                "&orderType=" . $orderType . "&transId=" . $transId . "&message=" . $message . "&localMessage=" . $localMessage . "&responseTime=" . $responseTime . "&errorCode=" . $errorCode .
                "&payType=" . $payType . "&extraData=" . $extraData;

            $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($m2signature == $partnerSignature) {
                if ($errorCode == '0') {
                    $order_latest->payment_status = 'paid';
                    $order_latest->save();
                } else {
                    $order_latest->payment_status = 'failed';
                    $order_latest->save();
                    $this->redirect('/cancel');
                }
            } else {
                $this->redirect('/cancel');

            }
        }

        return view('livewire.success-page', [
            'order_latest' => $order_latest,
        ]);
    }
}
