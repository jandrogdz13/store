{*<!--Collection Banner-->
<div class="collection-header">
	<div class="collection-hero">
		<div class="collection-hero__image">
			<img class="blur-up lazyload" data-src="{$vars.images}cat-women2.jpg" src="{$vars.images}cat-women2.jpg" alt="Women" title="Women" />
		</div>
		<div class="collection-hero__title-wrapper">
			<h1 class="collection-hero__title page-width">{$category}{(!empty($sub_category))? "  /  `$sub_category`": ''}
			</h1>
		</div>
	</div>
</div>
<!--End Collection Banner-->*}
<!--Page Title-->
<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper"><h1 class="page-width">{$category}{(!empty($sub_category))? "  /  `$sub_category`": ''}</h1></div>
	</div>
</div>
<!--End Page Title-->

<div class="container">
	<div class="row">
		<!--Sidebar-->
		<div class="col-12 col-sm-12 col-md-3 col-lg-2 sidebar filterbar">
			<div class="closeFilter d-block d-md-none d-lg-none"><i class="icon icon anm anm-times-l"></i></div>
			<div class="sidebar_tags">
				<!--Price Filter-->
				<div class="sidebar_widget filterBox filter-widget">
					<div class="widget-title">
						<h2>Precio</h2>
					</div>
					<form action="#" method="post" class="price-filter">
						<div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
							<div class="ui-slider-range ui-widget-header ui-corner-all"></div>
							<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
							<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
						</div>
						<div class="row">
							<div class="col-6">
								<p class="no-margin"><input id="amount" type="text"></p>
							</div>
							<div class="col-6 text-right margin-25px-top">
								<button class="btn btn-secondary btn--small">{translate('LBL_FILTER', 'main')}</button>
							</div>
						</div>
					</form>
				</div>
				<!--End Price Filter-->
				<!--Color Swatches-->
				<div class="sidebar_widget filterBox filter-widget">
					<div class="widget-title">
						<h2>Madera</h2>
					</div>
					<div class="filter-color swacth-list clearfix">
						<span class="swacth-btn black"></span>
						<span class="swacth-btn white checked"></span>
						<span class="swacth-btn red"></span>
						<span class="swacth-btn blue"></span>
						<span class="swacth-btn pink"></span>
						<span class="swacth-btn gray"></span>
						<span class="swacth-btn green"></span>
						<span class="swacth-btn orange"></span>
						<span class="swacth-btn yellow"></span>
						<span class="swacth-btn blueviolet"></span>
						<span class="swacth-btn brown"></span>
						<span class="swacth-btn darkGoldenRod"></span>
						<span class="swacth-btn darkGreen"></span>
						<span class="swacth-btn darkRed"></span>
						<span class="swacth-btn dimGrey"></span>
						<span class="swacth-btn khaki"></span>
					</div>
				</div>
				<!--End Color Swatches-->
				<!--Popular Products-->
				{*<div class="sidebar_widget">
					<div class="widget-title"><h2>Lo más top</h2></div>
					<div class="widget-content">
						<div class="list list-sidebar-products">
							<div class="grid">
                                {for $foo=1 to 4}
									<div class="grid__item">
										<div class="mini-list-item">
											<div class="mini-view_image">
												<a class="grid-view-item__link" href="#">
													<img class="grid-view-item__image" src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="" />
												</a>
											</div>
											<div class="details"> <a class="grid-view-item__title" href="#">Nombre mueblet</a>
												<div class="grid-view-item__meta"><span class="product-price__price"><span class="money">$173.60</span></span></div>
											</div>
										</div>
									</div>
								{/for}
							</div>
						</div>
					</div>
				</div>*}
				<!--End Popular Products-->
			</div>
		</div>
		<!--End Sidebar-->
		<!--Main Content-->
		<div class="col-12 col-sm-12 col-md-9 col-lg-10 main-col">
			<div class="productList">
				<!--Toolbar-->
				<button type="button" class="btn btn-filter d-block d-md-none d-lg-none">
					Filtros
				</button>
				<div class="toolbar">
					<div class="filters-toolbar-wrapper">
						<div class="row">
							<div class="col-4 col-md-4 col-lg-4 filters-toolbar__item collection-view-as d-flex justify-content-start align-items-center">
								{*<a href="shop-left-sidebar.html" title="Grid View" class="change-view change-view--active">
									<img src="{$vars.images}grid.jpg" alt="Grid" />
								</a>
								<a href="shop-listview.html" title="List View" class="change-view">
									<img src="{$vars.images}list.jpg" alt="List" />
								</a>*}
							</div>
							<div class="col-4 col-md-4 col-lg-4 text-center filters-toolbar__item filters-toolbar__item--count d-flex justify-content-center align-items-center">
								<span class="filters-toolbar__product-count">Mostrando: {$products|count} productos</span>
							</div>
							<div class="col-4 col-md-4 col-lg-4 text-right">
								<div class="filters-toolbar__item">
									<label for="SortBy" class="hidden">Ordenar</label>
									<select name="SortBy" id="SortBy" class="filters-toolbar__input filters-toolbar__input--sort">
										<option value="title-ascending" selected="selected">Ordenar</option>
										<option>Lo más top</option>
										<option>Nombre A-Z</option>
										<option>Nombre Z-A</option>
										<option>Precio más bajo</option>
										<option>Precio más alto</option>
										<option>Lo más nuevo</option>
										<option>Lo más viejo</option>
									</select>
									<input class="collection-header__default-sort" type="hidden" value="manual">
								</div>
							</div>

						</div>
					</div>
				</div>
				<!--End Toolbar-->
				<div class="grid-products grid--view-items">
					<div class="row">
                        {assign var=index_carousel value="0"}
                        {foreach from=$products item=product}
                            {include file=$vars.config.path_views|cat:'main/partials/Product.tpl'}
						{/foreach}

						{*<div class="col-6 col-sm-6 col-md-4 col-lg-3 item grid-view-item style2 grid-view-item--sold-out">
							<div class="grid-view_image">
								<!-- start product image -->
								<a href="product-accordion.html" class="grid-view-item__link">
									<!-- image -->
									<img class="grid-view-item__image primary blur-up lazyload" data-src="{$vars.images}product-images/product-image27.jpg" src="{$vars.images}product-images/product-image27.jpg" alt="image" title="product" />
									<!-- End image -->
									<!-- Hover image -->
									<img class="grid-view-item__image hover blur-up lazyload" data-src="{$vars.images}product-images/product-image27-1.jpg" src="{$vars.images}product-images/product-image27-1.jpg" alt="image" title="product" />
									<!-- End hover image -->
									<span class="sold-out"><span>Sold out</span></span>
								</a>
								<!-- end product image -->

								<!--start product details -->
								<div class="product-details hoverDetails text-center mobile">
									<!-- product name -->
									<div class="product-name">
										<a href="#">Camelia Reversible Jacket in Navy/Blue</a>
									</div>
									<!-- End product name -->
									<!-- product price -->
									<div class="product-price">
										<span class="price">$488.00</span>
									</div>
									<!-- End product price -->

									<div class="product-review">
										<i class="font-13 fa fa-star-o"></i>
										<i class="font-13 fa fa-star-o"></i>
										<i class="font-13 fa fa-star-o"></i>
										<i class="font-13 fa fa-star-o"></i>
										<i class="font-13 fa fa-star-o"></i>
									</div>
								</div>
								<!-- End product details -->
							</div>
						</div>*}
					</div>
				</div>
			</div>
			<!--End Main Content-->
		</div>
	</div>
</div>

<!--Banner category-->
<div class="section imgBanners">
	<div class="container-fluid">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
			<a href="#">
				<img src="{$vars.images}collection/image-banner-home15-5.jpg" alt="Save Big On Popular Designs" title="Save Big On Popular Designs" class="blur-up ls-is-cached lazyloaded">
			</a>
		</div>
	</div>
</div>
<!--End Banner category-->

<!--Related Product Slider-->
<div class="tab-slider-product section">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="section-header text-center">
					<h2 class="h2">Productos relacionados</h2>
				</div>
				<div class="tabs-listing">
                    {*<ul class="tabs clearfix">
						<li class="active" rel="tab1">Recámaras</li>
						<li rel="tab2">Comedores</li>
						<li rel="tab3">Complementos</li>
					</ul>*}
					<div class="tab_container">
						<div id="tab1" class="tab_content grid-products">
							<div class="productSlider">
                                {assign var=index_carousel value="1"}
                                {foreach from=$products_related item=product}
                                	{include file=$vars.config.path_views|cat:'main/partials/Product.tpl'}
                                {/foreach}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End Related Product Slider-->
