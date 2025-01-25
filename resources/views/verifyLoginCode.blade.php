<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #FFD359;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        a {
            color: #FFD359;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 2px solid #FFD359;
            transition: border-bottom 0.3s ease;
        }

        a:hover {
            border-bottom: 2px solid #D4AC0D;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
    </style>
    <title>Verificación de Cuenta</title>
</head>

<body>
    <div class="container">
        <h1>Hola {{ $user->name }},</h1>
        <p>¡Gracias por registrarte en nuestro sitio!</p>
        <p>Para completar la verificación de tu cuenta, por favor utiliza el siguiente código:</p>
        <p style="font-size: 20px; font-weight: bold; color: #FFD359;">{{ $code }}</p>
        <p>Si no solicitaste esta verificación, por favor ignora este mensaje.</p>
    </div>
    <div class="footer">
        Este mensaje ha sido enviado automáticamente. Por favor, no respondas a este correo.
    </div>
</body>

</html>
