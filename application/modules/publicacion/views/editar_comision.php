<div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
        
        <?php foreach ($publicaciones as $publicacion) : ?>
        
            <li <?php echo $publicacion->id == 1? 'class="active"' : ''; ?>><a href="#<?php echo $publicacion->id ?>" data-toggle="tab"><?php echo $publicacion->nombre ?></a></li>
        
        <?php endforeach ?>

    </ul>

    <div class="tab-content">
        
        <?php foreach ($publicaciones as $publicacion) : ?>
            
            <div class="tab-pane <?php echo $publicacion->id == 1? 'active' : '' ?>" id="<?php echo $publicacion->id ?>">

            <ul>

                <?php foreach ($publicacion->comisiones as $comision) : ?>
                    
                    <?php $fecha = json_decode($comision->fecha) ?>
                    
                    <li>
                        <div data-id="<?php echo $publicacion->id ?>" class="edit dias"><?php echo $fecha->dia ?></div>
                        <div data-id="<?php echo $publicacion->id ?>"class="edit comision"><?php echo $comision->comision ?></div>
                    </li>
                    
                <?php endforeach ?>

            </ul>

            </div>

        <?php endforeach ?>

    </div>
</div>