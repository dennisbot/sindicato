<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>

    <form class="form-horizontal" method="post" id="form-remision" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <div class="control-group <?php echo form_error('nro_guia') != '' ? 'error' : '';?>">
            <label class="control-label">* N&uacute;mero de gu&iacute;a de remisi&oacute;n: </label>
            <div class="controls">
                <input type="text" class="numeroentero" name="nro_guia" value="<?php echo $this->mdl_remision->form_value('nro_guia'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('razon_social') != '' ? 'error' : '';?>">
            <label class="control-label">* Raz&oacute;n social: </label>
            <div class="controls">
                <input type="text" name="razon_social" value="<?php echo $this->mdl_remision->form_value('razon_social'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('codigo') != '' ? 'error' : '';?>">
            <label class="control-label">C&oacute;digo de remisi&oacute;n: </label>
            <div class="controls">
                <input type="text" name="codigo" value="<?php echo $this->mdl_remision->form_value('codigo'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('ruc') != '' ? 'error' : '';?>">
            <label class="control-label">* N&uacute;mero de RUC: </label>
            <div class="controls">
                <input type="text" class="numeroentero" maxlength ="11" name="ruc" value="<?php echo $this->mdl_remision->form_value('ruc'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('tipo') != '' ? 'error' : '';?>">
            <label class="control-label">* Tipo: </label>
            <div class="controls">
                <input type="text" name="tipo" value="<?php echo $this->mdl_remision->form_value('tipo'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('sector') != '' ? 'error' : '';?>">
            <label class="control-label">* Sector: </label>
            <div class="controls">
                <input type="text" name="sector" value="<?php echo $this->mdl_remision->form_value('sector'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('observaciones') != '' ? 'error' : '';?>">
            <label class="control-label">* Observaciones: </label>
            <div class="controls">
            	<textarea placeholder="Ingrese alguna observaci&oacute;n" rows="10" cols="10" name="observaciones"><?php echo $this->mdl_remision->form_value('observaciones'); ?></textarea>
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha_emision') != '' ? 'error' : '';?>">
            <label class="control-label">* Fecha emisi&oacute;n: </label>
            <div class="controls">
            	 <div class="input-append date datepicker" id="dpStartDate1">
                    <input name="fecha_emision" id="fecha_emision" type="text" value="<?php echo format_date($this->mdl_remision->form_value('fecha_emision')) ?>" readonly="readonly" placeholder="click para seleccionar">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha_recepcion') != '' ? 'error' : '';?>">
            <label class="control-label">* Fecha de recepcion: </label>
            <div class="controls">
	            <div class="input-append date datepicker" id="dpStartDate2">
                    <input name="fecha_recepcion" id="fecha_recepcion" type="text" value="<?php echo format_date($this->mdl_remision->form_value('fecha_recepcion')) ?>" readonly ="readonly" placeholder="click para seleccionar">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha_recepcion') != '' ? 'error' : '';?>">
            <label class="control-label">* Selecciones el proveedor: </label>
            <div class="controls">
            <?php echo form_dropdown('proveedor_id', $proveedores, $this->mdl_remision->form_value('proveedor_id'), 'class="proveedor"') ?>
            </div>
        </div>
        <div class="control-group">
            <h3>Agregar Detalle de la Remisión:</h3>
            <div id="caja-validaciones"></div>
            <table id="to-add" class="table table-striped">
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad de Medida</th>
                    <th>Precio Unitario</th>
                    <th>Precio Unitario Calculado</th>
                    <th>Importe</th>
                    <th>Acciones</th>
                </tr>
                <tr>
                    <td>
                        <!-- esto debería ser un buscador de publicaciones -->
                        <select name="descripcion" id="descripcion"></select>
                    </td>
                    <td>
                        <input id="cantidad" class="ajustar" type="text">
                    </td>
                    <td>
                        <input id="unidad-medida" class="ajustar" type="text">
                    </td>
                    <td>
                        <input id="precio-unitario-guia" class="ajustar" type="text">
                    </td>
                    <td>
                        <input id="precio-unitario-calculado" class="ajustar" type="text">
                    </td>
                    <td>
                        <input id="importe" class="ajustar" type="text">
                    </td>
                    <td>
                        <button class="btn acciones" id="aniadir">Nuevo</button>
                    </td>
                </tr>
            </table>
            <h3>Detalle de las publicaciones:</h3>
            <table id="details" class="table table-striped">
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad de Medida</th>
                    <th>Precio Unitario Guia</th>
                    <th>Precio Unitario Calculado</th>
                    <th>Importe</th>
                    <th>acciones</th>
                </tr>
                <?php foreach ($detalles as $key => $detalle): ?>
                    <tr>
                        <td>
                            <?php echo $detalle->descripcion ?>
                            <input type="hidden" name="detalle[id][]" value="<?php echo $detalle->id ?>">
                            <input type="hidden" name="detalle[descripcion][]" value="<?php echo $detalle->descripcion ?>">
                            <input type="hidden" name="detalle[publicacion_id][]" value="<?php echo $detalle->publicacion_id ?>">
                        </td>
                        <td><?php echo $detalle->cantidad ?>
                            <input type="hidden" name="detalle[cantidad][]" value="<?php echo $detalle->cantidad ?>">
                        </td>
                        <td><?php echo $detalle->unidad_medida ?>
                            <input type="hidden" name="detalle[unidadMedida][]" value="<?php echo $detalle->unidad_medida ?>">
                        </td>
                        <td><?php echo $detalle->precio_unitario_guia ?>
                            <input type="hidden" name="detalle[precioUnitarioGuia][]" value="<?php echo $detalle->precio_unitario_guia ?>">
                        </td>
                        <td><?php echo $detalle->precio_unitario_calculado ?>
                            <input type="hidden" name="detalle[precioUnitarioCalculado][]" value="<?php echo $detalle->precio_unitario_calculado ?>">
                        </td>
                        <td><?php echo $detalle->importe ?>
                            <input type="hidden" name="detalle[importe][]" value="<?php echo $detalle->importe ?>">
                        </td>
                        <td><button class="btn btn-danger cancel-detail">&times;</button></td>
                    </tr>
                <?php endforeach ?>
            </table>

            <table id="pub_seleccionadas" class="table table-striped">
                <tr>
                    <th></th>
                    <th class="mas_datos">Día</th>
                    <th class="mas_datos">Precio Público</th>
                    <th class="mas_datos">Descuento</th>
                    <th class="mas_datos">Comision</th>

                    <th>Descripci&oacute;n</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Importe</th>
                </tr>
            </table>
            <table id="totales" class="table table-striped" style="width: 50%;">
                <tr>
                    <td><input name="cantidad_total" class="cantidad_total" value="0" readonly="readonly" /> </td>
                    <td>Total</td>
                    <td><input name="total" class="total" value="0" readonly="readonly" /> </td>
                </tr>
            </table>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="Cancelar" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="Guardar" />
            </div>
        </div>

    </form>
</div><!-- padded -->
