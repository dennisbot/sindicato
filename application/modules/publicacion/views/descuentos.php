<table id="descuentos" class="table table-striped table-hover table-bordered">
    <thead>
        <tr>

            <th>publicaci&oacute;n</th>
            <?php foreach ($columnas as $columna) : ?>

                <?php echo $columna->tipo_fecha != "aniversario"? "<th>" . $columna->nombre : "" ?>

            <?php endforeach ?>
            <th>aniversario</th>

        </tr>
    </thead>
    <tbody>
    <?php //var_dump($columnas) ?>
    <?php //var_dump($publicaciones) ?>
    <?php foreach ($publicaciones as $publicacion) : ?>

        <tr>

            <td class="publicacion"><?php echo $publicacion->nombre ?></td>

            <?php $aniversario_exists = false ?>

            <?php foreach ($columnas as $columna) : ?>

                <?php if( $columna->tipo_fecha != "aniversario" ) : ?>

                    <?php
                        /* $publicacion->id esta relacionada al descuento? */
                        $lista_publicaciones = explode(",", $columna->publicaciones);
                        if ( in_array($publicacion->id, $lista_publicaciones) ) :
                    ?>

                        <?php foreach ($publicacion->descuentos as $descuento) : ?>

                            <?php if ( $descuento->dia_descuento_id == $columna->dia_descuento_id ) : ?>

                                <td><?php echo $descuento->porcentaje ?>%</td>

                            <?php endif ?>

                        <?php endforeach ?>

                    <?php else : ?>

                        <td>0%</td>

                    <?php endif ?>

                <?php else : ?>

                    <?php
                        /* $publicacion->id esta relacionada al aniversario? */
                        $lista_publicaciones = explode(",", $columna->publicaciones);
                        if ( in_array($publicacion->id, $lista_publicaciones) ) :
                    ?>

                        <?php $aniversario_exists = true ?>

                    <?php endif ?>

                <?php endif ?>

            <?php endforeach ?>

            <?php if ( $aniversario_exists ) : ?>

                <?php foreach ($publicacion->descuentos as $descuento) : ?>

                    <?php if ( $descuento->tipo_fecha == "aniversario" ) : ?>

                        <td>
                            <?php
                                $fecha = json_decode($descuento->fecha);
                                echo $fecha->dia . ' de ' .  map_month_spanish(date('M', mktime(0, 0, 0, $fecha->mes, 10)));
                            ?>
                            <br/>
                            <?php echo $descuento->porcentaje ?>%
                        </td>

                    <?php endif ?>

                <?php endforeach ?>

            <?php else : ?>

                <td>0%</td>

            <?php endif ?>

        </tr>

    <?php endforeach; ?>

    </tbody>
</table>