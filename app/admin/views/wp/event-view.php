<div ng-app="mavenEventsApp">	

	<div class="panel panel-default" ng-controller="EventCtrl">
		<div class="panel-heading">{{event.title}}</div>
		<div class="panel-body">
			<form role="form"  name="formSettings" >

				<button type="submit" ng-disabled="formSettings.$invalid" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>

</div>
 