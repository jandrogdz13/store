{if $wishlist}
	{foreach from=$wishlist item=item}
		<div class="d-flex justify-content-start position-relative">
			<img src="{$vars.images}product-images/img_demo.webp" class="mr-2" alt="{$item.product_name}" width="50px">
			<div class="d-flex flex-column justify-content-center">
				<span>{$item.product_name}</span>
				<span>${$item.unit_price|number_format:2}</span>
			</div>
			<span class="add-cart bi bi-plus-circle-fill"></span>
		</div>
	{/foreach}
{else}
	<img src="{$vars.images}emptywishlist.jpg" alt="">
	<p class="h1 text-center">Tú lista de favoritos esta vacía</p>
{/if}
