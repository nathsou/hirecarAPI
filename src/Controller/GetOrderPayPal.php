<?php

namespace App\Controller;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PayPalPayment;


class GetOrderPayPal extends RequestDBController
{
    /**
     * verify_paiement
     * @Route("/verify_paiement")
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
            $paiement = [];
            $paiement = json_decode($content, true);
            $payment_ID = $paiement['paymentID'];
            $payer_id = $paiement['payerID'];
            $response = $paypal->executePayment($payment_ID, $payer_id);
            $response = json_decode($response);
            //TODO complete the action when the paiement is accepted bisous
            return $this->json($response);
        }
        return $this->mediaTypeConverter($request, ["error" => "given Paiment unvalidle"]);
    }
}
