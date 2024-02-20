{if !empty($cart.products)}
	<ul class="mini-products-list justify-content-start">
    {foreach from=$cart.products item=prod_cart}
		<li class="item">
			<a class="product-image" href="#">
				<img src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="{$prod_cart.product_name}" />
			</a>
			<div class="product-details" data-id="{$prod_cart.product_id}">
				<a href="#" class="remove" data-id="{$prod_cart.product_id}"><i class="anm anm-times-l" aria-hidden="true"></i></a>
				<a class="pName" href="product/{$prod_cart.keyword}">{$prod_cart.product_name}</a>
                {*<div class="variant-cart">Black / XL</div>*}
				<div class="wrapQtyBtn">
					<div class="qtyField" data-stock="{$prod_cart.stock}" data-qv="0">
						<a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
						<input type="text" id="Quantity" name="quantity" value="{$prod_cart.quantity}" class="product-form__input qty" disabled>
						<a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="priceRow">
					<div class="product-price">
						<span class="money {($prod_cart.discount gt 0)? 'text-decoration-line-through': ''}">${$prod_cart.unit_price|number_format:2} {translate('CURRENCY', 'main')}</span>
					</div>
				</div>
                {if $prod_cart.discount gt 0}
					<div class="priceRow">
						<div class="product-price">
							<span class="money">${$prod_cart.unit_price_inc_discount|number_format:2} {translate('CURRENCY', 'main')}</span>
						</div>
					</div>
                {/if}

			</div>
		</li>
    {/foreach}
	</ul>
{else}
	<img src="{$vars.images}empty-cart.png" alt="carrito vacío">
	<p class="h1 text-center">Tú carrito esta vacío</p>
	<p class="text-center w-75 mx-auto">Parece que no has añadido nada a tu carrito. Continúe y explore las categorías principales</p>
{/if}
