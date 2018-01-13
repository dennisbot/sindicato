<?php if (isset($vendedores)): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Monto/publicacion</th>
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
    <button id="toprint">Imprimir</button>
      <table class="table table-striped" style="width:100%;border:1px">
        <thead>
          <tr>
              <?php for ($t = 0; $t < 2; $t++) : ?>
                  <th>N°</th>
                  <th><span>Vendedor/Diario</span></th>
                  <?php $size = count($publicaciones) ?>
                  <?php foreach ($publicaciones as $publicacion): ?>
                    <th><?php echo $publicacion->shortname ?></th>
                  <?php endforeach ?>
              <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
        <?php for ($k = 0; $k < $amount; $k++) : ?>
        <tr>
            <?php if (isset($vendedores[0][$k])) : ?>
                    <td><?php echo $k + 1 ?></td>
                    <td>
                        <?php echo $vendedores[0][$k]->nickname ?>
                    </td>
                    <?php for ($i = 0; $i < $size; $i++): ?>
                    <?php $devolucion = 0 ?>
                    <td>
                        <?php echo $vendedores[0][$k]->{"cant_" . $publicaciones[$i]->detalle_remision_id} ?>
                        <span style="color:red">(<?php echo isset($vendedores[0][$k]->{"dev_pubid_" . $publicaciones[$i]->id}) ? $vendedores[0][$k]->{"dev_pubid_" . $publicaciones[$i]->id} : '0' ?>)</span>
                    </td>
                    <?php endfor ?>
            <?php else: ?>
                    <td colspan="<?php echo $size + 2 ?>"></td>
            <?php endif ?>
            <?php if(isset($vendedores[1][$k])): ?>
                    <td><?php echo ($amount + $k + 1) ?></td>
                    <td>
                        <?php echo $vendedores[1][$k]->nickname ?>
                    </td>
                    <?php for ($i = 0; $i < $size; $i++): ?>
                    <?php $devolucion = 0 ?>
                    <td>
                        <?php echo $vendedores[1][$k]->{"cant_" . $publicaciones[$i]->detalle_remision_id} ?>
                        <span style="color:red">(<?php echo isset($vendedores[1][$k]->{"dev_pubid_" . $publicaciones[$i]->id}) ? $vendedores[1][$k]->{"dev_pubid_" . $publicaciones[$i]->id} : '0' ?>)</span>
                    </td>
                    <?php endfor ?>
            <?php else: ?>
                    <td colspan="<?php echo $size + 2 ?>"></td>
            <?php endif ?>
        </tr>
        <?php endfor ?>
        </tbody>
  </table>
<?php endif ?>


