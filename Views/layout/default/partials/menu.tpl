
<!--Header-->
<div class="header-wrap classicHeader animated d-flex">
	<div class="container-fluid">
		<div class="row align-items-center">
			<!--Desktop Logo-->
			<div class="logo col-md-2 col-lg-2 d-none d-lg-block">
				<a href="/">
					<img src="{$vars.images}logo_mobel_letras.png" alt="Mobel Inn" width="100px" />
				</a>
			</div>
			<!--End Desktop Logo-->
			<div class="col-2 col-sm-3 col-md-3 col-lg-8">
				<div class="d-block d-lg-none">
					<button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
						<i class="icon anm anm-times-l"></i>
						<i class="anm anm-bars-r"></i>
					</button>
				</div>
				<!--Desktop Menu-->
				<nav class="grid__item" id="AccessibleNav"><!-- for mobile -->
					<ul id="siteNav" class="site-nav medium right hidearrow">
						<li class="lvl1 parent megamenu">
							<a href="/">{translate('_HOME', 'main')}</a>
						</li>
						{if $categories}
                            {foreach from=$categories item=category}
								<li class="lvl1 parent dropdown">
									<a href="category/{$category.keyword}" title="{$category.category_name}">
										{*<img src="{$vars.images}{$category.icon}" alt="{$category.category_name}">*}
										{$category.category_name} {if !empty($category.sub)}<i class="anm anm-angle-down-l"></i>{/if}
									</a>
									{if !empty($category.sub)}
										<ul class="dropdown">
                                            {foreach from=$category.sub item=sub}
												<li><a href="category/{$category.keyword}/{$sub.keyword}" class="site-nav" title="{$sub.category_name}">{$sub.category_name}</a></li>
                                            {/foreach}
										</ul>
									{/if}
								</li>
                            {/foreach}
						{/if}
						<li class="lvl1">
							<a href="#" class="active">
								<b>{translate('BUY_NOW', 'main')}</b><i class="anm anm-angle-down-l"></i>
							</a>
						</li>
					</ul>
				</nav>
				<!--End Desktop Menu-->
			</div>
			<!--Mobile Logo-->
			<div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
				<div class="logo">
					<a href="/">
						<img src="{$vars.images}logo_mobel_letras.png" alt="Mobel Inn" width="100px" />
					</a>
				</div>
			</div>
			<!--Mobile Logo-->
			<div class="col-4 col-sm-3 col-md-3 col-lg-2">
				<div class="site-cart">
					<a href="#" class="site-header__cart" title="Cart">
						<i class="icon anm anm-bag-l"></i>
						<span id="CartCount" class="site-header__cart-count" data-cart-render="item_count">{($cart.count)? $cart.count: 0}</span>
					</a>
				</div>
				<div class="site-header__search">
					<button type="button" class="search-trigger"><i class="icon anm anm-search-l"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End Header-->


<!--Mobile Menu-->
<div class="mobile-nav-wrapper" role="navigation">
	<div class="closemobileMenu"><i class="icon anm anm-times-l pull-right"></i> {translate('CLOSE_MENU', 'main')}</div>
	<ul id="MobileNav" class="mobile-nav">
		<li class="lvl1 parent megamenu">
			<a href="/">{translate('_HOME', 'main')}</a>
		</li>
        {if $categories}
            {foreach from=$categories item=category}
				<li class="lvl1 parent megamenu">
					<a href="category/{$category.keyword}">{$category.category_name} {if !empty($category.sub)}<i class="anm anm-plus-l"></i>{/if}</a>
                    {if !empty($category.sub)}
						<ul>
                            {foreach from=$category.sub item=sub}
								<li><a href="category/{$category.keyword}/{$sub.keyword}" class="site-nav" title="{$sub.category_name}">{$sub.category_name}</a></li>
                            {/foreach}
						</ul>
                    {/if}
				</li>
            {/foreach}
        {/if}
		<li class="lvl1">
			<a href="#"><b>{translate('BUY_NOW', 'main')}</b></a>
		</li>
	</ul>
</div>
<!--End Mobile Menu-->
