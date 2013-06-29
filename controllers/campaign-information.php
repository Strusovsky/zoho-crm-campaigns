<?php

if ($_POST)
{
	require '../includes/connection.inc.php';
	require '../includes/autoload.php';

	extract($_POST);

	$totals = new Totals();

	$seminar_total 	 = $totals->seminarCost($_POST);
	$travel_total 	 = $totals->travelCost($_POST);
	$marketing_total = $totals->marketingCost($_POST);

	if (isset($id) && $id != NULL)
	{
		$update = new UpdateCampaign($id, $campaign_id);

		$stmt = $conn->prepare("UPDATE zoho.campaigns 
								SET zoho_id = :zoho_id,
									campaign_id = :campaign_id,
									status = :status,
									name = :name,
									coop_commission = :coop_commission,
									days_on_road = :days_on_road,
									num_employees = :num_employees,
									hotel_cost = :hotel_cost,
									catering_cost = :catering_cost,
									location_rental = :location_rental,
									est_vehicle_miles = :est_vehicle_miles,
									vehicle_cost = :vehicle_cost,
									plane_ticket_cost = :plane_ticket_cost,
									packets_and_pens = :packets_and_pens,
									flyers = :flyers,
									radio = :radio,
									newspaper = :newspaper,
									direct_mail = :direct_mail,
									automated_calls = :automated_calls,
									self_cert_price = :self_cert_price,
									pe_price = :pe_price,
									self_cert_containment_price = :self_cert_containment_price,
									pe_containment_price = :pe_containment_price,
									inspection_price = :inspection_price,
									spill_kit_price = :spill_kit_price
								WHERE id = :id");

		$stmt->execute(array(
			':zoho_id' 						=> $zoho_id,
			':campaign_id' 					=> $campaign_id,
			':status' 						=> $status,
			':name' 						=> $name,
			':coop_commission'				=> $coop_commission,
			':days_on_road' 				=> $days,
			':num_employees' 				=> $employees,
			':hotel_cost' 					=> $hotel,
			':catering_cost' 				=> $catering,
			':location_rental' 				=> $rental,
			':est_vehicle_miles' 			=> str_replace(",", "", $miles),
			':vehicle_cost' 				=> $vehicle,
			':plane_ticket_cost' 			=> $plane,
			':packets_and_pens' 			=> str_replace(",", "", $packets),
			':flyers' 						=> str_replace(",", "", $flyers),
			':radio'						=> $radio,
			':newspaper'					=> $newspaper,
			':direct_mail' 					=> $mail,
			':automated_calls' 				=> $calls,
			':self_cert_price' 				=> $self_cert_price,
			':pe_price' 					=> $pe_price,
			':self_cert_containment_price'	=> $self_cert_containment_price,
			':pe_containment_price'			=> $pe_containment_price,
			':inspection_price' 			=> $inspection_price,
			':spill_kit_price' 				=> $spill_kit_price,
			':id' 							=> $id
		));

		$stmt = $conn->prepare("UPDATE zoho.totals 
								SET seminar_total = :seminar_total,
									travel_total = :travel_total,
									marketing_total = :marketing_total
								WHERE campaign_id = :campaign_id");

		$stmt->execute(array(':campaign_id' => $id, ':seminar_total' => $seminar_total, ':travel_total' => $travel_total, ':marketing_total' => $marketing_total));

		$update->updateCampaign($id, $campaign_id);

		echo json_encode($_POST);
	}
	else
	{
		$stmt = $conn->prepare("INSERT INTO zoho.campaigns (
									zoho_id,
									campaign_id,
									status,
									name,
									coop_commission,
									days_on_road,
									num_employees,
									hotel_cost,
									catering_cost,
									location_rental,
									est_vehicle_miles,
									vehicle_cost,
									plane_ticket_cost,
									packets_and_pens,
									flyers,
									radio,
									newspaper,
									direct_mail,
									automated_calls,
									self_cert_price,
									pe_price,
									self_cert_containment_price,
									pe_containment_price,
									inspection_price,
									spill_kit_price
								)
								VALUES(
									:zoho_id,
									:campaign_id,
									:status,
									:name,
									:coop_commission,
									:days_on_road,
									:num_employees,
									:hotel_cost,
									:catering_cost,
									:location_rental,
									:est_vehicle_miles,
									:vehicle_cost,
									:plane_ticket_cost,
									:packets_and_pens,
									:flyers,
									:radio,
									:newspaper,
									:direct_mail,
									:automated_calls,
									:self_cert_price,
									:pe_price,
									:self_cert_containment_price,
									:pe_containment_price,
									:inspection_price,
									:spill_kit_price
								)");

		$stmt->execute(array(
			':zoho_id' 						=> $zoho_id,
			':campaign_id' 					=> $campaign_id,
			':status' 						=> $status,
			':name' 						=> $name,
			':coop_commission'				=> $coop_commission,
			':days_on_road' 				=> $days,
			':num_employees' 				=> $employees,
			':hotel_cost' 					=> $hotel,
			':catering_cost' 				=> $catering,
			':location_rental' 				=> $rental,
			':est_vehicle_miles' 			=> str_replace(",", "", $miles),
			':vehicle_cost' 				=> $vehicle,
			':plane_ticket_cost' 			=> $plane,
			':packets_and_pens' 			=> str_replace(",", "", $packets),
			':flyers' 						=> str_replace(",", "", $flyers),
			':radio'						=> $radio,
			':newspaper'					=> $newspaper,
			':direct_mail' 					=> $mail,
			':automated_calls' 				=> $calls,
			':self_cert_price' 				=> $self_cert_price,
			':pe_price' 					=> $pe_price,
			':self_cert_containment_price'	=> $self_cert_containment_price,
			':pe_containment_price'			=> $pe_containment_price,
			':inspection_price' 			=> $inspection_price,
			':spill_kit_price' 				=> $spill_kit_price			
		));

		$insert_id = $conn->lastInsertId();
		
		$stmt = $conn->prepare("INSERT INTO zoho.totals (campaign_id, seminar_total, travel_total, marketing_total)
								VALUES (:campaign_id, :seminar_total, :travel_total, :marketing_total)");

		$stmt->execute(array(':campaign_id' => $insert_id, ':seminar_total' => $seminar_total, ':travel_total' => $travel_total, ':marketing_total' => $marketing_total));

		$update = new UpdateCampaign($insert_id, $campaign_id);
		$update->updateCampaign($insert_id, $campaign_id);

		echo json_encode($_POST);
	}
}