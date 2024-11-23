<!DOCTYPE html>
<html>

<head>
    <title>Anamnese Reprovada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
            padding: 20px;
        }

        .email-header {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .email-body {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }

        .email-body p {
            margin: 15px 0;
        }

        .email-footer {
            font-size: 14px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1 style="text-align: center;">Team Guima</h1>
        <div class="email-container">
            <div class="email-header">
                Olá {{ $name }},
            </div>
            <div class="email-body">
                <p>Sua anamnese foi reprovada.</p>
                <p><strong>Motivo:</strong> {{ $motivo_reprovacao }}</p>
                <p>Por favor, corrija as informações e envie novamente para aprovação.</p>
            </div>
            <div class="email-footer">
                <p>Atenciosamente,</p>
                <p>Team Guima</p>
            </div>
        </div>
</body>

</html>