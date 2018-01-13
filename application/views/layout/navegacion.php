<div class="navbar navbar-inverse">
	<div class="navbar-inner" style="border-radius: 0">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<?php echo  anchor('', '<i class="icon-home icon-white"></i> ' . $this->config->item('site_name'), array('class' => 'brand')); ?>
			<div class="nav-collapse collapse">

				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Publicaciones<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">Proveedor</li>
							<li><a href="<?php echo base_url('proveedor/index') ?>"><i class="icon-th-list"></i> Lista Proveedores</a></li>
							<li><a href="<?php echo base_url('proveedor/form') ?>"><i class="icon-plus"></i> Nuevo Proveedor</a></li>
							<li class="divider"></li>
							<li class="nav-header">Publicaci&oacute;n</li>
							<li><a href="<?php echo base_url('publicacion/index') ?>"><i class="icon-th-list"></i> Lista publicaciones</a></li>
							<li><a href="<?php echo base_url('publicacion/form') ?>"><i class="icon-plus"></i> Nueva Publicaci&oacute;n</a></li>
							<li class="divider"></li>
							<li class="nav-header">Comisiones Sindicato</li>
							<li><a href="<?php echo base_url('publicacion/comisiones') ?>"><i class="icon-th-list"></i> Lista comisiones para el sindicato</a></li>
							<li><a href="<?php echo base_url('publicacion/nueva_comision') ?>"><i class="icon-plus"></i> Nueva Comisi&oacute;n</a></li>
							<li class="divider"></li>
							<li class="nav-header">Descuentos Vendedores</li>
							<li><a href="<?php echo base_url('publicacion/descuentos') ?>"><i class="icon-th-list"></i> Lista de descuentos para el vendedor</a></li>
							<li><a href="<?php echo base_url('publicacion/nuevo_descuento') ?>"><i class="icon-plus"></i> Nuevo Descuento</a></li>
							<li class="divider"></li>
							<li class="nav-header">Precios al público</li>
							<li><a href="<?php echo base_url('publicacion/precios') ?>"><i class="icon-th-list"></i> Lista precios</a></li>
							<li><a href="<?php echo base_url('publicacion/nuevo_precio') ?>"><i class="icon-plus"></i> Nuevo Precio</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Pautas<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url('pauta/ver') ?>"><i class="icon-th-list"></i> Ver/generar Pauta</a></li>
							<li><a href="<?php echo base_url('pauta/plantilla') ?>"><i class="icon-th-list"></i> Ver/Editar Plantillas</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Remisiones<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">remisi&oacute;n</li>
							<li><a href="<?php echo base_url();?>remision"><i class="icon-th-list"></i> Lista remisiones</a></li>
							<li><a href="<?php echo base_url();?>remision/form"><i class="icon-plus"></i> Nueva remisi&oacute;n</a></li>
							<li class="divider"></li>
							<li class="nav-header">Devoluciones</li>
							<li><a href="<?php echo base_url();?>remision/devoluciones"><i class="icon-th-list"></i> Lista devoluciones</a></li>
							<li><a href="<?php echo base_url();?>remision/devolucion"><i class="icon-plus"></i> Hacer devoluci&oacute;n</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Vendedores<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">Vendedor</li>
							<li><a href="<?php echo base_url();?>vendedor/index"><i class="icon-th-list"></i> Lista vendedores</a></li>
							<li><a href="<?php echo base_url();?>vendedor/form"><i class="icon-plus"></i> Agregar vendedor</a></li>
							<li class="divider"></li>
							<li class="nav-header">Devoluciones</li>
							<li><a href="<?php echo base_url();?>"><i class="icon-plus"></i> Hacer devoluci&oacute;n</a></li>
							<li><a href="<?php echo base_url();?>pago/edicion"><i class="icon-pencil"></i> Edici&oacute;n de pagos</a></li>
							<li class="divider"></li>
							<li class="nav-header">Deudas</li>
							<li><a href="<?php echo base_url();?>vendedor/deudores"><i class="icon-plus"></i> Lista de deudores</a></li>
							<li class="nav-header">Pagos/ticket</li>
							<li><a href="<?php echo base_url('vendedor/ticket');?>"><i class="icon-file-text"></i> Ver pagos/ticket</a></li>
							<li class="divider"></li>
							<li class="nav-header">Padrón</li>
							<li><a href="<?php echo base_url();?>vendedor/ordenar"><i class="icon-sort-by-order"></i> Ordenar</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav">
					<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Egresos/Ingresos<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="nav-header">Egresos</li>
								<li><a href="<?php echo base_url('egreso/form') ?>"><i class="icon-plus"></i> Nuevo egreso </a></li>
								<li><a href="<?php echo base_url('egreso/index') ?>"><i class="icon-th-list"></i> Lista de egresos</a></li>
								<li class="divider"></li>
								<li class="nav-header">Ingresos</li>
								<li><a href="<?php echo base_url('ingreso/form') ?>"><i class="icon-plus"></i> Nuevo ingreso </a></li>
								<li><a href="<?php echo base_url('ingreso/index') ?>"><i class="icon-th-list"></i> Lista de ingresos</a></li>
								<li class="divider"></li>
								<li class="nav-header">Reportes</li>
								<li><a href="<?php echo base_url('ingreso/form_ingresos') ?>"><i class="icon-list-alt"></i> Ganancias </a></li>
								<li><a href="<?php echo base_url();?>vendedor/pagos"><i class="icon-list-alt"></i> Pagos vendedores</a></li>

							</ul>
					</li>
				</ul>
				<ul class="nav pull-right">
					<?php if ($this->milib->logueado()) : ?>
						<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Mi cuenta<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<!-- <li><?php echo anchor('operador/cuenta', '<i class="icon-cog"></i> Config'); ?></li>  -->
									<li><?php echo anchor("operador/logout", "<i class='icon-off'></i> Salir"); ?></li>
								</ul>
						</li>
					<?php endif ?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>