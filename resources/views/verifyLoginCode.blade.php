<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #071014;
            background-image: linear-gradient(160deg, #071014 0%, #0db8de 100%);
            color: #fff;
        }

        .card {
            background: #1A2226;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .card h1 {
            font-size: 26px;
            color: #0DB8DE;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .verification-code {
            display: inline-block;
            font-size: 28px;
            font-weight: bold;
            color: #0DB8DE;
            background: #0e2941;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .footer {
            font-size: 12px;
            margin-top: 20px;
            color: #888;
        }

        .footer a {
            color: #0DB8DE;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Hola {{ $user->name }},</h1>
        <p>¡Gracias por registrarte en nuestro sitio!</p>
        <p>Para completar la verificación de tu cuenta, utiliza el siguiente código:</p>
        <div class="verification-code">{{ $code }}</div>
        <p>Si no solicitaste esta verificación, ignora este mensaje.</p>
    </div>
    <div class="footer">
        Este mensaje ha sido enviado automáticamente. Por favor, no respondas a este correo.
    </div>
</body>
</html>
