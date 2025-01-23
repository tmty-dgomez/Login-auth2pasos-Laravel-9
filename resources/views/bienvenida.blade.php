<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido </title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #FFD359;
        }

        p {
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #FFD359;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #D4AC0D;
        }

        .verification-message {
            margin-top: 20px;
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>¡Hola {{ $user->name }}, bienvenido !</h1>

        <p></p>
        <div class="verification-message">
            ¡Tu cuenta ha sido verificada con éxito!
        </div>
    </div>
</body>

</html>