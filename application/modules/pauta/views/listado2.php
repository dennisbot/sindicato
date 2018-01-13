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
                        <?php foreach($vendedor as $v) : ?>
                        <tr>
                            <td><?php echo $k++ + 1 ?></td>
                            <td class="vendedor" data-vendedor-id="<?php echo $v->id ?>" data-title="cobrar">
                                <?php echo $v->nickname ?>
                            </td>
                            <?php for ($i = 0; $i < $size; $i++): ?>
                            <?php $devolucion = $v->publicacion[$publicaciones[$i]->nombre]["devolucion"] ?>
                            <td>
                                <?php echo $v->publicacion[$publicaciones[$i]->nombre]["reparto"] ?>
                                <span style="color: red">(<?php echo $devolucion ?>)</span>
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
<div id="modal-cobrar" class="modal hide fade" tabindex="-1">
    <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Realizar Cobro a "<span class="nombre-vendedor upper"></span>"</h3>
    </div>
    <div class="modal-body">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <?php foreach ($publicaciones as $publicacion): ?>
                        <th class="capital" style="text-align: center"><?php echo $publicacion->nombre ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php $concepto = array("entregado:", "devuelto:") ?>
            <tbody>
                <?php for ($k = 0; $k < 2; $k++) : ?>
                    <tr>
                        <td class="capital" style="text-align: right">
                            <?php echo $concepto[$k] ?>
                        </td>
                        <?php for ($i = 0; $i < $size; $i++) : ?>
                            <td style="text-align: center"><input type="text" class="currency" style="width: 44%" <?php echo $k == 0 ? "readonly" : "" ?>></td>
                        <?php endfor ?>
                    </tr>
                <?php endfor ?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</a>
        <a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Guardar</a>
    </div>
</div>