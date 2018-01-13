<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<dl>
    	<input type="hidden" name="vendedor_id" value="<?php echo $this->mdl_remision->form_value('vendedor_id'); ?>" />
	</dl>
	<form class="form-horizontal" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<div class="control-group">
	        <div class="controls">
	            <?php $today = date("d/m/Y"); ?>
	            Seleccione la fecha:&nbsp;
	            <span class="input-append date calendari" id="dpStartDate">
	            	<input type="text" class="datepicker" name="fecha_inicio" value="<?php echo ($this->input->post('fecha_inicio') != '')?$this->input->post('fecha_inicio'):$today; ?>" readonly="readonly" />
	            	<span class="add-on"><i class="icon-calendar"></i></span>
	            </span>
	            <input type="submit" class="btn btn-success" name="btn_consulta" value="Mostrar pagos" />
			</div>
		</div>
        <div class="control-group">
            <div class="controls">
                <div>
                	<?php
                		if(!empty($pagos)){
                			$suma_total = 0;
                			$colum = 2;
                		?>
					      	<table class="table table-striped" style="width:100%;border:1px">
						        <thead>
						          <tr>
						              <?php for ($t = 0; $t < 2; $t++) : ?>
						                  <th>N°</th>
						                  <th><span>Vendedor</span></th>
						                  <th><span>Monto</span></th>
						              <?php endfor; ?>
						          </tr>
						        </thead>
						        <tbody>
						        <?php 
						        $i = 0;
						        $j = 1;	
						        foreach ($pagos as $pago) { 
									if($i % $colum == 0){
								        echo "<tr>";
								    }
								?>    
						        	<td><?php echo $j; ?></td>
						        	<td><?php echo $pago->nickname; ?></td>
						        	<td>S/. <?php echo $pago->pago; ?></td>
						        <?php
									if(($i % $colum) == ($colum - 1) || ($i + 1) == count($pagos)){
										echo "</tr>";
									}
									$i++;						         
						        	$j++;
						        	$suma_total += $pago->pago;
						        } ?>
						        
						        </tbody>
					  		</table>
					  		<table class="table table-striped" style="width:100%;border:1px">
						        <tr>
						        	<td style="background:#5FBE5F;color: #FFF">Total ingresos: S/. <?php echo number_format($suma_total, 2); ?></td>
						        </tr>
					  		</table>

                		<?php }
                		else {
                			echo 'Lo sentimos no tenemos pagos para la fecha seleccionada.';
                		} ?>
                </div>
            </div>
        </div>
    </form>
</div><!-- padded -->