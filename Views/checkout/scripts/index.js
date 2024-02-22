const Checkout_Js = {

	url_geodata: 'https://geodata.phplift.net/api/index.php',

	// Address
	open_sidebar: function(){
		const self = this;
		jQuery('#select_address').on('click', function(e){
			e.preventDefault();
			jQuery('#checkout-address').toggleClass('open');
			jQuery(".backdrop").toggleClass('open');
			Util_Js.hide_sidebar();

			Util_Js._request({
				url: 'checkout/addresses'
			}).then(function(_html){
				jQuery('#content-addresses').html(_html);
				self.add_address();
				self.select_address();
				self.edit_address();
			});
		});
	},

	add_address: function(){
		const self = this;
		jQuery('#add-address').on('click', function(){
			Util_Js._request({
				url: 'checkout/address_form'
			}).then(function(_html){
				jQuery('#content-addresses').html(_html);
				Util_Js.onInputEvents();
				Util_Js.change_country();
				self.cancel_form();
				self.send_form();
			});
		});
	},

	edit_address: function(){
		const self = this;
		jQuery('.edit-address').on('click', function(){
			const target = jQuery(this);
			const address_id = parseInt(target.parents('.card').data('id'));

			jQuery('.loader-cart').addClass('show');
			Util_Js._request({
				url: 'checkout/address_form/1',
				data: {
					address_id
				},
			}).then(function(_html){
				jQuery('#content-addresses').html(_html);
				Util_Js.onInputEvents();
				Util_Js.change_country();
				self.cancel_form();
				self.send_form(address_id);

				// Set State & City
				setTimeout(async function(){
					await jQuery('select#country').change();
					setTimeout(async function(){
						await jQuery('select#state').change();
						setTimeout(async function(){
							await jQuery('select#city').change();
							jQuery('.loader-cart').removeClass('show');
						}, 1000);
					}, 1000);
				},1000);

			});
		});
	},

	/*change_country: function(){
		let self = this;
		jQuery('select#country').change(function(){
			let target = jQuery(this);
			let id = parseInt(target.val());

			let state = jQuery('select#state');
			let city = jQuery('select#city');

			if(id === 0){
				// State
				let option = new Option('Selecciona estado', 0);
				state.attr('disabled', false).empty();
				jQuery(option).html('Selecciona estado');
				state.append(option);

				// city
				let option_c = new Option('Selecciona ciudad', 0);
				city.attr('disabled', false).empty();
				jQuery(option_c).html('Selecciona ciudad');
				city.append(option_c);
				return false;
			}

			Util_Js._request({
				external_url: self.url_geodata + '?type=getStates&countryId=' + id,
				type: 'GET',
			}).then(async function(data){
				if(jQuery.isArray(data.result)){

					let state_value = state.data('value');

					await jQuery.each(data.result, function(idx, el){
						let text = el.name.toUpperCase();
						let option = new Option(text, el.id);

						if(state_value !== '' && el.name.toLowerCase() === state_value.toLowerCase())
							jQuery(option).attr('selected', true).html(text);
						else
							jQuery(option).html(text);

						jQuery('select#state').append(option);
					});
					self.change_state();
				}
			});
		});
	},*/

	/*change_state: function(){
		let self = this;
		jQuery('select#state').change(function(){
			let target = jQuery(this);
			let id = parseInt(target.val());
			let country_id = parseInt(jQuery('select#country').val());
			let city = jQuery('select#city');

			if(id === 0 || country_id === 0){
				// city
				let option = new Option('Selecciona ciudad', 0);
				city.attr('disabled', false).empty();
				jQuery(option).html('Selecciona ciudad');
				city.append(option);
				return false;
			}

			Util_Js._request({
				external_url: self.url_geodata + '?type=getCities&countryId=' + country_id + '&stateId=' + id,
				type: 'GET',
			}).then(async function(data){

				if(jQuery.isArray(data.result)){
					let city_value = city.data('value');

					await jQuery.each(data.result, function(idx, el){
						let text = el.name.toUpperCase();
						let option = new Option(text, el.id);

						if(city_value !== '' && el.name.toLowerCase() === city_value.toLowerCase())
							jQuery(option).attr('selected', true).html(text);
						else
							jQuery(option).html(text);

						jQuery('select#city').append(option);
					});
					jQuery('select#city').attr('disabled', false);
				}
			});
		});
	},*/

	select_address: function(){
		jQuery('#content-addresses').on('click', '.card-body', function(e){

			e.stopPropagation();
			e.stopImmediatePropagation();
			let e_target = jQuery(e.target);
			if(e_target.is('span') && e_target.attr('class').includes('edit-address'))
				return false;

			const target = jQuery(this);
			const address_id = parseInt(target.parent().data('id'));

			jQuery('#content-addresses').find('.card').removeClass('active');
			jQuery(this).addClass('active');

			jQuery('.loader-cart').addClass('show');
			Util_Js._request({
				url: 'checkout/address',
				data: {
					address_id
				}
			}).then(async function(_json){
				console.debug(_json);
				if(_json.success){
					let zipcode = _json.data.postcode;
					let suburb = _json.data.suburb;
					jQuery('#select_address').text('C.P. ' + zipcode + ', ' + suburb);
					jQuery('#address_id').val(_json.data.addressid);
					//jQuery('#rates').empty();
					jQuery('#rates li:not(:first-child)').remove();

					setTimeout(function(){
						jQuery('.loader-cart').removeClass('show');
						jQuery('.close-sidebar').click();
					}, 1000);

				}else{
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.err,
						icon: 'error',
					});
				}
			}).catch(function() {
				alert("some error");
			});
		});
	},

	cancel_form: function(){
		const self = this;
		jQuery('.btn-cancel-addr').on('click', function(){
			Util_Js._request({
				url: 'checkout/addresses'
			}).then(function(_html){
				jQuery('#content-addresses').html(_html);
				self.add_address();
				self.edit_address();
			});
		});
	},

	send_form: function(edit = 0){
		const self = this;

		jQuery('.btn-save-addr').on('click', function(){
			jQuery('#AddressForm').submit();
		});

		jQuery('#AddressForm').on('submit', async function(e){
			e.preventDefault();

			const requires = [
				'name',
				'street',
				'outdoor_num',
				'suburb',
				'country',
				'state',
				'city',
				'postcode',
			];
			let frm = jQuery(this)
			let form_data = new FormData(frm[0]);

			let country = jQuery("#country option:selected").text();
			let state = jQuery("#state option:selected").text();
			let city = jQuery("#city option:selected").text();

			form_data.delete('country');
			form_data.set('state', state);
			form_data.set('city', city);

			let empties = [];
			for (const [key, value] of form_data){
				if(requires.includes(key)){
					if(value.trim() === ''){
						empties.push(key);
					}
				}
			}

			if(!jQuery.isEmptyObject(empties)){
				Alert_Js.auto_close({
					title: 'Direcciones',
					text: 'Llena todos los campos requeridos',
					icon: 'error',
				});
				return false;
			}

			Util_Js._requestForm({
				url: 'checkout/save_address/' + edit,
				data: form_data
			}).then(function(_json){
				console.debug(_json);
				if(_json.success){
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.msg,
						icon: 'success',
					}).then(function(){
						let zipcode = _json.data.postcode;
						let suburb = _json.data.suburb;
						jQuery('#select_address').text('C.P. ' + zipcode + ', ' + suburb);

						Util_Js._request({
							url: 'checkout/addresses'
						}).then(function(_html){
							jQuery('#content-addresses').html(_html);
							self.add_address();
							self.edit_address();
							self.select_address();
						});
					});
				}else{
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.err,
						icon: 'error',
					});
				}
			});

			console.debug(form_data);
		});
	},

	// Shipping
	get_rates: function(){
		const self = this;
		jQuery('.get-rates').on('click', function(){
			jQuery('.loader-cart-page').addClass('show');

			let address_id = parseInt(jQuery('#address_id').val());
			let rate_id = parseInt(jQuery('#rate_id').val());

			if(!address_id){
				Alert_Js.auto_close({
					title: 'Direcciones',
					text: 'Selecciona dirección de entrega',
					icon: 'error',
				});
				jQuery('.loader-cart-page').removeClass('show');
				return false;
			}

			Util_Js._request({
				url: 'checkout/get_rates'
			}).then(async function(_json){
				console.debug(_json);

				if(_json.success){
					try{
						let target = jQuery('#rates');
						jQuery('#rates li:not(:first-child)').remove();
						let url_origin = window.location.origin;
						await jQuery.each(_json.data, function(idx, rate){
							let item = rate.attributes;
							//console.debug(rate);
							let li = jQuery('<li ' +
								'class="rate d-flex align-items-center justify-content-start" ' +
								'data-id="' + rate.id + '" ' +
								'data-provider="' + item.provider + '" ' +
								'data-service_level_name="' + item.service_level_name + '" ' +
								'data-service_level_code="' + item.service_level_code + '" ' +
								'data-days="' + item.days + '" ' +
								'data-total_pricing="' + item.total_pricing + '" ' +
								'>' +
								'<img src="'+ url_origin +'/Views/layout/default/images/shipments/' + item.provider.toLowerCase() + '.svg" alt="'+ item.provider +'" />' +
								'<div class="provider d-flex flex-column justify-content-center align-items-start" data-service-code="'+item.service_level_code+'">' +
								'	<span>'+ item.provider +' <span class="service_level">'+ item.service_level_name +'</span></span>' +
								'	<span>Entrega estimada '+ item.days +' días</span>' +
								'	<span>$'+ Util_Js._number_format(parseFloat(item.total_pricing)) +' '+ item.currency_local +'</span>' +
								'</div>' +
								'</li>');
							target.append(li);
						});
						self.select_rate();
						jQuery('.loader-cart-page').removeClass('show');
					}catch(error){
						console.debug(error);
						jQuery('.loader-cart-page').removeClass('show');
					}

				}else{
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.err,
						icon: 'error',
					}).then(function(){
						jQuery('.loader-cart-page').removeClass('show');
					});
				}
			});
		});
	},

	select_rate: function(){
		jQuery('.rate').on('click', function(){
			const target = jQuery(this);
			let rate_id = target.data('id');
			let provider = target.data('provider');
			let service_level_name = target.data('service_level_name');
			let service_level_code = target.data('service_level_code');
			let total_pricing = parseFloat(target.data('total_pricing'));
			let days = parseInt(target.data('days'));

			let shipping_cost = jQuery('.shipping_cost');
			let total = jQuery('.total-amount-checkout');

			jQuery('.rate').removeClass('selected');
			jQuery(this).addClass('selected');

			Util_Js._request({
				url: 'checkout/shipping',
				data: {
					rate_id,
					provider,
					service_level_name,
					service_level_code,
					total_pricing,
					days
				}
			}).then(async function(_json){
				console.debug(_json);
				if(_json.success){
					jQuery('#rate_id').val(rate_id);
					Util_Js._counter(shipping_cost, total_pricing);
					Util_Js._counter(total, parseFloat(_json.data.totals.subtotal_inc_disc) + total_pricing);
					jQuery('.row.shipping').find('#provider-service').html(provider + ' -- <small>' + service_level_name + '</small>');
				}else{

				}
			});
		});
	},

	// Payment Gateways
	select_payment_gateway: function(){
		const self = this;
		jQuery('.accordion-list > li > .answer').hide();
		jQuery('.payment_gateway h3').on('click', function(){
			const target = jQuery(this);
			const parent = target.parent();
			const type = parent.data('id');
			let address_id = parseInt(jQuery('#address_id').val());
			let rate_id = parseInt(jQuery('#rate_id').val());

			if(!address_id){
				Alert_Js.auto_close({
					title: 'Direcciones',
					text: 'Selecciona una dirección de entrega',
					icon: 'error',
				});
				return false;
			}

			if(!rate_id){
				Alert_Js.auto_close({
					title: 'Envíos',
					text: 'Selecciona un servicio de envío',
					icon: 'error',
				});
				return false;
			}

			if(type === 'spei')
				jQuery('#cartCheckout').show();
			else
				jQuery('#cartCheckout').hide();

			if(parent.hasClass('active'))
				return false;

			if (parent.hasClass("active")) {
				parent.removeClass("active").find(".answer").slideUp();
			} else {
				jQuery(".accordion-list > li.active .answer").slideUp();
				jQuery(".accordion-list > li.active").removeClass("active");

				jQuery('.loader-cart-page').addClass('show');
				Util_Js._request({
					url: 'checkout/payment',
					data: {
						type
					}
				}).then(async function(_json){
					//console.debug(_json);
					if(_json.success){
						jQuery('#payment_id').val(type);
						if(type === 'mercadopago'){
							await self.init_mp(_json)
						}else if(type === 'paypal'){
							await self.init_paypal(_json);
						}else{
							await self.init_transfer();
						}

						setTimeout(async function(){
							await parent.addClass("active").find(".answer").slideDown();
							jQuery('.loader-cart-page').removeClass('show');

							jQuery('html, body').animate({
								scrollTop: jQuery('.payment_gateway.active').offset().top
							}, 1000);

						}, 1500);
					}else{
						console.dedbug(_json);
					}
				});
			}
		});
	},

	init_transfer: function(){
		jQuery('#cartCheckout').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();

			if(!(jQuery('#cartTearm').is(':checked'))){
				Alert_Js.auto_close({
					title: 'Términos',
					text: 'Debes aceptar los términos y condiciones',
					icon: 'error',
				});
				return false;
			}

			jQuery('.loader-cart-page').addClass('show');
			Util_Js._request({
				url: 'checkout/create_order',
				data: {
					type: 'transferencia',
					payment_detail: {
						'status': 'PENDING',
						'id': 0
					}
				}
			}).then(function(_json) {
				console.debug(_json);
				if(_json.success) {
					jQuery('.loader-cart-page').removeClass('show');
					window.location.href = 'checkout/summary/' + _json.data.order_id ;
				}else {
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.err,
						icon: 'error',
					});
					jQuery('.loader-cart-page').removeClass('show');
				}
			});
		});
	},

	init_mp: async function(cart){
		if(jQuery('#cardPaymentBrick_container').html().length > 0)
			window.cardPaymentBrickController.unmount()


		let api_key = cart.data.apis.mercadopago.MP_SANDBOX
			? cart.data.apis.mercadopago.MP_API_KEY_SANDBOX
			: cart.data.apis.mercadopago.MP_API_KEY_PRODUCTION;

		const mp = new MercadoPago(api_key, {
			locale: 'es-MX'
		});
		const bricksBuilder = mp.bricks();

		const renderCardPaymentBrick = async (bricksBuilder) => {
			const settings = {
				initialization: {
					amount: parseFloat(cart.data.totals.subtotal_inc_disc) + parseFloat(cart.data.totals.shipping), // monto total a pagar
				},
				callbacks: {
					onReady: () => {
						setTimeout(function(){
							jQuery('.loader-cart-page').removeClass('show');
						}, 1000);
						/*
						  Callback llamado cuando Brick está listo.
						  Aquí puedes ocultar cargamentos de su sitio, por ejemplo.
						*/
					},
					onSubmit: (formData) => {
						// callback llamado al hacer clic en el botón enviar datos
						return new Promise((resolve, reject) => {

							if(!(jQuery('#cartTearm').is(':checked'))){
								Alert_Js.auto_close({
									title: 'Términos',
									text: 'Debes aceptar los términos y condiciones',
									icon: 'error',
								});
								reject();
								return false;
							}

							//console.debug(formData);
							jQuery('.loader-cart-page').addClass('show');
							Util_Js._request_json({
								url: 'checkout/intent_ML',
								data: JSON.stringify(formData)
							}).then(function(response){
								console.debug(response);
								if (response.data.status === 'approved') {
									// Save order on app
									Util_Js._request({
										url: 'checkout/create_order',
										data: {
											type: 'mercadopago',
											payment_detail: response
										}
									}).then(function(_json) {
										if(_json.success) {
											jQuery('.loader-cart-page').removeClass('show');
											window.location.href = 'checkout/summary/' + _json.data.order_id ;
										}else {
											Alert_Js.auto_close({
												title: _json.title,
												text: _json.err,
												icon: 'error',
											});
											jQuery('.loader-cart-page').removeClass('show');
											reject();
										}
									});
									resolve();
								}else{
									Alert_Js.auto_close({
										title: 'Mercado Pago',
										text: response.data.status_detail,
										icon: 'error',
									}).then(function(){
										jQuery('.loader-cart-page').removeClass('show');
										reject();
									});
								}
							}).catch(function(error){
								console.debug(error);
								Alert_Js.auto_close({
									title: 'Mercado Pago',
									text: 'Sucedio algo al crear el pago',
									icon: 'error',
								}).then(function(){
									reject();
								});
							});
						});
					},
					onError: (error) => {
						// callback llamado para todos los casos de error de Brick
						console.error(error);
						jQuery('.loader-cart-page').removeClass('show');

					}
				},
			};
			window.cardPaymentBrickController = await bricksBuilder.create(
				'cardPayment',
				'cardPaymentBrick_container',
				settings,
			);
		};
		await renderCardPaymentBrick(bricksBuilder);
	},

	init_paypal: function(cart){
		if(jQuery('#paypal-buttons-container').html().length > 0)
			jQuery('#paypal-buttons-container').html('');


		setTimeout(function(){
			// Render the PayPal buttons
			paypal.Buttons({
				env: cart.data.apis.paypal.PAYPAL_SANDBOX? 'sandbox': 'production',
				client: {
					sandbox: cart.data.apis.paypal.PAYPAL_CLIENT_ID_SANDBOX,
					production: cart.data.apis.paypal.PAYPAL_CLIENT_ID_PRODUCTION
				},
				style: {
					layout: 'vertical',
					color:  'black',
					shape:  'rect',
					label:  'paypal',
					height: 50,
				},
				createOrder(data, actions) {
					return actions.order.create({
						purchase_units: [{
							reference_id: cart.data.session_id,
							description: "Mobel Inn",
							amount: {
								value: parseFloat(cart.data.totals.subtotal_inc_disc) + parseFloat(cart.data.totals.shipping),
							},
							payment_descriptor: "Tienda Mobel Inn",
							//custom: "Account Code: XXXXX"
						}],
						application_context: {
							shipping_preference: "NO_SHIPPING"
						}
					});
				},
				onApprove(data, actions) {
					// This function captures the funds from the transaction.
					return actions.order.capture().then(function(order_data){
						jQuery('.loader-cart-page').addClass('show');
						//console.debug(order_data);
						// Save order on app
						if(order_data.status = 'COMPLETED'){
							Util_Js._request({
								url: 'checkout/create_order',
								data: {
									type: 'paypal',
									payment_detail: order_data
								}
							}).then(function(_json) {
								if(_json.success) {
									jQuery('.loader-cart-page').removeClass('show');
									window.location.href = 'checkout/summary/' + _json.data.order_id ;
								}else {
									Alert_Js.auto_close({
										title: _json.title,
										text: _json.err,
										icon: 'error',
									});
									jQuery('.loader-cart-page').removeClass('show');
								}
							});
						}else{
							jQuery('.loader-cart-page').removeClass('show');
						}
					});
				},
				onError(err) {
					// For example, redirect to a specific error page
					Alert_Js.auto_close({
						title: 'Paypal',
						text: 'Error al crear el pago',
						icon: 'error',
					});
					console.debug(error);
				},
				onClick(){
					if(!(jQuery('#cartTearm').is(':checked'))){
						Alert_Js.auto_close({
							title: 'Términos',
							text: 'Debes aceptar los términos y condiciones',
							icon: 'error',
						});
						return false;
					}
				}
			}).render('#paypal-buttons-container');
		},1300);

	},

	init: function(){
		this.open_sidebar();
		this.get_rates();
		this.select_rate();
		this.select_payment_gateway();
	},

};

jQuery(document).ready(function(){
	Checkout_Js.init();
});
