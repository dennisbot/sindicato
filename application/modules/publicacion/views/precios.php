<table id="precios" class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			
			<th>publicaci&oacute;n</th>
			<?php foreach ($columnas as $columna) : ?>

				<th><?php echo $columna ?></th>

			<?php endforeach ?>

		</tr>
	</thead>
	<tbody>
	
	<?php foreach ($publicaciones as $publicacion) : ?>

		<tr>
			
			<td class="publicacion"><?php echo $publicacion->nombre ?></td>

			<?php for ($i=0; $i < count($columnas); $i++) { ?>

				<?php
					$hay_precio = false;
					$precio = "0";
				?>

				<?php for ($j=0; $j < count($publicacion->precios); $j++) { ?>

					<?php
					switch ($publicacion->precios[$j]['dia']) {
						case $columnas[$i]:
							$hay_precio = true;
							$precio = $publicacion->precios[$j]['precio'];
							break;
					}
					?>

				<?php } ?>

				<td class="precio">S/ <?php echo $precio ?></td>
				
			<?php } ?>

		</tr>	

	<?php endforeach; ?>

	</tbody>
</table>