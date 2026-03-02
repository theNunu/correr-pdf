<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans; font-size: 12px; }
.contenedor {
    padding:20px;
    border:1px solid #dc3545;
    background:#f8d7da;
}
.titulo {
    text-align:center;
    font-size:16px;
    font-weight:bold;
}
.bloque {
    margin-top:15px;
    padding:15px;
    border:1px solid #721c24;
    background:#f5c6cb;
}
</style>
</head>
<body>

<div class="contenedor">
    <div class="titulo">DESPIDO DE LA EMPRESA</div>

    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Apellido:</strong> <?php echo $apellido; ?></p>
    <p><strong>Cédula:</strong> <?php echo $cedula; ?></p>

    <div class="bloque">
        Se notifica la terminación laboral del trabajador 
        <strong><?php echo $nombre . ' ' . $apellido; ?></strong> 
        con número de cédula 
        <strong><?php echo $cedula; ?></strong>.
    </div>
</div>

</body>
</html>