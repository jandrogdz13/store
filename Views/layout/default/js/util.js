const Util_Js = {

	url: location.protocol + '//' + location.host + '/',
	module : jQuery('#module').val(),
	t_module : jQuery('#t_module').val(),
	single_module : jQuery('#single_module').val(),
	version : jQuery('#version').val(),

	// Regex
	regexp_empty : /^.{0}$/,
	regexp_text : /["'a-zA-ZáéíóúÁÉÍÓÚâêîôûñÑ\s-]/g,
	regexp_numeric : /[0-9]/g,
	regexp_alphaNumeric : /["'a-zA-ZáéíóúÁÉÍÓÚâêîôûñÑ\s0-9\-\.\/]/g,
	regexp_decimal : /^\d+\.\d{0,2}$/,
	regexp_email : /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
	regexp_st_pass : /^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$/,

	url_geodata: 'https://geodata.phplift.net/api/index.php',

	_request : function(args = {}, before = true){
		let self = this;
		return jQuery.ajax({
			url: typeof args.external_url == 'undefined'? self.url + args.url: args.external_url,
			type : typeof args.type == 'undefined'? 'POST': args.type,
			data : typeof args.data == 'undefined'? {}: args.data,
			async: typeof args.async == 'undefined'? true: args.async,
			beforeSend: function(){
				//if(before) self.showOverlay();
			}
		});
	},

	_request_json : function(args = {}, before = true){
		let self = this;
		return jQuery.ajax({
			url: typeof args.external_url == 'undefined'? self.url + args.url: args.external_url,
			type : typeof args.type == 'undefined'? 'POST': args.type,
			data : typeof args.data == 'undefined'? {}: args.data,
			async: typeof args.async == 'undefined'? true: args.async,
			contentType: "application/json",
			cache:false,
			processData: false,
			beforeSend: function(){
				//if(before) self.showOverlay();
			}
		});
	},

	_requestForm : function(args = {}, before = false){
		let self = this;
		return jQuery.ajax({
			url: self.url + args.url,
			type : typeof args.type == 'undefined'? 'POST': args.type,
			data : typeof args.data == 'undefined'? {}: args.data,
			cache:false,
			contentType: false,
			processData: false,
			beforeSend: function(){

			}
		});
	},

	prevent_send_form_enter: function(){
		jQuery(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	},

	onInputEvents : function(){
		let self = this;

		//Text
		jQuery('input[data-uitype="text"]').on('keypress blur', function(e){
			var target = jQuery(this);
			if(e.type == 'blur'){
				//self.validate(target);
				//self.data[target.attr('id')] = target.val();
			}else{
				var charCode = String.fromCharCode((e.which) ? e.which : e.keyCode);
				if (!new RegExp(self.regexp_text).test(charCode)) {
					e.preventDefault();
					return false;
				}
			}
		});

		//Numeric
		jQuery('input[data-uitype="numeric"]').on('keypress blur', function(e){
			var target = jQuery(this);
			if(e.type == 'blur'){
				/*if(typeof target.data('rules') !== 'undefined' && target.data('rules').includes('require'))
					self.validate(target);*/
				//self.validate(jQuery(this));
			}else{
				var charCode = String.fromCharCode((e.which) ? e.which : e.keyCode);
				if (!new RegExp(self.regexp_numeric).test(charCode)) {
					e.preventDefault();
					return false;
				}
			}
		});

		// Alpha numeric
		jQuery('input[data-uitype="text-numeric"]').on('keypress blur', function(e){
			var target = jQuery(this);
			if(e.type == 'blur'){
				if(!target.val())
					target.val('');

				/*if(typeof target.data('rules') !== 'undefined' && target.data('rules').includes('require'))
					self.validate(target);*/
				//self.validate(target);
			}else{
				var charCode = String.fromCharCode((e.which) ? e.which : e.keyCode);
				if (!new RegExp(self.regexp_alphaNumeric).test(charCode)) {
					e.preventDefault();
					return false;
				}
			}
		});

		// Decimal
		jQuery('input[data-uitype="currency"], input[data-uitype="decimal"]').on('keypress blur', function(e){
			let target = jQuery(this);
			let value = target.val();
			if(e.type == 'blur'){

				if(!value.includes('.') && (value > 0 || value !== ''))
					target.val(value + '.00'); //.toFixed(2);

				if(typeof target.data('rules') !== 'undefined' && target.data('rules').includes('require'))
					self.validate(target);
				//self.validate(jQuery(this));
			}else{
				var charCode = (e.which) ? e.which : e.keyCode;
				if(charCode == 46){
					return value.indexOf('.') === -1? true: false;
				}else{
					if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
				}
				return true;
			}
		});

		// Email
		/*jQuery('input[data-uitype="email"]').on('blur', function(e){
			e.stopPropagation()
			let target = jQuery(this);
			var name = target.attr('name');
			var lblError = jQuery('#' + name + '-error');
			let value = target.val();
			var rules = target.data('rules');

			if(rules.length > 0){
				var strerr = !self.regexp_email.test(value.toLowerCase())? 'Ingresa un emai válido': '';
				self.setErrorInput(target, strerr);
			}
		});*/

		// Pasword
		jQuery('input[data-uitype="password"]').on('keyup blur', function(e){
			let target = jQuery(this);
			e.stopPropagation();

			if(e.type == 'blur'){
				/*if(target.val().length < 8){
					self.setErrorInput(target, 'Ingresa al menos 8 caracteres');
					return;
				}*/
				//self.validate(target);
			}else{
				let name = target.attr('id');
				let badge = jQuery('label[for="'+name+'"]').find('span');

				if (target.val().length === 0) {
					badge.text('').addClass('badge-opacity-warning');
					return;
				}

				//Regular Expressions.
				var regex = new Array();
				regex.push("[A-Z]"); //Uppercase Alphabet.
				regex.push("[a-z]"); //Lowercase Alphabet.
				regex.push("[0-9]"); //Digit.
				regex.push("[$@$!%*#?&]"); //Special Character.

				var passed = 0;

				//Validate for each Regular Expression.
				for (var i = 0; i < regex.length; i++) {
					if (new RegExp(regex[i]).test(target.val())) {
						passed++;
					}
				}

				//Validate for length of Password.
				if (passed > 2 && target.val().length > 8) {
					passed++;
				}

				var _class = '';
				var _text = '';
				switch (passed) {
					case 0:
					case 1:
						_text = "Débil";
						_class = "warning";
						break;
					case 2:
						_text = "Buena";
						_class = "primary";
						break;
					case 3:
					case 4:
						_text = "Fuerte";
						_class = "success";
						break;
					case 5:
						_text = "Muy fuerte";
						_class = "danger";
						break;
				}
				badge.text(_text);
				badge.removeClass('badge-opacity-warning badge-opacity-primary badge-opacity-success badge-opacity-danger').addClass('badge-opacity-' + _class);


			}
		});

		// Picklist
	},

	// Cart
	add_to_cart: function(){
		const self = this;
		jQuery('.btn-addto-cart, .product-form__cart-submit').on('click', function(e){

			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();

			const target = jQuery(this);
			const product_id = target.data('id');
			let is_quick_view = parseInt(target.data('qv'));
			let parent, product_name, quantity = 1;

			if(is_quick_view){
				quantity = parseInt(jQuery('.product-form__input.qty').val());
				product_name = jQuery('.product-single__title').text();
			}else{
				if(window.location.href.includes('product')){
					product_name = jQuery('.product-single__title').text();
				}else{
					parent = target.parents('.product-details');
					product_name = parent.find('.product-name a').text();
				}

			}

			if(product_id > 0){
				self._request({
					url: 'cart/add',
					data: {
						product_id,
						product_name,
						quantity,
						is_cart: 0,
					}
				}).then(function(json){
					if(json.success){
						Alert_Js.auto_close({
							title: json.title,
							text: json.msg,
							progress_bar: true,
							icon: 'success',
						});
						self.calculate_totals();
					}else{
						Alert_Js.auto_close({
							title: json.title,
							text: json.err,
							progress_bar: true,
							icon: 'error',
						});
					}
				});
			}
		});
	},

	remove_from_cart: function(){
		const self = this;
		jQuery('.remove').on('click', function(e){

			e.preventDefault();

			const target = jQuery(this);
			const product_id = target.data('id');
			let item = target.parents('.item');
			let parent = target.parent();
			let product_name = parent.find('.pName').text();

			jQuery('.loader-cart').addClass('show');
			self._request({
				url: 'cart/remove',
				data: {
					product_id,
					product_name,
					quantity: 1,
				}
			}).then(function(json){
				//console.debug(json);
				if(json.success){
					item.remove();
					Alert_Js.auto_close({
						title: json.title,
						text: json.msg,
						progress_bar: true,
						icon: 'success',
					}).then(function(){
						self.calculate_totals();
						jQuery('.loader-cart').removeClass('show');
					});
				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						progress_bar: true,
						icon: 'error',
					});
					jQuery('.loader-cart').removeClass('show');
				}
			});

		});
	},

	remove_from_checkout: function(){
		const self = this;
		jQuery('.cart__remove').on('click', function(e){

			e.preventDefault();

			const target = jQuery(this);
			const product_id = target.data('id');
			let item = target.parents('tr.cart__row');
			let product_name = item.find('td .list-view-item__title').text();

			self._request({
				url: 'cart/remove',
				data: {
					product_id,
					product_name,
					quantity: 1,
				}
			}).then(function(json){
				//console.debug(json);
				if(json.success){
					item.remove();
					Alert_Js.auto_close({
						title: json.title,
						text: json.msg,
						progress_bar: true,
						icon: 'success',
					}).then(function(){
						if(json.data.products.length <= 0)
							window.location.href = window.location.origin;
					});
				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						progress_bar: true,
						icon: 'error',
					});
				}
			});

		});
	},

	open_cart: function(){
		const self = this;
		jQuery('.site-header__cart').on('click', function(){
			self.calculate_totals();
		});
	},

	close_cart: function(){
		jQuery('#close-cart').on('click', function(){
			jQuery("body").find("#header-cart").removeClass('open');
			jQuery(".backdrop").removeClass('open');
			jQuery('#content-products').html('');
		});
	},

	qnt_incre: function(){
		const self = this;
		jQuery(".qtyBtn").on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();

			let qtyField = jQuery(this).parent(".qtyField"),
				oldValue = jQuery(qtyField).find(".qty").val(),
				newVal = 1;
			let stock = parseInt(qtyField.data('stock'));
			let is_quick_view = qtyField.data('qv');

			if (jQuery(this).is(".plus")) {

				if(parseInt(oldValue) + 1 > stock)
					return;

				newVal = parseInt(oldValue) + 1;
			} else if (oldValue > 1) {
				newVal = parseInt(oldValue) - 1;
			}
			jQuery(qtyField).find(".qty").val(newVal);

			if(!is_quick_view){
				let target = jQuery(this);
				let parent = target.parents('.product-details');
				let product_id = parseInt(parent.data('id'));
				let product_name = parent.find('.pName').text();

				if(product_id > 0){
					jQuery('.loader-cart').addClass('show');
					self._request({
						url: 'cart/add',
						data: {
							product_id,
							product_name,
							quantity: newVal,
							is_cart: 1
						}
					}).then(async function(json){
						if(json.success){
							await self.calculate_totals();
							setTimeout(function(){
								jQuery('.loader-cart').removeClass('show');
							}, 1500);
						}else{
							Alert_Js.auto_close({
								title: json.title,
								text: json.err,
								progress_bar: true,
								icon: 'error',
							});
						}
					});
				}
			}

		});
	},

	hide_cart: function(){
		jQuery(".backdrop").on('click', function(){
			jQuery("body").find("#header-cart").removeClass('open');
			jQuery(".backdrop").removeClass('open');
			jQuery('#content-products').html('');
		});
	},


	calculate_totals: function(){

		const self = this;
		let url = 'cart/cart';
		self._request({
			url
		}).then(function(_html){
			jQuery.when(
				jQuery('#content-products').html(_html)
			).then(function(){
				self._request({
					url: 'main/totals/1'
				}).then(function(_json){
					console.debug(_json);

					self.close_cart();
					self.qnt_incre();

					// Set count products cart
					jQuery('#CartCount').text(_json.data.count);
					if(parseInt(_json.data.count) <= 0){
						//jQuery(".backdrop").click();
						jQuery('#content-products').addClass('d-flex flex-column justify-content-center');
						jQuery('#header-cart .total').hide();
					}else{

						jQuery('#content-products').removeClass();
						jQuery('#header-cart .total').removeClass('hide').show(300);

						// Events
						self.remove_from_cart();

						// Recalculate
						let currency = $.cookie('currency');
						let subtotal = jQuery('.totals__cart__subtotal .product-price .money');
						let discounts = jQuery('.totals__cart__discount .product-price .money');
						let subtotal_inc_discount = jQuery('.totals__cart__subtotal_discount .product-price .money');
						let shipping = jQuery('.totals__cart__shipping .product-price .money');
						let total = jQuery('.totals__cart__total .product-price .money');

						setTimeout(function(){
							self._counter(subtotal, parseFloat(_json.data.totals.subtotal));
							self._counter(discounts, parseFloat(_json.data.totals.discounts));
							self._counter(subtotal_inc_discount, parseFloat(_json.data.totals.subtotal_inc_disc));
							self._counter(shipping, parseFloat(_json.data.totals.shipping));
							self._counter(total, parseFloat(_json.data.totals.subtotal_inc_disc) + parseFloat(_json.data.totals.shipping));
						}, 500);
					}
				});
			});
		});
	},

	// Product
	quick_view: function(){
		const self = this;
		jQuery('.quick-view').on('click', function(e){
			const target = jQuery(this);
			let product_id = target.data('id');
			e.preventDefault();

			self._request({
				url: 'product/quick_view',
				data: {
					product_id
				},
			}).then(function(_html){
				jQuery('#content_quickview .modal-body').html(_html);
				self.add_to_cart();
				self.qnt_incre();
				self.add_wishlist();
			});
		});
	},

	add_wishlist: function(){
		const self = this;
		jQuery('.add-to-wishlist').on('click', function(e){
			const target = jQuery(this);
			let product_id = target.data('id');
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();

			let add_to_wishlist = jQuery('.add-to-wishlist');

			self._request({
				url: 'product/wishlist',
				data: {
					product_id
				},
			}).then(function(_json){
				if(_json.success){
					add_to_wishlist.find('i').toggleClass('anm-heart');
					add_to_wishlist.find('i').toggleClass('anm-heart-l');
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.msg,
						progress_bar: true,
						icon: 'success',
					});
				}else{
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.err,
						progress_bar: true,
						icon: 'error',
					});
				}
			});
		});
	},

	change_language: function(){
		const self = this
		jQuery('#language li').on('click', function(){
			let target = jQuery(this);
			let lang = target.data('lang');
			let url_ob = new URL(window.location.href);

			let a = document.querySelector("#google_translate_element select");
			a.value = lang;
			a.dispatchEvent(new Event('change'));

			if(lang === 'es')
				$.cookie('currency', 'MXN');
			else
				$.cookie('currency', 'USD');
		});
	},

	change_currency: function(){
		const self = this
		jQuery('#currencies li').on('click', function(){
			let target = jQuery(this);
			let currency = target.data('currency');
			$.cookie('currency', currency);
			setTimeout(function(){
				window.location.reload();
			}, 700);
		});
	},

	init_accordion: function(){
		const self = this;
		jQuery('.accordion-list > li > .answer').hide();
		jQuery('.accordion-list h3').on('click', function(){
			const target = jQuery(this);
			const parent = target.parent();

			if(parent.hasClass("active")) {
				parent.removeClass("active").find(".answer").slideUp();
			} else {
				jQuery(".accordion-list > li.active .answer").slideUp();
				jQuery(".accordion-list > li.active").removeClass("active");
				parent.addClass("active").find(".answer").slideDown();
			}
		});
	},

	// Account
	open_sidebar: function(){
		const self = this;
		jQuery('.account-trigger').on('click', function(e){
			e.preventDefault();
			jQuery('#sidebar-account').toggleClass('open');
			jQuery(".backdrop").toggleClass('open');
			self.hide_sidebar();

			Util_Js._request({
				url: 'account/account'
			}).then(function(_html){
				//	console.debug(_html);
				jQuery('#sidebar-content').html(_html);
				self.show_detail();
				self.init_tab();
				self.add_address_account();
				self.edit_address_account();
				self.click_tab();
			});
		});
	},

	hide_sidebar: function(){
		jQuery(".backdrop, .close-sidebar").on('click', function(e){
			e.stopPropagation();
			jQuery("body").find("#checkout-address, #sidebar-account").removeClass('open');
			jQuery(".backdrop").removeClass('open');
		});
	},

	show_detail: function(){
		jQuery('.icon-swipe-down').on('click', function(){
			let target = jQuery(this);
			jQuery('.order-detail').slideUp(300);
			jQuery('.icon-swipe-down').show(300);
			target.hide(300);
			target.parents('.card-body').find('.order-detail').slideDown(300);
		});
	},

	// Address
	add_address_account: function(){
		const self = this;
		jQuery('#add-address').on('click', function(){
			Util_Js._request({
				url: 'checkout/address_form'
			}).then(function(_html){
				jQuery('#tab-address .fadein').html(_html);
				self.onInputEvents();
				self.change_country();
				self.send_form_account();
				self.cancel_form();
			});
		});
	},

	edit_address_account: function(){
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
				jQuery('#tab-address .fadein').html(_html);
				Util_Js.onInputEvents();
				self.change_country();
				self.send_form_account(address_id);
				self.cancel_form();

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

	cancel_form: function(){
		const self = this;
		jQuery('.btn-cancel-addr').on('click', function(){
			Util_Js._request({
				url: 'checkout/addresses'
			}).then(function(_html){
				jQuery('#tab-address .fadein').html(_html);
				self.add_address_account();
				self.edit_address_account();
			});
		});
	},

	change_country: function(){
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
	},

	change_state: function(){
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
	},

	send_form_account: function(edit = 0){
		const self = this;

		jQuery('.btn-save-addr').on('click', function(){
			jQuery('#AddressForm').submit();
		});


		jQuery('#AddressForm').on('submit', async function(e){
			e.preventDefault();

			const requires = [
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
				//console.debug(_json);
				if(_json.success){
					Alert_Js.auto_close({
						title: _json.title,
						text: _json.msg,
						icon: 'success',
					}).then(function(){
						Util_Js._request({
							url: 'checkout/addresses'
						}).then(function(_html){
							jQuery('#tab-address .fadein').html(_html);
							self.add_address_account();
							self.edit_address_account();
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

	// Functions

	init_tab: function(){
		jQuery(".tabs").on('click', '.tab', function(e){
			e.preventDefault();
			jQuery(".tab").removeClass("active");
			jQuery(".content").removeClass("show");
			jQuery(this).addClass("active");
			jQuery(jQuery(this).attr("href")).addClass("show");
		});
	},

	click_tab: function(){
		jQuery('.tab').on('click', function(){
			const target = jQuery(this);
			const text = target.attr('title');
			jQuery('#sidebar-account #cart-title h2').text(text);
		});
	},

	_round : function(number, digits = 2){
		let multiplier = Math.pow(10, digits),
			adjustedNum = number * multiplier,
			truncatedNum = Math.ceil(adjustedNum); // Math[adjustedNum < 0 ? 'ceil' : 'floor'](adjustedNum);
		return truncatedNum / multiplier;
		//return Math.round((num + Number.EPSILON) * 100) / 100;
	},

	_number_format : function(_number, decimals = 2, dec_point = '.', thousands_sep = ',') {
		number = parseFloat(_number).toFixed(decimals);

		var nstr = number.toString();
		nstr += '';
		x = nstr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? dec_point + x[1] : '';
		var rgx = /(\d+)(\d{3})/;

		while (rgx.test(x1))
			x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

		return x1 + x2;
	},

	_counter : async function(target, amount, decimals = 2, symbol = '$'){
		const self = this;
		let deferred = new $.Deferred();

		if(amount > 0){
			target.text(amount);
			await target.each(function (){
				jQuery(this).prop('Counter', 0).stop(true).animate({
					Counter: jQuery(this).text()
				},{
					duration: 300,
					easing: 'swing',
					step: function (now){
						jQuery(this).text(symbol + now.toFixed(2));
					},
					complete: function () {
						var _amount = self._round(amount, decimals);
						jQuery(this).text(symbol + self._number_format(_amount, decimals));
						deferred.resolve();
					}
				});
			});
		}else{
			if(target.length > 0) target.text(symbol + '0.00');
			deferred.resolve();
		}
		return deferred.promise();
	},

	readCookie: function(name) {
		var c = document.cookie.split('; '),
			cookies = {}, i, C;

		for (i = c.length - 1; i >= 0; i--) {
			C = c[i].split('=');
			cookies[C[0]] = C[1];
		}
		return cookies[name];
	},

	init: function(){

		this.prevent_send_form_enter();
		this.change_currency();
		this.change_language();
		this.onInputEvents();
		this.open_cart();
		this.hide_cart();

		// Product
		this.quick_view();
		this.add_wishlist();

		if(window.location.href.includes('product'))
			this.init_accordion();

		// Cart
		this.add_to_cart();
		this.remove_from_cart();
		this.remove_from_checkout();

		// Account
		this.open_sidebar();

		let currency = $.cookie('currency');
		if(!currency)
			$.cookie('currency', 'MXN');

	},
};

jQuery(document).ready(function(){
	Util_Js.init();
});

function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'es',
		includedLanguages: 'en,es',
		autoDisplay: false
	}, 'google_translate_element');

	let a = document.querySelector("#google_translate_element select");
	a.selectedIndex=1;
	a.dispatchEvent(new Event('change'));
}
