
{if !empty($orders)}
	{foreach from=$orders item=order}
		<div class="card mt-2 w-100 pb-3" data-id="{$order.orderid}">
			<div class="card-body">
				<div class=" d-flex justify-content-start align-items-center">
					<div class="d-flex flex-column w-100">

						{if $order.stage eq 'Pedido'}
							{assign var=stage_class value='info'}
						{elseif $order.stage eq 'En Produccion'}
                            {assign var=stage_class value='warning'}
						{elseif $order.stage eq 'Completado'}
                            {assign var=stage_class value='success'}
                        {elseif $order.stage eq 'Cancelado'}
                            {assign var=stage_class value='danger'}
						{/if}

						<div class="d-flex justify-content-between align-items-start">
							<span class="h3"><strong>Pedido #{$order.orderid}</strong></span>
							<span class="badge badge-{$stage_class}">{$order.stage}</span>
						</div>
						<div class="d-flex justify-content-between align-items-start">
							<span style="min-width: 150px; "><i class="mr-2 bi bi-calendar-event"></i> Fecha creado:</span>
							<span>{translateDate($order.created_date)}</span>
						</div>
						<div class="d-flex justify-content-between align-items-start">
							<span style="min-width: 150px; "><i class="mr-2 bi bi-cash-coin"></i> Monto:</span>
							<span class="text-right">${$order.amount|number_format} {translate('CURRENCY', 'main')}</span>
						</div>
						<div class="d-flex justify-content-between align-items-start">
							<span style="min-width: 150px; "><i class="mr-2 bi bi-credit-card"></i> Método de pago:</span>
							<span class="text-right">{$order.payment.type}</span>
						</div>
						<div class="d-flex justify-content-between align-items-start">
							<span style="min-width: 150px; "><i class="mr-2 bi bi-house"></i> Dirección:</span>
							<span class="text-right">{$order.address.street} {$order.address.outdoor_num} {$order.address.interior_um}, {$order.address.suburb} {$order.address.postcode}</span>
						</div>
						<div class="d-flex justify-content-center align-items-center position-relative">
							<span><i class="icon-swipe-down bi bi-chevron-double-down"></i></span>
						</div>
					</div>
				</div>
				<div class="order-detail mt-4" style="display: none">
					<h3>Detalle del pedido</h3>
					{foreach from=$order.detail item=prod}
						<div class="prod-detail d-flex justify-content-start position-relative mb-2">
							<div class="order-qty">{$prod.quantity}</div>
							{if $prod.is_service|boolval}
								<img src="{$vars.images}delivery-truck.svg" alt="{$prod.product}" width="50px">
							{else}
								<img src="{$vars.images}product-images/img_demo.webp" alt="{$prod.product}" width="50px">
							{/if}

							<div class="d-flex flex-column justify-content-center">
								<span>{$prod.product}</span>
								<span>${$prod.unit_price|number_format:2}</span>
							</div>

						</div>
					{/foreach}

				</div>
			</div>
		</div>
	{/foreach}
{else}
	<img src="{$vars.images}empty_orders.jpg" alt="">
	<p class="h1 text-center">No hay pedidos aún</p>
{/if}

