<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedVenue', $venue ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<div ng-controller="VenueCtrl">	
	<input type="hidden" name="mvn[venue][id]" ng-value="venue.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal" style="margin:15px 0;">
				<ng-form name="venueForm">
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Phone*</label>
						<div class="col-sm-6">
							<input required class="form-control" type="text" ng-model="venue.phone" name="mvn[venue][phone]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][phone]'].$error.required">The venue's phone is required</p>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Address 1*</label>
						<div class="col-sm-6 input-append">
							<div ng-class="{'input-group': venueForm['mvn[venue][address]'] && venueForm['mvn[venue][city]'] && venueForm['mvn[venue][state]'].$valid && venueForm['mvn[venue][country]'].$valid }">
								<input class="form-control" required type="text" ng-model="venue.address" ng-change="checkAddressIsComplete()" name="mvn[venue][address]"  />
								<p class="help-block" ng-if="venueForm['mvn[venue][address]'].$error.required">The venue's Address is required</p>
								<span class="input-group-btn">
									<button type="button" class="btn btn-primary search-map" 
											ng-if="venueForm['mvn[venue][address]'] && venueForm['mvn[venue][city]'] && venueForm['mvn[venue][state]'].$valid && venueForm['mvn[venue][country]'].$valid"
											ng-click="refreshAddress()">
										<span class="glyphicon glyphicon-search">	
										</span>
									</button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Address 2 </label>
						<div class="col-sm-6">
							<input class="form-control" type="text" ng-model="venue.address2" name="mvn[venue][address2]"  />
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">City*</label>
						<div class="col-sm-6">
							<input class="form-control" required type="text" ng-model="venue.city" name="mvn[venue][city]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][city]'].$error.required">The venue's City is required</p>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">State*</label>
						<div class="col-sm-6">
							<input class="form-control" required type="text" ng-model="venue.state" name="mvn[venue][state]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][state]'].$error.required">The venue's State is required</p>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Zip*</label>
						<div class="col-sm-6">
							<input class="form-control" required type="text" ng-model="venue.zip" name="mvn[venue][zip]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][zip]'].$error.required">The venue's Zip is required</p>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Country*</label>
						<div class="col-sm-6">
							<select class="form-control"  required ng-model="venue.country" name='mvn[venue][country]'
									ng-options="countryI as country.name for (countryI, country) in countries" id="addressSelect"></select>
							<p class="help-block" ng-if="venueForm['mvn[venue][country]'].$error.required">The venue's Country is required</p>
						</div>
						<div class="col-sm-4 venue-map">
							<google-map center="map.center" zoom="map.zoom"></google-map>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Contact*</label>
						<div class="col-sm-10">
							<input class="form-control" required type="text" ng-model="venue.contact" name="mvn[venue][contact]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][contact]'].$error.required">The venue's Contact is required</p>
						</div>
					</div>
					<div class="form-group" show-errors>
						<label for="" class="col-sm-2 control-label">Seating Chart*</label>
						<div class="col-sm-10">
							<input class="form-control" required type="text" ng-model="venue.seatingChart" name="mvn[venue][seatingChart]"  />
							<p class="help-block" ng-if="venueForm['mvn[venue][seatingChart]'].$error.required">The venue's Seating Chart is required</p>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Twitter </label>
						<div class="col-sm-10">
							<input class="form-control" type="text" ng-model="venue.twitter" name="mvn[venue][twitter]"  />
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Website </label>
						<div class="col-sm-10">
							<input class="form-control" type="text" ng-model="venue.website" name="mvn[venue][website]"  />
						</div>
					</div>
				</ng-form>
			</div>
		</tab>
	</tabset>
	<!--
		<div class="alert alert-danger" ng-show="post.$invalid">
			<span ng-show="post.$error.required">Required elements</span>
			<span ng-show="post.$error.invalid">Invalid elements</span>
		</div>-->
</div>
