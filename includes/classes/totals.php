<?php

class Totals {

	public function travelCost($values)
	{
		extract($values);
		$total = ($plane + ((str_replace(",", "", $miles) / 20) * 3.5) + $hotel + (($employees * 40) * $days) + $vehicle);
		
		return $total;
	}

	public function seminarCost($values)
	{
		extract($values);
		$total = ($catering + $rental);

		return $total;
	}

	public function productCost($values)
	{
		extract($values);
		$total = ($pe_sold * 1219) + ($self_cert_sold * 1219) + ($self_cert_containment_sold * 3546) + ($pe_containment_sold * 5260);
		
		return $total;
	}	

	public function marketingCost($values)
	{
		extract($values);
		$total = ((str_replace(",", "", $packets) * 2) + (str_replace(",", "", $flyers) * 0.5) + $radio + $newspaper + ($mail * 1.1) + (($calls * 3) * 0.035));

		return $total;
	}

	public function seminarExpense($values)
	{
		extract($values);
		$plan_cost 	= $this->productCost($values);
		$total 		= $plan_cost + $seminar_total + $travel_total + $marketing_total;
		
		return $total;
	}

	public function seminarRevenue($values)
	{
		extract($values);
		$totals = array(
			'spill_kit_amount'				=> $spill_kit_price * $spill_kits_sold,
			'pe_amount'						=> $pe_price * $pe_sold,
			'self_cert_amount'				=> $self_cert_price * $self_cert_sold,
			'pe_containment_amount'			=> $pe_containment_price * $pe_containment_sold,
			'self_cert_containment_amount'	=> $self_cert_containment_price * $self_cert_containment_sold,
			'inspection_amount'				=> $inspection_price * $inspection_sold
		);
		
		$total 				= array_sum($totals);
		$totals['total'] 	= $total;

		return $totals;
	}

	public function coopRevenue($values)
	{
		extract($values);
		$total = ($pe_amount + $self_cert_amount) * ($coop_commission / 100);

		return $total;
	}

	public function salesRevenue($values)
	{
		extract($values);	
		$total = (($pe_amount + $self_cert_amount) + ($pe_containment_amount + $self_cert_containment_amount)) * 0.2;

		return $total;
	}
}