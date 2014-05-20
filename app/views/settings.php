<div class="panel panel-default">
	<div class="panel-heading">Settings</div>
	<div class="panel-body">
		<form role="form"  name="formSettings" ng-submit="saveSettings(settings)">
			<div class="alert alert-danger" ng-show="formSettings.$invalid">
				<span ng-show="formSettings.$error.required">Required elements</span>
				<span ng-show="formSettings.$error.invalid">Invalid elements</span>
			</div>

			<div class="form-group" ng-repeat="setting in settings" ng-class="{'has-error':innerForm.theInput.$error.required}">
				<ng-form name="innerForm">
					<label for="{{setting.name}}">{{setting.label}}</label>						
					<input ng-required="{{setting.required}}" type="{{setting.type}}" class="form-control" ng-model="setting.value" name="theInput" />

					<div ng-show="innerForm.theInput.$dirty && formSettings.theInput.$invalid">
						<span class="error" ng-show="innerForm.theInput.$error.required">The field is required.</span>
					</div>
				</ng-form>																											
			</div>

			<button type="submit" ng-disabled="formSettings.$invalid" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>



<div class="panel panel-warning">
	<div class="panel-heading">Referrals</div>
	<div class="panel-body">
		<p>It will update all your entries referrals types</p>
		<button type="submit" class="btn btn-warning" ng-click="updateReferrals()">{{formFields.updateReferralLabel}}</button>
	</div>
</div>