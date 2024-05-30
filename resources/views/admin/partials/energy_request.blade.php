@php

if($userRequest->no_gas == null){
$gasTotal =  (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null) * 
 (isset($advantages["gas_price"]) ? $advantages["gas_price"] : null);
$networkCostGas = (isset($advantages["network_cost_gas"]) ? $advantages["network_cost_gas"] : null);
$deliveryCostGas = (isset($advantages["delivery_cost_gas"]) ? $advantages["delivery_cost_gas"] : null);
$gasTotal = $gasTotal + $deliveryCostGas + $networkCostGas + ((isset($advantages["government_levies_gas"]) ? $advantages["government_levies_gas"] : null) * (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null));
}


$normalElectricCost = (isset($advantages["normal_electric_consume"]) ? $advantages["normal_electric_consume"] : null) * (isset($advantages["normal_electric_price"]) ? $advantages["normal_electric_price"] : null);
$peakElectricCost = (isset($advantages["peak_electric_consume"]) ? $advantages["peak_electric_consume"] : null) * (isset($advantages["peak_electric_price"]) ? $advantages["peak_electric_price"] : null);

 

$feedInNormalCost = 0;
$feedInPeakCost = 0;
$feedInCost = 0;
if($userRequest->solar_panels && $userRequest->solar_panels > 0){
  $feedInPeakCost = $advantages["feed_in_peak"]*$advantages["feed_in_peak_price"];
$feedInNormalCost = $advantages["feed_in_normal"]*$advantages["feed_in_normal_price"];
$feedInCost = $feedInPeakCost + $feedInNormalCost;

$totalFeedIn = $advantages["feed_in_peak"] + $advantages["feed_in_normal"];
if($userRequest->feedInCost){
$feedInCostRange = json_decode($userRequest->feedInCost->feed_in_cost, true);
$feedInCostValue = array_filter($feedInCostRange, function($item) use ($totalFeedIn) {
    return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
});
}
if (!empty($feedInCostValue)) {
    $feedInCostValue = reset($feedInCostValue);
    
}
}
$feedInCostRangeValue = $feedInCostValue['amount']??0;
$reductionCostElectric = (isset($advantages["reduction_of_energy_tax"]) ? $advantages["reduction_of_energy_tax"] : null);
$deliveryCostElectric = (isset($advantages["delivery_cost_electric"]) ? $advantages["delivery_cost_electric"] : null);
$networkCostElectric = (isset($advantages["network_cost_electric"]) ? $advantages["network_cost_electric"] : null);
$electricityCost = $normalElectricCost + $peakElectricCost + $deliveryCostElectric + $networkCostElectric + $feedInCostRangeValue - $feedInCost - $reductionCostElectric;

$deliveryCost = $deliveryCostGas + $deliveryCostElectric;


