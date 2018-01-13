<form action="" method="post" class="form-horizontal" id="form-proveedores">
  <div class="row">
    <div class="span6">
    	<div class="control-group">
    		<label for="" class="control-label">Seleccione el Proveedor:</label>
    		<div class="controls">
    			<?php echo form_dropdown('proveedores', $proveedores, $proveedor, 'class="chosen-select" id="select-proveedores"') ?>
    		</div>
    	</div>
    </div>
    <div class="span6">
      <ul style="list-style: none">
            <li><?php echo anchor('descripcion_tipo_plantilla/index', '<i class=icon-list></i> Agregar Nuevo tipo de plantilla');?></li>
        </ul>
    </div>
  </div>
	<!-- <div class="control-group">
		<label for="" class="control-label">Seleccione el Dia:</label>
		<div class="controls">
			<?php // echo form_dropdown('descripciones', $descripciones, $descripcion_id, 'class="chosen-select"') ?>
		</div>
	</div> -->
	<!-- <div class="control-group">
		<div class="controls">
			<input type="submit" value="Enviar" class="btn">
		</div>
	</div> -->
<div class="row-fluid upper tofix">
  <div class="span6">
    <?php if (isset($vendedores)): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <?php foreach ($publicaciones as $publicacion): ?>
            <th><?php echo $publicacion->nombre ?></th>
          <?php endforeach ?>
        </tr>
        <tr>
          <?php for ($i = 0, $max = count($publicaciones); $i < $max; $i++): ?>
          <?php endfor ?>
        </tr>
        <tr>
          <?php foreach ($publicaciones as $publicacion): ?>
            <th><?php echo form_dropdown('descripciones[' . $publicacion->id . ']', $descripciones, $keys_descripciones[$publicacion->id], 'class="chosen-select"') ?></th>
          <?php endforeach ?>
        </tr>
        <tr>
          <?php foreach ($publicaciones as $publicacion): ?>
            <td id="publicacion-<?php echo $publicacion->id ?>"><?php echo $publicacion->total_plantilla_dia ?></td>
          <?php endforeach ?>
        </tr>
      </thead>
    </table>
    <?php endif ?>
  </div>
</div>
</form>

<div class="row-fluid upper">
    <div class="span12">
    <?php if (isset($vendedores)): ?>
           <?php $k = 0; ?>
          <div class="row-fluid">
          <?php foreach ($vendedores as $vendedor): ?>
              <div class="span6">
                  <table class="table table-striped table-bordered vendedores">
                    <thead>
                      <tr>
                          <th>N°</th>
                          <th><span class="capital">Vendedor/Diario</span></th>
                          <?php $size = count($publicaciones) ?>
                          <?php foreach ($publicaciones as $publicacion): ?>
                            <th><?php echo $publicacion->shortname ?></th>
                          <?php endforeach ?>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $pos = 0; ?>
                    <?php foreach($vendedor as $v) : ?>
                    <tr class="<?php echo ($pos++ % 2 == 0) ? 'even' : 'odd' ?>">
                        <td><?php echo $k++ + 1 ?></td>
                        <td class="vendedor" data-vendedor-id="<?php echo $v->id ?>">
                            <?php echo $v->nickname ?>
                        </td>
                        <?php for ($i = 0; $i < $size; $i++): ?>
                        <td class="editinplace" id="<?php echo $publicaciones[$i]->id . "-" . $v->id . "-" . $keys_descripciones[$publicaciones[$i]->id] ?>">
                            <?php echo $v->publicacion[$publicaciones[$i]->nombre]["reparto"] ?>
                        </td>
                        <?php endfor ?>
                    </tr>
                    <?php endforeach ?>
                    </tbody>
                  </table>
              </div>
          <?php endforeach ?>
          </div>
    <?php endif ?>
    </div>
</div>