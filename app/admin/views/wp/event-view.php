<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>

<div ng-controller="EventCtrl">	

	<input type="hidden" name="mvn[event][id]" ng-value="event.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal" style="margin:15px 0;">
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Price:</label>
					<div class="col-sm-2">
						<input class="form-control" type="input" ng-model="event.price" name="mvn[event][price]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Registration Start:</label>
					<div class="col-sm-2">
						<input class="form-control" required type="date" ng-model="event.registrationStartDate" name="mvn[event][registrationStartDate]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="input-time" class="col-sm-2 control-label">Registration End:</label>		
					<div class="col-sm-2">
						<input class="form-control" required ng-model="event.registrationEndDate" type="date" name="mvn[event][registrationEndDate]" />
					</div>
				</div>
				<div class="form-group"  >
					<label for="input-time" class="col-sm-2 control-label">Event Start:</label>
					<div class="col-sm-2">
						<input class="form-control" required ng-model="event.eventStartDate" type="date" name="mvn[event][eventStartDate]" />
					</div>
				</div>

				<div class="form-group"  >
					<label for="input-time" class="col-sm-2 control-label">Event End:</label>
					<div class="col-sm-2">
						<input class="form-control" required ng-model="event.eventEndDate" type="date" name="mvn[event][eventEndDate]" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-2">
						<div class="checkbox"  >
							<label >Allow group registration:
							<input class="form-control" type="checkbox" value="1"  ng-model="event.allowGroupRegistration"   name="mvn[event][allowGroupRegistration]" />
							</label>
						</div>
					</div>
				</div>
				<div class="form-group"  >
					<label for="input-time" class="col-sm-2 control-label">Max group registrants:</label>
					<div class="col-sm-2">
						<input class="form-control" type="text"  ng-model="event.maxGroupRegistrants"   name="mvn[event][maxGroupRegistrants]" />
					</div>
				</div>
				<div class="form-group"  >
					<label for="input-time" class="col-sm-2 control-label">Attendee Limit:</label>
					<div class="col-sm-2">
						<input class="form-control" type="text" ng-model="event.attendeeLimit"  name="mvn[event][attendeeLimit]" />
					</div>
				</div>
				<div class="form-group">
					<label for="input-time" class="col-sm-2 control-label">Closed:</label>
					<div class="col-sm-2">
						<input class="form-control" disabled="disabled" type="checkbox" value="1" ng-model="event.closed"  name="mvn[event][closed]" />
					</div>
				</div>
			</div>
		</tab>
		<tab heading="Variations" ng-controller="VariationsCtrl">
			<div style="margin:15px 0;">
			<button type="button" ng-click="addVariation()"  class="btn btn-default" style="margin-bottom:15px;">Add Variation</button>

			<form name="priceForm" >
				<div class="row form-horizontal" ng-repeat="variation in variations">
					<div class="col-md-4">
						<div class="form-group"  >
							<label for="" class="col-sm-2 control-label">Name:</label>
							<div class="col-sm-10">
								<div class="col-sm-8">
									<input class="form-control" type="text" ng-value="variation.name"  />
								</div>
								<button type="button" ng-click="deleteVariation(variation)" class="btn btn-danger">Delete</button>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" ng-repeat="option in variation.options" >
							<label for="" class="col-sm-2 control-label">Name:</label>
							<div class="col-sm-10">
								<div class="col-sm-8">
									<input class="form-control" type="text" ng-model="option.name"  />
								</div>
								<button type="button" ng-click="deleteOption(variation, option)" class="btn btn-danger">Delete</button>
							</div>
						</div>

					</div>
				</div>

				<div class='row form-group' ng-repeat="variation in variations">
					<div class="col-md-4">
						<label for="" class="col-sm-2 control-label">{{variation.name}}</label>
						<div class="col-sm-10">
							<select class="form-control" ng-model="selectedVariation.selectedOption" ng-options="option.name for option in variation.options"></select>
						</div>
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
			</div>
			</form>

		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
