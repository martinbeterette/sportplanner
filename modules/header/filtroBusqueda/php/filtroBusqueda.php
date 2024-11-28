<?php

$deporte = $conexion->query("SELECT id_deporte, descripcion_deporte FROM deporte");

$superficie = $conexion->query("SELECT id_tipo_terreno, descripcion_tipo_terreno FROM tipo_terreno");

$horario = $conexion->query("SELECT id_horario, horario_inicio, horario_fin FROM horario");

?>
<form id="filtro_deporte" method="get" action="getFiltroBusqueda.php">

    <!-- Selección del deporte -->
    <div class="form_filtro">
        <label for="deporte">Deporte:</label>
        <select id="deporte" name="deporte">
            <option value="">Seleccionar</option>
            <?php while ($row = $deporte->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_deporte']; ?>" <?php if ($row['id_deporte'] == $deporte) echo 'selected'; ?>>
                    <?php echo $row['descripcion_deporte']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Seleccionar Tipo de deporte dinámico -->
    <div class="form_filtro" id="tipo-deporte">
        <label for="tipoDeporte">Formato:</label>
        <select id="tipoDeporte" name="tipoDeporte">
            <option value="">Seleccionar</option>
        </select>
    </div>

    <!-- Selección de la superficie -->
    <div class="form_filtro">
        <label for="superficie">Superficie:</label>
        <select id="superficie" name="superficie">
            <option value="">Seleccionar</option>
            <?php while ($row = $superficie->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_tipo_terreno']; ?>" <?php if ($row['id_tipo_terreno'] == $superficie) echo 'selected'; ?>>
                    <?php echo $row['descripcion_tipo_terreno']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Selección de la fecha -->
    <div class="form_filtro">
        <label for="fecha">Fecha:</label>
        <input type="text" id="fecha" name="fecha" class="flackFech"
            value="<?php echo htmlspecialchars($fecha); ?>">
    </div>

    <!-- Selección de la hora -->
    <div class="form_filtro">
        <label for="hora">Hora:</label>
        <select name="horario" id="horario">
            <option value="">Seleccionar</option>
            <?php while ($row = $horario->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_horario']; ?>"> <?php if ($row['id_horario'] == $horario) echo 'selected'; ?>
                    <?php echo $row['horario_inicio']; ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="form_filtro">
        <label for="btn-filtro">Buscador</label>
        <button type="submit">Buscar Canchas</button>
    </div>

</form>