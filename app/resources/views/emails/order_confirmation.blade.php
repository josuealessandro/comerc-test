<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pedido</title>
</head>
<body>
<h1>Pastelaria do Josué</h1>
<p>Obrigado por fazer o seu pedido conosco!</p>

<h2>Detalhes do Pedido:</h2>
<p>ID do Pedido: {{ $order->id }}</p>
<p>Cliente: {{ $client->name }}</p>

<h3>Produtos:</h3>
<ul>
    @foreach ($products as $product)
        <li>{{ $product->name }} - R$ {{ number_format($product->price_cents / 100, 2) }}</li>
    @endforeach
</ul>

<p>Total do Pedido: R$ {{ number_format($order->total_price_cents / 100, 2) }}</p>

<p>Obrigado por escolher a Pastelaria do Josué. Aguardamos sua visita!</p>
</body>
</html>
