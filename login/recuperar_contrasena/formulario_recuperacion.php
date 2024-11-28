<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="icons/pestaña.jpg">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.5);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ingrese su correo electrónico para la recuperación</h2>
        <form action="recuperacion.php" method="POST">
            <input type="text" name="email" placeholder="Correo Electrónico">
            <input type="submit" value="Enviar">
        </form>
        <a href="../../index_tincho.php" class="back-link">Volver al inicio</a>
    </div>
</body>
</html>
