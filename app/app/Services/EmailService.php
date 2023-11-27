<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function sendOrderConfirmationEmail($to, $order, $client, $products)
    {
        Mail::send('emails.order_confirmation', compact('order', 'client', 'products'), function ($message) use ($to) {
            $message->to($to)
                ->subject('Confirmação de Pedido - Pastelaria do Josué');
        });
    }
}
