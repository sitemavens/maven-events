<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedVenue', $venue ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<div ng-controller="VenueCtrl">	
	<input type="hidden" name="mvn[venue][id]" ng-value="venue.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal" style="margin:15px 0;">
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Phone:</label>
					<div class="col-sm-6">
							<input class="form-control" type="text" ng-model="venue.phone" name="mvn[venue][phone]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Address 1</label>
					<div class="col-sm-6 input-append">
						<div class="input-group">
							<input class="form-control" type="text" ng-model="venue.address" name="mvn[venue][address]"  />
							<span class="input-group-btn">
								<button type="button" class="btn btn-primary search-map" ng-click="refreshAddress()">
									<span class="glyphicon glyphicon-search">	
									</span>
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Address 2</label>
					<div class="col-sm-6">
						<input class="form-control" type="text" ng-model="venue.address2" name="mvn[venue][address2]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">City</label>
					<div class="col-sm-6">
						<input class="form-control" type="text" ng-model="venue.city" name="mvn[venue][city]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">State</label>
					<div class="col-sm-6">
						<input class="form-control" type="text" ng-model="venue.state" name="mvn[venue][state]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Zip</label>
					<div class="col-sm-6">
						<input class="form-control" type="text" ng-model="venue.zip" name="mvn[venue][zip]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Country</label>
					<div class="col-sm-6">
						<select class="form-control" ng-model="venue.country"
								ng-options="countryI as country.name for (countryI, country) in countries" id="addressSelect" />
						<input type="hidden" value="{{venue.country}}" name="mvn[venue][country]"/>
					</div>
					<div class="col-sm-4 venue-map">
						<google-map center="map.center" zoom="map.zoom"></google-map>
					</div>
				</div>	
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Contact</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" ng-model="venue.contact" name="mvn[venue][contact]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Twitter</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" ng-model="venue.twitter" name="mvn[venue][twitter]"  />
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Website</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" ng-model="venue.website" name="mvn[venue][website]"  />
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
