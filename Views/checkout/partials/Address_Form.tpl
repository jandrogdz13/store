<div class="container pt-2 h-100 p-0">
	<div class="row h-100">
		<div class="col-12">
			<form method="post" action="#" id="AddressForm" accept-charset="UTF-8" class="contact-form">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label for="name" class="required">{translate('ENTRY_NAME', $module)}</label>
							<input type="text" name="name" placeholder="{translate('ENTRY_NAME', $module)}" value="{($edit|boolval)? $address.name: ''}" id="name" autofocus="" data-uitype="text" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group">
							<label for="street" class="required">{translate('ENTRY_STREET', $module)}</label>
							<input type="text" name="street" placeholder="{translate('ENTRY_STREET', $module)}" value="{($edit|boolval)? $address.street: ''}" id="street" autofocus="" data-uitype="text" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<label for="outdoor_num" class="required">{translate('ENTRY_OUT', $module)}</label>
							<input type="text" name="outdoor_num" placeholder="{translate('ENTRY_OUT', $module)}" value="{($edit|boolval)? $address.outdoor_num: ''}" id="outdoor_num" class="" data-uitype="text-numeric" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<label for="interior_num">{translate('ENTRY_INT', $module)}</label>
							<input type="text" name="interior_num" placeholder="{translate('ENTRY_INT', $module)}" value="{($edit|boolval)? $address.interior_num: ''}" id="interior_num" data-uitype="text-numeric" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="form-group" >
							<label for="suburb" class="required">{translate('ENTRY_SUBURB', $module)}</label>
							<input type="text" name="suburb" placeholder="{translate('ENTRY_SUBURB', $module)}" value="{($edit|boolval)? $address.suburb: ''}" id="suburb" class="" data-uitype="text-numric" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group">
							<label for="country" class="required">{translate('ENTRY_COUNTRY', $module)}</label>
							<select id="country" name="country" data-value="{($edit|boolval)? $address.country: ''}">
								<option value="0" {($edit|boolval)? '': 'selected'}>Selecciona país</option>
								{foreach from=['142' => 'México', '277' => 'Estados Unidos'] item=country key=code}
									<option value="{$code}" {($edit|boolval And $country eq $address.country)? 'selected': ''}>{$country|upper}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group" >
							<label for="state" class="required">{translate('ENTRY_STATE', $module)}</label>
							<select id="state" name="state" data-value="{($edit|boolval)? $address.state: ''}">
								<option value="0" selected>Selecciona estado</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group" >
							<label for="city" class="required">{translate('ENTRY_CITY', $module)}</label>
							<select id="city" name="city" data-value="{($edit|boolval)? $address.city: ''}">
								<option value="0" selected>Selecciona ciudad</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6">
						<div class="form-group" class="required">
							<label for="postcode" class="required">{translate('ENTRY_POSTCODE', $module)}</label>
							<input type="text" name="postcode" placeholder="{translate('ENTRY_POSTCODE', $module)}" value="{($edit|boolval)? $address.postcode: ''}" id="postcode" class="" data-uitype="numeric" data-rules="require">
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 pb-5">
						<div class="form-group">
							<label for="references">{translate('ENTRY_REF', $module)}</label>
							<textarea name="references" id="references" placeholder="{translate('ENTRY_REF', $module)}" rows="4">{($edit|boolval)? $address.references: ''}</textarea>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="section-buttons-sidebar mt-2">
	<div class="button-action">
		<input type="button" class="btn btn-cancel btn-cancel-addr" value="{translate('CANCEL_BTN', 'main')}">
	</div>
	<div class="button-action">
		<input type="button" class="btn btn-secondary btn-save-addr" value="{translate('REGISTER_BTN', $module)}">
	</div>
</div>
