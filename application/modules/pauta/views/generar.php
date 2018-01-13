<div class="row-fluid">
    <div class="span8 offset2">
        <?php $this->load->view('dashboard/system_messages'); ?>
        <form action="" method="post" class="form-horizontal">
            <div class="control-group">
                <label for="" class="control-label">Proveedor:</label>
                <div class="controls">
                    <?php echo $proveedor ?>
                </div>
            </div>
            <div class="control-group">
                <label for="" class="control-label">Fecha:</label>
                <div class="controls">
                    <?php echo $curdate ?>
                    <input type="hidden" name="fecha" value="<?php echo $curdate ?>">
                </div>
            </div>
            <div class="control-group">
                <label for="" class="control-label">Hora de llegada:</label>
                <div class="controls input-append bootstrap-timepicker">
                    <input type="text" class="timepicker" value="<?php echo isset($time) ? $time : ""; ?>">
                    <span class="add-on"><i class="icon-time"></i></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Llegada:</label>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="medio_transporte" value="aéreo">
                        Via Aérea
                    </label>
                    <label class="radio">
                        <input type="radio" name="medio_transporte" value="terrestre" checked>
                        Via terrestre
                    </label>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Usar Plantilla:</label>
            </div>
            <?php foreach ($publicaciones as $publicacion): ?>
            <div class="control-group">
                <label class="control-label"><?php echo $publicacion->nombre ?>: </label>
                <div class="controls">
                    <?php echo form_dropdown('descripciones[' . $publicacion->id . ']', $descripciones, $day_number, 'class="chosen-select"') ?>
                </div>
            </div>
            <?php endforeach ?>

            <div class="control-group">
                <div class="controls">
                    <input type="hidden" name="remision_id" value="<?php echo $remision_id ?>">
                    <input type="submit" class="btn" value="generar pauta" name="generar-pauta">
                </div>
            </div>
        </form>
    </div>
</div>