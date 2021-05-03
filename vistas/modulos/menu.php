<aside class="main-sidebar">

	 <section class="sidebar">

		<ul class="sidebar-menu">

			<li class="<?php if ($_GET["ruta"] == "inicio") {
				echo "active";
			} ?>">

				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>

			</li>


			<li class="<?php if ($_GET["ruta"] == "afiliados" || $_GET["ruta"] == "afiliados-no-socios" | $_GET["ruta"] == "afiliado-detalle") {
				echo "active";
			} ?>">

				<a href="afiliados">

					<i class="fa fa-users"></i>
					<span>Afiliados</span>

				</a>

			</li>

			<li class="<?php if ($_GET["ruta"] == "categorias") {
				echo "active";
			} ?>">

				<a href="categorias">

					<i class="fa fa-th"></i>
					<span>Categorías</span>

				</a>

			</li>

			<li class="<?php if ($_GET["ruta"] == "productos") {
				echo "active";
			} ?>">

				<a href="productos">

					<i class="fa fa-product-hunt"></i>
					<span>Productos</span>

				</a>

			</li>

			<?php

			if ($_SESSION["perfil"] == "Administrador" ){ ?>

			<li class="<?php if ($_GET["ruta"] == "usuarios") {
				echo "active";
			} ?>">

				<a href="usuarios">

					<i class="fa fa-user"></i>
					<span>Usuarios</span>

				</a>

			</li>


			<?php } ?>
			<!--<li>

				<a href="clientes">

					<i class="fa fa-users"></i>
					<span>Proveedores</span>

				</a>

			</li>-->

			<li class="<?php if ($_GET["ruta"] == "pedidos") {
				echo "active";
			} ?>">

				<a href="pedidos">

					<i class="fa fa-list"></i>
					<span>Pedidos</span>

				</a>

			</li>

			<li class="<?php if ($_GET["ruta"] == "entregas") {
				echo "active";
			} ?>">

				<!--<a href="entregas">

					<i class="fa fa-list"></i>
					<span>Entregas</span>

				</a>-->

			</li>

			<!--<li class="treeview">

				<a href="#">

					<i class="fa fa-list-ul"></i>

					<span>Pedidos</span>

					<span class="pull-right-container">

						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">

					<li>

						<a href="pedidos">

							<i class="fa fa-circle-o"></i>
							<span>Administrar pedidos</span>

						</a>

					</li>

					<li>

						<a href="crear-pedido">

							<i class="fa fa-circle-o"></i>
							<span>Crear pedido</span>

						</a>

					</li>

					<li>

						<a href="reportes">

							<i class="fa fa-circle-o"></i>
							<span>Reporte de pedidos</span>

						</a>

					</li>

				</ul>

			</li>

			<li class="treeview">

				<a href="#">

					<i class="fa fa-list-ul"></i>

					<span>Entregas</span>

					<span class="pull-right-container">

						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">

					<li>

						<a href="entregas">

							<i class="fa fa-circle-o"></i>
							<span>Administrar entregas</span>

						</a>

					</li>

					<li>

						<a href="crear-entrega">

							<i class="fa fa-circle-o"></i>
							<span>Crear entrega</span>

						</a>

					</li>

					<!--<li>

						<a href="reportes">

							<i class="fa fa-circle-o"></i>
							<span>Reporte de entregas</span>

						</a>

					</li>-->-->

				</ul>

			</li>

		</ul>

	 </section>

</aside>
