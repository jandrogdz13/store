<!--Page Title-->
{*<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper"><h1 class="page-width">{translate('REGISTER_TITLE', $module)}</h1></div>
	</div>
</div>*}
<!--End Page Title-->

<div class="container-fluid p-0">
	<div class="row">
		<div class="col-12 col-sm-12">
			<div>
				<div class="row account-container">
					<div class="row back-to-store d-flex justify-content-center align-items-center text-center d-block d-md-none d-lg-none">
						<a class="p-3 w-100" href="/"><img src="{$vars.images}logo_mobel.png" alt="Mobel Inn" width="100px" /></a>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6 p-0">
						<img src="{$vars.images}Furniture6.jpeg" id="img-login" title="Log In" width="100%" >
					</div>
					<div id="form-login" class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex flex-column align-items-center bg-white px-5">
						<div class="row d-none d-md-block d-lg-block">
							<div class="logo">
								<a href="/">
									<img src="{$vars.images}logo_mobel_letras.png" alt="Mobel Inn" />
								</a>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-12">
								<p class="h3 mb-3 text-center">{translate('REGISTER_ALT', $module)}</p>
							</div>
						</div>
						<form method="post" action="#" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">
							<div class="row p-3">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="FirstName" class="required">{translate('ENTRY_FIRST_NAME', $module)}</label>
										<input type="text" name="first_name" placeholder="{translate('ENTRY_FIRST_NAME', $module)}" id="FirstName" autofocus="" data-uitype="text" data-rules="require">
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="LastName" class="required">{translate('ENTRY_LAST_NAME', $module)}</label>
										<input type="text" name="last_name" placeholder="{translate('ENTRY_LAST_NAME', $module)}" id="LastName" data-uitype="text" data-rules="require">
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="Phone" class="required">{translate('ENTRY_PHONE', $module)}</label>
										<input type="text" name="phone" placeholder="{translate('ENTRY_PHONE', $module)}" id="Phone" data-uitype="numeric" data-rules="require">
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="CustomerEmail" class="required">{translate('ENTRY_EMAIL', $module)}</label>
										<input type="email" name="email" placeholder="{translate('ENTRY_EMAIL', $module)}" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="" data-uitype="email" data-rules="require">
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="CustomerPassword" class="required">{translate('ENTRY_PASS', $module)} <span class="badge badge-opacity-warning"></span></label>
										<input type="password" value="" name="password" placeholder="{translate('ENTRY_PASS', $module)}" id="CustomerPassword" class="" data-uitype="password" data-rules="require">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
									<button type="submit" class="btn mb-3">{translate('REGISTER_BTN', $module)} <i class="ml-2 bi bi-arrow-right"></i></button>
								</div>
							</div>
							<div class="row mb-5">
								<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
									<a href="account/login" class=" mb-3"> <i class="mr-2 bi bi-arrow-left"></i>{translate('LOGIN_BTN', $module)}</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


{*<div class="container">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
			<div class="row mb-5 d-flex justify-content-center align-items-center text-center">
				<img class="icon-img" src="{$vars.images}contact-form.png" alt="Registro" width="100px">
			</div>
			<div class="row">
				<div class="col-12">
					<p class="h3 mb-3 text-center">{translate('REGISTER_ALT', $module)}</p>
				</div>
			</div>
			<div class="mb-4">
				<form method="post" action="#" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="FirstName" class="required">{translate('ENTRY_FIRST_NAME', $module)}</label>
								<input type="text" name="first_name" placeholder="{translate('ENTRY_FIRST_NAME', $module)}" id="FirstName" autofocus="" data-uitype="text" data-rules="require">
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="LastName" class="required">{translate('ENTRY_LAST_NAME', $module)}</label>
								<input type="text" name="last_name" placeholder="{translate('ENTRY_LAST_NAME', $module)}" id="LastName" data-uitype="text" data-rules="require">
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="Phone" class="required">{translate('ENTRY_PHONE', $module)}</label>
								<input type="text" name="phone" placeholder="{translate('ENTRY_PHONE', $module)}" id="Phone" data-uitype="numeric" data-rules="require">
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="CustomerEmail" class="required">{translate('ENTRY_EMAIL', $module)}</label>
								<input type="email" name="email" placeholder="{translate('ENTRY_EMAIL', $module)}" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="" data-uitype="email" data-rules="require">
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="CustomerPassword" class="required">{translate('ENTRY_PASS', $module)} <span class="badge badge-opacity-warning"></span></label>
								<input type="password" value="" name="password" placeholder="{translate('ENTRY_PASS', $module)}" id="CustomerPassword" class="" data-uitype="password" data-rules="require">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
							<button type="submit" class="btn mb-3">{translate('REGISTER_BTN', $module)} <i class="ml-2 bi bi-arrow-right"></i></button>
						</div>
					</div>
					<div class="row">
						<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
							<a href="account/login" class=" mb-3"> <i class="mr-2 bi bi-arrow-left"></i>{translate('LOGIN_BTN', $module)}</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>*}
