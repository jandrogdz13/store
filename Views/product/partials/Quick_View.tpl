<div id="ProductSection-product-template" class="product-template__container prstyle1">
	<div class="product-single">
		<!-- Start model close -->
		<a href="javascript:void()" data-dismiss="modal" class="model-close-btn pull-right" title="close">
			<span class="icon icon anm anm-times-l"></span>
		</a>
		<!-- End model close -->
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-12">
				<div class="product-details-img">
					<div class="pl-20">
						<img src="{$vars.images}product-images/img_demo.webp" alt="" />
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-12">
				<div class="product-single__meta">
					<h2 class="product-single__title">{$quick_product.product_name}</h2>
					<div class="prInfoRow">
						<div class="product-stock">
                            {if $quick_product.stock gt 0}
								<span class="instock">{translate('IN_STOCK', 'main')}</span>
                            {else}
								<span class="outstock">{translate('OUT_STOCK', 'main')}</span>
                            {/if}
						</div>
						<div class="product-sku">SKU: <span class="variant-sku">{$quick_product.sku}</span></div>
					</div>
					<p class="product-single__price product-single__price-product-template">
						<span class="visually-hidden">{translate('UNIT_PRICE', 'main')}</span>
						<span id="ComparePrice-product-template">
							<span class="money {($quick_product.discount gt 0)? 'text-decoration-line-through': ''}">${$quick_product.unit_price|number_format:2}</span>
						</span>
                        {if $quick_product.discount gt 0}
						<span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
							<span id="ProductPrice-product-template">
								<span class="money">${$quick_product.unit_price_inc_discount|number_format:2}</span>
							</span>
						</span>
						{/if}
					</p>
					<div class="product-single__description rte">
						{$quick_product.description}
					</div>


					<!-- Product Action -->
					<div class="product-action clearfix">
						<div class="product-form__item--quantity">
							<div class="wrapQtyBtn">
								<div class="qtyField" data-stock="{$quick_product.stock}" data-qv="1">
									<a class="qtyBtn minus"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
									<input type="text" id="Quantity" name="quantity" value="1" class="product-form__input qty">
									<a class="qtyBtn plus"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
						<div class="product-form__item--submit">
							<button type="button" name="add" class="btn product-form__cart-submit" data-id="{$quick_product.product_id}" data-qv="1">
								<span><i class="bi bi-cart mr-2"></i>{translate('ADD_TO_CART', 'main')}</span>
							</button>
						</div>
					</div>
					<!-- End Product Action -->

					{if !empty($customer)}
						<div class="display-table shareRow">
							<div class="display-table-cell">
								<div class="wishlist-btn">
									<a class="wishlist add-to-wishlist" href="#" title="{translate('ADD_WISHLIST', 'main')}" data-id="{$quick_product.product_id}">
										<i class="icon anm {($quick_product.is_wishlist|boolval)? 'anm-heart': 'anm-heart-l'}" aria-hidden="true"></i> <span>{translate('ADD_WISHLIST', 'main')}</span>
									</a>
								</div>
							</div>
						</div>
					{/if}

				</div>
			</div>
		</div>
		<!--End-product-single-->
	</div>
</div>
