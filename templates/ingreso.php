<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .contenedor {
            width: 100%;
            padding: 20px;
            border: 1px solid #28a745;
            background-color: #d4edda;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .bloque {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #155724;
            background-color: #c3e6cb;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <div class="titulo">INGRESO A LA EMPRESA</div>

    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Apellido:</strong> <?php echo $apellido; ?></p>
    <p><strong>Cédula:</strong> <?php echo $cedula; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Fecha de nacimiento:</strong> <?php echo $fecha_nacimiento; ?></p>

    <div class="bloque">
        Se le da la bienvenida al trabajador 
        <strong><?php echo $nombre . ' ' . $apellido; ?></strong> 
        con número de cédula 
        <strong><?php echo $cedula; ?></strong> 
        a su nuevo puesto de trabajo.
    </div>
</div>

</body>
</html>