<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:300');
        body {
            background-color: #071014;
            background-image: linear-gradient(160deg, #071014 0%, #0db8de 100%);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            color: #ECF0F5;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #1A2226;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #0DB8DE;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #ECF0F5;
            margin-bottom: 20px;
        }
        a {
            background: #0DB8DE;
            color: #FFFFFF;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 7px;
            display: inline-block;
            transition: background 0.3s;
        }
        a:hover {
            background: #0e2941;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    @if(isset($user))
        <div class="container">
            <h1>Hello {{ $user->name }},</h1>
            <p>Thank you for registering at NovaBytes!</p>
            <p>To complete your account verification, please press the following button:</p>
            <a href="{{ $url }}" class="btn btn-primary">
                Verify Your Account
            </a>
        </div>
    @else
        <div class="container">
            <h1>Error</h1>
            <p>User data not found.</p>
        </div>
    @endif
</body>
</html>

<!-- resources/views/verifyEmail.blade.php-->
