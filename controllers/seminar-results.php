<?php

if ($_POST)
{
	require '../includes/connection.inc.php';
	require '../includes/autoload.php';

	$calculate = new Totals();
	$update = new UpdateCampaign($_POST['id'], $_POST['campaign_id']);

	$stmt = $conn->prepare("SELECT self_cert_price, pe_price, self_cert_containment_price, pe_containment_price, spill_kit_price, inspection_price
							FROM campaigns
							WHERE id = :id");
	$stmt->execute(array(':id' => $_POST['id']));
	$prices = $stmt->fetch(PDO::FETCH_ASSOC);

	$values = array_merge($_POST, $prices);

	$totals = $calculate->seminarRevenue($values);

	if (isset($_POST['seminar_id']) && $_POST['seminar_id'] != NULL)
	{
		$stmt = $conn->prepare("UPDATE zoho.seminars
								SET attendees = :attendees,
									num_under_threshold = :num_under_threshold,
									pe_sold = :pe_sold,
									self_cert_sold = :self_cert_sold,
									pe_containment_sold = :pe_containment_sold,
									self_cert_containment_sold = :self_cert_containment_sold,
									spill_kits_sold = :spill_kits_sold,
									inspection_sold = :inspection_sold,
									spill_kit_amount = :spill_kit_amount,
									pe_amount = :pe_amount,
									self_cert_amount = :self_cert_amount,
									pe_containment_amount = :pe_containment_amount,
									self_cert_containment_amount = :self_cert_containment_amount,
									inspection_amount = :inspection_amount,
									total = :total
								WHERE id = :id");
		$stmt->execute(array(
			':attendees' 					=> $_POST['attendees'],
			':num_under_threshold' 			=> $_POST['num_under_threshold'],
			':pe_sold'			 			=> $_POST['pe_sold'],
			':self_cert_sold' 				=> $_POST['self_cert_sold'],
			':pe_containment_sold' 			=> $_POST['pe_containment_sold'],
			':self_cert_containment_sold' 	=> $_POST['self_cert_containment_sold'],
			':spill_kits_sold' 				=> $_POST['spill_kits_sold'],
			':inspection_sold' 				=> $_POST['inspection_sold'],
			':spill_kit_amount' 			=> $totals['spill_kit_amount'],
			':pe_amount' 					=> $totals['pe_amount'],
			':self_cert_amount' 			=> $totals['self_cert_amount'],
			':pe_containment_amount' 		=> $totals['pe_containment_amount'],
			':self_cert_containment_amount' => $totals['self_cert_containment_amount'],
			':inspection_amount' 			=> $totals['inspection_amount'],
			':total'						=> $totals['total'],
			':id'							=> $_POST['seminar_id']
		));

		echo json_encode($values);
	}
		
	$stmt = $conn->prepare("SELECT c.id,
								   SUM(attendees) AS attendees,
								   SUM(num_under_threshold) AS num_under_threshold,
								   SUM(pe_sold) AS pe_sold,
								   SUM(self_cert_sold) AS self_cert_sold,
								   SUM(pe_containment_sold) AS pe_containment_sold,
								   SUM(self_cert_containment_sold) AS self_cert_containment_sold,
								   SUM(spill_kits_sold) AS spill_kits_sold,
								   SUM(inspection_sold) AS inspection_sold
							FROM seminars AS s JOIN campaigns AS c ON s.campaign_id = c.id WHERE c.id = :id");
	$stmt->execute(array(':id' => $_POST['id']));
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$seminar_revenue = array_merge($row, $prices);
	$totals = $calculate->seminarRevenue($seminar_revenue);

	extract($row);
	extract($totals);

	$stmt = $conn->prepare("UPDATE totals 
						   SET attendees = :attendees, 
						       num_under_threshold = :num_under_threshold, 
						       pe_sold = :pe_sold, 
						       self_cert_sold = :self_cert_sold, 
						       pe_containment_sold = :pe_containment_sold, 
						       self_cert_containment_sold = :self_cert_containment_sold, 
						       spill_kits_sold = :spill_kits_sold,
						       inspection_sold = :inspection_sold,
						       spill_kit_amount = :spill_kit_amount,
						       pe_amount = :pe_amount,
						       self_cert_amount = :self_cert_amount,
						       pe_containment_amount = :pe_containment_amount,
						       self_cert_containment_amount = :self_cert_containment_amount,
						       inspection_amount = :inspection_amount
						   WHERE campaign_id = :campaign_id");
	
	$stmt->execute(array(
		':attendees' 					=> $attendees,
		':num_under_threshold' 			=> $num_under_threshold,
		':pe_sold' 						=> $pe_sold,
		':self_cert_sold' 				=> $self_cert_sold,
		':pe_containment_sold' 			=> $pe_containment_sold,
		':self_cert_containment_sold' 	=> $self_cert_containment_sold,
		':spill_kits_sold' 				=> $spill_kits_sold,
		':inspection_sold' 				=> $inspection_sold,
		':campaign_id' 					=> $id,
		':spill_kit_amount' 			=> $spill_kit_amount,
		':pe_amount' 					=> $pe_amount,
		':self_cert_amount' 			=> $self_cert_amount,
		':pe_containment_amount' 		=> $pe_containment_amount,
		':self_cert_containment_amount' => $self_cert_containment_amount,
		':inspection_amount' 			=> $inspection_amount
	));

	$update->updateCampaign($_POST['id'], $_POST['campaign_id']);

}