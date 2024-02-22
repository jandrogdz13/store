<!--Page Title-->
{*<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper"><h1 class="page-width">{translate('LOGIN_TITLE', $module)}</h1></div>
	</div>
</div>*}
<!--End Page Title-->
<div class="row back-to-store d-flex justify-content-center align-items-center text-center d-block d-md-none d-lg-none">
	<a class="p-3 w-100" href="/"><img src="{$vars.images}logo_mobel.png" alt="Mobel Inn" width="100px" /></a>
</div>

<div class="container-fluid p-0">
	<div class="account-container">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 p-0">
			<img src="{$vars.images}Furniture4.jpeg" id="img-login" title="Log In" width="100%" >
		</div>
		<div id="form-login" class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex flex-column align-items-center bg-white px-3">
			<div class="row d-none d-md-block d-lg-block">
				<div class="logo">
					<a href="/">
						<img src="{$vars.images}logo_mobel_letras.png" alt="Mobel Inn" />
					</a>
				</div>
			</div>
			<div class="row mt-4 p-3">
				<div class="col-12">
					<p class="h3 mb-3 text-center">{translate('LOGIN_ALT', $module)}</p>
				</div>
			</div>
			<form method="post" action="#" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">
				<div class="row p-3">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label for="CustomerEmail">{translate('ENTRY_EMAIL', $module)}</label>
							<input type="email" name="email" placeholder="{translate('ENTRY_EMAIL', $module)}" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="" autocomplete="off">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label for="CustomerPassword">{translate('ENTRY_PASS', $module)}</label>
							<input type="password" value="" name="password" placeholder="{translate('ENTRY_PASS', $module)}" id="CustomerPassword" class="">
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
						<button type="submit" class="btn mb-3">{translate('LOGIN_BTN', $module)} <i class="ml-2 bi bi-arrow-right"></i></button>
						<p class="mb-4">
							<span id="customer_register_link">{translate('CREATE_ACCOUNT', $module)}</span><br>
							<a href="account/forgot" id="RecoverPassword">{translate('FORGOT_PASS', $module)}</a>
						</p>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
