<!--Page Title-->
<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper"><h1 class="page-width">{translate('CHECKOUT_TITLE', $module)}</h1></div>
	</div>
</div>
<!--End Page Title-->

<div class="container">
	<input type="hidden" name="address_id" id="address_id" value="{$cart.address.addressid}">
	<input type="hidden" name="rate_id" id="rate_id" value="{$cart.shipping.rate_id}">
	<input type="hidden" name="payment_id" id="payment_id" value="{$cart.payment.payment_id}">

	<div class="row mb-2">
		<div class="col-12">
			<a href="/">
				<button class="btn btn--secondary">
				{translate('BACK_TO_SOTRE', $module)}
				</button>
			</a>
		</div>
	</div>
	<div class="row">
		<div id="checkout-cart" class="col-12 col-sm-12 col-md-7 col-lg-7 main-col">
			<form action="#" method="post" class="cart style2">
				<table>
					<thead class="cart__row cart__header">
					<tr>
						<th class="action">&nbsp;</th>
						<th colspan="2" class="text-center">{translate('TD_PRODUCT', $module)}</th>
						<th class="text-center">{translate('TD_UP', $module)}</th>
						<th class="text-center">{translate('TD_QTY', $module)}</th>
						<th class="text-right">{translate('TD_TOTAL', $module)}</th>
					</tr>
					</thead>
					<tbody>
					{foreach from=$cart.products item=product}
						<tr class="cart__row border-bottom line1 cart-flex border-top">
							<td class="text-center small--hide">
								<a href="" class="btn btn--secondary cart__remove" data-id="{$product.product_id}">
									<i class="icon icon anm anm-times-l"></i>
								</a>
							</td>
							<td class="cart__image-wrapper cart-flex-item">
								<a href="#"><img class="cart__image" src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="{$product.product_name}"></a>
							</td>
							<td class="cart__meta small--text-left cart-flex-item">
								<div class="list-view-item__title">
									<a href="product/{$product.keyword}">{$product.product_name}</a>
								</div>
							</td>
							<td class="cart__price-wrapper cart-flex-item">
								<span class="money">${$product.unit_price_inc_discount|number_format:2}</span>
							</td>
							<td class="cart__update-wrapper cart-flex-item text-right">
								<div class="cart__qty text-center">
									<div class="qtyField">
										<input class="cart__qty-input qty" type="text" name="updates[]" id="qty" value="{$product.quantity}" pattern="[0-9]*" readonly>
									</div>
								</div>
							</td>
							<td class="text-right small--hide cart-price">
								<div><span class="money">${($product.quantity * $product.unit_price_inc_discount)|number_format:2}</span></div>
							</td>
						</tr>
					{/foreach}

					</tbody>
					{*<tfoot>
					<tr>
						<td colspan="3" class="text-left"><a href="http://annimexweb.com/" class="btn--link cart-continue"><i class="icon icon-arrow-circle-left"></i> Continue shopping</a></td>
						<td colspan="3" class="text-right"><button type="submit" name="update" class="btn--link cart-update"><i class="fa fa-refresh"></i> Update</button></td>
					</tr>
					</tfoot>*}
				</table>
			</form>
		</div>
		<div class="col-12 col-sm-12 col-md-5 col-lg-5 cart__footer">
			<div class="solid-border">
					<h2><i class="bi bi-truck"></i> {translate('BLOCK_SHIPPING', $module)}</h2>
					<p class="p-0 mb-0">{$customer.name}</p>
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
				<p class="cart_tearm">
					<label>
						<input type="checkbox" name="tearm" id="cartTearm" class="checkbox" value="tearm" required="">
                        {translate('TERM_CONDITIONS', 'main')}
					</label>
				</p>
				<input type="submit" name="checkout" id="cartCheckout" class="btn btn--small-wide checkout" value="Checkout" style="display: none;">
				<div class="paymnet-img"><img src="{$vars.images}payment-img.jpg" alt="Payment"></div>

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

				<div class="row border-bottom pb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">Subtotal</span>
					<span class="col-12 col-sm-6 text-right"><span class="money">${$cart.totals.subtotal|number_format:2}</span></span>
				</div>
				{*<div class="row border-bottom pb-2 pt-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">Tax</span>
					<span class="col-12 col-sm-6 text-right">${$cart.totals.taxes|number_format:2}</span>
				</div>*}
				<div class="row shipping mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">
						{translate('CART_SHIPPING', 'main')}
						<p id="provider-service">{$cart.shipping.provider} -- <small>{$cart.shipping.service_level_name}</small></p>
					</span>
					<span class="col-12 col-sm-6 shipping_cost text-right">
						${$cart.shipping.total_pricing|number_format:2}
					</span>
				</div>
				<div class="row border-bottom pb-2 pt-2">
					<span class="col-12 col-sm-6 cart__subtotal-title"><strong>{translate('CART_TOTAL', 'main')}</strong></span>
					<span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right">
						<span class="money">${($cart.totals.subtotal_inc_disc + $cart.shipping.total_pricing)|number_format:2}</span>
					</span>
				</div>

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
		<h2 class="text-center p-4">{translate('SIDEBAR_ADDRESSES', $module)}</h2>
		<span class="close-sidebar"><i class="icon icon anm anm-times-l"></i> </span>
	</div>
	<div id="content-addresses" class="container"></div>
</div>
<!--End Address Popup-->
