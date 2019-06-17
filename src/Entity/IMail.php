<?php


namespace App\Entity;


interface IMail
{
    public function sendMessage($subject, $to, $toName, $messageBody);
}