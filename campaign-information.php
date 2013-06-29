<?php
	require 'includes/connection.inc.php';
	require 'includes/autoload.php';

	if (!empty($_GET) && $_GET['id'] != NULL)
	{
		$record = new FetchCampaign($_GET['id']);
		$xml 	= $record->fetch();

		if (isset($xml))
		{
			$zoho = array(
				'campaign_id'		=> (string) $xml->result->Campaigns->row[0]->FL[0],
				'name'				=> (string) $xml->result->Campaigns->row[0]->FL[1],
				'status'			=> (string) $xml->result->Campaigns->row[0]->FL[2],
				'zoho_id'			=> (string) $xml->result->Campaigns->row[0]->FL[3],
				'coop_commission'	=> (string) $xml->result->Campaigns->row[0]->FL[4]
			);
			
			$stmt = $conn->prepare("SELECT * FROM zoho.campaigns WHERE campaign_id = :campaign_id");
			$stmt->execute(array(':campaign_id' => $zoho['campaign_id']));
			$campaign = $stmt->fetch();

			$stmt = $conn->prepare("SELECT seminars.name, seminars.id, city, state, `date`, street, phone, coop_contact, coop_phone FROM seminars INNER JOIN campaigns ON seminars.campaign_id = campaigns.id WHERE campaigns.id = :campaign_id ORDER BY `date`");
			$stmt->execute(array(':campaign_id' => $campaign['id']));
			$seminars = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Campaign: Information</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/base.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/maskMoney.js"></script>
	</head>
	<body>
		<h1 class="logo ir">agCompliance</h1>
		<div class="top-bar">
			<h2>
				<a href="campaign-information.php?id=<?=isset($zoho['zoho_id'])?$zoho['zoho_id']:'';?>">Campaign: <?=isset($zoho['name'])?$zoho['name']:'';?></a>
				<span class="right">
					<a class="btn btn-inverse" href="campaign-information.php?id=<?=isset($zoho['zoho_id'])?$zoho['zoho_id']:'';?>"><i class="icon-home icon-white"></i></a>
					<a class="btn btn-inverse" href="#" onclick="close_window();return false;"><i class="icon-remove icon-white"></i></a>
				</span>
			</h2>
		</div>
		<h4 id="status" data-status="<?=isset($zoho['status'])?$zoho['status']:'';?>">
			<strong>
				Campaign: <?=isset($zoho['name'])?$zoho['name']:'';?> - Status: <span id="status-info"><?=isset($zoho['status'])?$zoho['status']:'';?></span>
				<span class="right">
					<a href="campaign-information.php?id=<?=isset($zoho['zoho_id'])?$zoho['zoho_id']:'';?>"><i class="icon-edit"></i> Edit</a>
					&nbsp&nbsp
					<a href="#deleteCampaign" role="button" data-toggle="modal"><i class="icon-trash"></i> Delete</a>
				</span>
			</strong>
		</h4>
		<div class="main-content clearfix">
			<form action="" method="POST" id="form">
				<input type="hidden" name="campaign_id" value="<?=isset($zoho['campaign_id'])?$zoho['campaign_id']:'';?>">
				<input type="hidden" name="id" value="<?=isset($campaign['id'])?$campaign['id']:'';?>">
				<input type="hidden" name="name" value="<?=isset($zoho['name'])?$zoho['name']:'';?>">
				<input type="hidden" name="status" value="<?=isset($zoho['status'])?$zoho['status']:'';?>">
				<input type="hidden" name="zoho_id" value="<?=isset($zoho['zoho_id'])?$zoho['zoho_id']:'';?>">
				<input type="hidden" name="coop_commission" value="<?=isset($zoho['coop_commission'])?$zoho['coop_commission']:'';?>">
				<div class="span6">
					<h3>Campaign Expenses</h3>
					<div class="row">
						<div class="span6">
							<legend>Seminar Expenses</legend>
							<div class="row">
								<div class="span3">
									<div id="days" class="control-group error">
										<label for="days" class="control-label">Days On Road</label>
										<div class="controls">
											<input type="text" name="days" maxlength="2" value="<?=isset($campaign['days_on_road'])?$campaign['days_on_road']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="employees" class="control-group error">
										<label for="employees" class="control-label"># Employees</label>
										<div class="controls">
											<input type="text" name="employees" maxlength="2" value="<?=isset($campaign['num_employees'])?$campaign['num_employees']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="hotel" class="control-group error">
										<label for="hotel" class="control-label">Hotel Cost</label>
										<div class="controls">
											<input type="text" name="hotel" maxlength="10" data-input="currency" value="<?=isset($campaign['hotel_cost'])?$campaign['hotel_cost']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="catering" class="control-group error">
										<label for="catering" class="control-label">Catering Cost</label>
										<div class="controls">
											<input type="text" name="catering" maxlength="10" data-input="currency" value="<?=isset($campaign['catering_cost'])?$campaign['catering_cost']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="rental" class="control-group error">
										<label for="rental" class="control-label">Location Rental</label>
										<div class="controls">
											<input type="text" name="rental" maxlength="10" data-input="currency" value="<?=isset($campaign['location_rental'])?$campaign['location_rental']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="miles" class="control-group error">
										<label for="miles" class="control-label">Estimated Vehicle Miles</label>
										<div class="controls">
											<input type="text" name="miles" maxlength="6" data-input="number" value="<?=isset($campaign['est_vehicle_miles'])?$campaign['est_vehicle_miles']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="plane" class="control-group error">
										<label for="plane" class="control-label">Plane Ticket Cost</label>
										<div class="controls">
											<input type="text" name="plane" maxlength="10" data-input="currency" value="<?=isset($campaign['plane_ticket_cost'])?$campaign['plane_ticket_cost']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="vehicle" class="control-group error">
										<label for="vehicle" class="control-label">Vehicle Cost</label>
										<div class="controls">
											<input type="text" name="vehicle" maxlength="10" data-input="currency" value="<?=isset($campaign['vehicle_cost'])?$campaign['vehicle_cost']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<legend>Marketing Expenses</legend>
							<div class="row">
								<div class="span3">
									<div id="packets" class="control-group error">
										<label for="packets" class="control-label">Farmer Packets and Pens</label>
										<div class="controls">
											<input type="text" name="packets" maxlength="6" data-input="number" value="<?=isset($campaign['packets_and_pens'])?$campaign['packets_and_pens']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="flyers" class="control-group error">
										<label for="flyers" class="control-label">Flyers</label>
										<div class="controls">
											<input type="text" name="flyers" maxlength="6" data-input="number" value="<?=isset($campaign['flyers'])?$campaign['flyers']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="radio" class="control-group error">
										<label for="radio" class="control-label">Radio</label>
										<div class="controls">
											<input type="text" name="radio" maxlength="10" data-input="currency" value="<?=isset($campaign['radio'])?$campaign['radio']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="newspaper" class="control-group error">
										<label for="newspaper" class="control-label">Newspaper</label>
										<div class="controls">
											<input type="text" name="newspaper" maxlength="10" data-input="currency" value="<?=isset($campaign['newspaper'])?$campaign['newspaper']:'';?>">
										</div>
									</div>	
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="mail" class="control-group error">
										<label for="mail" class="control-label">Direct Mail</label>
										<div class="controls">
											<input type="text" name="mail" maxlength="6" data-input="number" value="<?=isset($campaign['direct_mail'])?$campaign['direct_mail']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="calls" class="control-group error">
										<label for="calls" class="control-label">Automated Calls</label>
										<div class="controls">
											<input type="text" name="calls" maxlength="6" data-input="number" value="<?=isset($campaign['automated_calls'])?$campaign['automated_calls']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<legend>Price Costs</legend>
							<div class="row">
								<div class="span2">
									<div class="control-group">
										<label for="self_cert_price" class="control-label">T1/T2</label>
										<div>
											<select name="self_cert_price" class="span2">
												<option value="2250" <?=isset($campaign['self_cert_price'])&&($campaign['self_cert_price']==2250)?'selected':'';?>>$2,250</option>
												<option value="1800" <?=isset($campaign['self_cert_price'])&&($campaign['self_cert_price']==1800)?'selected':'';?>>$1,800</option>
												<option value="1348" <?=isset($campaign['self_cert_price'])&&($campaign['self_cert_price']==1348)?'selected':'';?>>$1,348</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="pe_price" class="control-label">PE</label>
										<div>
											<select name="pe_price" class="span2">
												<option value="4780" <?=isset($campaign['pe_price'])&&($campaign['pe_price']==4780)?'selected':'';?>>$4,780</option>
												<option value="3980" <?=isset($campaign['pe_price'])&&($campaign['pe_price']==3980)?'selected':'';?>>$3,980</option>
												<option value="3680" <?=isset($campaign['pe_price'])&&($campaign['pe_price']==3680)?'selected':'';?>>$3,680</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="spill_kit_price" class="control-label">Spill Kit</label>
										<div>
											<select name="spill_kit_price" class="span2">
												<option value="49">$49</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span2">
									<div class="control-group">
										<label for="self_cert_containment_price" class="control-label">T1/T2 Containment</label>
										<div>
											<select name="self_cert_containment_price" class="span2">
												<option value="5445" <?=isset($campaign['self_cert_containment_price'])&&($campaign['self_cert_containment_price']==5445)?'selected':'';?>>$5,445</option>
												<option value="4445" <?=isset($campaign['self_cert_containment_price'])&&($campaign['self_cert_containment_price']==4445)?'selected':'';?>>$4,445</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="pe_containment_price" class="control-label">PE Containment</label>
										<div>
											<select name="pe_containment_price" class="span2">
												<option value="7215" <?=isset($campaign['pe_containment_price'])&&($campaign['pe_containment_price']==7215)?'selected':'';?>>$7,215</option>
												<option value="5115" <?=isset($campaign['pe_containment_price'])&&($campaign['pe_containment_price']==5115)?'selected':'';?>>$5,115</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="inspection_price" class="control-label">Inspection</label>
										<div>
											<select name="inspection_price" class="span2">
												<option value="500" <?=isset($campaign['inspection_price'])&&($campaign['inspection_price']==500)?'selected':'';?>>$500</option>
												<option value="350" <?=isset($campaign['inspection_price'])&&($campaign['inspection_price']==350)?'selected':'';?>>$350</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<br>
							<br>
							<br>
							<br>
							<div class="buttons">
								<button type="button" name="update_campaign" id="update_campaign" value="update_campaign" class="btn">Save</button>
								<button type="button" name="insert_campaign_add_seminar" id="insert_campaign_add_seminar" value="insert_campaign_add_seminar" class="btn end">Save &amp; Add Seminar</button>
							</div>							
						</div>

					</div>
				</div>
			</form>
			<div class="span5 offset1">
				<h3>Campaign Seminars</h3>
				<? if (empty($seminars)): ?>
					<p class="quiet">No seminars have been linked to this campaign. <a href="seminar-information.php?campaign_id=<?=isset($zoho['campaign_id'])?$zoho['campaign_id']:'';?>" class="right"><i class="icon-plus"></i> Add Seminar</a></p>

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
								<tr class="seminar-popover" data-title="<?=$seminar['name'];?>" data-content="<h4>Phone:</h4><p><?=$seminar['phone'];?></p><h4>Address:</h4><p><?=$seminar['street'];?></p><p><?=$seminar['city'];?>, <?=$seminar['state'];?></p><h4>Contact:</h4><p><?=$seminar['coop_contact'];?></p><p><?=$seminar['coop_phone'];?></p>
">
									<td><?=$seminar['city'];?>, <?=$seminar['state'];?></td>
									<td><?=($seminar['date']=='0000-00-00 00:00:00')?'Not Set':date('M jS, Y',strtotime($seminar['date']));?></td>
									<td><?=($seminar['date']=='0000-00-00 00:00:00')?'Not Set':date('g:i A',strtotime($seminar['date']));?></td>
									<td>
										<a href="seminar-information.php?id=<?=$seminar['id'];?>&campaign_id=<?=$zoho['campaign_id'];?>" class="seminar-info" data-toggle="tooltip"><i class="icon-edit"></i></a>
										&nbsp;
										<a href="controllers/seminar-duplicate.php?id=<?=$seminar['id'];?>&campaign_id=<?=$zoho['campaign_id'];?>" class="seminar-duplicate" data-toggle="tooltip"><i class="icon-share"></i></a>
										&nbsp;
										<a href="#deleteModal<?=$seminar['id'];?>" role="button" data-toggle="modal"><i class="icon-trash"></i></a>									
									</td>
								</tr>
							<? endforeach; ?>	
						</tbody>
					</table>
					<a href="seminar-information.php?campaign_id=<?=isset($zoho['campaign_id'])?$zoho['campaign_id']:'';?>" class="right"><i class="icon-plus"></i> Add Seminar</a>
				<? endif; ?>
			</div>
		</div>
		<? if (!empty($seminar)): ?>
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
						<a href="controllers/delete-seminar.php?seminar_id=<?=$seminar['id'];?>&zoho_id=<?=$campaign['zoho_id'];?>&id=<?=$campaign['id'];?>&campaign_id=<?=$zoho['campaign_id'];?>" class="btn btn-danger">Delete</a>
					</div>
				</div>
			<? endforeach; ?>
		<? endif; ?>
		<!-- Hidden Confirm Modal -->
		<div id="deleteCampaign" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteCampaign" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h2 id="deleteCampaign">Are You Sure?</h2>
			</div>
			<div class="modal-body">
				<p>You are about to delete the information for the campaign: <?=isset($zoho['name'])?$zoho['name']:'';?>. This action will also delete any seminar information linked to this campaign and cannot be undone.</p>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<a href="controllers/delete-campaign.php?id=<?=isset($campaign['id'])?$campaign['id']:'';?>&zoho_id=<?=isset($zoho['zoho_id'])?$zoho['zoho_id']:'';?>&campaign_id=<?=isset($zoho['campaign_id'])?$zoho['campaign_id']:'';?>" class="btn btn-danger">Delete</a>
			</div>
		</div>
		<div class="overlay">
			<img class="ajax-loader" src="img/ajax-loader-white.gif" alt="ajax-loader">
		</div>
		<script>
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
		</script>
		<script src="js/main.js"></script>
	</body>
</html>	