<div class="row-fluid">
    <div class="span8 offset2">
        <?php $this->load->view('dashboard/system_messages') ?>
        <form action="<?php echo base_url('pauta/ver'); ?>" method="post" class="form-inline">

            <label for="" class="control-label">Seleccione fecha:</label>
            <span class="input-append date datepicker">
                <input type="text"
                name="fecha"
                placeholder="click para seleccionar"
                readonly="readonly"
                value="<?php echo $curdate ?>">
                <span class="add-on"><i class="icon-calendar norm"></i></span>
            </span>

            <input type="submit" class="btn" value="Ver remisiones" name="ver-remision">
        </form>
        <?php if (isset($remisiones) and !empty($remisiones)) : ?>
            <div class="remisiones" data-curdate="<?php echo $curdate ?>">
                <div class="alert alert-info">Remisiones para el dia <strong><?php echo $report_date ?></strong></div>
                <?php foreach ($remisiones as $remision): ?>
                    <div data-remision-id="<?php echo $remision["remision_id"] ?>" data-proveedor="<?php echo $remision["nombre"] ?>" data-idproveedor="<?php echo $remision["proveedor_id"] ?>" class="ver-pauta" data-title="ver/generar pauta de esta remisión">
                        <?php echo $remision["nro_guia"]." - Razon social: " . $remision["razon_social"] . " - Proveedor: " . $remision["nombre"]?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <div class="alert alert-error remisiones" data-curdate="<?php echo $curdate ?>">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>No hay remisiones</strong> registradas para el dia seleccionado.<br>
                <strong><a href="#" onclick="return false;">Ingrese</a></strong> La remisión del dia respectivo primero.
            </div>
        <?php endif ?>
    </div>
</div>
