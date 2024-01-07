<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 18px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 24px;
        }

        h2 {
            font-size: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Cher(e) {{ $user->name }},</h1>

    <h2>Nous sommes ravis de vous informer que votre commande a été confirmée avec succès.</h2>

    <ul>
        <li><strong>Numéro de commande :</strong> {{ $user->id }}</li>
        <li><strong>Date de commande :</strong> {{ $user->order_placed_date }}</li>
        <li><strong>Produits commandés :</strong></li>
    </ul>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Ingrédients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finalresult as $item)
                <tr>
                    <td>{{ $item['ItemName'] }}</td>
                    <td>{{ $item['ItemQty'] }}</td>
                    <td>{{ $item['ItemAmt'] }}</td>
                    <td>
                        <ul>
                            @foreach($item['Ingredients'] as $ingredient)
                                <li>
                                    {{ $ingredient['item_name'] }} - {{ $ingredient['price'] }} €
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Montant total TTC : {{ $user->total_price }} €</h2>

    <h2>Nous sommes impatients de vous servir à nouveau.</h2>

    <p>Cordialement,<br>L'équipe de FoodPlace41.</p>
</body>
</html>