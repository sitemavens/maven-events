<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>

<div ng-controller="EventCtrl">	

	<input type="hidden" name="mvn[event][id]" ng-value="event.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal"  >
				<div class="form-group"  >
					<label for="input-text"  >Registration Start:</label>		
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
							<label for="input-text"  >Name:</label>
							<input  type="text" ng-value="variation.name"  />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" ng-repeat="option in variation.options" >
							<label for="input-text"  >Name:</label>
							<input  type="text" ng-model="option.name"  />
							<button type="button" ng-click="deleteOption(variation,option)" class="btn btn-danger">Delete</button>
						</div>
						
					</div>
				</div>
				
				<select ng-model="selectedVariation" ng-options="variation.name for variation in variations"></select>
				<select ng-model="selectedVariation.optionId" ng-options="option.name for option in selectedVariation.options"></select>
				
				<button type="button" ng-click="addPrice(selectedPrice)" class="btn btn-primary">{{labels.addButton}}</button>
				<button type="button" ng-click="delete(selectedPrice)" ng-show="selectedPrice.id" class="btn btn-danger">Delete</button>
				<button type="button" ng-click="cancel()" ng-show="selectedPrice.id" class="btn btn-default">Cancel</button>
				<ul>
					<li ng-repeat="item in prices" ng-click="showPrice(item)">{{item.name}} - {{item.price}}</li>
				</ul>
			</form>
			
		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
