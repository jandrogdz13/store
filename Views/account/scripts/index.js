const Account_Js = {

	on_submit_login_form: function(){
		const self = this;
		jQuery('#CustomerLoginForm').submit(function(e){
			e.preventDefault();
			e.stopPropagation();

			let url = 'account/login';
			let email = jQuery('#CustomerEmail').val();
			let pass = jQuery('#CustomerPassword').val();

			// Validate Email
			if(!Util_Js.regexp_email.test(email.toLowerCase())){
				Alert_Js.auto_close({
					title: 'Log In',
					text: 'Ingresa un email válido',
					icon: 'error',
				});
				return false;
			}

			let form_data = new FormData(this);

			Util_Js._requestForm({
				url: url,
				data: form_data
			}).then(function(json){
				console.debug(json);
				if(json.success){
					window.location.href = '/';
				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						icon: 'error',
					});
				}
			});
		});
	},

	on_submit_register_form: function(){
		const self = this;
		jQuery('#CustomerLoginForm').submit(function(e){
			e.preventDefault();
			e.stopPropagation();

			let url = 'account/register';
			let first_name = jQuery('#FirstName').val();
			let last_name = jQuery('#LastName').val();
			let email = jQuery('#CustomerEmail').val();
			let pass = jQuery('#CustomerPassword').val();

			// Validate First Name
			if(!Util_Js.regexp_text.test(first_name.toLowerCase().trim()) || first_name.trim() === ''){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Nombre es un campo requerido y solo debe contener letras',
					progress_bar: true,
					icon: 'error',
				});
				jQuery('#FirstName').focus().select();
				return false;
			}

			// Validate Last Name
			if(!Util_Js.regexp_text.test(last_name.toLowerCase().trim()) || last_name.trim() === ''){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Apellidos es un campo requerido y solo debe contener letras',
					progress_bar: true,
					icon: 'error',
				});
				jQuery('#LastName').focus().select();
				return false;
			}

			// Validate Email
			if(!Util_Js.regexp_email.test(email.toLowerCase())){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Email no tiene un formato válido',
					progress_bar: true,
					icon: 'error',
				});
				jQuery('#CustomerEmail').focus().select();
				return false;
			}
			jQuery('.loader-cart-page').addClass('show');

			// Validate Pass
			/*if(!Util_Js.regexp_st_pass.test(pass) || pass.trim() === ''){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'El password parece no ser muy seguro',
					progress_bar: true,
					icon: 'error',
				});
				return false;
			}*/

			let form_data = new FormData(this);

			Util_Js._requestForm({
				url: url,
				data: form_data
			}).then(function(json){
				//console.debug(json);
				if(json.success){
					Alert_Js.auto_close({
						title: json.title,
						text: json.msg,
						icon: 'success',
					});

					setTimeout(function(){
						window.location.href = '/';
					},2000);
					jQuery('.loader-cart-page').removeClass('show');

				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						icon: 'error',
					});
					jQuery('.loader-cart-page').removeClass('show');
				}
			});
		});
	},

	on_submit_recovery_form: function(e){
		const self = this;
		jQuery('#validate-btn').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();

			let url = 'account/forgot';
			let email = jQuery('#CustomerEmail').val();

			// Validate Email
			if(!Util_Js.regexp_email.test(email.toLowerCase())){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Email no tiene un formato válido',
					icon: 'error',
				});
				jQuery('#CustomerEmail').focus().select();
				return false;
			}

			jQuery('.loader-cart-page').addClass('show');
			let form_data = new FormData(jQuery('#CustomerLoginForm')[0]);

			Util_Js._requestForm({
				url: url,
				data: form_data
			}).then(function(json){
				//console.debug(json);
				if(json.success){
					jQuery('#validate-btn').addClass('hide');
					Alert_Js.auto_close({
						title: json.title,
						text: json.msg,
						icon: 'success',
						timer: 5500
					}).then(function(){
						setTimeout(function(){
							window.location.href = 'account/login';
							jQuery('.loader-cart-page').removeClass('show');
						}, 1500);
					});
				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						icon: 'error',
					});
					jQuery('.loader-cart-page').removeClass('show');
				}
			});
		});

		jQuery('#recovery-btn').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();

			let url = 'account/reset';
			let pass = jQuery('#CustomerPassword').val();
			let pass_confirm = jQuery('#CustomerPasswordConfirm').val();

			if(pass === '' || pass_confirm === ''){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Ingresa los datos solicitados',
					icon: 'error',
				});
				jQuery('#CustomerPassword').focus().val('');
				jQuery('#CustomerPasswordConfirm').val('');
				return false;
			}

			if(pass !== pass_confirm){
				Alert_Js.auto_close({
					title: 'Formulario',
					text: 'Los datos ingresados no coinciden',
					icon: 'error',
				});
				jQuery('#CustomerPassword').focus().val('');
				jQuery('#CustomerPasswordConfirm').val('');
				return false;
			}

			jQuery('.loader-cart-page').addClass('show');
			let form_data = new FormData(jQuery('#CustomerLoginForm')[0]);

			Util_Js._requestForm({
				url: url,
				data: form_data
			}).then(function(json){
				//console.debug(json);
				if(json.success){

					Alert_Js.auto_close({
						title: json.title,
						text: json.msg,
						icon: 'success',
						timer: 5500
					}).then(function(){
						setTimeout(function(){
							window.location.href = 'account/login';
							jQuery('.loader-cart-page').removeClass('show');
						}, 1500);
					});
				}else{
					Alert_Js.auto_close({
						title: json.title,
						text: json.err,
						icon: 'error',
					});
					jQuery('.loader-cart-page').removeClass('show');
				}
			});
		});
	},

	init: function(){
		const current_url = window.location.href

		if(current_url.includes('login'))
			this.on_submit_login_form();

		if(current_url.includes('register'))
			this.on_submit_register_form();

		if(current_url.includes('forgot') || current_url.includes('reset'))
			this.on_submit_recovery_form();
	},

};

jQuery(document).ready(function(){
	Account_Js.init();
});
