<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedPriceOperators', $priceOperators ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'DefaultPriceOperator', $defaultPriceOperator ); ?>

<div ng-controller="EventCtrl">	

	<input type="hidden" name="mvn[event][id]" ng-value="event.id" />

	<tabset>
		<tab heading="General">
			<div class="form"  >
				<div class="form-group"  >
					<label for=""  >Price:</label>		
					<input class="form-control"  type="input" ng-model="event.price" name="mvn[event][price]"  />
				</div>
				<div class="form-group"  >
					<label for=""  >Registration Start:</label>		
					<input class="form-control" required type="date" ng-model="event.registrationStartDate" name="mvn[event][registrationStartDate]"  />
				</div>
				<div class="form-group"  >
					<label for="input-time">Registration End:</label>		
					<input class="form-control" required ng-model="event.registrationEndDate" type="date" name="mvn[event][registrationEndDate]" />
				</div>
				<div class="form-group"  >
					<label for="input-time">Event Start:</label>
					<input class="form-control" required ng-model="event.eventStartDate" type="date" name="mvn[event][eventStartDate]" />
				</div>

				<div class="form-group"  >
					<label for="input-time">Event End:</label>
					<input class="form-control" required ng-model="event.eventEndDate" type="date" name="mvn[event][eventEndDate]" />
				</div>
				<div class="checkbox"  >
					<label >Allow group registration:
						<input class="form-control" type="checkbox" value="1"  ng-model="event.allowGroupRegistration"   name="mvn[event][allowGroupRegistration]" />
					</label>
				</div>
				<div class="form-group"  >
					<label for="input-time">Max group registrants:</label>
					<input class="form-control" type="text"  ng-model="event.maxGroupRegistrants"   name="mvn[event][maxGroupRegistrants]" />
				</div>
				<div class="form-group"  >
					<label for="input-time">Attendee Limit:</label>
					<input class="form-control" type="text" ng-model="event.attendeeLimit"  name="mvn[event][attendeeLimit]" />
				</div>
				<div class="form-group">
					<label for="input-time">Closed:</label>
					<input class="form-control" disabled="disabled" type="checkbox" value="1" ng-model="event.closed"  name="mvn[event][closed]" />
				</div>
			</div>
		</tab>
		<tab heading="Variations" ng-controller="VariationsCtrl">
			<button type="button" ng-click="addVariation()"  class="btn btn-default">Add Variation</button>

			<div class="form-horizontal" >
				<div class="panel panel-default" ng-repeat="variation in variations">
					<div class="panel-body">
						<div class="col-md-4">
							<div class="form-group"  >
								<label for=""  >Name:</label>
								<input type="hidden" name="mvn[event][variations][{{$index}}][id]" ng-value="variation.id" />
								<input type="hidden" name="mvn[event][variations][{{$index}}][thingId]" ng-value="eventId" />
								<input  type="text" ng-model="variation.name" name="mvn[event][variations][{{$index}}][name]" placeholder="Variation"/>
								<button type="button" ng-click="deleteVariation(variation)" class="btn btn-danger btn-xs">Delete</button>
								<button type="button" ng-click="addOption($index)" class="btn btn-primary btn-xs">Add Option</button>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group" ng-repeat="option in variation.options" >
								<label for=""  >Name:</label>
								<input type="hidden"  name="mvn[event][variations][{{$parent.$index}}][options][{{$index}}][id]" ng-value="option.id">
								<input type="hidden"  name="mvn[event][variations][{{$parent.$index}}][options][{{$index}}][variationId]" ng-value="variation.id">
								<input  type="text" name="mvn[event][variations][{{$parent.$index}}][options][{{$index}}][name]" ng-model="option.name"  placeholder="Option" />
								<button type="button" ng-click="deleteOption(variation, option)" class="btn btn-danger btn-xs">Delete</button>
							</div>

						</div>
					</div>
				</div>
				{{variations}}
				<div class="panel panel-default" >
					<div class="panel-body">
						<div class='row'>
							<div class="col-md-4"  ng-repeat="variation in variations">
								{{variation.name}}
								<select ng-model="selectedCombination[variation.name]" ng-options="option.name for option in variation.options"></select>
							</div>
						</div>
						<div class='row pull-right'>
							<button type="button" ng-click="addCombination(selectedCombination)" ng-disabled="combinationsSelected()" class="btn btn-primary">Add variation</button>
							<button type="button" ng-click="addAll()" class="btn btn-primary"  >Add all</button>
						</div>
						{{selectedCombination}}
					</div>
				</div>
				{{variationsCombinations}}
				<div class="row" >
					<div class="col-md-6" ng-repeat="variationCombination in variationsCombinations track by variationCombination.id">
						<div class="panel panel-default" >
							<div class="panel-body">
								<h4 class="options"><span class="label label-default" ng-repeat="option in variationCombination.options">{{option.option.name}}</span></h4>
								<div class="form-group"  >
									<label for=""  >Price</label>
									<input type="hidden" ng-repeat="option in variationCombination.options" name="mvn[event][combinations][{{$parent.$index}}][options][{{option.option.variationId}}][variationId]" ng-value="option.option.variationId"/>
									<input type="hidden" ng-repeat="option in variationCombination.options" name="mvn[event][combinations][{{$parent.$index}}][options][{{option.option.variationId}}][id]" ng-value="option.option.id"/>
									<select name="mvn[event][combinations][{{$index}}][priceOperator]" ng-model="variationCombination.priceOperator" ng-options="key as  value for (key, value) in priceOperators"></select>
									<input name="mvn[event][combinations][{{$index}}][price]"  type="text" ng-model="variationCombination.price" />
									<button type="button" ng-click="deleteCombination($index)" class="btn btn-danger btn-xs">Remove</button>									
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
