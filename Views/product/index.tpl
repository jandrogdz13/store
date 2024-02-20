<!--MainContent-->
<div id="MainContent" class="main-content" role="main">
	<!--Breadcrumb-->
	<div class="bredcrumbWrap">
		<div class="container breadcrumbs">
			<a href="/" title="{translate('BREADCRUMBS_HOME', 'main')}">{translate('_HOME', 'main')}</a><span aria-hidden="true">›</span><span>{$product.product_name}</span>
		</div>
	</div>
	<!--End Breadcrumb-->

	<div id="ProductSection-product-template" class="product-template__container prstyle1 container">
		<!--product-single-->
		<div class="product-single">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="product-details-img">
						<div class="product-thumb">
							<div id="gallery" class="product-dec-slider-2 product-tab-left">
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" class="slick-slide slick-cloned" data-slick-index="-4" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="-3" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="-2" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="-1" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="0" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="1" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
								<a data-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-ES.webp" class="slick-slide slick-cloned" data-slick-index="2" aria-hidden="true" tabindex="-1">
									<img class="blur-up lazyload" src="{$vars.images}product-images/SILLA-ATENAS-ES.webp" alt="" />
								</a>
							</div>
						</div>
						<div class="zoompro-wrap product-zoom-right pl-20">
							<div class="zoompro-span">
								<img class="zoompro blur-up lazyload" data-zoom-image="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" alt="" src="{$vars.images}product-images/SILLA-ATENAS-LAT.jpg" />
							</div>
							{if $product.discount gt 0}
								<div class="product-labels">
									<span class="lbl on-sale">{translate('ON_SALE', 'main')}</span>
                                    {*<span class="lbl pr-label1">{translate('LABEL_NEW', 'main')}</span>*}
								</div>
							{/if}

							<div class="product-buttons">
								<a href="#" class="btn prlightbox" title="Zoom"><i class="icon anm anm-expand-l-arrows" aria-hidden="true"></i></a>
							</div>
						</div>
						<div class="lightboximages">
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="1462x2048"></a>
							<a href="{$vars.images}product-images/SILLA-ATENAS-ES.webp" data-size="731x1024"></a>
						</div>

					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="product-single__meta">
						<h1 class="product-single__title">{$product.product_name}</h1>
						<div class="prInfoRow">
							<div class="product-stock">
								{if $product.stock gt 0}
									<span class="instock">{translate('IN_STOCK', 'main')}</span>
								{else}
									<span class="outstock">{translate('OUT_STOCK', 'main')}</span>
								{/if}
							</div>
							<div class="product-sku">SKU: <span class="variant-sku">{$product.sku}</span></div>
						</div>
						<p class="product-single__price product-single__price-product-template">
							<span class="visually-hidden">{translate('UNIT_PRICE', 'main')}</span>
							<span id="ComparePrice-product-template">
								<span class="money {($product.discount gt 0)? 'text-decoration-line-through': ''}">
									${$product.unit_price|number_format:2} {translate('CURRENCY', 'main')}
								</span>
							</span>
							{if $product.discount gt 0}
								<span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
									<span id="ProductPrice-product-template">
										<span class="money">
											${$product.unit_price_inc_discount|number_format:2} {translate('CURRENCY', 'main')}
										</span>
									</span>
								</span>
							{/if}

							{if $product.discount_amount gt 0}
								<br>
								<span class="discount-badge">
									{*<span class="devider">|</span>&nbsp;*}
									<span>{translate('SAVINGS', 'main')}</span>
									<span id="SaveAmount-product-template" class="product-single__save-amount">
										<span class="money">${$product.discount_amount|number_format:2} {translate('CURRENCY', 'main')}</span>
									</span>
									<span class="off">(<span>{$product.discount|number_format:0}</span>%)</span>
								</span>
							{/if}

						</p>
						{*<div class="orderMsg" data-user="23" data-time="24">
							<img src="{$vars.images}order-icon.jpg" alt="" />
							<strong class="items">5</strong> sold in last <strong class="time">26</strong> hours
						</div>*}
					</div>
					<div class="product-single__description rte">
						<p>{$product.description}</p>
					</div>

					{if $product.stock lte $product.min_stock}
						<div id="quantity_message">{translate(sprintf('QUANTITY_MESSAGE', $product.stock), 'main')}</div>
					{/if}

						{*<div class="swatch clearfix swatch-0 option1" data-option-index="0">
							<div class="product-form__item">
								<label class="header">Madera: <span class="slVariant">Madera seleccionada</span></label>
								<div data-value="Black" class="swatch-element color black available">
									<input class="swatchInput" id="swatch-0-black" type="radio" name="option-0" value="Black"><label class="swatchLbl color small" for="swatch-0-black" style="background-color:black;" title="Black"></label>
								</div>
								<div data-value="Maroon" class="swatch-element color maroon available">
									<input class="swatchInput" id="swatch-0-maroon" type="radio" name="option-0" value="Maroon"><label class="swatchLbl color small" for="swatch-0-maroon" style="background-color:maroon;" title="Maroon"></label>
								</div>
								<div data-value="Blue" class="swatch-element color blue available">
									<input class="swatchInput" id="swatch-0-blue" type="radio" name="option-0" value="Blue"><label class="swatchLbl color small" for="swatch-0-blue" style="background-color:blue;" title="Blue"></label>
								</div>
								<div data-value="Dark Green" class="swatch-element color dark-green available">
									<input class="swatchInput" id="swatch-0-dark-green" type="radio" name="option-0" value="Dark Green"><label class="swatchLbl color small" for="swatch-0-dark-green" style="background-color:darkgreen;" title="Dark Green"></label>
								</div>
							</div>
						</div>
						<div class="swatch clearfix swatch-1 option2" data-option-index="1">
							<div class="product-form__item">
								<label class="header">Terminado: <span class="slVariant">Dato seleccionado</span></label>
								<div data-value="XS" class="swatch-element xs available">
									<input class="swatchInput" id="swatch-1-xs" type="radio" name="option-1" value="XS">
									<label class="swatchLbl medium rectangle" for="swatch-1-xs" title="Mate">Mate</label>
								</div>
								<div data-value="S" class="swatch-element s available">
									<input class="swatchInput" id="swatch-1-s" type="radio" name="option-1" value="S">
									<label class="swatchLbl medium rectangle" for="swatch-1-s" title="Semi-Mate">Semi Mate</label>
								</div>
								<div data-value="M" class="swatch-element m available">
									<input class="swatchInput" id="swatch-1-m" type="radio" name="option-1" value="M">
									<label class="swatchLbl medium rectangle" for="swatch-1-m" title="Brillo">Brillo</label>
								</div>
								<div data-value="L" class="swatch-element l available">
									<input class="swatchInput" id="swatch-1-l" type="radio" name="option-1" value="L">
									<label class="swatchLbl medium rectangle" for="swatch-1-l" title="Alto-Brillo">Alto Brillo</label>
								</div>
							</div>
						</div>*}
						<!-- Product Action -->
						<div class="product-action clearfix">
							<div class="product-form__item--quantity">
								<div class="wrapQtyBtn">
									<div class="qtyField">
										<a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
										<input type="text" id="Quantity" name="quantity" value="1" class="product-form__input qty">
										<a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
							<div class="product-form__item--submit">
								<button type="button" name="add" class="btn product-form__cart-submit" data-id="{$product.product_id}">
									<span><i class="bi bi-cart mr-2"></i> {translate('ADD_TO_CART', 'main')}</span>
								</button>
							</div>
							{*<div class="shopify-payment-button" data-shopify="payment-button">
								<button type="button"
										class="shopify-payment-button__button shopify-payment-button__button--unbranded">
									{translate('BUY_NOW', $module)}
								</button>
							</div>*}
						</div>
						<!-- End Product Action -->

					{if !empty($customer)}
						<div class="display-table shareRow">
							<div class="display-table-cell medium-up--one-third">
								<div class="wishlist-btn">
									<a class="wishlist add-to-wishlist" href="#" title="{translate('ADD_WISHLIST', 'main')}">
                                        {if $product.is_wishlist|boolval}
											<i class="icon anm anm-heart" aria-hidden="true"></i>
											<span>{translate('REMOVE_WISHLIST', 'main')}</span>
                                        {else}
											<i class="icon anm anm-heart-l" aria-hidden="true"></i>
											<span>{translate('ADD_WISHLIST', 'main')}</span>
                                        {/if}

									</a>
								</div>
							</div>
						</div>
					{/if}


					<p class="shippingMsg">
						<i class="fa fa-truck" aria-hidden="true"></i> {translate('ESTIMATED_DATE', $module)}
						<b id="fromDate">Viernes 09 Febrero</b> /
						<b id="toDate">Viernes 23 Febrero</b>
					</p>
					<div class="userViewMsg" data-user="20" data-time="11000">
						<i class="fa fa-users" aria-hidden="true"></i>
						<strong class="uersView">14</strong> {translate('USERS_VIEW_PRODUCT', $module)}
					</div>
				</div>
			</div>
		</div>
		<!--End-product-single-->
		<!--Product Fearure-->
		<div class="prFeatures">
			<div class="row">
				<div class="col-12 col-sm-6 col-md-3 col-lg-3 feature">
					<img src="{$vars.images}credit-card.png" alt="{translate('SECURE_PAYMENTS', 'main')}" />
					<div class="details"><h3>{translate('SECURE_PAYMENTS', 'main')}</h3>{translate('GATWAYS_PAYMENT', 'main')}</div>
				</div>
				<div class="col-12 col-sm-6 col-md-3 col-lg-3 feature">
					<img src="{$vars.images}shield.png" alt="{translate('CONFIDENCE', 'main')}" />
					<div class="details"><h3>{translate('CONFIDENCE', 'main')}</h3>{translate('PERSONAL_DATA', 'main')}</div>
				</div>
				<div class="col-12 col-sm-6 col-md-3 col-lg-3 feature">
					<img src="{$vars.images}worldwide.png" alt="{translate('SHIPPING_ON_ZMG', 'main')}" />
					<div class="details"><h3>{translate('SHIPPING_ON_ZMG', 'main')}</h3>{translate('FREE_SHIPPING_ZMG', 'main')}</div>
				</div>
				<div class="col-12 col-sm-6 col-md-3 col-lg-3 feature">
					<img src="{$vars.images}phone-call.png" alt="{translate('SUPPORT', 'main')}" />
					<div class="details"><h3>{translate('SUPPORT', 'main')}</h3>{translate('SUPPORT_INFO', 'main')}</div>
				</div>
			</div>
		</div>
		<!--End Product Fearure-->

		<!--Product Tabs-->
		<div class="tabs-listing">
			<ul class="product-tabs">
				{*<li rel="tab1"><a class="tablink">Detalle del producto</a></li>*}
				<li rel="tab4"><a class="tablink">{translate('SHIPPING_AND_RETURNS', 'main')}</a></li>
			</ul>
			<div class="tab-container">
				{*<div id="tab1" class="tab-content">
					<div class="product-description rte">
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
						<ul>
							<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
							<li>Sed ut perspiciatis unde omnis iste natus error sit</li>
							<li>Neque porro quisquam est qui dolorem ipsum quia dolor</li>
							<li>Lorem Ipsum is not simply random text.</li>
							<li>Free theme updates</li>
						</ul>
						<h3>Sed ut perspiciatis unde omnis iste natus error sit voluptatem</h3>
						<p>You can change the position of any sections such as slider, banner, products, collection and so on by just dragging and dropping.&nbsp;</p>
						<h3>Lorem Ipsum is not simply random text.</h3>
						<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.</p>
						<p>Change colors, fonts, banners, megamenus and more. Preview changes are live before saving them.</p>
						<h3>1914 translation by H. Rackham</h3>
						<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.</p>
						<h3>Section 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</h3>
						<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
						<h3>The standard Lorem Ipsum passage, used since the 1500s</h3>
						<p>You can use variant style from colors, images or variant images. Also available differnt type of design styles and size.</p>
						<h3>Lorem Ipsum is not simply random text.</h3>
						<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.</p>
						<h3>Proin ut lacus eget elit molestie posuere.</h3>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.</p>
					</div>
				</div>*}

				<div id="tab4" class="tab-content">
					<h4>Returns Policy</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eros justo, accumsan non dui sit amet. Phasellus semper volutpat mi sed imperdiet. Ut odio lectus, vulputate non ex non, mattis sollicitudin purus. Mauris consequat justo a enim interdum, in consequat dolor accumsan. Nulla iaculis diam purus, ut vehicula leo efficitur at.</p>
					<p>Interdum et malesuada fames ac ante ipsum primis in faucibus. In blandit nunc enim, sit amet pharetra erat aliquet ac.</p>
					<h4>Shipping</h4>
					<p>Pellentesque ultrices ut sem sit amet lacinia. Sed nisi dui, ultrices ut turpis pulvinar. Sed fringilla ex eget lorem consectetur, consectetur blandit lacus varius. Duis vel scelerisque elit, et vestibulum metus.  Integer sit amet tincidunt tortor. Ut lacinia ullamcorper massa, a fermentum arcu vehicula ut. Ut efficitur faucibus dui Nullam tristique dolor eget turpis consequat varius. Quisque a interdum augue. Nam ut nibh mauris.</p>
				</div>
			</div>
		</div>
		<!--End Product Tabs-->

		<!--Related Product Slider-->
		<div class="tab-slider-product section">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="section-header text-center">
							<h2 class="h2">{translate('RELATED_PRODUCTS', $module)}</h2>
						</div>
						<div class="tabs-listing">
							<div class="tab_container">
								<div id="tab1" class="tab_content grid-products">
									<div class="productSlider">
                                        {assign var=index_carousel value="1"}
                                        {for $foo=1 to 10}
                                            {include file=$vars.config.path_views|cat:'main/partials/Product.tpl'}
                                        {/for}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--End Related Product Slider-->
	</div>
	<!--#ProductSection-product-template-->
</div>
<!--MainContent-->
