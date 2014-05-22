<div ng-app="mavenEventsListApp">	

	<div class="wrap"  ng-controller="EventListCtrl">
		<h2>Events <a href="http://local.maven.com/wp-admin/post-new.php?post_type=mvn_event" class="add-new-h2">New Event</a></h2>


		<ul class="subsubsub">
			<li class="all">All <span class="count">({{event_count}})</span>|</li>
			<li class="draft">Draft <span class="count">({{draft_count}})</span></li>
		</ul>
		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Search events:</label>
			<input id="post-search-input" name="s" type="search" ng-model="filter.search">
			<input type="button" name="" id="search-submit" class="button" value="Search events" ng-click="doSearch()">
		</p>

		<div class="tablenav top">

			<div class="alignleft actions bulkactions">
				<select name="action">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="edit" class="hide-if-no-js">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input name="" id="doaction" class="button action" value="Apply" type="submit">
			</div>
			<div class="alignleft actions">
				<select name="m">
					<option selected="selected" value="0">All dates</option>
					<option value="201405">May 2014</option>
				</select>
				<input name="" id="post-query-submit" class="button" value="Filter" type="submit">		</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">1 item</span>
				<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event">«</a>
					<a class="prev-page disabled" title="Go to the previous page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">‹</a>
					<span class="paging-input"><input class="current-page" title="Current page" name="paged" value="1" size="1" type="text"> of <span class="total-pages">1</span></span>
					<a class="next-page disabled" title="Go to the next page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">›</a>
					<a class="last-page disabled" title="Go to the last page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">»</a></span></div>
			<br class="clear">
		</div>
		<table class="wp-list-table widefat fixed pages">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
						<a href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;orderby=title&amp;order=asc">
							<span>Title</span><span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="taxonomy-mvne_venue" class="manage-column column-taxonomy-mvne_venue" style="">
						Venue
					</th>
					<th scope="col" id="taxonomy-mvne_presenter" class="manage-column column-taxonomy-mvne_presenter" style="">
						Presenter
					</th>
					<th scope="col" id="taxonomy-mvne_category" class="manage-column column-taxonomy-mvne_category" style="">
						Category
					</th>
					<th scope="col" id="date" class="manage-column column-date sortable asc" style="">
						<a href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;orderby=date&amp;order=desc">
							<span>Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>	
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
						<input id="cb-select-all-2" type="checkbox">
					</th>
					<th scope="col" class="manage-column column-title sortable desc" style="">
						<a href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;orderby=title&amp;order=asc">
							<span>Title</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-taxonomy-mvne_venue" style="">
						Venue
					</th>
					<th scope="col" class="manage-column column-taxonomy-mvne_presenter" style="">
						Presenter
					</th>
					<th scope="col" class="manage-column column-taxonomy-mvne_category" style="">
						Category
					</th>
					<th scope="col" class="manage-column column-date sortable asc" style="">
						<a href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;orderby=date&amp;order=desc">
							<span>Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>	
				</tr>
			</tfoot>

			<tbody id="the-list">
				<tr id="post-6" class="post-6 type-mvn_event status-draft hentry alternate iedit author-self level-0" ng-repeat="event in events">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-6">Select (no title)</label>
						<input id="cb-select-6" name="post[]" value="6" type="checkbox">
			<div class="locked-indicator"></div>
			</th>
			<td class="post-title page-title column-title"><strong><a class="row-title" href="http://local.maven.com/wp-admin/post.php?post=6&amp;action=edit" title="Edit “(no title)”">(no title)</a> - <span class="post-state">Draft</span></strong>
				<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
				<div class="row-actions"><span class="edit"><a href="http://local.maven.com/wp-admin/post.php?post=6&amp;action=edit" title="Edit this item">Edit</a> | </span><span class="inline hide-if-no-js"><a href="#" class="editinline" title="Edit this item inline">Quick&nbsp;Edit</a> | </span><span class="trash"><a class="submitdelete" title="Move this item to the Trash" href="http://local.maven.com/wp-admin/post.php?post=6&amp;action=trash&amp;_wpnonce=583387b1c5">Trash</a> | </span><span class="view"><a href="http://local.maven.com/?post_type=mvn_event&amp;p=6&amp;preview=true" title="Preview “(no title)”" rel="permalink">Preview</a></span></div>
				<div class="hidden" id="inline_6">
					<div class="post_title"></div>
					<div class="post_name"></div>
					<div class="post_author">1</div>
					<div class="comment_status">closed</div>
					<div class="ping_status">closed</div>
					<div class="_status">draft</div>
					<div class="jj">21</div>
					<div class="mm">05</div>
					<div class="aa">2014</div>
					<div class="hh">13</div>
					<div class="mn">40</div>
					<div class="ss">54</div>
					<div class="post_password"></div><div class="post_parent">0</div><div class="post_category" id="mvne_venue_6"></div><div class="post_category" id="mvne_presenter_6"></div><div class="post_category" id="mvne_category_6"></div></div></td><td class="taxonomy-mvne_venue column-taxonomy-mvne_venue">—</td><td class="taxonomy-mvne_presenter column-taxonomy-mvne_presenter">—</td><td class="taxonomy-mvne_category column-taxonomy-mvne_category">—</td><td class="date column-date"><abbr title="2014/05/21 1:40:54 PM">2014/05/21</abbr><br>Last Modified</td>		</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">

			<div class="alignleft actions bulkactions">
				<select name="action2">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="edit" class="hide-if-no-js">Edit</option>
					<option value="trash">Move to Trash</option>
				</select>
				<input name="" id="doaction2" class="button action" value="Apply" type="submit">
			</div>
			<div class="alignleft actions">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">1 item</span>
				<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event">«</a>
					<a class="prev-page disabled" title="Go to the previous page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">‹</a>
					<span class="paging-input">1 of <span class="total-pages">1</span></span>
					<a class="next-page disabled" title="Go to the next page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">›</a>
					<a class="last-page disabled" title="Go to the last page" href="http://local.maven.com/wp-admin/edit.php?post_type=mvn_event&amp;paged=1">»</a></span></div>
			<br class="clear">
		</div>




		<table style="display: none"><tbody id="inlineedit">

				<tr id="inline-edit" class="inline-edit-row inline-edit-row-post inline-edit-mvn_event quick-edit-row quick-edit-row-post inline-edit-mvn_event" style="display: none"><td colspan="6" class="colspanchange">

						<fieldset class="inline-edit-col-left"><div class="inline-edit-col">
								<h4>Quick Edit</h4>

								<label>
									<span class="title">Title</span>
									<span class="input-text-wrap"><input name="post_title" class="ptitle" type="text"></span>
								</label>

								<label>
									<span class="title">Slug</span>
									<span class="input-text-wrap"><input name="post_name" type="text"></span>
								</label>


								<label><span class="title">Date</span></label>
								<div class="inline-edit-date">
									<div class="timestamp-wrap"><select name="mm">
											<option value="01">01-Jan</option>
											<option value="02">02-Feb</option>
											<option value="03">03-Mar</option>
											<option value="04">04-Apr</option>
											<option value="05" selected="selected">05-May</option>
											<option value="06">06-Jun</option>
											<option value="07">07-Jul</option>
											<option value="08">08-Aug</option>
											<option value="09">09-Sep</option>
											<option value="10">10-Oct</option>
											<option value="11">11-Nov</option>
											<option value="12">12-Dec</option>
										</select> <input name="jj" value="21" size="2" maxlength="2" autocomplete="off" type="text">, <input name="aa" value="2014" size="4" maxlength="4" autocomplete="off" type="text"> @ <input name="hh" value="14" size="2" maxlength="2" autocomplete="off" type="text"> : <input name="mn" value="11" size="2" maxlength="2" autocomplete="off" type="text"></div><input id="ss" name="ss" value="53" type="hidden">			</div>
								<br class="clear">

								<div class="inline-edit-group">
									<label class="alignleft">
										<span class="title">Password</span>
										<span class="input-text-wrap"><input name="post_password" class="inline-edit-password-input" type="text"></span>
									</label>

									<em style="margin:5px 10px 0 0" class="alignleft">
										–OR–				</em>
									<label class="alignleft inline-edit-private">
										<input name="keep_private" value="private" type="checkbox">
										<span class="checkbox-title">Private</span>
									</label>
								</div>


							</div></fieldset>


						<fieldset class="inline-edit-col-center inline-edit-categories"><div class="inline-edit-col">


								<span class="title inline-edit-categories-label">Venue</span>
								<input name="tax_input[mvne_venue][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_venue-checklist">
								</ul>


								<span class="title inline-edit-categories-label">Presenter</span>
								<input name="tax_input[mvne_presenter][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_presenter-checklist">
								</ul>


								<span class="title inline-edit-categories-label">Category</span>
								<input name="tax_input[mvne_category][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_category-checklist">
								</ul>


							</div></fieldset>


						<fieldset class="inline-edit-col-right"><div class="inline-edit-col">




								<div class="inline-edit-group">
									<label class="inline-edit-status alignleft">
										<span class="title">Status</span>
										<select name="_status">
											<option selected="selected" value="publish">Published</option>
											<option value="future">Scheduled</option>
											<option value="pending">Pending Review</option>
											<option value="draft">Draft</option>
										</select>
									</label>


								</div>


							</div></fieldset>

						<p class="submit inline-edit-save">
							<a accesskey="c" href="#inline-edit" class="button-secondary cancel alignleft">Cancel</a>
							<input id="_inline_edit" name="_inline_edit" value="662b94fcfd" type="hidden">				<a accesskey="s" href="#inline-edit" class="button-primary save alignright">Update</a>
							<span class="spinner"></span>
							<input name="post_view" value="list" type="hidden">
							<input name="screen" value="edit-mvn_event" type="hidden">
							<input name="post_author" value="" type="hidden">
							<span class="error" style="display:none"></span>
							<br class="clear">
						</p>
					</td></tr>

				<tr id="bulk-edit" class="inline-edit-row inline-edit-row-post inline-edit-mvn_event bulk-edit-row bulk-edit-row-post bulk-edit-mvn_event" style="display: none"><td colspan="6" class="colspanchange">

						<fieldset class="inline-edit-col-left"><div class="inline-edit-col">
								<h4>Bulk Edit</h4>
								<div id="bulk-title-div">
									<div id="bulk-titles"></div>
								</div>



							</div></fieldset><fieldset class="inline-edit-col-center inline-edit-categories"><div class="inline-edit-col">


								<span class="title inline-edit-categories-label">Venue</span>
								<input name="tax_input[mvne_venue][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_venue-checklist">
								</ul>


								<span class="title inline-edit-categories-label">Presenter</span>
								<input name="tax_input[mvne_presenter][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_presenter-checklist">
								</ul>


								<span class="title inline-edit-categories-label">Category</span>
								<input name="tax_input[mvne_category][]" value="0" type="hidden">
								<ul class="cat-checklist mvne_category-checklist">
								</ul>


							</div></fieldset>


						<fieldset class="inline-edit-col-right"><div class="inline-edit-col">




								<div class="inline-edit-group">
									<label class="inline-edit-status alignleft">
										<span class="title">Status</span>
										<select name="_status">
											<option selected="selected" value="-1">— No Change —</option>
											<option value="publish">Published</option>

											<option value="private">Private</option>
											<option value="pending">Pending Review</option>
											<option value="draft">Draft</option>
										</select>
									</label>


								</div>


							</div></fieldset>

						<p class="submit inline-edit-save">
							<a accesskey="c" href="#inline-edit" class="button-secondary cancel alignleft">Cancel</a>
							<input name="bulk_edit" id="bulk_edit" class="button button-primary alignright" value="Update" accesskey="s" type="submit">			<input name="post_view" value="list" type="hidden">
							<input name="screen" value="edit-mvn_event" type="hidden">
							<span class="error" style="display:none"></span>
							<br class="clear">
						</p>
					</td></tr>
			</tbody></table>
		<br class="clear">
	</div>
</div>
