<?php $this->load->view('dashboard/system_messages') ?>
<form class="form-horizontal">
  <div class="control-group">
    <label class="control-label" style="line-height: 30px">Seleccionar vendedor:</label>
    <div class="controls">
      <div id="caja-publicacion">
      </div>
    </div>
    <label class="control-label" style="line-height: 30px">Seleccionar fecha de pago:</label>
    <div class="controls">
    	<?php $today = date("d/m/Y"); ?>
		<span class="input-append date calendari" id="dpStartDate">
        	<input type="text" class="datepicker" name="fecha_inicio" value="<?php echo ($this->input->post('fecha_inicio') != '')?$this->input->post('fecha_inicio'):$today; ?>" readonly="readonly" />
			    <span class="add-on"><i class="icon-calendar"></i></span>
    </span>

    </div>
  </div>
</form>
  <div id="resultados"></div>
<script id="deudastpl" type="text/template">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Fecha Repartida</th>
        <th>Publicación</th>
        <th>Cantidad Repartida</th>
        <th>Devuelto</th>
        <th>A Cuenta</th>
        <th>Total a Cobrar</th>
        <th>Saldo a Cobrar</th>
		<th>Modificar? <i class="icon-unchecked icon-large" id="seleccionar-todo"></i></th>
      </tr>
    </thead>
    <tbody>
      {{#result}}
      <tr data-dpid="{{dpid}}" data-precio-vendedor="{{precio_vendedor}}">
        <td class="fecha-repartida">{{fecha}}</td>
        <td class="nombre-publicacion">{{nombre}}</td>
        <td class="repartido">{{cantidad}}</td>
        <td class="td-cantidad-dev devolucion">
          {{{sidevuelve}}}
        </td>
        <td class="abonado">{{abonado}}</td>
        <td class="total">{{monto_deuda}}</td>
        <td class="td-cantidad-dev saldo">
          <input type="text" value="{{monto_pago}}" readonly="readonly">
        </td>
        <td><input type="checkbox" class="btn check-cobrar" /></td>
      </tr>
      {{/result}}
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="eltotal">Total</td>
        <td class="td-cantidad-dev saldo eltotal"><input type="text" class="tb-total" value=""></td>
        <td class="eltotal"><button class="btn btn-cobrar1">Modificar</button></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="eltotal" style="height: 45px" colspan="2"><button class="btn btn-cobrar-imprimir" data-imprimir="1">Modificar e imprimir (Ctrl + F8)</button></td>
      </tr>
    </tbody>
  </table>
</script>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Información a Imprimir</h3>
  </div>
  <div class="modal-body">
    <div class="resumen-pago"></div>
  </div>
  <div class="modal-footer">
    <button class="btn" type="button" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn btn-primary">Guardar cambios</button>
  </div>
</div>