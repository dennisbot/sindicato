<div id="caja-botones">
  <button id="imprimir" class="btn btn-large">imprimir</button>
  <?php if ($show_anular): ?>
    <button id="anular" class="btn btn-danger btn-large" data-href="<?php echo $url_anular; ?>" data-pauta-id="<?php echo $pauta_id ?>">Anular Pauta</button>
  <?php endif ?>
</div>
<div class="row-fluid upper tofix">
  <div class="span6">
    <?php if (isset($vendedores)): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Monto/publicación</th>
          <?php foreach ($publicaciones as $publicacion): ?>
            <th><?php echo $publicacion->nombre ?></th>
          <?php endforeach ?>
        </tr>
        <tr>
          <td>Cantidad a repartir</td>
          <?php foreach ($publicaciones as $publicacion): ?>
            <td id="publicacion-detalle-<?php echo $publicacion->id ?>" class="<?php echo $publicacion->total == $publicacion->cantidad ? 'alert-success' : 'alert-danger' ?>">
              <?php echo $publicacion->total ?>
            </td>
          <?php endforeach ?>
        </tr>
        <tr>
          <td>Cantidad Disponible</td>
          <?php foreach ($publicaciones as $publicacion): ?>
            <td id="publicacion-remision-<?php echo $publicacion->id ?>" class="alert-success">
              <?php echo $publicacion->cantidad ?>
            </td>
          <?php endforeach ?>
        </tr>
      </thead>
    </table>
    <?php endif ?>
  </div>
</div>
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
                          <th><span>Vendedor/Diario</span></th>
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
                        <td data-vendedor-id="<?php echo $v->id ?>">
                            <?php echo $v->nickname ?>
                        </td>
                        <?php for ($i = 0; $i < $size; $i++): ?>
                        <?php $devolucion = 0 ?>
                        <td class="editar">
                            <div class="editinplace" id="<?php echo $publicaciones[$i]->id . "-" . $v->{"dpid_". $publicaciones[$i]->detalle_remision_id} ?>">
                              <?php echo $v->{"cant_" . $publicaciones[$i]->detalle_remision_id} ?>
                            </div>
                            <span style="color:red">(<?php echo isset($v->{"dev_pubid_" . $publicaciones[$i]->id}) ? $v->{"dev_pubid_" . $publicaciones[$i]->id} : '0' ?>)</span>
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