<?php

require '../includes/connection.inc.php';
require '../includes/autoload.php';

$calculate 	= new Totals();
$update 	= new UpdateCampaign($_GET['id'], $_GET['campaign_id']);

if (isset($_GET['seminar_id']) && $_GET['seminar_id'] !== NULL)
{
	$stmt = $conn->prepare("DELETE FROM zoho.seminars WHERE id = :id");
	$stmt->bindParam(':id', $_GET['seminar_id']);
	$stmt->execute();

	$stmt = $conn->prepare("SELECT c.id,
								   SUM(attendees) AS attendees,
								   SUM(num_under_threshold) AS num_under_threshold,
								   SUM(pe_sold) AS pe_sold,
								   SUM(self_cert_sold) AS self_cert_sold,
								   SUM(pe_containment_sold) AS pe_containment_sold,
								   SUM(self_cert_containment_sold) AS self_cert_containment_sold,
								   SUM(spill_kits_sold) AS spill_kits_sold,
								   SUM(inspection_sold) AS inspection_sold,
								   pe_price,
								   self_cert_price,
								   pe_containment_price,
								   self_cert_containment_price,
								   spill_kit_price,
								   inspection_price
							FROM seminars AS s JOIN campaigns AS c ON s.campaign_id = c.id WHERE c.id = :id");
	$stmt->execute(array(':id' => $_GET['id']));
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$totals = $calculate->seminarRevenue($row);

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
						       spill_kit_amount = :spill_kit_amount,
						       pe_amount = :pe_amount,
						       self_cert_amount = :self_cert_amount,
						       pe_containment_amount = :pe_containment_amount,
						       self_cert_containment_amount = :self_cert_containment_amount
						   WHERE campaign_id = :campaign_id");
	
	$stmt->execute(array(
		':attendees' 					=> $attendees,
		':num_under_threshold' 			=> $num_under_threshold,
		':pe_sold' 						=> $pe_sold,
		':self_cert_sold' 				=> $self_cert_sold,
		':pe_containment_sold' 			=> $pe_containment_sold,
		':self_cert_containment_sold' 	=> $self_cert_containment_sold,
		':spill_kits_sold' 				=> $spill_kits_sold,
		':campaign_id' 					=> $id,
		':spill_kit_amount' 			=> $spill_kit_amount,
		':pe_amount' 					=> $pe_amount,
		':self_cert_amount' 			=> $self_cert_amount,
		':pe_containment_amount' 		=> $pe_containment_amount,
		':self_cert_containment_amount' => $self_cert_containment_amount
	));
}

$update->updateCampaign($_GET['id'], $_GET['campaign_id']);

ob_start();
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/campaign-information.php?id=" . $_GET['zoho_id']);
ob_end_clean();