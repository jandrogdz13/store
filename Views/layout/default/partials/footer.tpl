

			<!--Footer-->
			<!--Minicart Popup-->
			<div id="header-cart" class="block block-cart">
				<div class="loader-cart">
					<div class="loader">
						<span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span>
					</div>
				</div>
				<div id="cart-title" class="position-relative">
					<h2 class="text-left p-3">{translate('SIDEBAR_CART_TITLE', 'main')}</h2>
					<span id="close-cart"><i class="icon icon anm anm-times-l"></i> </span>
				</div>
				<div id="content-products">
                    {*{include file=$vars.config.path_views|cat:'cart/partials/Cart.tpl'}*}
				</div>

				<div class="total {(empty($cart.products))? 'hide': ''} p-4">

                    {if (isset($cart.totals.shipping) And $cart.totals.shipping gt 0) Or $cart.totals.discounts gt 0}
						<div class="total-in totals__cart__subtotal p-0">
							<span class="label">{translate('CART_SUBTOTAL', 'main')}:</span>
							<span class="product-price">
								<span class="money">${$cart.totals.subtotal|number_format:2}</span>
								{translate('CURRENCY', 'main')}
							</span>
						</div>
					{/if}
                    {if $cart.totals.discounts gt 0}
						<div class="total-in totals__cart__discount p-0">
							<span class="label">{translate('CART_DISCOUNTS', 'main')}:</span>
							<span class="product-price">
								<span class="money">${$cart.totals.discounts|number_format:2}</span>
								{translate('CURRENCY', 'main')}
							</span>
						</div>
						{*<div class="total-in totals__cart__subtotal_discount">
							<span class="label cart__subtotal">{translate('CART_SUBTOTAL_INC_DISC', 'main')}:</span>
							<span class="product-price">
								<span class="money">${$cart.totals.subtotal_inc_disc|number_format:2}</span>
							</span>
						</div>*}
					{/if}

					{if isset($cart.totals.shipping) And $cart.totals.shipping gt 0}
						<div class="total-in totals__cart__shipping p-0">
							<span class="label">{translate('CART_SHIPPING', 'main')}:</span>
							<span class="product-price">
								<span class="money">${$cart.totals.shipping|number_format:2}</span>
								{translate('CURRENCY', 'main')}
							</span>
						</div>
					{/if}

					<div class="total-in totals__cart__total p-0">
						<span class="label">{translate('CART_TOTAL', 'main')}:</span>
						<span class="product-price">
								<span class="money cart__subtotal h2">${($cart.totals.subtotal_inc_disc + $cart.totals.shipping)|number_format:2}</span>
								{translate('CURRENCY', 'main')}
							</span>
					</div>
					<div class="row-fluid mb-3 pt-3">
						<div class="col-12 px-0">
							<p>{translate('TAXES_SHIPPING_CART', 'main')}</p>
						</div>
					</div>
					<div class="buttonSet text-center">
						{*<a href="cart.html" class="btn btn-secondary btn--small">View Cart</a>*}
						<a href="checkout" class="btn btn-secondary w-100">Checkout <i class="ml-2 bi bi-arrow-right"></i></a>
					</div>
				</div>
			</div>
			<!--End Minicart Popup-->

			<!--Account Popup-->
			<div id="sidebar-account" class="block block-cart">
				<div class="loader-cart">
					<div class="loader">
						<span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span>
					</div>
				</div>
				<div id="cart-title" class="position-relative">
					<h2 class="text-left p-4">{translate('TAB_ORDERS', 'main')}</h2>
					<span class="close-sidebar"><i class="icon icon anm anm-times-l"></i> </span>
				</div>
				<div id="sidebar-content" class="" style="overflow-y: auto; height: calc(100vh - 75px); margin-top: 0; padding-bottom: 10px;"></div>
			</div>
			<!--End Address Popup-->


			<div class="loader-cart-page">
				<div class="loader">
					<span class="dot"></span>
					<span class="dot"></span>
					<span class="dot"></span>
				</div>
			</div>

			<div class="backdrop"></div>
			<footer id="footer">
                {if !$module|in_array:['checkout', 'account']}
					<div class="newsletter-section">
						<div class="container">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-7 w-100 d-flex justify-content-start align-items-center">
									<div class="display-table">
										<div class="display-table-cell footer-newsletter">
											<div class="section-header text-center">
												<label class="h2">{translate('SUSCRIBE_NEWSLETTER', 'main')}</label>
											</div>
											<div class="input-group">
												<input type="email" class="input-group__field newsletter__input" name="EMAIL" value="" placeholder="{translate('NEWSLETTER_INPUT', 'main')}" required="">
												<span class="input-group__btn">
												<button type="submit" class="btn newsletter__submit" name="commit" id="Subscribe">
													<span class="newsletter__submit-text--large">{translate('SUSCRIBE_BUTTON', 'main')}</span>
												</button>
											</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-5 d-flex justify-content-end align-items-center">
									<div class="footer-social">
										<ul class="list--inline site-footer__social-icons social-icons">
											<li>
												<a class="social-icons__link" href="https://facebook.com/Mobelinnoficial" target="_blank" title="Facebook">
													<i class="icon icon-facebook"></i>
													<span class="icon__fallback-text">Facebook</span>
												</a>
											</li>
											<li>
												<a class="social-icons__link" href="https://instagram.com/mobel_inn" target="_blank" title="Instagram">
													<i class="icon icon-instagram"></i>
													<span class="icon__fallback-text">Instagram</span>
												</a>
											</li>
											<li>
												<a class="social-icons__link" href="https://pinterest.com/mobel_inn" target="_blank" title="Pinterest">
													<i class="icon icon-pinterest"></i>
													<span class="icon__fallback-text">Pinterest</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="site-footer">
						<div class="container-fluid">
							<!--Footer Links-->
							<div class="footer-top">
								<div class="row">
									<div class="col-12 col-sm-12 col-md-3 col-lg-3 footer-links">
										<h4 class="h4">Compra rápida</h4>
										<ul>
                                            {if $categories}
                                                {foreach from=$categories item=category}
													<li class="">
														<a href="category/{$category.keyword}" title="{$category.category_name}">
                                                            {$category.category_name}
														</a>
													</li>
                                                {/foreach}
                                            {/if}
											{*<li><a href="#">Recámaras</a></li>
											<li><a href="#">Comedores</a></li>
											<li><a href="#">Complementos</a></li>
											<li><a href="#">Living</a></li>
											<li><a href="#">Catálogo</a></li>*}
										</ul>
									</div>
									<div class="col-12 col-sm-12 col-md-3 col-lg-3 footer-links">
										<h4 class="h4">Informativo</h4>
										<ul>
											<li><a href="#">Política de privacidad</a></li>
											<li><a href="#">Términos y condiciones</a></li>
										</ul>
									</div>
									<div class="col-12 col-sm-12 col-md-3 col-lg-3 footer-links">
										<h4 class="h4">Servicio al cliente</h4>
										<ul>
											{*<li><a href="#">Solicitar información</a></li>*}
											{*<li><a href="#">Contáctanos</a></li>*}
											<li><a href="#">Pedidos y Devoluciones</a></li>
											<li><a href="mailto:soporte@mobelinn.com">Soporte</a></li>
										</ul>
									</div>
									<div class="col-12 col-sm-12 col-md-3 col-lg-3 contact-box">
										<h4 class="h4">{translate('CONTACT_US', 'main')}</h4>
										<ul class="addressFooter">
											<li><i class="icon anm anm-map-marker-al"></i><p>Las Flores 115a Col. Víctor Hugo,<br>C.P. 45190 Zapopan, Jal.</p></li>
											<li class="phone"><i class="icon anm anm-phone-s"></i><p>+52 (33) 0000 0000</p></li>
											<li class="email"><i class="icon anm anm-envelope-l"></i><p>ventas@mobelinn.com</p></li>
										</ul>
									</div>
								</div>
							</div>
							<!--End Footer Links-->
							<hr>
							<div class="footer-bottom">
								<div class="row">
									<!--Footer Copyright-->
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 copyright text-center">
										<span>Derechos de autor © 2024 Mobel Inn.</span>
									</div>
									<!--End Footer Copyright-->
								</div>
							</div>
						</div>
					</div>

				{/if}


			</footer>
			<!--End Footer-->

			<!--Scoll Top-->
			<span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
			<!--End Scoll Top-->

			{include file=$vars.config.def_route|cat:'partials/quick-view.tpl'}

			{include file=$vars.config.def_route|cat:'partials/newsletter.tpl'}

			<!-- Including Jquery -->
			<script src="{$vars.js}vendor/jquery-3.3.1.min.js"></script>
			<script src="{$vars.js}vendor/jquery.cookie.js"></script>
			<script src="{$vars.js}vendor/modernizr-3.6.0.min.js"></script>
			<script src="{$vars.js}vendor/wow.min.js"></script>

			<!-- Including Javascript -->
			<script src="{$vars.js}bootstrap.min.js"></script>
			<script src="{$vars.js}plugins.js"></script>
			<script src="{$vars.js}popper.min.js"></script>
			<script src="{$vars.js}lazysizes.js"></script>
			<script src="{$vars.js}main.js"></script>
			<script src="{$vars.js}sweetalert2.js"></script>

			<!-- Photoswipe Gallery -->
			<script src="{$vars.js}vendor/photoswipe.min.js"></script>
			<script src="{$vars.js}vendor/photoswipe-ui-default.min.js"></script>

			{if $module eq 'checkout'}
				<script src="https://www.paypal.com/sdk/js?client-id={$vars.config.apis.paypal.PAYPAL_CLIENT_ID_SANDBOX}&components=buttons&currency=MXN&intent=capture"></script>
				<script src="https://sdk.mercadopago.com/js/v2"></script>
			{/if}


			<!--For Newsletter Popup-->
			<script>
				jQuery(document).ready(function(){
					jQuery('.closepopup').on('click', function () {
						jQuery('#popup-container').fadeOut();
						jQuery('#modalOverly').fadeOut();
					});

					var visits = jQuery.cookie('visits') || 0;
					visits++;
					jQuery.cookie('visits', visits, { expires: 1, path: '/' });
					console.debug(jQuery.cookie('visits'));
					/*if ( jQuery.cookie('visits') > 1 ) {
						jQuery('#modalOverly').hide();
						jQuery('#popup-container').hide();
					} else {
						var pageHeight = jQuery(document).height();
						jQuery('<div id="modalOverly"></div>').insertBefore('body');
						jQuery('#modalOverly').css("height", pageHeight);
						jQuery('#popup-container').show();
					}
					if (jQuery.cookie('noShowWelcome')) { jQuery('#popup-container').hide(); jQuery('#active-popup').hide(); }*/
				});

				jQuery(document).mouseup(function(e){
					var container = jQuery('#popup-container');
					if( !container.is(e.target)&& container.has(e.target).length === 0)
					{
						container.fadeOut();
						jQuery('#modalOverly').fadeIn(200);
						jQuery('#modalOverly').hide();
					}
				});
			</script>
			<!--End For Newsletter Popup-->


			<script>
				$(function(){
					var $pswp = $('.pswp')[0],
						image = [],
						getItems = function() {
							var items = [];
							$('.lightboximages a').each(function() {
								var $href   = $(this).attr('href'),
									$size   = $(this).data('size').split('x'),
									item = {
										src : $href,
										w: $size[0],
										h: $size[1]
									}
								items.push(item);
							});
							return items;
						}
					var items = getItems();

					$.each(items, function(index, value) {
						image[index]     = new Image();
						image[index].src = value['src'];
					});
					$('.prlightbox').on('click', function (event) {
						event.preventDefault();

						var $index = $(".active-thumb").parent().attr('data-slick-index');
						$index++;
						$index = $index-1;

						var options = {
							index: $index,
							bgOpacity: 0.9,
							showHideOpacity: true
						}
						var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
						lightBox.init();
					});
				});
			</script>

			<!-- Script by template -->
			<script src="{$vars.js}alert.js"></script>
			<script src="{$vars.js}util.js"></script>
            {if $scripts}
                {foreach item=script from=$scripts}
					<script src="{$vars.scripts}{$script}?v={$timestamp}"></script>
                {/foreach}
            {/if}
			<!-- End Scripts by template -->

		</div>
		<!-- End pageWrapper -->

			<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="pswp__bg"></div>
				<div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>

	</body>
</html>
