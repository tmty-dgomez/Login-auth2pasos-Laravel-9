<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malicious Activity Detected</title>
    <style>
        body {
            font-family: 'Arial Black', Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #1a1a1a;
            color: #ff4d4d;
        }

        .warning-icon {
            font-size: 100px;
            color: #ff0000;
            margin-bottom: 20px;
            animation: pulse 1s infinite;
        }

        h1 {
            font-size: 36px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        p {
            font-size: 20px;
            line-height: 1.5;
        }

        .alert-box {
            display: inline-block;
            padding: 20px 30px;
            border: 2px solid #ff0000;
            border-radius: 10px;
            background: #330000;
            box-shadow: 0px 0px 20px #ff4d4d;
            margin-top: 20px;
            text-align: left;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }
    </style>
</head>
<body>
    <div class="warning-icon">⚠️</div>
    <h1>Malicious Activity Detected</h1>
    <p>Your request appears to contain potentially harmful content. <br>
       This incident has been logged and further actions may be taken.</p>

    <div class="alert-box">
        <strong>Details:</strong>
        <p>
            - Suspicious parameters detected<br>
            - Restricted actions attempted<br>
            - Please refrain from malicious activity
        </p>
    </div>
</body>
</html>
