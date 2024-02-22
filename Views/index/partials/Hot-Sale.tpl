<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="section-header text-center">
					<div class="custom-title">
						<h2 class="h2">Muebles en tendencía</h2>
					</div>
					<p class="alt">DESCUBRE MUEBLES EXCLUSIVOS</p>
				</div>
			</div>
		</div>
		<div class="productSlider-style2 grid-products">
			{foreach from=$hot_sale item=product}

				<div class="col-12 item">
					<!-- start product image -->
					<div class="product-image">
						<!-- start product image -->
						<a href="product/{$product.keyword}" class="grid-view-item__link">
							<!-- image -->
							<img class="primary blur-up lazyload" data-src="{$vars.images}product-images/img_demo.webp" src="{$vars.images}product-images/img_demo.webp" alt="image" title="product">
							<!-- End image -->
							<!-- Hover image -->
							<img class="hover blur-up lazyload" data-src="{$vars.images}product-images/img_demo.webp" src="{$vars.images}product-images/img_demo.webp" alt="image" title="product">
							<!-- End hover image -->
						</a>
						<!-- end product image -->
						<!-- Start product button -->
						{* <form class="variants add" action="#" onclick="window.location.href='cart.html'"method="post">
							<button class="btn btn-addto-cart" type="button" tabindex="0">Add To Cart</button>
						</form> *}
						<div class="button-set">
							<a href="javascript:void(0)" title="{translate('QUICK_VIEW', 'product')}" class="quick-view-popup quick-view" data-toggle="modal" data-target="#content_quickview" data-id="{$product.product_id}">
								<i class="icon anm anm-search-plus-r"></i>
							</a>
							{if !empty($customer)}
								<div class="wishlist-btn">
									<a class="wishlist add-to-wishlist" href="javascript:void(0)" title="{translate('ADD_WISHLIST', 'main')}" data-id="{$product.product_id}" data-qv="0">
										<i class="icon anm {($product.is_wishlist|boolval)? 'anm-heart': 'anm-heart-l'}"></i>
									</a>
								</div>
							{/if}
						</div>
						<!-- end product button -->
					</div>
					<!-- end product image -->
					<!--start product details -->
					<div class="product-details text-center">
						<!-- product name -->
						<div class="product-name">
							<a href="product/{$product.keyword}" title="{$product.product_name}">{$product.product_name}</a>
						</div>
						<!-- End product name -->
						<!-- product price -->
						<div class="product-price">
							<span class="price">${$product.unit_price|number_format:2} {translate('CURRENCY', 'main')}</span>
						</div>
						<!-- End product price -->

						{*<button class="btn btn-addto-cart" type="button" tabindex="{$product@iteration}" data-id="{$product.product_id}" style="width: 100%; padding: 15px">
							<i class="bi bi-cart mr-2"></i>{translate('ADD_TO_CART', 'main')}
						</button>*}

					</div>
					<!-- End product details -->
				</div>
			{/foreach}
		</div>
	</div>
</div>
