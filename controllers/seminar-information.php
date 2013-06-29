<?php

if ($_POST)
{
	require '../includes/connection.inc.php';
	require '../includes/autoload.php';
	
	extract($_POST);

	$calculate = new Totals();
	$update = new UpdateCampaign($id, $campaign_id);

	$date = str_replace("/", "-", $datetime);

	$stmt = $conn->prepare("SELECT self_cert_price, pe_price, self_cert_containment_price, pe_containment_price, spill_kit_price, inspection_price
							FROM campaigns
							WHERE id = :id");
	$stmt->execute(array(':id' => $_POST['id']));
	$prices = $stmt->fetch(PDO::FETCH_ASSOC);

	$values = array_merge($_POST, $prices);

	$totals = $calculate->seminarRevenue($values);	

	if (isset($seminar_id) && $seminar_id != NULL)
	{
		$stmt = $conn->prepare("UPDATE zoho.seminars
								SET campaign_id = :campaign_id,
									`date` = :date,
									name = :name,
									contact = :contact,
									phone = :phone,
									street = :street,
									city = :city,
									state = :state,
									zip_code = :zip_code,
									county = :county,
									arrival_time = :arrival_time,
									arrival_confirm = :arrival_confirm,
									coop_contact = :coop_contact,
									coop_phone = :coop_phone,
									refreshments_offered = :refreshments_offered,
									refreshments_type = :refreshments_type,
									refreshments_payment = :refreshments_payment,
									radio_station = :radio_station,
									radio_contact = :radio_contact,
									radio_phone = :radio_phone,
									newspaper_name = :newspaper_name,
									newspaper_contact = :newspaper_contact,
									newspaper_phone = :newspaper_phone,
									advertising_cost = :advertising_cost,
									advertising_payment = :advertising_payment,
									projector_screen = :projector_screen,
									wifi = :wifi,
									cell_reception = :cell_reception,
									attendees = :attendees,
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
			':campaign_id' 					=> $id,
			':date'							=> $date,
			':name'							=> $name,
			':contact'						=> $contact,
			':phone' 						=> $phone,
			':street' 						=> $street,
			':city'			 				=> $city,
			':state' 						=> $state,
			':zip_code' 					=> $zip_code,
			':county'			 			=> $county,
			':arrival_time' 				=> $arrival_time,
			':arrival_confirm' 				=> $arrival_confirm,
			':coop_contact' 				=> $coop_contact,
			':coop_phone' 					=> $coop_phone,
			':refreshments_offered' 		=> $refreshments_offered,
			':refreshments_type' 			=> $refreshments_type,
			':refreshments_payment' 		=> $refreshments_payment,
			':radio_station' 				=> $radio_station,
			':radio_contact'				=> $radio_contact,
			':radio_phone' 					=> $radio_phone,
			':newspaper_name' 				=> $newspaper_name,
			':newspaper_contact'			=> $newspaper_contact,
			':newspaper_phone' 				=> $newspaper_phone,
			':advertising_cost'				=> str_replace(",", "", $advertising_cost),
			':advertising_payment'			=> $advertising_payment,			
			':projector_screen' 			=> $projector_screen,
			':wifi'							=> $wifi,
			':cell_reception'				=> $cell_reception,
			':attendees' 					=> str_replace(",", "", $attendees),
			':num_under_threshold' 			=> str_replace(",", "", $num_under_threshold),
			':pe_sold'			 			=> str_replace(",", "", $pe_sold),
			':self_cert_sold' 				=> str_replace(",", "", $self_cert_sold),
			':pe_containment_sold' 			=> str_replace(",", "", $pe_containment_sold),
			':self_cert_containment_sold' 	=> str_replace(",", "", $self_cert_containment_sold),
			':spill_kits_sold' 				=> str_replace(",", "", $spill_kits_sold),
			':inspection_sold' 				=> str_replace(",", "", $inspection_sold),
			':spill_kit_amount' 			=> str_replace(",", "", $totals['spill_kit_amount']),
			':pe_amount' 					=> str_replace(",", "", $totals['pe_amount']),
			':self_cert_amount' 			=> str_replace(",", "", $totals['self_cert_amount']),
			':pe_containment_amount' 		=> str_replace(",", "", $totals['pe_containment_amount']),
			':self_cert_containment_amount' => str_replace(",", "", $totals['self_cert_containment_amount']),
			':inspection_amount' 			=> str_replace(",", "", $totals['inspection_amount']),
			':total'						=> str_replace(",", "", $totals['total']),
			':id'							=> $seminar_id
		));

		echo json_encode($_POST);
	}
	else
	{
		$stmt = $conn->prepare("INSERT INTO zoho.seminars (
									campaign_id,
									`date`,
									name,
									contact,
									phone,
									street,
									city,
									state,
									zip_code,
									county,
									arrival_time,
									arrival_confirm,
									coop_contact,
									coop_phone,
									refreshments_offered,
									refreshments_type,
									refreshments_payment,
									radio_station,
									radio_contact,
									radio_phone,
									newspaper_name,
									newspaper_contact,
									newspaper_phone,
									advertising_cost,
									advertising_payment,
									projector_screen,
									wifi,
									cell_reception,
									attendees,
									num_under_threshold,
									pe_sold,
									self_cert_sold,
									pe_containment_sold,
									self_cert_containment_sold,
									spill_kits_sold,
									inspection_sold,
									spill_kit_amount,
									pe_amount,
									self_cert_amount,
									pe_containment_amount,
									self_cert_containment_amount,
									inspection_amount,
									total											
								)
								VALUES(
									:campaign_id,
									:date,
									:name,
									:contact,
									:phone,
									:street,
									:city,
									:state,
									:zip_code,
									:county,
									:arrival_time,
									:arrival_confirm,
									:coop_contact,
									:coop_phone,
									:refreshments_offered,
									:refreshments_type,
									:refreshments_payment,
									:radio_station,
									:radio_contact,
									:radio_phone,
									:newspaper_name,
									:newspaper_contact,
									:newspaper_phone,
									:advertising_cost,
									:advertising_payment,									
									:projector_screen,
									:wifi,
									:cell_reception,
									:attendees,
									:num_under_threshold,
									:pe_sold,
									:self_cert_sold,
									:pe_containment_sold,
									:self_cert_containment_sold,
									:spill_kits_sold,
									:inspection_sold,
									:spill_kit_amount,
									:pe_amount,
									:self_cert_amount,
									:pe_containment_amount,
									:self_cert_containment_amount,
									:inspection_amount,
									:total											
								)");


		$stmt->execute(array(
			':campaign_id' 					=> $id,
			':date'							=> $date,
			':name'							=> $name,
			':contact'						=> $contact,
			':phone' 						=> $phone,
			':street' 						=> $street,
			':city'			 				=> $city,
			':state' 						=> $state,
			':zip_code' 					=> $zip_code,
			':county'			 			=> $county,
			':arrival_time' 				=> $arrival_time,
			':arrival_confirm' 				=> $arrival_confirm,
			':coop_contact' 				=> $coop_contact,
			':coop_phone' 					=> $coop_phone,
			':refreshments_offered' 		=> $refreshments_offered,
			':refreshments_type' 			=> $refreshments_type,
			':refreshments_payment' 		=> $refreshments_payment,
			':radio_station' 				=> $radio_station,
			':radio_contact' 				=> $radio_contact,
			':radio_phone' 					=> $radio_phone,
			':newspaper_name' 				=> $newspaper_name,
			':newspaper_contact'			=> $newspaper_contact,
			':newspaper_phone' 				=> $newspaper_phone,
			':advertising_cost'				=> str_replace(",", "", $advertising_cost),
			':advertising_payment'			=> $advertising_payment,		
			':projector_screen' 			=> $projector_screen,
			':wifi'							=> $wifi,
			':cell_reception'				=> $cell_reception,
			':attendees' 					=> str_replace(",", "", $attendees),
			':num_under_threshold' 			=> str_replace(",", "", $num_under_threshold),
			':pe_sold'			 			=> str_replace(",", "", $pe_sold),
			':self_cert_sold' 				=> str_replace(",", "", $self_cert_sold),
			':pe_containment_sold' 			=> str_replace(",", "", $pe_containment_sold),
			':self_cert_containment_sold' 	=> str_replace(",", "", $self_cert_containment_sold),
			':spill_kits_sold' 				=> str_replace(",", "", $spill_kits_sold),
			':inspection_sold' 				=> str_replace(",", "", $inspection_sold),
			':spill_kit_amount' 			=> str_replace(",", "", $totals['spill_kit_amount']),
			':pe_amount' 					=> str_replace(",", "", $totals['pe_amount']),
			':self_cert_amount' 			=> str_replace(",", "", $totals['self_cert_amount']),
			':pe_containment_amount' 		=> str_replace(",", "", $totals['pe_containment_amount']),
			':self_cert_containment_amount' => str_replace(",", "", $totals['self_cert_containment_amount']),
			':inspection_amount' 			=> str_replace(",", "", $totals['inspection_amount']),
			':total'						=> str_replace(",", "", $totals['total']),
		));

		echo json_encode($_POST);
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