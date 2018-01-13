<table id="comisiones" class="table table-striped table-hover table-bordered">
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
					$hay_comision = false;
					$comision = "0";
				?>

				<?php for ($j=0; $j < count($publicacion->comisiones); $j++) { ?>

					<?php
					switch ($publicacion->comisiones[$j]['dia']) {
						case $columnas[$i]:
							$hay_comision = true;
							$comision = $publicacion->comisiones[$j]['comision'];
							break;
					}
					?>

				<?php } ?>

				<td class="comision"><?php echo $comision ?> %</td>
				
			<?php } ?>

		</tr>	

	<?php endforeach; ?>

	</tbody>
</table>