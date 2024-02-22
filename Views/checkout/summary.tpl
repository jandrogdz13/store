<!--Page Title-->
<div class="page section-header text-center mb-0 p-3" style="background-color: #2C3338; border: none; ">
	<div class="page-title">
		<div class="wrapper">
			<a href="/">
				<img src="{$vars.images}logo_mobel.png" alt="Mobel Inn" width="200px" />
			</a>
		</div>
	</div>
</div>
<!--End Page Title-->

<div class="container summary-template ">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-7 col-lg-7 main-col mb-5">
			<div class="container px-4">
                {*<div class="row d-flex justify-content-center align-items-center mb-3 py-3" style="background-color: #2C3338">
				<a href="/">
					<img src="{$vars.images}logo_mobel.png" alt="Mobel Inn" width="200px" />
				</a>
			</div>*}
				<div class="row mb-3 mt-2">
					<h1>¡Gracias por comprar con nosotros! Nos alegra contar con tu confianza.</h1>
                    {if $order.payment.type eq 'transferencia'}
						<h3>Tu orden fue recibida, estaremos pendientes a recibir el comprobante para procesar tu pedido.</h3>
                    {else}
						<h3>Tu orden fue recibida y será procesada muy pronto.</h3>
                    {/if}

				</div>
				<div class="row">
					<h4>A continuación, los detalles de tu compra.</h4>
				</div>
				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>No. orden</strong></p>
					<p class="ml-2">{$order.orderid}</p>
				</div>
				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>Fecha del pedido</strong></p>
					<p class="ml-2">{translateDate($order.created_date, false, true, true)}</p>
				</div>
				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>Estado de la orden</strong></p>
					<p class="ml-2">{$order.stage}</p>
				</div>
				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>Método de pago</strong></p>
					<p class="ml-2">{$order.payment.type|capitalize}</p>
				</div>

                {if $order.payment.type eq 'transferencia'}
					<div class="row d-flex flex-column mb-3">
						<p class="mb-0"><strong>Instrucciones</strong></p>
						<p class="ml-2">Datos para la transferencía</p>
					</div>
                {/if}

				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>No. Transacción</strong></p>
					<p class="ml-2">{$order.payment.payment_detail.id}</p>
				</div>
				<div class="row d-flex flex-column mb-3">
					<p class="mb-0"><strong>Dirección de entrega</strong></p>
					<p class="ml-2 mb-0">{$order.address.street} {$order.address.outdoor_num} {$order.address.interior_num},</p>
					<p class="ml-2 mb-0">{$order.address.suburb} {$order.address.postcode},</p>
					<p class="ml-2 mb-0">{$order.address.city} {$order.address.state}, {$order.address.country}</p>
				</div>
				<div class="row d-flex flex-column mt-5 d-flex justify-content-center align-items-center text-center w-100">
					<a class="btn  p-3" href="/"><i class="mr-2 bi bi-arrow-left"></i> {translate('BACK_TO_SOTRE', $module)}</a>
				</div>
			</div>

		</div>
		<div class="col-12 col-sm-12 col-md-5 col-lg-5 cart__footer p-5" style="background-color: #f1f1f1; height: 100vh;">
			<h3 class="font-weight-bold mb-3">Detalle del pedido</h3>
            {foreach from=$order.detail item=prod}
                {if !$prod.is_service|boolval}
				<div class="prod-detail d-flex justify-content-start position-relative mb-4">
					<div class="order-qty">{$prod.quantity}</div>
					<img src="{$vars.images}product-images/img_demo.webp" alt="{$prod.product}">
					<div class="d-flex flex-column justify-content-center">
						<span>{$prod.product}</span>
						<span>${$prod.unit_price|number_format:2}</span>
					</div>
				</div>
                {/if}
            {/foreach}
			<hr>
			<div class="row border-bottom pb-2">
				<span class="col-12 col-sm-6 cart__subtotal-title">Subtotal</span>
				<span class="col-12 col-sm-6 text-right"><span class="money">${$order.totals.subtotal|number_format:2}</span></span>
			</div>
            {*<div class="row border-bottom pb-2 pt-2">
				<span class="col-12 col-sm-6 cart__subtotal-title">Tax</span>
				<span class="col-12 col-sm-6 text-right">${$cart.totals.taxes|number_format:2}</span>
			</div>*}
			<div class="row shipping mb-2">
					<span class="col-12 col-sm-6 cart__subtotal-title">
						{translate('CART_SHIPPING', 'main')}
						<p id="provider-service">{$order.shipping.provider} -- <small>{$order.shipping.service_level_name}</small></p>
					</span>
				<span class="col-12 col-sm-6 shipping_cost text-right">
						${$order.totals.shipping|number_format:2}
					</span>
			</div>
			<hr>
			<div class="row border-bottom pb-2 pt-2">
				<span class="col-12 col-sm-6 cart__subtotal-title">
					<strong class="h1 font-weight-bold">{translate('CART_TOTAL', 'main')}</strong>
				</span>
				<span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right">
						<span class="money h1 font-weight-bold">${($order.amount)|number_format:2} <small>MXN</small></span>
					</span>
			</div>

		</div>
	</div>
</div>
