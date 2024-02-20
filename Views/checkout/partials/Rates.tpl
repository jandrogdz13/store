{if $rates}
	{foreach from=$rates item=$rate}
		<li class="rate">
			<img src="https://mobelinn.test/Views/layout/default/images/vase-flower.png" alt="{$rate.provider}" />
			<div class="provider" data-service-code="{$rate.service_level_code}">
				<span>{$rate.provider}</span>
				<span>{$rate.service_level_name}</span>
			</div>
			<div class="total_pricing">
				<span>{$rate.days} para envío</span>
				<span>{$rate.total_pricing} {$rate.currency_local}</span>
			</div>
		</li>
	{/foreach}

{/if}
