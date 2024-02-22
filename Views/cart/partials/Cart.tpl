{if !empty($cart.products)}
	<ul class="mini-products-list justify-content-start">
    {foreach from=$cart.products item=prod_cart}
		<li class="item position-relative">
			<a class="product-image" href="#">
				<img src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="{$prod_cart.product_name}" />
			</a>
			<div class="product-details d-flex" data-id="{$prod_cart.product_id}" style="min-height: 90px; ">
				<a href="javascript:void(0);" class="remove" data-id="{$prod_cart.product_id}">
					<i class="bi bi-trash" aria-hidden="true"></i>
				</a>

				<div class="name-qty d-flex flex-column justify-content-between w-100">
					<div>
						<a class="pName" href="product/{$prod_cart.keyword}">{$prod_cart.product_name}</a>
						<div class="variant-cart">Madera parota</div>
					</div>

					<div class="up-discount d-flex justify-content-between align-items-center">

						<div class="wrapQtyBtn">
							<div class="qtyField" data-stock="{$prod_cart.stock}" data-qv="0">
								<a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
								<input type="text" id="Quantity" name="quantity" value="{$prod_cart.quantity}" class="product-form__input qty" disabled style="background-color: #ffffff;">
								<a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
							</div>
						</div>

						<div class="d-flex flex-column">
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

					</div>


				</div>
			</div>
		</li>
    {/foreach}
	</ul>
{else}
	<img src="{$vars.images}empty-cart.png" alt="carrito vacío">
	<p class="h1 text-center">Tú carrito esta vacío</p>
	<p class="text-center w-75 mx-auto">Parece que no has añadido nada a tu carrito. Continúe y explore las categorías principales</p>
{/if}
