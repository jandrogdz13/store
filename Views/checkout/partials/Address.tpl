<div id="button-container">
	<button type="button" id="add-address" class="btn btn--secondary btn--small-wide checkout p-3 w-100"> {translate('ADD_ADDRESS', 'main')}</button>
</div>

{if $addresses}
	{foreach from=$addresses item=$address}
		<div class="card mt-2 w-100 {($address_cart.addressid eq $address.addressid)? 'active': ''}" data-id="{$address.addressid}">
			<span class="edit-address">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-highlighter" viewBox="0 0 16 16">
  				<path fill-rule="evenodd" d="M11.096.644a2 2 0 0 1 2.791.036l1.433 1.433a2 2 0 0 1 .035 2.791l-.413.435-8.07 8.995a.5.5 0 0 1-.372.166h-3a.5.5 0 0 1-.234-.058l-.412.412A.5.5 0 0 1 2.5 15h-2a.5.5 0 0 1-.354-.854l1.412-1.412A.5.5 0 0 1 1.5 12.5v-3a.5.5 0 0 1 .166-.372l8.995-8.07zm-.115 1.47L2.727 9.52l3.753 3.753 7.406-8.254zm3.585 2.17.064-.068a1 1 0 0 0-.017-1.396L13.18 1.387a1 1 0 0 0-1.396-.018l-.068.065zM5.293 13.5 2.5 10.707v1.586L3.707 13.5z"/>
				</svg>
			</span>
			<div class="card-body">
				<div class="addr-container d-flex justify-content-start align-items-center">
					<div class="d-flex flex-column">
						<span>{$address.street} {$address.outdoor_num} {$address.interior_num} </span>
						<span>{translate('SHORT_SUBURB', $module)} {$address.suburb} {translate('POSTCODE', $module)} {$address.postcode} {$address.city} {$address.state}</span>
						<span>{$address.references}</span>
					</div>
				</div>
			</div>
		</div>
	{/foreach}
{else}
	<img src="{$vars.images}empty_address2.jpg" alt="">
	<p class="h1 text-center">No se encontró ninguna dirección</p>
{/if}
