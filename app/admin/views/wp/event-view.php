<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>

<div ng-controller="EventCtrl">	
 
		<input type="hidden" name="mvn[event][id]" ng-value="event.id" />
		
		<div class="alert alert-danger" ng-show="post.$invalid">
				<span ng-show="post.$error.required">Required elements</span>
				<span ng-show="post.$error.invalid">Invalid elements</span>
			</div>
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="input-text"  >Registration Start:</label>
					</th>
					<td>
						<input required type="date" ng-model="event.registrationStartDate" name="mvn[event][registrationStartDate]"  /><br />
					</td>
				</tr>
				     
				<tr>
					<th>
						<label for="input-time">Registration End:</label>
					</th>
					<td>
						<input required ng-model="event.registrationEndDate" type="date" name="mvn[event][registrationEndDate]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Event Start:</label>
					</th>
					<td>
						<input required ng-model="event.eventStartDate" type="date" name="mvn[event][eventStartDate]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Event End:</label>
					</th>
					<td>
						<input required ng-model="event.eventEndDate" type="date" name="mvn[event][eventEndDate]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Allow group registration:</label>
					</th>
					<td>
						<input type="checkbox" value="1"  ng-model="event.allowGroupRegistration"   name="mvn[event][allowGroupRegistration]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Max group registrants:</label>
					</th>
					<td>
						<input type="text"  ng-model="event.maxGroupRegistrants"   name="mvn[event][maxGroupRegistrants]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Attendee Limit:</label>
					</th>
					<td>
						<input type="text" ng-model="event.attendeeLimit"  name="mvn[event][attendeeLimit]" />
					</td>
				</tr>
				
				<tr>
					<th>
						<label for="input-time">Closed:</label>
					</th>
					<td>
						<input disabled="disabled" type="checkbox" value="1" ng-model="event.closed"  name="mvn[event][closed]" />
					</td>
				</tr>
			</tbody>
		</table>
	

</div>
 