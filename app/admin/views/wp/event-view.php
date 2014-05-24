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
		<tab heading="Prices">
			
			<form name="priceForm">
				<div class="form-group"  >
					<label for="input-text"  >Name:</label>		
					<input  type="text" ng-model="" name=""  />
				</div>
				
				<div class="form-group"  >
					<label for="input-text"  >Price:</label>		
					<input  type="number" ng-model="" name=""  />
				</div>
				<button type="submit"  class="btn btn-primary">Add</button>
				<ul>
					<li ng-repeat="price in prices">{{name}} - {{price}}</li>
				</ul>
			</form>
			
		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
