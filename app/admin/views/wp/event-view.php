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
		<tab heading="Prices" ng-controller="PricesCtrl">
			
			<form name="priceForm" >
				<div class="form-group"  >
					<label for="input-text"  >Name:</label>
					<input  type="text" ng-model="selectedPrice.name"  />
				</div>
				
				<div class="form-group"  >
					<label for="input-text"  >Price:</label>
					<input  type="number" ng-model="selectedPrice.price"   />
				</div>
				<button type="button" ng-click="addPrice(selectedPrice)" class="btn btn-primary">{{labels.addButton}}</button>
				<button type="button" ng-click="delete(selectedPrice)" ng-show="selectedPrice.id" class="btn btn-danger">Delete</button>
				<button type="button" ng-click="cancel()" ng-show="selectedPrice.id" class="btn btn-default">Cancel</button>
				<ul>
					<li ng-repeat="item in prices" ng-click="showPrice(item)">{{item.name}} - {{item.price}}</li>
				</ul>
			</form>
			
		</tab>
		<tab heading="<?php esc_attr_e( 'Variations' ) ?>" ng-controller="VariationCtrl">
			
			<form name="variationsForm" >
				<button type="button" ng-click="addVariation(selectedVariation)" class="btn btn-primary"><?php esc_html_e( 'Add Variation') ?></button>
				<div class="form-group"  >
					<button type="button" ng-click="deleteVariation(selectedVariation)" ng-show="selectedVariation.id" class="btn btn-danger"><?php esc_html_e( 'Delete') ?></button>
					<input type="text" ng-model="selectedVariation.name"  />
					<div class="form-group">
						<button type="button" ng-click="addOption(selectedVariation)" class="btn btn-primary"><?php esc_html_e( 'Add Option') ?></button>
						<button type="button" ng-click="deleteOption(selectedVariation)" ng-show="selectedVariation.id" class="btn btn-danger"><?php esc_html_e( 'Delete') ?></button>
						<input  type="text" ng-model="selectedVariation.option.name"   />
					</div>
				</div>
				<div>
					Select - Boton ADD - Boton Generate All
				</div>
				Listado de variaciones agregadas.
				<ul>
					
					<li ng-repeat="item in variations" ng-click="showVariation(item)">
						NOMBRE
						Price
						Sale Price
						Manage Stock
						Stock
						SKU
						IMAGE
						REMOVE
						{{item.name}} - {{item.price}}
					</li>
				</ul>
			</form>
			
		</tab>
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
