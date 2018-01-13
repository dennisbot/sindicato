<?php $this->load->view('dashboard/system_messages') ?>
<form class="form-horizontal">
  <div class="control-group">
    <div class="controls">
      <h3>Buscar por:</h3>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="radio">
        <input type="radio" name="searchby" id="vendedor" value="vendedor">
        Vendedor
      </label>
      <label class="radio">
        <input type="radio" name="searchby" id="proveedor" value="publicacion">
        Publicación
        <div class="subitems">
          <label class="radio">
            <input type="radio" name="clase-publicacion" value="periodico" id="periodico" checked>
            Periodico
          </label>
          <label class="radio">
            <input type="radio" name="clase-publicacion" value="revista" id="publicacion">
            Revista/Agregado
          </label>
        </div>
      </label>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <div id="caja-publicacion">
      </div>
    </div>
  </div>
</form>
  <div id="resultados"></div>
<style>
  table.table th, table.table td {
    text-align: center;
    vertical-align: middle;
  }
</style>
<script id="deudastpl" type="text/template">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Fecha Repartida</th>
        <th>Descripcion publicación</th>
        <th>Cantidad Repartida</th>
        <th>Devuelto</th>
        <th>A Cuenta</th>
        <th>Total a Cobrar</th>
        <th>Saldo a Cobrar</th>
        <th>Cobrar?</th>
      </tr>
    </thead>
    <tbody>
      {{#result}}
      <tr data-dpid="{{dpid}}" data-precio-vendedor="{{precio_vendedor}}">
        <td>{{fecha}}</td>
        <td>{{nombre}}</td>
        <td class="repartido">{{cantidad}}</td>
        <td class="td-cantidad-dev devolucion">
          {{{sidevuelve}}}
        </td>
        <td class="abonado">{{abonado}}</td>
        <td class="total">{{monto_deuda}}</td>
        <td class="td-cantidad-dev saldo">
          <input type="text" value="{{saldo}}">
        </td>
        <td><button class="btn btn-cobrar">Cobrar</button></td>
      </tr>
      {{/result}}
    </tbody>
  </table>
</script>