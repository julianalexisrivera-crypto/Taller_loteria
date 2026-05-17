<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>🎰 Lotería — Juego de Imágenes</title>
</head>
<body>
    <?php
    $puntaje   = isset($_SESSION['puntaje'])   ? $_SESSION['puntaje']   : 2000;
    $resultado = $_SESSION['resultado'] ?? '';
    $numeros   = $_SESSION['numeros']   ?? [1, 2, 3];

    $claseResultado = '';
    if ($resultado === 'Ganaste')   $claseResultado = 'ganaste';
    if ($resultado === 'Perdiste')  $claseResultado = 'perdiste';
    if ($resultado === 'Game Over') $claseResultado = 'gameover';
    ?>

    <div class="container">

        <div class="game-header">
            <div class="game-title">🎰 LOTERÍA</div>
            <div class="game-subtitle">¡Prueba tu suerte!</div>
        </div>

        <div class="score-panel">
            <span class="score-coin">🪙</span>
            <span class="score-label">Puntaje</span>
            <span class="score-value"><?php echo $puntaje; ?></span>
        </div>

        <div class="slot-wrapper">
            <div class="slot">
                <img src="imagenes/<?php echo $numeros[0]; ?>.jpg" alt="Imagen 1">
            </div>
            <div class="slot">
                <img src="imagenes/<?php echo $numeros[1]; ?>.jpg" alt="Imagen 2">
            </div>
            <div class="slot">
                <img src="imagenes/<?php echo $numeros[2]; ?>.jpg" alt="Imagen 3">
            </div>
        </div>

        <div class="resultado <?php echo $claseResultado; ?>">
            <?php if ($resultado): ?>
                <?php
                if ($resultado === 'Ganaste')   echo '🎉 ¡GANASTE! +200';
                elseif ($resultado === 'Perdiste') echo '😞 Perdiste  -10';
                elseif ($resultado === 'Game Over') echo '💀 GAME OVER';
                else echo htmlspecialchars($resultado);
                ?>
            <?php else: ?>
                ¡Presiona Jugar!
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <div class="btn-group">
            <?php if ($resultado === 'Game Over'): ?>
                <a class="btn btn-restart" href="index.php">🔄 Volver a Jugar</a>
            <?php else: ?>
                <a class="btn btn-play" href="index.php?action=jugar">🎲 Jugar</a>
                <a class="btn btn-save" href="index.php?action=guardar">💾 Guardar</a>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
