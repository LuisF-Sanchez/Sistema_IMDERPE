<?php
session_start();

require_once '../controlador/controlador_editar_atleta.php';

if (!isset($atleta)) {
    header("Location: ver_atletas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Atleta - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style10.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="ver_atletas.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <span style="color: white; font-weight: bold; letter-spacing: 1px;">
                ID ATLETA: <?php echo $atleta['id']; ?>
            </span>
        </div>
    </header>

    <main class="central-container">
        <div class="glass-form-container">
            <div class="form-header">
                <i class="fas fa-user-edit fa-3x" style="color: #FBC02D;"></i>
                <h2>Modificar Información de Atleta</h2>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Asegúrese de verificar los cambios antes de guardar</p>
            </div>

            <form action="../controlador/controlador_editar_atleta.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $atleta['id']; ?>">

                <div class="form-grid">
                    <div class="input-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($atleta['nombre']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($atleta['apellido']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="cedula">Cédula de Identidad</label>
                        <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($atleta['cedula']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="genero">Género</label>
                        <select name="genero" id="genero" required>
                            <option value="Masculino" <?php echo ($atleta['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo ($atleta['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $atleta['fecha_nacimiento']; ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="representante_id">Representante Legal</label>
                        <select name="representante_id" id="representante_id" required>
                            <?php 
                            $res_representantes->data_seek(0);
                            while($rep = $res_representantes->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $rep['id']; ?>" 
                                    <?php echo ($atleta['representante_id'] == $rep['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rep['nombre'] . " " . $rep['apellido']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="entrenador_id">Entrenador Asignado</label>
                        <select name="entrenador_id" id="entrenador_id" required>
                            <?php 
                            $res_entrenadores->data_seek(0);
                            while($ent = $res_entrenadores->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $ent['id']; ?>" 
                                    <?php echo ($atleta['entrenador_id'] == $ent['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ent['nombre'] . " " . $ent['apellido']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="disciplina_id">Disciplina Deportiva</label>
                        <select name="disciplina_id" id="disciplina_id" required>
                            <?php 
                            $res_disciplinas->data_seek(0);
                            while($disc = $res_disciplinas->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $disc['id']; ?>" 
                                    <?php echo ($atleta['disciplina_id'] == $disc['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($disc['nombre_disciplina']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="estado">Estado en el Sistema</label>
                        <select name="estado" id="estado" required>
                            <option value="activo" <?php echo ($atleta['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                            <option value="inactivo" <?php echo ($atleta['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="btn_actualizar" value="ok" class="btn-submit">
                    <i class="fas fa-save"></i> GUARDAR CAMBIOS
                </button>
            </form>
        </div>
    </main>
</body>
</html>