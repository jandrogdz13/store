<!DOCTYPE html>
<html class="no-js" lang="es">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta http-equiv="Content-Language" content="es_MX">
		<title>{$vars.title}</title>
		<base href="{$vars.config.base_url}">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Descubre lo que {$vars.config.app} tiene para ti."/>
		<link rel="canonical" href="{$vars.config.base_url}" />


		<!-- Open Graph (OG) meta tags are snippets of code that control how URLs are displayed when shared on social media  -->
		<meta property="og:locale" content="es_MX" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="{$vars.title}" />
		<meta property="og:url" content="{$vars.config.base_url}" />
		<meta property="og:site_name" content="{$vars.config.app}" />
		<!-- For the og:image content, replace the # with a link of an image -->
		<!-- <meta property="og:image" content="#" />-->
		<meta property="og:description" content="Descubre lo que {$vars.config.app} tiene para ti." />


		<link rel="shortcut icon" href="{$vars.images}logo_mobel.png" sizes="32x32" />
		<link rel="shortcut icon" href="{$vars.images}logo_mobel.png" sizes="192x192" />
		<link rel="apple-touch-icon" href="{$vars.images}logo_mobel.png" />
		<meta name="msapplication-TileImage" content="{$vars.images}logo_mobel.png" />
		<link href="{$vars.images}logo_mobel.png" type="image/x-icon" rel="shortcut icon" />

		<!-- CSS -->
		<link rel="stylesheet" href="{$vars.css}plugins.css">
		<link rel="stylesheet" href="{$vars.css}bootstrap.min.css">
		<link rel="stylesheet" href="{$vars.css}style.css">
		<link rel="stylesheet" href="{$vars.css}responsive.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

		<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


		<!-- Structured Data  -->
		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "WebSite",
				"name": "{$vars.config.base_url}",
				"url": "{$vars.config.base_url}"
			}
		</script>

    </head>

    <body class="{$tpl_class} home4-fullwidth">
		<div id="pre-loader">
			<img src="{$vars.images}loader.gif" alt="Loading..." />
		</div>
		<div id="google_translate_element"></div>

		<!-- pageWrapper -->
		<div class="pageWrapper">

            {if !$module|in_array:['checkout', 'account']}
				<!--Search Form Drawer-->
				<div class="search">
					<div class="search__form">
						<button class="go-btn search__button" type="submit"><i class="icon anm anm-search-l"></i></button>
						<input class="search__input" type="search" name="q" value="" placeholder="{translate('SEARCH_PLACEHOLDER', 'main')}" aria-label="{translate('SEARCH_LABEL', 'main')}" autocomplete="off">
						<button type="button" class="search-trigger close-btn"><i class="anm anm-times-l"></i></button>
					</div>
				</div>
				<!--End Search Form Drawer-->

				<!--Top Header-->
				<div class="top-header">
					<div class="container-fluid">
						<div class="row">
							<div class="col-10 col-sm-8 col-md-5 col-lg-4">
								{*<div class="currency-picker">
									<span class="selected-currency">{$currency}</span>
									<ul id="currencies">
										<li data-currency="MXN" class="">MXN</li>
										<li data-currency="USD" class="">USD</li>
									</ul>
								</div>*}
								<div class="language-dropdown">
									<span class="language-dd">{translate('LANG_SPANISH', 'main')}</span>
									<ul id="language">
										<li class="" data-lang="es">{translate('LANG_SPANISH', 'main')}</li>
										<li class="" data-lang="en">{translate('LANG_ENGLISH', 'main')}</li
									</ul>
								</div>
								{*<p class="phone-no"><i class="anm anm-phone-s"></i> +52 (33) 0000 0000</p>*}
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4 d-none d-lg-none d-md-block d-lg-block">
								<div class="text-center"><p class="top-header_middle-text">{translate('TOP_BANNER', 'main')}</p></div>
							</div>
							<div class="col-2 col-sm-4 col-md-3 col-lg-4 text-right">
								<span class="user-menu d-block d-lg-none"><i class="anm anm-user-al" aria-hidden="true"></i></span>
								<ul class="customer-links list-inline">
                                    {if $authenticated|boolval}
										{*<li><i class="bi bi-heart"></i> <a href="account/wishlist">{translate('WISHLIST', 'main')}</a></li>*}
										<li class="account-trigger">
											<i class="bi bi-person"></i>
											<a href="#">{translate('MY_ACCOUNT', 'main')}</a>
										</li>
										<li><i class="bi bi-door-closed"></i> <a href="account/logout">{translate('LOG_OUT', 'main')}</a></li>
                                    {else}
										<li><i class="bi bi-person"></i> <a href="account/login">{translate('LOG_IN', 'main')}</a></li>
										<li><i class="bi bi-person-badge"></i> <a href="account/register">{translate('REGISTER', 'main')}</a></li>
                                    {/if}
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!--End Top Header-->

                {include file=$vars.config.def_route|cat:'partials/menu.tpl'}
			{/if}

