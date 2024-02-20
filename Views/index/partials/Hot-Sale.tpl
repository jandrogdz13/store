<!--Tab slider-->
<div class="tab-slider-product section mt-5 mb-5">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="section-header text-center">
					<div class="custom-title">
						<h2 class="h2">Muebles en tendencía</h2>
					</div>
					<p class="alt">DESCUBRE MUEBLES EXCLUSIVOS</p>
				</div>
				<div class="tabs-listing">
					<div class="tab_container">
						<div id="tab1" class="tab_content grid-products">
							<div class="productSlider">
                                {assign var=index_carousel value="1"}
                                {foreach from=$products item=product}
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
<!--End Tab slider-->
