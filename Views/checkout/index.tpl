<!--Page Title-->
<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper d-flex justify-content-between align-items-center px-3">
			<a href="/" title="{translate('BACK_TO_SOTRE', 'main')}"><i class="mr-2 bi bi-arrow-left h1"></i></a>
			<h1 class="page-width">{translate('CHECKOUT_TITLE', $module)}</h1>
			<span>&nbsp;</span>
		</div>
	</div>
</div>
<!--End Page Title-->

<div class="container">
	<input type="hidden" name="address_id" id="address_id" value="{$cart.address.addressid}">
	<input type="hidden" name="rate_id" id="rate_id" value="{$cart.shipping.rate_id}">
	<input type="hidden" name="payment_id" id="payment_id" value="{$cart.payment.payment_id}">

	<div class="row">
		<div id="checkout-cart" class="col-12 col-sm-12 col-md-7 col-lg-7 main-col">
            {foreach from=$cart.products item=product}
			<div class="d-flex justify-content-start mb-3 position-relative">
				<div class="cart__image-wrapper mr-3">
					<img class=" cart__image" src="{$vars.images}product-images/img_demo.webp" alt="{$product.product_name}">
				</div>

				<div class="name-qty d-flex flex-column justify-content-between w-100">
					<div>
						<a class="pName" href="product/{$product.keyword}">{$product.product_name}</a>
						<div class="variant-cart">Madera parota</div>
					</div>

					<div class="up-discount d-flex justify-content-between align-items-center">

						<div class="wrapQtyBtn">
							<div class="qtyField" data-stock="{$product.stock}" data-qv="0">
								<input type="text" id="Quantity" name="quantity" value="x{$product.quantity}" class="product-form__input qty" disabled style="background-color: #ffffff;">
							</div>
						</div>

						<div class="d-flex flex-column">
							<div class="priceRow">
								<div class="product-price">
									<span class="money {($product.discount gt 0)? 'text-decoration-line-through': ''}">${$product.unit_price_inc_discount|number_format:2} {translate('CURRENCY', 'main')}</span>
								</div>
							</div>
                            {if $product.discount gt 0}
								<div class="priceRow">
									<div class="product-price">
										<span class="money">${($product.quantity * $product.unit_price_inc_discount)|number_format:2} {translate('CURRENCY', 'main')}</span>
									</div>
								</div>
                            {/if}
						</div>
					</div>
				</div>
			</div>
			{/foreach}
		</div>
		<div class="col-12 col-sm-12 col-md-5 col-lg-5 cart__footer">
			<div class="solid-border">
					<h2><i class="bi bi-truck"></i> {translate('BLOCK_SHIPPING', $module)}</h2>
					<span>Recibe: </span>
					<span class="p-0 mb-0 address-name">{(!empty($cart.address))? $cart.address.name: $customer.name}</span>
					<p class="p-0">{translate('SEND_TO', $module)}
						<a href="" id="select_address" class="text-underline" style="color: #000">
							{(!empty($cart.address))? "C.P. `$cart.address.postcode`, `$cart.address.suburb`": 'Elije una dirección'}
						</a>
					</p>
					{*<textarea name="note" id="CartSpecialInstructions" class="cart-note__input"></textarea>*}
					<ul id="rates">
						<li
							class="rate d-flex align-items-center justify-content-start"
							data-id="12345"
							data-provider="Flete local"
							data-service_level_name="Terrestre"
							data-service_level_code="FT"
							data-days="5"
							data-total_pricing="120"
						>
							<img src="{$vars.images}delivery-truck.svg" alt="Flete Local" />
							<div class="provider d-flex flex-column justify-content-center align-items-start" data-service-code="FT">
									<span>Flete local<span class="service_level">  Terrestre</span></span>
									<span>Entrega estimada 5 días</span>
									<span>$120.00 {translate('CURRENCY', 'main')}</span>
							</div>
						</li>
					</ul>
					<div class="col-12 col-sm-12 col-md-12 actionRow text-center">
						<div><input type="button" class="btn btn--secondary get-rates" value="{translate('GET_RATES', $module)}"></div>
					</div>
				</div>
			<div class="solid-border">
				<h2><i class="bi bi-credit-card"></i> {translate('BLOCK_GATEWAYS', $module)}</h2>

                {*<div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>*}
				<ul class="accordion-list">
					<li class="payment_gateway" data-id="spei">
						<h3>Transferencía directa SPEI</h3>
						<div class="answer">
							<p>No cuenta etc...</p>
						</div>
					</li>
					<li class="payment_gateway" data-id="paypal">
						<h3>Paypal Express</h3>
						<div class="answer">
							<div id="paypal-buttons-container" class="mt-3"></div>
						</div>
					</li>
					<li class="payment_gateway" data-id="mercadopago">
						<h3>Mercado Pago</h3>
						<div class="answer">
							<div id="cardPaymentBrick_container"></div>
						</div>
					</li>
				</ul>

				<div class="row pb-2 d-flex justify-content-between flex-nowrap">
					<span class="col-sm-6 cart__subtotal-title">Subtotal</span>
					<span class="col-sm-6 text-right"><span class="money">${$cart.totals.subtotal|number_format:2}</span></span>
				</div>
				{*<div class="row border-bottom pb-2 pt-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">Tax</span>
					<span class="col-12 col-sm-6 text-right">${$cart.totals.taxes|number_format:2}</span>
				</div>*}
				<div class="row shipping mb-2 d-flex justify-content-between flex-nowrap">
					<span class="col-sm-6 cart__subtotal-title">
						{translate('CART_SHIPPING', 'main')}
						<p id="provider-service">{$cart.shipping.provider} -- <small>{$cart.shipping.service_level_name}</small></p>
					</span>
					<span class="col-sm-6 shipping_cost text-right">
						${$cart.shipping.total_pricing|number_format:2}
					</span>
				</div>
				<div class="row pb-2 pt-2 d-flex justify-content-between flex-nowrap">
					<span class="col-sm-6 cart__subtotal-title"><strong class="h2">{translate('CART_TOTAL', 'main')}</strong></span>
					<span class="col-sm-6 cart__subtotal-title cart__subtotal text-right">
						<span class="money total-amount-checkout h2">${($cart.totals.subtotal_inc_disc + $cart.shipping.total_pricing)|number_format:2}</span>
					</span>
				</div>

				<p class="cart_tearm">
					<label>
						<input type="checkbox" name="tearm" id="cartTearm" class="checkbox" value="tearm" required="">
                        {translate('TERM_CONDITIONS', 'main')}
					</label>
				</p>
				<button type="submit" name="checkout" id="cartCheckout" class="btn btn-secondary checkout" style="display: none;">Terminar compra</button>
				<div class="paymnet-img"><img src="{$vars.images}payment-img.jpg" alt="Payment"></div>

				{*<div class="row shipping mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">
						<strong>{translate('CART_SHIPPING', 'main')}</strong>
						<p id="provider-service">{$cart.shipping.provider} -- <small>{$cart.shipping.service_level_name}</small></p>
					</span>
					<span class="col-12 col-sm-6 cart__subtotal-title shipping_cost cart__subtotal text-right">
						<span class="money">${$cart.shipping.total_pricing|number_format:2}</span>
					</span>
				</div>
				<div class="row mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title"><strong>{translate('CART_TOTAL', 'main')}</strong></span>
					<span class="col-12 col-sm-6 cart__subtotal-title total cart__subtotal text-right">
						<span class="money">${$cart.totals.subtotal_inc_disc|number_format:2}</span>
						<small>{translate('CURRENCY', 'main')}</small>
					</span>
				</div>*}
			</div>
			{*<div class="solid-border">
				<div class="row shipping mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">
						<strong>{translate('CART_SHIPPING', 'main')}</strong>
						<p id="provider-service">{$cart.shipping.provider} -- <small>{$cart.shipping.service_level_name}</small></p>
					</span>
					<span class="col-12 col-sm-6 cart__subtotal-title shipping_cost cart__subtotal text-right">
						<span class="money">${$cart.shipping.total_pricing|number_format:2}</span>
					</span>
				</div>
				<div class="row mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title"><strong>{translate('CART_TOTAL', 'main')}</strong></span>
					<span class="col-12 col-sm-6 cart__subtotal-title total cart__subtotal text-right">
						<span class="money">${$cart.totals.subtotal_inc_disc|number_format:2}</span>
						<small>{translate('CURRENCY', 'main')}</small>
					</span>
				</div>
				*}{*<div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>*}{*
				<p class="cart_tearm">
					<label>
						<input type="checkbox" name="tearm" id="cartTearm" class="checkbox" value="tearm" required="">
						{translate('TERM_CONDITIONS', 'main')}
					</label>
				</p>
				<input type="submit" name="checkout" id="cartCheckout" class="btn btn--small-wide checkout" value="Checkout" disabled="disabled">
				<div class="paymnet-img"><img src="{$vars.images}payment-img.jpg" alt="Payment"></div>
			</div>*}
		</div>
	</div>
</div>


<!--Address Popup-->
<div id="checkout-address" class="block block-cart">
	<div class="loader-cart">
		<div class="loader">
			<span class="dot"></span>
			<span class="dot"></span>
			<span class="dot"></span>
		</div>
	</div>
	<div id="cart-title" class="position-relative">
		<h2 class="text-left p-3">{translate('SIDEBAR_ADDRESSES', $module)}</h2>
		<span class="close-sidebar"><i class="icon icon anm anm-times-l"></i> </span>
	</div>
	<div id="content-addresses" class="container px-3 pt-3"></div>
</div>
<!--End Address Popup-->
