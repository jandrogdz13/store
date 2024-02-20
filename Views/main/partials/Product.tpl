{assign var=item_class value=($index_carousel|boolval)? 'col-md-12': 'col-6 col-sm-6 col-md-4 col-lg-3 grid-view-item '}

<div class="{$item_class} item">
	<!-- start product image -->
	<div class="product-image">
		<!-- start product image -->
		<a href="product/{$product.keyword}">
			<!-- image -->
			<img class="primary blur-up lazyload" data-src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="image" title="product">
			<!-- End image -->
			<!-- Hover image -->
			<img class="hover blur-up lazyload" data-src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="image" title="product">
			<!-- End hover image -->
			<!-- product label -->
            {*<div class="product-labels rectangular">
				<span class="lbl on-sale">-16%</span>
				<span class="lbl pr-label1">new</span>
			</div>*}
			<!-- End product label -->
		</a>
		<!-- end product image -->

		<!-- countdown start -->
        {*<div class="saleTime desktop" data-countdown="2024/03/01"></div>*}
		<!-- countdown end -->

		<!-- Start product button -->
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
			<a href="product/{$product.keyword}">{$product.product_name}</a>
		</div>
		<!-- End product name -->
		<!-- product price -->
		<div class="product-price">
            {*<span class="old-price">$500.00</span>*}
			<span class="price">${$product.unit_price|number_format:2} {translate('CURRENCY', 'main')}</span>
		</div>
		<!-- End product price -->
		<button class="btn btn-addto-cart" type="button" tabindex="{$product@iteration}" data-id="{$product.product_id}" style="width: 100%; padding: 15px">
			<i class="bi bi-cart mr-2"></i>{translate('ADD_TO_CART', 'main')}
		</button>
	</div>
	<!-- End product details -->
</div>
