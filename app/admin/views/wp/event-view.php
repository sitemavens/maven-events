<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>

<div ng-controller="EventCtrl">	

	<input type="hidden" name="mvn[event][id]" ng-value="event.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal"  >
				<div class="form-group"  >
					<label for=""  >Price:</label>		
					<input  type="input" ng-model="event.price" name="mvn[event][price]"  />
				</div>
				<div class="form-group"  >
					<label for=""  >Registration Start:</label>		
					<input required type="date" ng-model="event.registrationStartDate" name="mvn[event][registrationStartDate]"  />
				</div>
				<div class="form-group"  >
					<label for="input-time">Registration End:</label>		
					<input required ng-model="event.registrationEndDate" type="date" name="mvn[event][registrationEndDate]" />
				</div>
				<div class="form-group"  >
					<label for="input-time">Event Start:</label>
					<input required ng-model="event.eventStartDate" type="date" name="mvn[event][eventStartDate]" />
				</div>

				<div class="form-group"  >
					<label for="input-time">Event End:</label>
					<input required ng-model="event.eventEndDate" type="date" name="mvn[event][eventEndDate]" />
				</div>
				<div class="checkbox"  >
					<label >Allow group registration:
						<input type="checkbox" value="1"  ng-model="event.allowGroupRegistration"   name="mvn[event][allowGroupRegistration]" />
					</label>
				</div>
				<div class="form-group"  >
					<label for="input-time">Max group registrants:</label>
					<input type="text"  ng-model="event.maxGroupRegistrants"   name="mvn[event][maxGroupRegistrants]" />
				</div>
				<div class="form-group"  >
					<label for="input-time">Attendee Limit:</label>
					<input type="text" ng-model="event.attendeeLimit"  name="mvn[event][attendeeLimit]" />
				</div>
				<div class="form-group">
					<label for="input-time">Closed:</label>
					<input disabled="disabled" type="checkbox" value="1" ng-model="event.closed"  name="mvn[event][closed]" />
				</div>
			</div>
		</tab>
		<tab heading="Variations" ng-controller="VariationsCtrl">
			<button type="button" ng-click="addVariation()"  class="btn btn-default">Add Variation</button>

			<form name="priceForm" >
				<div class="row" ng-repeat="variation in variations">
					<div class="col-md-4">
						<div class="form-group"  >
							<label for=""  >Name:</label>
							<input  type="text" ng-value="variation.name"  />
							<button type="button" ng-click="deleteVariation(variation)" class="btn btn-danger btn-xs">Delete</button>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" ng-repeat="option in variation.options" >
							<label for=""  >Name:</label>
							<input  type="text" ng-model="option.name"  />
							<button type="button" ng-click="deleteOption(variation, option)" class="btn btn-danger btn-xs">Delete</button>
						</div>

					</div>
				</div>

				<div class='row' ng-repeat="variation in variations">
					<div class="col-md-4">
						{{variation.name}}
						<select ng-model="selectedVariation.selectedOption" ng-options="option.name for option in variation.options"></select>
					</div>
				</div>

				<button type="button" ng-click="addCombination(selectedVariation)" ng-disabled="selectedVariation.id == '-1' || !selectedVariation.selectedOption" class="btn btn-primary">Add variation</button>
				<button type="button" ng-click="addAll()" class="btn btn-primary"  >Add all</button>
				<div class="row" ng-repeat="variationCombination in variationsCombinations"  >
					<div class="col-md-4">
						<label for=""  >{{variationCombination.variation.name}},{{variationCombination.option.name}} </label>
						<div class="form-group"  >
							<label for=""  >Price</label>
							<input  type="text" ng-value="variationCombination.price"  />
						</div>
					</div>
				</div>

			</form>

		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
