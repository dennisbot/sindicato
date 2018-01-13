<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<div class="control-group label label-info">
		Todos los campos con (*) son obligatorios.
	</div>
    <form class="form-horizontal" method="post" id="form-remision" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<table>
			<tr>
				<td>
			        <input type="hidden" name="proveedor_id" value="<?php echo $this->mdl_remision->form_value('proveedor_id'); ?>" />
			        <div class="control-group <?php echo form_error('nro_guia') != '' ? 'error' : '';?>">
			            <label class="control-label">* N&uacute;mero de gu&iacute;a de remisi&oacute;n: </label>
			            <div class="controls">
			                <input type="text" id="numeroguia" class="nroguia" name="nro_guia" value="<?php echo $this->mdl_remision->form_value('nro_guia'); ?>" />
			            </div>
			        </div>
				</td>
				<td>
			        <div class="control-group <?php echo form_error('nro_guia_credipago') != '' ? 'error' : '';?>">
			            <label class="control-label"> N&uacute;mero de gu&iacute;a credipago: </label>
			            <div class="controls">
			                <input type="text" id="nro_guia_credipago" class="nro_guia_credipago" name="nro_guia_credipago" value="<?php echo $this->mdl_remision->form_value('nro_guia_credipago'); ?>" />
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td>
			        <div class="control-group <?php echo form_error('razon_social') != '' ? 'error' : '';?>">
			            <label class="control-label">* Raz&oacute;n social: </label>
			            <div class="controls">
			                <input type="text" name="razon_social" value="<?php echo $this->mdl_remision->form_value('razon_social'); ?>" />
			            </div>
			        </div>
				</td>
				<td>
			        <div class="control-group <?php echo form_error('codigo') != '' ? 'error' : '';?>">
			            <label class="control-label">C&oacute;digo de remisi&oacute;n: </label>
			            <div class="controls">
			                <input type="text" name="codigo" value="<?php echo $this->mdl_remision->form_value('codigo'); ?>" />
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td>
			        <div class="control-group <?php echo form_error('ruc') != '' ? 'error' : '';?>">
			            <label class="control-label">N&uacute;mero de RUC: </label>
			            <div class="controls">
			                <input type="text" class="numeroentero" maxlength ="11" name="ruc" value="<?php echo $this->mdl_remision->form_value('ruc'); ?>" />
			            </div>
			        </div>
				</td>
				<td>
			        <div class="control-group <?php echo form_error('tipo') != '' ? 'error' : '';?>">
			            <label class="control-label">¿es Remisión de Periódico o de Revista?: </label>
			            <div class="controls">
                            <?php $radio_son_periodicos = $this->mdl_remision->form_value('son_periodicos'); ?>
                            <label class="radio">
                                <input type="radio" name="son_periodicos" value="1" <?php if ($radio_son_periodicos || $radio_son_periodicos == null) : ?> checked <?php endif; ?> />
                                Remisión de periodicos
                            </label>
                            <label class="radio">
                                <input type="radio" name="son_periodicos" value="0" <?php if (!$radio_son_periodicos && $radio_son_periodicos != null) : ?> checked <?php endif; ?> />
                                Remisión de revistas
                            </label>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<!-- <td>
			        <div class="control-group <?php echo form_error('sector') != '' ? 'error' : '';?>">
			            <label class="control-label">Sector: </label>
			            <div class="controls">
			                <input type="text" name="sector" value="<?php echo $this->mdl_remision->form_value('sector'); ?>" />
			            </div>
			        </div>
				</td>  -->
				<td>
			        <div class="control-group <?php echo form_error('observaciones') != '' ? 'error' : '';?>">
			            <label class="control-label">Observaciones: </label>
			            <div class="controls">
			            	<textarea placeholder="Ingrese alguna observaci&oacute;n" rows="8" cols="20" name="observaciones"><?php echo $this->mdl_remision->form_value('observaciones'); ?></textarea>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td>
			        <div class="control-group <?php echo form_error('fecha_vencimiento') != '' ? 'error' : '';?>">
			            <label class="control-label">* Fecha vencimiento: </label>
			            <div class="controls">
			            	 <div class="input-append date datepickerv" id="dpStartDate1" data-date="" data-date-format="dd/mm/yyyy" language="es">
			                    <input style="width:193px;" class="span2"  name="fecha_vencimiento" id="fecha_vencimiento" type="text" value="<?php if (isset($fecha_vencimiento)) {echo $fecha_vencimiento;} ?>" readonly="readonly" placeholder="click para seleccionar">
			                    <span class="add-on"><i class="icon-calendar"></i></span>
			                </div>
			            </div>
			        </div>
				</td>
				<td>
			        <div class="control-group <?php echo form_error('fecha_recepcion') != '' ? 'error' : '';?>">
			            <label class="control-label">* Fecha de recepcion: </label>
			            <div class="controls">
				            <div class="input-append date datepicker" id="dpStartDate2" data-date="" data-date-format="dd/mm/ yyyy" language="es">
			                    <input style="width:193px;" class="span2"  name="fecha_recepcion" id="fecha_recepcion" type="text" value="<?php if (isset($fecha_recepcion)) {echo $fecha_recepcion;} ?>" readonly ="readonly" placeholder="click para seleccionar">
			                    <span class="add-on"><i class="icon-calendar"></i></span>
			                </div>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td>
			        <div class="control-group <?php echo form_error('fecha_emision') != '' ? 'error' : '';?>">
			            <label class="control-label">* Fecha emisi&oacute;n: </label>
			            <div class="controls">
			            	 <div class="input-append date datepicker" id="dpStartDate1" data-date="" data-date-format="dd/mm/yyyy" language="es">
			                    <input style="width:193px;" class="span2"  name="fecha_emision" id="fecha_emision" type="text" value="<?php if (isset($fecha_emision_date)) {echo $fecha_emision_date;} ?>" readonly="readonly" placeholder="click para seleccionar">
			                    <span class="add-on"><i class="icon-calendar"></i></span>
			                </div>
			            </div>
			        </div>
				</td>
				<td>
			        <div class="control-group <?php echo form_error('fecha_recepcion') != '' ? 'error' : '';?>">
			            <label class="control-label">* Seleccione el proveedor: </label>
			            <div class="controls">
			            <?php
			            /*if (isset($proveedor_id)) {
			               $proveedor_id = -1;
			            }
			            echo $proveedor_id; */
			            ?>
			            	<select name="proveedor_id" class="proveedor">
			                        <option>Seleccione Proveedor</option>
			            		<?php foreach ($proveedores as $proveedor) { ?>
			            			<option value="<?php echo $proveedor->id; if ($proveedor_id == $proveedor->id) {echo '" selected "' ;}?>"><?php echo $proveedor->nombre; ?></option>
			            		<?php }?>
			            	</select>
			            </div>
			        </div>
				</td>
			</tr>
		</table>

        <div class="control-group">
            <div class="controls">
                <input type="hidden"  name="existen_detalles_remision" id="existen_detalles_remision" value="<?php echo $existen_detalles_remision; ?>" />

            <div class="controls">
                <span class="alert alert-error" id="mensaje-detalle" style="display:none">
                    Necesita especificar <strong>"comisiones"</strong> y <strong>"porcentajes"</strong> de descuento para las publicaciones del <strong>proveedor</strong> que acaba de seleccionar
                </span>
            </div>
        </div>
        <div class="control-group1 <?php echo form_error('fecha_recepcion') != '' ? 'error' : '';?>">
            <div class="controls1">
                <div>
                	<table id="pub_seleccionadas" class="table table-striped">
                		<tr>
                			<th>Item</th>
                            <th class="mas_datos">Día</th>
                            <th class="mas_datos">Precio Público</th>
                            <th class="mas_datos">Dscto</th>
                            <th class="mas_datos">Comisi&oacute;n</th>

                			<th>Descripci&oacute;n</th>
                			<th>Precio Vendedor</th>
                            <th>Cantidad</th>
                            <th>Precio unitario gu&iacute;a</th>
                            <th>Precio unitario calculado</th>
                            <th>Ganancia Sindicato</th>
                			<th>Importe</th>
                		</tr>
                    <?php
                    if (count($detalles) > 0) {
                    	//print_r($detalles);exit;
	                    $i = 1;
	                    foreach ($detalles as $detalle)
	                    {
	                        ?>
	                    <tr>
	                        <td class='id'><?php echo $i; ?><input type='hidden' name='detalle[id][]' class='publicacion' value='<?php echo $detalle->id; ?>'/>
	                        <input type='hidden' name='detalle[publicacion_id][]' class='publicacion' value='<?php echo $detalle->publicacion_id; ?>'/> </td>
		                    <td><input type='hidden' name='detalle[fecha][]' value='<?php echo $detalle->fecha;?> '/ >
		                    <?php  echo $detalle->fecha;?> </td>
							<td><input type='hidden' name='detalle[precioPublico][]' value='<?php echo $detalle->precioPublico;?>' class="precio_publico" /><?php  echo $detalle->precioPublico;?> </td>
	                      	<td><input type='hidden' name='detalle[descuentoAplicado][]' value='<?php echo $detalle->descuentoAplicado;?> '/ ><?php  echo $detalle->descuentoAplicado.'%';?> </td>
	                      	<td><input type='hidden' name='detalle[comision][]' value='<?php echo $detalle->comision;?>' class="comision" /><?php  echo $detalle->comision.'%';?> </td>
	                      	<td><input type='hidden' name='detalle[nombrePublicacion][]' value='<?php echo $detalle->nombrePublicacion;?> '/ ><?php  echo $detalle->nombrePublicacion;?> </td>
	                      	<td><input type='text' name='detalle[precio_vendedor][]' class='precio_vendedor currency' value='<?php echo $detalle->precio_vendedor; ?>' readonly="readonly" /> </td>
	                      	<td><input type='text' name='detalle[cantidad][]' class='cantidad currency_cantidad' value='<?php echo $detalle->cantidad; ?>' /> </td>
	                      	<td><input type='text' name='detalle[precioUnitarioGuia][]' class='precio_guia currency' value='<?php echo number_format($detalle->precio_unitario_guia, 3); ?>' style='background-color:rgba(185, 74, 72,0.3);' /> </td>
	                      	<td><input type='text' name='detalle[precioUnitarioCalculado][]' readonly class='precio' value='<?php echo number_format($detalle->precio_unitario_calculado, 3); ?>' style='background-color:rgba(112, 179, 105, 8);color:#FFF;' /> </td>
	                      	<td><input type='text' name='detalle[ganancia_sindicato][]' class='ganancia_sindicato currency' value='<?php echo $detalle->ganancia_sindicato; ?>' readonly="readonly" /> </td>
	                      	<td><input type='text' class='importe currency' readonly name='detalle[importe][]' value='<?php echo $detalle->importe;?> ' />
						</tr>
	                    <?php  $i++;} ?>
                     <?php  }
                     else
                     {
                        echo '<script type="text/javascript">  fallo_post ="fallor_post";</script>';
                     }
                     ?>
                	</table>
                	<table id="totales" class="table table-striped" style="width: 100%;">
                		<tr>
                			<td rowspan="20">&nbsp;</td>
                			<td>Cantidad total</td>
                			<td><input name="cantidad_total" class="cantidad_total" value="<?php echo $cantidad_total_sindicato;?>" readonly="readonly"/> </td>
                            <td>Ganancia total referencial</td>
                            <td><input name="ganancia_total_sindicato" class="ganancia_total_sindicato" value="<?php echo $ganancia_total_sindicato;?>" readonly="readonly" /> </td>
                			<td>Importe total</td>
                			<td><input name="total" class="total" value="<?php echo number_format($total, 3); ?>" readonly="readonly" /> </td>
                		</tr>
                	</table>
                </div>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="Guardar" />
            </div>
        </div>
    </form>
</div><!-- padded -->
