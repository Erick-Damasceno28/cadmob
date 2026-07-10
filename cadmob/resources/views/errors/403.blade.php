<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado - CadImob</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 480px;
        }
        .code {
            font-size: 80px;
            font-weight: bold;
            color: #4169E1;
        }
        h1 {
            font-size: 24px;
            color: #111827;
            margin: 16px 0 8px;
        }
        p {
            color: #6b7280;
            margin-bottom: 32px;
        }
        a {
            background: #4169E1;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            background: #3451c7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">403</div>
        <h1>Acesso Não Autorizado</h1>
        <p>Você não tem permissão para acessar esta página.<br>Entre em contato com o administrador do sistema.</p>
        <a href="/dashboard">Voltar ao Dashboard</a>
    </div>
</body>
</html>
