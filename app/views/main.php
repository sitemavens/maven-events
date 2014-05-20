<div class="row page-header">
	<div class='col-md-6'>
		<h2>Dashboard</h2>
	</div>
	<div class="col-md-6" style="padding-top:15px;"  ng-controller="DateSelectorCtrl">
		<div class='col-md-4'>
			<div class="input-group">
				<input type="text" class="form-control" datepicker-popup="yyyy/MM/dd" 
				       ng-model="filterService.from" is-open="fromOpened" min="minDate" max="filterService.to" 
				       datepicker-options="dateOptions" close-text="Close" show-button-bar="false" show-weeks="false" ng-blur="selectFrom()" />
				<span class="input-group-btn">
					<button class="btn btn-default" ng-click="openFrom($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				</span>
			</div>
		</div>
		<div class='col-md-4'>

			<div class="input-group">
				<input type="text" class="form-control" datepicker-popup="yyyy/MM/dd" 
				       ng-model="filterService.to" is-open="toOpened" min="filterService.from" max="maxDate" 
				       datepicker-options="dateOptions" close-text="Close" show-button-bar="false" show-weeks="false" ng-blur="selectTo()"/>
				<span class="input-group-btn">
					<button class="btn btn-default" ng-click="openTo($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				</span>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6" ng-controller="FormSelectorCtrl">
		<span ng-click="toggleSelect()"><i class="glyphicon glyphicon-arrow-down" ></i>&nbsp;Forms:</span>
		<strong ng-repeat="form in (filterService.forms| filter:{selected:true})"><< #{{form.id}} {{form.title}} >></strong>
		<div collapse="!showSelect"  ng-repeat="form in filterService.forms">
			<span><input type="checkbox" ng-model="form.selected"/>
				#{{form.id}} {{form.title}}</span>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">Graph</div>
		<div class="panel-body">
			<div class="col-md-6">
				<select ng-model="filterService.stat">		
					<option ng-repeat="stat in stats" ng-disabled="filterService.vsStat == stat" value="{{stat}}">{{stat}}</option>
				</select>
				VS.
				<select ng-model="filterService.vsStat">
					<option ng-repeat="stat in stats" ng-disabled="filterService.stat == stat" value="{{stat}}">{{stat}}</option>
				</select>
				<i class="glyphicon glyphicon-remove-circle" ng-click="removeVsMetric()" ng-show="filterService.vsStat != null"></i>
			</div>
			<div class="col-md-6">
				<div class='full-width alignright'>
					<div class="btn-group">
						<button ng-model="filterService.interval" btn-radio="'H'" type="button" class="btn btn-default">Hour</button>
						<button ng-model="filterService.interval" btn-radio="'D'" type="button" class="btn btn-default">Day</button>
						<button ng-model="filterService.interval" btn-radio="'W'" type="button" class="btn btn-default">Week</button>
						<button ng-model="filterService.interval" btn-radio="'M'" type="button" class="btn btn-default">Month</button>
					</div>
				</div>
			</div>
			<div class='col-md-12'>

				<div google-chart chart="selectedChart" style="height: 400px; width: 100%; {{cssStyle}}">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">Stats</div>
		<div class="panel-body">
			<div class="col-md-4" ng-repeat="chart in charts">
				<strong>#{{chart.title.id}} {{chart.title.form}} - {{chart.title.stat}}</strong>
				<h4>{{chart.title.total}}</h4>
				<div  google-chart chart="chart.chart" style="{{cssStyle}}"></div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">Referrals</div>
		<div class="panel-body">
			<table class="table table-striped">
				<tr>
					<th>#</th>
					<th>Source</th>
					<th>Count <span class="badge">{{referralTotal}}</span></th>
					<th style="width: 50%"></th>
				</tr>
				<tr ng-repeat="referral in referrals|orderBy:'-count'">
					<td><strong>{{$index + 1}}. </strong></td>
					<td>{{referral.referral_type}}</td>
					<td>{{referral.count}}</td>
					<td>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="{{referral.count}}" aria-valuemin="0" aria-valuemax="{{referralTotal}}" style="width: {{ (referral.count / referralTotal) * 100 | number:0}}%;">
								{{ (referral.count / referralTotal) * 100 | number:0}}% 
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
