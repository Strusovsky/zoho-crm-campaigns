<?php
	require 'includes/connection.inc.php';

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
		
		$stmt = $conn->prepare("SELECT seminars.name, seminars.id, city, state, date, street, phone, coop_contact, coop_phone FROM seminars INNER JOIN campaigns ON seminars.campaign_id = campaigns.id WHERE campaigns.id = :campaign_id");
		$stmt->execute(array(':campaign_id' => $campaign['id']));
		$seminars = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Campaign: Seminars Results</title>
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
					&nbsp;&nbsp;
					<a href="#deleteModal" role="button" data-toggle="modal"><i class="icon-trash"></i> Delete</a>
				</span>				
			</strong>
		</h4>
		<div class="main-content clearfix">
			<form method="POST" action="" id="form">
				<input type="hidden" name="campaign_id" value="<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>">
				<input type="hidden" name="id" value="<?=isset($campaign['id'])?$campaign['id']:'';?>">
				<input type="hidden" name="name" value="<?=isset($campaign['name'])?$campaign['name']:'';?>">
				<input type="hidden" name="seminar_id" value="<?=isset($seminar['id'])?$seminar['id']:'';?>">
				<input type="hidden" name="datetime" id="datetime">
				<div class="span6">
					<h3>Seminar Results</h3>
					<div class="row">
						<div class="span6">
							<div class="row">
								<div class="span3">		
									<div id="attendees" class="control-group error">
										<label for="attendees" class="control-label">Attendees</label>
										<div class="controls">
											<input type="text" name="attendees" maxlength="6" data-input="number" value="<?=isset($seminar['attendees'])?$seminar['attendees']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="num_under_threshold" class="control-group error">
										<label for="num_under_threshold" class="control-label"># Under Threshold</label>
										<div class="controls">
											<input type="text" name="num_under_threshold" maxlength="6" data-input="number" value="<?=isset($seminar['num_under_threshold'])?$seminar['num_under_threshold']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="pe_sold" class="control-group error">
										<label for="pe_sold" class="control-label">PE's Sold</label>
										<div class="controls">
											<input type="text" name="pe_sold" maxlength="6" data-input="number" value="<?=isset($seminar['pe_sold'])?$seminar['pe_sold']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="self_cert_sold" class="control-group error">
										<label for="self_cert_sold" class="control-label">Self Cert Sold</label>
										<div class="controls">
											<input type="text" name="self_cert_sold" maxlength="6" data-input="number" value="<?=isset($seminar['self_cert_sold'])?$seminar['self_cert_sold']:'';?>">
										</div>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="span3">
									<div id="pe_containment_sold" class="control-group error">
										<label for="pe_containment_sold" class="control-label">PE Containment Sold</label>
										<div class="controls">
											<input type="text" name="pe_containment_sold" maxlength="6" data-input="number" value="<?=isset($seminar['pe_containment_sold'])?$seminar['pe_containment_sold']:'';?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="self_cert_containment_sold" class="control-group error">
										<label for="self_cert_containment_sold" class="control-label">Self Cert Containment Sold</label>
										<div class="controls">
											<input type="text" name="self_cert_containment_sold" maxlength="6" data-input="number" value="<?=isset($seminar['self_cert_containment_sold'])?$seminar['self_cert_containment_sold']:'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span3">						
									<div id="spill_kits_sold" class="control-group error">
										<label for="spill_kits_sold" class="control-label">Spill Kits Sold</label>
										<div class="controls">
											<input type="text" name="spill_kits_sold" maxlength="6" data-input="number" value="<?=isset($seminar['spill_kits_sold'])?$seminar['spill_kits_sold']:'';?>">	
										</div>
									</div>
								</div>
								<div class="span3">
									<div id="inspection_sold" class="control-group error">
										<label for="inspection_sold" class="control-label">Inspections Sold</label>
										<div class="controls">
											<input type="text" name="inspection_sold" maxlength="6" data-input="number" value="<?=isset($seminar['inspection_sold'])?$seminar['inspection_sold']:'';?>">	
										</div>
									</div>
								</div>
							</div>
							<br>
							<br>
							<br>
							<br>
							<div class="buttons">
								<button type="button" name="update_seminar_results" id="update_seminar_results" value="update_seminar_results" class="btn">Save</button>
								<button type="button" name="insert_results_add_seminar" id="insert_results_add_seminar" value="insert_results_add_seminar" class="btn end">Save &amp; Add Seminar</button>
							</div>							
						</div>
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
							<td><?=date('M jS, Y',strtotime($seminar['date']));?></td>
							<td><?=date('g:i A',strtotime($seminar['date']));?></td>
							<td>
								<a href="seminar-information.php?id=<?=$seminar['id'];?>&campaign_id=<?=$campaign['campaign_id'];?>" class="seminar-info" data-toggle="tooltip"><i class="icon-list-alt"></i></a>
								&nbsp;
								<a href="seminar-results.php?id=<?=$seminar['id'];?>&campaign_id=<?=$campaign['campaign_id'];?>" class="seminar-results" data-toggle="tooltip"><i class="icon-edit"></i></a>
								&nbsp;
								<a href="#deleteModal<?=$seminar['id'];?>" role="button" data-toggle="modal"><i class="icon-trash"></i></a>									
							</td>
						</tr>
						<? endforeach; ?>	
					</tbody>
				</table>
				<a href="seminar-information.php?campaign_id=<?=isset($campaign['campaign_id'])?$campaign['campaign_id']:'';?>" class="right"><i class="icon-plus"></i> Add Seminar</a>
				<? endif; ?>
			</div>
		</div>
		<? if (isset($seminars)): ?>
			<? foreach ($seminars as $seminar): ?>
				<!-- Hidden Confirm Modal -->
				<div id="deleteModal<?=$seminar['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal<?=$seminar['id']; ?>" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						<h2 id="deleteModal<?=$seminar['id']; ?>">Are You Sure?</h2>
					</div>
					<div class="modal-body">
						<p>You are about to delete the seminar information for the campaign: <?=isset($campaign['name'])?$campaign['name']:'';?>. This action cannot be undone.</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
						<a href="controllers/delete-seminar.php?seminar_id=<?=$seminar['id']; ?>&zoho_id=<?=$campaign['zoho_id']; ?>&id=<?=$campaign['id']; ?>&campaign_id=<?=$campaign['campaign_id'];?>" class="btn btn-danger">Delete</a>
					</div>
				</div>		
			<? endforeach; ?>
		<? endif; ?>
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
			$('.seminar-info').tooltip({
			    title: 'Edit Seminar Information'
			});

			$('.seminar-results').tooltip({
			    title: 'Edit Seminar Results'
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