$networkCost = $networkCostGas + $networkCostElectric;
$totalCost = $gasTotal + $electricityCost;
$reductionOfEnergyTax = (isset($advantages["reduction_of_energy_tax"]) ? $advantages["reduction_of_energy_tax"] : null);
@endphp
<div class="container">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-between align-items-center">
            Current
            <a class="collapsed" data-toggle="collapse" href="#currentCollapse" aria-expanded="false" aria-controls="currentCollapse">
              <i class="fas fa-caret-down"></i>
            </a>
          </h5>
          <div class="collapse" id="currentCollapse">
            <ul class="list-unstyled">
              <?php
              
              
              $currentItems = array(
                  array(
                        'label' => 'Normal electric cost (' . (isset($advantages["normal_electric_consume"]) ? $advantages["normal_electric_consume"] : null) . 'kWh x €' . (isset($advantages["normal_electric_price"]) ? $advantages["normal_electric_price"] : null) . '/kWh)',
                        'value' => $normalElectricCost
                    ),

                  array(
                        'label' => 'Off peak electric cost (' . 
                        (isset($advantages["peak_electric_consume"]) ? $advantages["peak_electric_consume"] : null) . 'kWh x €' . 
                        (isset($advantages["peak_electric_price"]) ? $advantages["peak_electric_price"] : null) . '/kWh)',
                        'value' => $peakElectricCost
                    ),
                  array(
                      'label' => 'Normal return delivery (-' . (isset($advantages["feed_in_normal"]) ? $advantages["feed_in_normal"] : null) . 'kWh x €' . (isset($advantages["feed_in_normal_price"]) ? $advantages["feed_in_normal_price"] : null) . '/kWh)',
                      'value' => -$feedInNormalCost
                  ),
                  array(
                      'label' => 'Off-peak return delivery (-' . (isset($advantages["feed_in_peak"]) ? $advantages["feed_in_peak"] : null) . 'kWh x €' . (isset($advantages["feed_in_peak_price"]) ? $advantages["feed_in_peak_price"] : null) . '/kWh)',
                      'value' => -$feedInPeakCost
                  ),
                  array(
                      'label' => 'Fixed delivery cost electric',
                      'value' => $deliveryCostElectric
                  ),
                  array(
                      'label' => 'Feed-in costs (scale (' . (!empty($feedInCostValue)?$feedInCostValue['from_range'] .'-'. $feedInCostValue['to_range']:'') .  ') kWh)',
                      'value' => !empty($feedInCostValue)?$feedInCostValue['amount']:''
                  ),
                  array(
                      'label' => 'Network management costs electric',
                      'value' => $networkCostElectric
                  ),
                  array(
                      'label' => 'Reduction of energy tax',
                      'value' => -$reductionOfEnergyTax
                  ),
                  array(
                      'label' => '<b>Electric Total</b>',
                      'value' => '<b>' . $electricityCost . '</b>'
                  ),
              );
              if(!$feedInNormalCost){

              }
              if(!$feedInPeakCost){

              }

              foreach ($currentItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>€ ' . $item['value'] . '</div>';
                  echo '</li>';
              }
              ?>
            </ul>
          </div>
          <hr>
          <h5 class="card-title d-flex justify-content-between align-items-center">
            Gas
            <a class="collapsed" data-toggle="collapse" href="#gasCollapse" aria-expanded="false" aria-controls="gasCollapse">
              <i class="fas fa-caret-down"></i>
            </a>
          </h5>
          @if($userRequest->no_gas == null)
          <div class="collapse" id="gasCollapse">
            <ul class="list-unstyled">
              <?php
              $gasItems = array(
                  array(
                      'label' => 'Gas consumption (' . (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null) . 'm3 * €' . (isset($advantages["gas_price"]) ? $advantages["gas_price"] : null) . '/m3)',
                      'value' => (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null) * ((isset($advantages["gas_price"]) ? $advantages["gas_price"] : null))
                  ),
                  array(
                      'label' => 'Government levies* ' . (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null) . 'm3 x €' . (isset($advantages["government_levies_gas"]) ? $advantages["government_levies_gas"] : null) . '/m3)',
                      'value' => (isset($advantages["government_levies_gas"]) ? $advantages["government_levies_gas"] : null) * (isset($advantages["gas_consume"]) ? $advantages["gas_consume"] : null)
                  ),
                  array(
                      'label' => 'Fixed delivery costs gas',
                      'value' => (isset($advantages["delivery_cost_gas"]) ? $advantages["delivery_cost_gas"] : null)
                  ),
                  array(
                      'label' => 'Network management costs',
                      'value' => (isset($advantages["network_cost_gas"]) ? $advantages["network_cost_gas"] : null)
                  ),
                  array(
                      'label' => '<b>Gas Total</b>',
                      'value' => '<b>' . $gasTotal . '</b>'
                  ),
              );
              if($userRequest->no_gas == null){
              foreach ($gasItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>€ ' . $item['value'] . '</div>';
                  echo '</li>';
              }
            }
              ?>
            </ul>
          </div>
          <hr>
          @endif
          <div class="row">
            <div class="col-6">
              <p class="mb-0"><b>Subtotal electricity @if($userRequest->no_gas == null) + gas @endif</p></b>
            </div>
            <div class="col-6 text-right">
              <p class="mb-0"><b>€ <?php echo $totalCost; ?></b></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cashback</h5>
          <div class="row">
            <div class="col-6">
              <p class="mb-0">&nbsp;</p>
            </div>
            <div class="col-6 text-right">
              <p class="mb-0">€ -{{$userRequest->cashback}}</p>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <p class="mb-0"><b>Total Per Year</b></p>
            </div>
            <div class="col-6 text-right">
              <p class="mb-0"><b>€ {{$totalCost - $userRequest->cashback}}</b></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>