<!--Tabs-->
	<nav class="tabs">
		<a class="tab active" href="#tab-orders" title="{translate('TAB_ORDERS', 'main')}"><i class="bi bi-journal-bookmark"></i></a>
		<a class="tab" href="#tab-address" title="{translate('TAB_ADDRESS', 'main')}"><i class="bi bi-house"></i></a>
		<a class="tab" href="#tab-wishlist" title="{translate('WISHLIST', 'main')}"><i class="bi bi-heart"></i></a>
	</nav>
	<div class="tab-container" style="padding: 5px 20px">
		<div id="tab-orders" class="content show">
			<div class="fadein w-100">
                {include file=$vars.config.path_views|cat:$module|cat:'/partials/orders.tpl'}
			</div>
		</div>

		<div id="tab-address" class="content">
			<div class="fadein w-100">
                {include file=$vars.config.path_views|cat:'checkout/partials/Address.tpl'}
			</div>
		</div>

		<div id="tab-wishlist" class="content">
			<div class="fadein w-100">
                {include file=$vars.config.path_views|cat:$module|cat:'/partials/Wishlist.tpl'}
			</div>
		</div>
	</div>
<!--End Tabs-->
