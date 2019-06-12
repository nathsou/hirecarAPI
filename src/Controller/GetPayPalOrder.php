<?php

namespace App\Controller;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\PayPalPayment;

class GetPayPalOrder extends MediaTypeController
{

    protected $spec_name = "verify_payment";
    /**
     * verify_payment
     * @Route("/verify_payment")
     * condition="context.getMethod() in ['POST']
     */
    public function verify_payment(Request $request)
    {
        $paypal = new PayPAlPayment();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . '/../../.env');
        $paypal->setClientID($_ENV["clientId"]);
        $paypal->setSecret($_ENV['secretId']);
        $paypal->setSandboxMode($_ENV['mode']);
        if ($content = $request->getContent()) {
            $payment = json_decode($content, true);
            $payment_ID = $payment['paymentID'];
            $payer_id = $payment['payerID'];
            $response = $paypal->executePayment($payment_ID, $payer_id);
            $response = json_decode($response);
            //TODO complete the action when the payment is accepted bisous
            return $this->json($response);
        }

        return $this->handleResponse($request, [
            "msg" => "invalid payment received",
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }
}
