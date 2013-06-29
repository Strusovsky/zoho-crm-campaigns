<?php
	require 'includes/connection.inc.php';
	require 'includes/autoload.php';

	$utilities = new Utilities();

	if (!empty($_GET) && $_GET['campaign_id'] != NULL)
	{
		$stmt = $conn->prepare("SELECT * FROM zoho.campaigns WHERE campaign_id = :campaign_id");
		$stmt->execute(array(':campaign_id' => $_GET['campaign_id']));
		$campaign = $stmt->fetch();

		if (!empty($_GET['id']) && $_GET['id'] != NULL)
		{
			$stmt = $conn->prepare("SELECT * FROM zoho.seminars WHERE id = :id");
			$stmt->execute(array(':id' => $_GET['id']));
			$seminar = $stmt->fetch();

		}
		
		$stmt = $conn->prepare("SELECT seminars.id, seminars.name, city, state, `date`, street, phone, coop_contact, coop_phone
								FROM seminars
								INNER JOIN campaigns ON seminars.campaign_id = campaigns.id
								WHERE campaigns.id = :campaign_id
								ORDER BY `date`");
		$stmt->execute(array(':campaign_id' => $campaign['id']));
		$seminars = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Campaign: <?=isset($campaign['name'])?$campaign['name']:'';?> - Seminar Information</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/base.css">
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="stylesheet" href="css/timepicker.min.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/maskMoney.js"></script>		
		<script src="js/datepicker.js"></script>
		<script src="js/timepicker.min.js"></script>
	</head>
	<body>
		<h1 class="logo ir">agCompliance</h1>
		<div class="top-bar">
			<h2>
				Seminar: <?=isset($seminar['name'])?$seminar['name']:'';?>
				<span class="right">
					<a class="btn btn-inverse" href="campaign-information.php?id=<?=isset($campaign['zoho_id'])?$campaign['zoho_id']:'';?>"><i class="icon-home icon-white"></i></a>
					<a class="btn btn-inverse" href="#" onclick="close_window();return false;"><i class="icon-remove icon-white"></i></a>
				</span>
			</h2>
		</div>
		<h4 id="status" data-status="<?=isset($campaign['status'])?$campaign['status']:'';?>">
			<strong>
				<a href="campaign-information.php?id=<?=isset($campaign['zoho_id'])?$campaign['zoho_id']:'';?>">Campaign: <?=isset($campaign['name'])?$campaign['name']:'';?></a> - Status: <span id="status-info"><?=isset($campaign['status'])?$campaign['status']:'';?></span>
				<span class="right">
					<a href="campaign-information.php?id=<?=isset($campaign['zoho_id'])?$campaign['zoho_id']:'';?>"><i class="icon-edit"></i> Edit</a>
					&nbsp&nbsp
					<a href="#deleteModal" role="button" data-toggle="modal"><i class="icon-trash"></i> Delete</a>
				</span>				
			</strong>
		</h4>
		<div class="main-content clearfix">
			<form method="POST" action="" id="form">
				<input type="hidden" name="campaign_id" value="<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>">
				<input type="hidden" name="id" value="<?=isset($campaign['id'])?$campaign['id']:'';?>">
				<input type="hidden" name="seminar_id" value="<?=isset($seminar['id'])?$seminar['id']:'';?>">
				<input type="hidden" name="datetime" id="datetime">
				<div class="span6 seminar-content">
					<ul class="nav nav-tabs" id="seminar-tabs">
						<li><a href="#facility">Facility</a></li>
						<li><a href="#info">Information</a></li>
						<li><a href="#results">Results</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane" id="facility">
							<div class="row">
								<div class="span6">
									<legend>Information</legend>
									<div class="row">
										<div class="span3">
											<div class="input-append bootstrap-datepicker left">
												<div id="date" class="control-group error">
													<label for="date" class="control-label">Seminar Date</label>
													<div id="datepicker" class="controls input-append date">
														<input type="text" name="date"  class="datepicker-input" value="<?=isset($seminar['date'])&&$seminar['date']!=='0000-00-00 00:00:00'?date('Y/m/d',strtotime($seminar['date'])):'Not Set';?>">
														<span class="add-on"><i class="icon-calendar"></i></span>
													</div>
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="input-append bootstrap-timepicker left t1">
												<div id="time" class="control-group error">
													<label for="time" class="control-label">Seminar Time</label>
													<div class="controls input-append">
														<input type="text" name="time" id="timepicker" class="timepicker-input" value="<?=isset($seminar['date'])&&$seminar['date']!=='0000-00-00 00:00:00'?date('g:i A',strtotime($seminar['date'])):'12:00 AM';?>">
														<span class="add-on"><i class="icon-time"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span6">
											<div id="name" class="control-group error">
												<label for="name" class="control-label">Venue Name</label>
												<div class="controls">
													<input type="text" name="name" class="span6" value="<?=isset($seminar['name'])?$seminar['name']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div id="contact" class="control-group error">
												<label for="contact" class="control-label">Venue Contact</label>
												<div class="controls">
													<input type="text" name="contact" value="<?=isset($seminar['contact'])?$seminar['contact']:'';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="phone" class="control-group error">
												<label for="phone" class="control-label">Venue Phone Number</label>
												<div class="controls">
													<input type="text" name="phone" maxlength="26" value="<?=isset($seminar['phone'])?$seminar['phone']:'';?>">
												</div>
											</div>
										</div>										
									</div>
									<legend>Address</legend>
									<div class="row">
										<div class="span3">
											<div id="street" class="control-group error">
												<label for="street" class="control-label">Street</label>
												<div class="controls">
													<input type="text" name="street" value="<?=isset($seminar['street'])?$seminar['street']:'';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="city" class="control-group error">
												<label for="city" class="control-label">City</label>
												<div class="controls">
													<input type="text" name="city" value="<?=isset($seminar['city'])?$seminar['city']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div id="state" class="control-group error">
												<label for="state" class="control-label">State</label>
												<div class="controls">
													<select name="state">
														<?=$utilities->states($seminar['state']);?>
													</select>
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="zip_code" class="control-group error">
												<label for="zip_code" class="control-label">Zip Code</label>
												<div class="controls">
													<input type="text" name="zip_code" maxlength="10" value="<?=isset($seminar['zip_code'])?$seminar['zip_code']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span6">
											<div id="county" class="control-group error">
												<label for="county" class="control-label">County</label>
												<div class="controls">
													<input type="text" name="county" value="<?=isset($seminar['county'])?$seminar['county']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<legend>Arrival Time</legend>
									<div class="row">
										<div class="span3">
											<div class="input-append bootstrap-timepicker left t2">
												<div class="control-group error">
													<label for="arrival_time" class="control-label">Arrival Time</label>
													<div class="controls input-append">
														<input type="hidden" name="arrival_time" id="arrival_time">
														<input type="text" id="timepicker2" class="timepicker-input" value="<?=isset($seminar['arrival_time'])?date('g:i A',strtotime($seminar['arrival_time'])):'';?>">
														<span class="add-on"><i class="icon-time"></i></span>
													</div>
												</div>
											</div>
										</div>								
										<div class="span3">
											<label for="confirmed">Is Arrival Time Confirmed?</label>
											<select name="arrival_confirm" class="span3">
												<option value="">--</option>
												<option value="yes" <?=isset($seminar['arrival_confirm'])&&($seminar['arrival_confirm']=='yes')?'selected':'';?>>Yes</option>
												<option value="no" <?=isset($seminar['arrival_confirm'])&&($seminar['arrival_confirm']=='no')?'selected':'';?>>No</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="info">
							<div class="row">
								<div class="span6">									
									<legend>Co-op Contact</legend>
									<div class="row">
										<div class="span3">
											<div class="control-group error">
												<label for="coop_contact">Contact</label>
												<div class="controls">
													<input type="text" name="coop_contact" value="<?=isset($seminar['coop_contact'])?$seminar['coop_contact']:'';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group error">
												<label for="coop_phone">Contact Phone Number</label>
												<div class="controls">
													<input type="text" name="coop_phone" maxlength="26" value="<?=isset($seminar['coop_phone'])?$seminar['coop_phone']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<legend>Refreshments</legend>
									<div class="row">
										<div class="span2">
											<div class="control-group error">
												<label for="refreshments_offered">Offered</label>
												<div class="controls">
													<select name="refreshments_offered" class="span2">
														<option value="">--</option>
														<option value="yes" <?=isset($seminar['refreshments_offered'])&&($seminar['refreshments_offered']=='yes')?'selected':'';?>>Yes</option>
														<option value="no" <?=isset($seminar['refreshments_offered'])&&($seminar['refreshments_offered']=='no')?'selected':'';?>>No</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group error">
												<label for="refreshments_type">Type</label>
												<div class="controls">
													<select name="refreshments_type" class="span2">
														<option value="">--</option>
														<option value="breakfast" <?=isset($seminar['refreshments_type'])&&($seminar['refreshments_type']=='breakfast')?'selected':'';?>>Breakfast</option>
														<option value="lunch" <?=isset($seminar['refreshments_type'])&&($seminar['refreshments_type']=='lunch')?'selected':'';?>>Lunch</option>
														<option value="dinner" <?=isset($seminar['refreshments_type'])&&($seminar['refreshments_type']=='dinner')?'selected':'';?>>Dinner</option>
														<option value="snacks" <?=isset($seminar['refreshments_type'])&&($seminar['refreshments_type']=='snacks')?'selected':'';?>>Snacks</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group error">
												<label for="refreshments_payment">Payment</label>
												<div class="controls">
													<select name="refreshments_payment" class="span2">
														<option value="">--</option>
														<option value="50/50" <?=isset($seminar['refreshments_payment'])&&($seminar['refreshments_payment']=='50/50')?'selected':'';?>>50/50</option>
														<option value="coop" <?=isset($seminar['refreshments_payment'])&&($seminar['refreshments_payment']=='coop')?'selected':'';?>>Co-op</option>
														<option value="agcompliance" <?=isset($seminar['refreshments_payment'])&&($seminar['refreshments_payment']=='agcompliance')?'selected':'';?>>agCompliance</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<legend>Advertising</legend>
									<div class="row">
										<div class="span6">
											<div class="control-group error">
												<label for="radio_station">Radio Station</label>
												<div class="controls">
													<input type="text" name="radio_station" value="<?=isset($seminar['radio_station'])?$seminar['radio_station']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div class="control-group error">
												<label for="radio_contact">Radio Station Contact</label>
												<div class="controls">
													<input type="text" name="radio_contact" value="<?=isset($seminar['radio_contact'])?$seminar['radio_contact']:'';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group error">
												<label for="radio_phone">Radio Station Phone Number</label>
												<div class="controls">
													<input type="text" name="radio_phone" value="<?=isset($seminar['radio_phone'])?$seminar['radio_phone']:'';?>">
												</div>
											</div>
										</div>										
									</div>
									<div class="row">
										<div class="span6">
											<div class="control-group error">
												<label for="newspaper_name">Newspaper Name</label>
												<div class="controls">
													<input type="text" name="newspaper_name" value="<?=isset($seminar['newspaper_name'])?$seminar['newspaper_name']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div class="control-group error">
												<label for="newspaper_contact">Newspaper Contact</label>
												<div class="controls">
													<input type="text" name="newspaper_contact" value="<?=isset($seminar['newspaper_contact'])?$seminar['newspaper_contact']:'';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group error">
												<label for="newspaper_phone">Newspaper Phone Number</label>
												<div class="controls">
													<input type="text" name="newspaper_phone" value="<?=isset($seminar['newspaper_phone'])?$seminar['newspaper_phone']:'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div class="control-group error">
												<label for="advertising_cost">Advertising Cost</label>
												<div class="controls">
													<input type="text" name="advertising_cost" maxlength="10" data-input="currency" value="<?=isset($seminar['advertising_cost'])?$seminar['advertising_cost']:'0.00';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group error">
												<label for="advertising_payment">Advertising Payment</label>
												<div class="controls">
													<select name="advertising_payment">
														<option value="">--</option>
														<option value="50/50" <?=isset($seminar['advertising_payment'])&&($seminar['advertising_payment']=='50/50')?'selected':'';?>>50/50</option>
														<option value="coop" <?=isset($seminar['advertising_payment'])&&($seminar['advertising_payment']=='coop')?'selected':'';?>>Co-op</option>
														<option value="agcompliance" <?=isset($seminar['advertising_payment'])&&($seminar['advertising_payment']=='agcompliance')?'selected':'';?>>agCompliance</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<legend>Facility Amenities</legend>
									<div class="row">
										<div class="span2">
											<div class="control-group error">
												<label for="projector_screen">Projector Screen</label>
												<div class="controls">
													<select name="projector_screen" class="span2">
														<option value="">--</option>
														<option value="yes" <?=isset($seminar['projector_screen'])&&($seminar['projector_screen']=='yes')?'selected':'';?>>Yes</option>
														<option value="no" <?=isset($seminar['projector_screen'])&&($seminar['projector_screen']=='no')?'selected':'';?>>No</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group error">
												<label for="wifi">WiFi</label>
												<div class="controls">
													<select name="wifi" class="span2">
														<option value="">--</option>
														<option value="yes" <?=isset($seminar['wifi'])&&($seminar['wifi']=='yes')?'selected':'';?>>Yes</option>
														<option value="no" <?=isset($seminar['wifi'])&&($seminar['wifi']=='no')?'selected':'';?>>No</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group error">
												<label for="cell_reception">Cell Phone Reception</label>
												<div class="controls">
													<select name="cell_reception" class="span2">
														<option value="">--</option>
														<option value="yes" <?=isset($seminar['cell_reception'])&&($seminar['cell_reception']=='yes')?'selected':'';?>>Yes</option>
														<option value="no" <?=isset($seminar['cell_reception'])&&($seminar['cell_reception']=='no')?'selected':'';?>>No</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="results">
							<div class="row">
								<div class="span6">
									<legend>Results</legend>
									<div class="row">
										<div class="span3">		
											<div id="attendees" class="control-group error">
												<label for="attendees" class="control-label">Attendees</label>
												<div class="controls">
													<input type="text" name="attendees" maxlength="6" data-input="number" value="<?=isset($seminar['attendees'])?$seminar['attendees']:'0';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="num_under_threshold" class="control-group error">
												<label for="num_under_threshold" class="control-label"># Under Threshold</label>
												<div class="controls">
													<input type="text" name="num_under_threshold" maxlength="6" data-input="number" value="<?=isset($seminar['num_under_threshold'])?$seminar['num_under_threshold']:'0';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div id="pe_sold" class="control-group error">
												<label for="pe_sold" class="control-label">PE's Sold</label>
												<div class="controls">
													<input type="text" name="pe_sold" maxlength="6" data-input="number" value="<?=isset($seminar['pe_sold'])?$seminar['pe_sold']:'0';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="self_cert_sold" class="control-group error">
												<label for="self_cert_sold" class="control-label">Self Cert Sold</label>
												<div class="controls">
													<input type="text" name="self_cert_sold" maxlength="6" data-input="number" value="<?=isset($seminar['self_cert_sold'])?$seminar['self_cert_sold']:'0';?>">
												</div>
											</div>									
										</div>
									</div>
									<div class="row">
										<div class="span3">
											<div id="pe_containment_sold" class="control-group error">
												<label for="pe_containment_sold" class="control-label">PE Containment Sold</label>
												<div class="controls">
													<input type="text" name="pe_containment_sold" maxlength="6" data-input="number" value="<?=isset($seminar['pe_containment_sold'])?$seminar['pe_containment_sold']:'0';?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="self_cert_containment_sold" class="control-group error">
												<label for="self_cert_containment_sold" class="control-label">Self Cert Containment Sold</label>
												<div class="controls">
													<input type="text" name="self_cert_containment_sold" maxlength="6" data-input="number" value="<?=isset($seminar['self_cert_containment_sold'])?$seminar['self_cert_containment_sold']:'0';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="span3">						
											<div id="spill_kits_sold" class="control-group error">
												<label for="spill_kits_sold" class="control-label">Spill Kits Sold</label>
												<div class="controls">
													<input type="text" name="spill_kits_sold" maxlength="6" data-input="number" value="<?=isset($seminar['spill_kits_sold'])?$seminar['spill_kits_sold']:'0';?>">	
												</div>
											</div>
										</div>
										<div class="span3">
											<div id="inspection_sold" class="control-group error">
												<label for="inspection_sold" class="control-label">Inspections Sold</label>
												<div class="controls">
													<input type="text" name="inspection_sold" maxlength="6" data-input="number" value="<?=isset($seminar['inspection_sold'])?$seminar['inspection_sold']:'0';?>">	
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
					<br>
					<br>
					<br>
					<div class="buttons">
						<button type="button" name="update_seminar_info" id="update_seminar_info" value="update_seminar_info" class="btn">Save</button>
						<button type="button" name="insert_seminar_add_seminar" id="insert_seminar_add_seminar" value="insert_seminar_add_seminar" class="btn end">Save &amp; Add Seminar</button>
					</div>	
				</div>
			</form>
			<div class="span5 offset1">
				<h3>Campaign Seminars</h3>
				<? if (empty($seminars)): ?>
					<p class="quiet">No seminars have been linked to this campaign. <a href="seminar-information.php?campaign_id=<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>" class="right"><i class="icon-plus"></i> Add Seminar</a></p>
				<? else: ?>
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>Location</th>
								<th>Date</th>
								<th>Time</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<? foreach ($seminars as $seminar): ?>
							<tr <?=(isset($seminar['id'])&&isset($_GET['id']))&&($seminar['id']==$_GET['id'])?'class="seminar-popover info"':'class="seminar-popover"';?> data-title="<?=$seminar['name'];?>" data-content="<h4>Phone:</h4><p><?=$seminar['phone'];?></p><h4>Address:</h4><p><?=$seminar['street'];?></p><p><?=$seminar['city'];?>, <?=$seminar['state'];?></p><h4>Contact:</h4><p><?=$seminar['coop_contact'];?></p><p><?=$seminar['coop_phone'];?></p>
">
								<td><?=$seminar['city'];?>, <?=$seminar['state'];?></td>
								<td><?=($seminar['date']=='0000-00-00 00:00:00')?'Not Set':date('M jS, Y',strtotime($seminar['date']));?></td>
								<td><?=($seminar['date']=='0000-00-00 00:00:00')?'Not Set':date('g:i A',strtotime($seminar['date']));?></td>
								<td>
									<a href="seminar-information.php?id=<?=$seminar['id'];?>&campaign_id=<?=$campaign['campaign_id'];?>" class="seminar-info" data-toggle="tooltip"><i class="icon-edit"></i></a>
									&nbsp;
									<a href="/controllers/seminar-duplicate.php?id=<?=$seminar['id'];?>&campaign_id=<?=$campaign['campaign_id'];?>" class="seminar-duplicate" data-toggle="tooltip"><i class="icon-share"></i></a>
									&nbsp;
									<a href="#deleteModal<?=$seminar['id'];?>" role="button" data-toggle="modal"><i class="icon-trash"></i></a>									
								</td>
							</tr>
							<?php endforeach; ?>	
						</tbody>
					</table>
					<a href="seminar-information.php?campaign_id=<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>" class="right"><i class="icon-plus"></i> Add Seminar</a>
				<?php endif; ?>
			</div>
		</div>
		<? if (isset($seminars)): ?>
			<? foreach ($seminars as $seminar): ?>
				<!-- Hidden Confirm Modal -->
				<div id="deleteModal<?=$seminar['id'];?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal<?=$seminar['id'];?>" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						<h2 id="deleteModal<?=$seminar['id'];?>">Are You Sure?</h2>
					</div>
					<div class="modal-body">
						<p>You are about to delete the seminar information for the campaign: <?=isset($campaign['name'])?$campaign['name']:'';?>. This action cannot be undone.</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						<a href="controllers/delete-seminar.php?seminar_id=<?=$seminar['id'];?>&zoho_id=<?=$campaign['zoho_id'];?>&id=<?=$campaign['id'];?>&campaign_id=<?=$campaign['campaign_id'];?>" class="btn btn-danger">Delete</a>
					</div>
				</div>		
			<?php endforeach; ?>
		<?php endif; ?>
		<!-- Hidden Confirm Modal -->
		<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h2 id="deleteModal">Are You Sure?</h2>
			</div>
			<div class="modal-body">
				<p>You are about to delete the information for the campaign: <?=isset($campaign['name'])?$campaign['name']:'';?>. This action will also delete any seminar information linked to this campaign and cannot be undone.</p>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<a href="controllers/delete-campaign.php?id=<?=isset($campaign['id'])?$campaign['id']:'';?>&zoho_id=<?=$campaign['zoho_id'];?>&campaign_id=<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>" class="btn btn-danger">Delete</a>
			</div>
		</div>
		<div class="overlay">
			<img class="ajax-loader" src="img/ajax-loader-white.gif" alt="ajax-loader">
		</div>

		<script>
		$('#seminar-tabs a[href="#facility"]').tab('show');

		$('#seminar-tabs a[href="#facility"]').click(function (e) {
  			e.preventDefault();
  			$(this).tab('show');
		});

		$('#seminar-tabs a[href="#info"]').click(function (e) {
  			e.preventDefault();
  			$(this).tab('show');
		});

		$('#seminar-tabs a[href="#results"]').click(function (e) {
  			e.preventDefault();
  			$(this).tab('show');
		});

			$('.seminar-info').tooltip({
			    title: 'Edit Seminar Information'
			});

			$('.seminar-duplicate').tooltip({
			    title: 'Duplicate Seminar'
			});

			$('.seminar-popover').popover({
			    html: true,
			    trigger: 'click',
			    placement: 'left',
			    delay: 500
			});

			var date 		= $('#datepicker'),
				dateInput 	= date.find('input'),
				time  		= $('#timepicker'),
				time2 		= $('#timepicker2'),
				datetime, d, h, m;
			
			date.datepicker({
				format: 'yyyy/mm/dd',
				autoclose: true
			}).change(function() {
				d = new Date(dateInput.val()+" "+t1hour.val()+":"+t1minute.val()+" "+t1meridian.val());

				h = d.getHours();
				m = d.getMinutes();

				datetime = dateInput.val() + ' ' + h + ':' + m;
				datetimeInput.val(datetime);
			});

			time.timepicker().change(function() {
				d = new Date(dateInput.val()+" "+t1hour.val()+":"+t1minute.val()+" "+t1meridian.val());

				h = d.getHours();
				m = d.getMinutes();

				datetime = dateInput.val() + ' ' + h + ':' + m;
				datetimeInput.val(datetime);
			});
			
			time2.timepicker().change(function() {
				d = new Date(dateInput.val()+" "+t2hour.val()+":"+t2minute.val()+" "+t2meridian.val());

				h = d.getHours();
				m = d.getMinutes();

				$('#arrival_time').val(dateInput.val() + ' ' + h + ':' + m);

			});	

			var t1hour = $('.t1 .bootstrap-timepicker-hour'),
				t1minute = $('.t1 .bootstrap-timepicker-minute'),
				t1meridian = $('.t1 .bootstrap-timepicker-meridian'),
				t2hour = $('.t2 .bootstrap-timepicker-hour'),
				t2minute = $('.t2 .bootstrap-timepicker-minute'),
				t2meridian = $('.t2 .bootstrap-timepicker-meridian'),
				datetimeInput = $('#datetime');

			d = new Date(dateInput.val()+" "+t1hour.val()+":"+t1minute.val()+" "+t1meridian.val());

			h = d.getHours();
			m = d.getMinutes();

			datetime = dateInput.val() + ' ' + h + ':' + m;
			datetimeInput.val(datetime);

			d2 = new Date(dateInput.val()+" "+t2hour.val()+":"+t2minute.val()+" "+t2meridian.val());

			h = d2.getHours();
			m = d2.getMinutes();

			$('#arrival_time').val(dateInput.val()+" "+h + ':' + m);			
		</script>
		<script src="js/main.js"></script>
	</body>
</html>