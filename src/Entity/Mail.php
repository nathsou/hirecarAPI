<?php

namespace App\Entity;

use Exception;
use Google_Client;
use Google_Service_Gmail;


class Mail
{
    private $client;
    private $serviceGoogle;
    private $mailer;
    private $message;

    public function __construct(){
        $this->client = $this->getClient();
        $this->serviceGoogle = new \Google_Service_Gmail($this->client);
        $this->mailer = $this->serviceGoogle->users_messages;
    }

    private function getClient(){
        $client = new Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
        $client->setAuthConfig(__DIR__.'/../../credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $tokenPath = __DIR__.'/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
            return $client;
        }
        else throw new Exception('connection to the API is impossible ' .$tokenPath);
    }

    public function sendMessage($subject,$to,$toName,$messageBody){
        $this->message = (new \Swift_Message($subject))
            ->setFrom('hirecarnoreply@gmail.com')
            ->setTo([$to=>$toName])
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setBody($messageBody);
        $encodedMessage=(new \Swift_Mime_ContentEncoder_Base64ContentEncoder())->encodeString($this->message);
        $this->message = new \Google_Service_Gmail_Message();
        $this->message->setRaw($encodedMessage);
       $this->mailer->send('me',$this->message);
    }
}