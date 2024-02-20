<!--Page Title-->
<div class="page section-header text-center">
	<div class="page-title">
		<div class="wrapper"><h1 class="page-width">{translate('RECOVERY_TITLE', $module)}</h1></div>
	</div>
</div>
<!--End Page Title-->

{if empty($err)}
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
				<p class="h3 mb-3">{translate('RESET_TEXT', $module)}</p>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
				<div class="mb-4">
					<form method="post" action="#" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">
						<input type="hidden" name="code" id="code" value="{$code}">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label for="CustomerPassword" class="required">{translate('ENTRY_PASS', $module)} <span class="badge badge-opacity-warning"></span></label>
									<input type="password" value="" name="password" placeholder="{translate('ENTRY_PASS', $module)}" id="CustomerPassword" class="" data-uitype="password" data-rules="require">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label for="CustomerPasswordConfirm" class="required">{translate('ENTRY_PASS_CONFIRM', $module)} <span class="badge badge-opacity-warning"></span></label>
									<input type="password" value="" name="password_confirm" placeholder="{translate('ENTRY_PASS_CONFIRM', $module)}" id="CustomerPasswordConfirm" class="" data-uitype="password" data-rules="require">
								</div>
							</div>
						</div>
						<div id="recovery-btn" class="row">
							<div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
								<input type="submit" class="btn mb-3" value="{translate('RECOVERY_BTN', $module)}">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{else}
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
				<p class="h1 mb-3">{$err}</p>
				<a href="/" title="{translate('BREADCRUMBS_HOME', 'main')}">{translate('BREADCRUMBS_HOME', 'main')}</a>
			</div>
		</div>
	</div>
{/if}

