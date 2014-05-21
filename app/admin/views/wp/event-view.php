<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedEvent', $event ); ?>

<div ng-app="mavenEventsApp">	

	<form ng-controller="EventCtrl">
		
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="input-text">{{otro.title}} - Registration Start:</label>
					</th>
					<td>
						<input type="text" name="registrationStart"  /><br />
					</td>
				</tr>
				     
				<tr>
					<th>
						<label for="input-time">Registration End</label>
					</th>
					<td>
						<input name="registrationEnd" type="text" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	

</div>
 