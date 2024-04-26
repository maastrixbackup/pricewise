@php
//$advantages = array(
    //"gas_consume" => 50,
    //"gas_price" => 100,
    //"delivery_cost_gas" => 10,
    //"delivery_cost_electric" => 10,
    //"normal_electric_price" => 10,
    //"peak_electric_price" => 10,
    //"normal_electric_consume" => 100,
    //"peak_electric_consume" => 100,
    //"feed_in_peak" => 10,
    //"feed_in_normal" => 100,
    //"network_cost_gas" => 10,
    //"network_cost_electric" => 10,
    //"no_of_person" => 5,
    //"mechanic_charge" => 50,
    //"government_levies_gas" => 120,
    //"government_levies_electric" => 120,
    //"reduction_of_energy_tax" => 110
//);

$normalElectricLabel = 'Normal Electric Cost';
$offPeakElectricLabel = 'Off Peak Electric Cost';
if($userRequest->no_gas == null){
$gasTotal = ($advantages["gas_consume"] * $advantages["gas_price"]);
$networkCostGas = $advantages["network_cost_gas"];
$deliveryCostGas = $advantages["delivery_cost_gas"];
$gasTotal = $gasTotal + $deliveryCostGas + $networkCostGas + ($advantages["government_levies_gas"]*$advantages["gas_consume"]/100);
}

$normalElectricCost = $advantages["normal_electric_consume"] * $advantages["normal_electric_price"];
$peakElectricCost = $advantages["peak_electric_consume"] * $advantages["peak_electric_price"];
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
if($userRequest->solar_panels && $userRequest->solar_panels > 0){
  //$normalElectricCost = ($advantages["normal_electric_consume"] - $advantages["normal_electric_consume"]) * $advantages["normal_electric_price"];
  //$normalElectricCost = $advantages["feed_in_peak"] + $advantages["feed_in_normal"] - $advantages["normal_electric_consume"] + $advantages["peak_electric_consume"];
}

$reductionCostElectric = $advantages["reduction_of_energy_tax"];
$deliveryCostElectric = $advantages["delivery_cost_electric"];
$networkCostElectric = $advantages["network_cost_electric"];
$electricityCost = $normalElectricCost + $peakElectricCost + $deliveryCostElectric + $networkCostElectric + $feedInCostValue['amount'] - $feedInPeakCost - $feedInNormalCost - $reductionCostElectric;

$deliveryCost = $deliveryCostGas + $deliveryCostElectric;


$networkCost = $networkCostGas + $networkCostElectric;
$totalCost = $gasTotal + $electricityCost;
$reductionOfEnergyTax = $advantages["reduction_of_energy_tax"];
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
                        'label' => 'Normal electric cost (' . $advantages["normal_electric_consume"] . 'kWh x €' . $advantages["normal_electric_price"] . '/kWh)',
                        'value' => $normalElectricCost
                    ),
                  array(
                        'label' => 'Off peak electric cost (' . $advantages["peak_electric_consume"] . 'kWh x €' . $advantages["peak_electric_price"] . '/kWh)',
                        'value' => $peakElectricCost
                    ),
                  array(
                      'label' => 'Normal return delivery (-' . $advantages["feed_in_normal"] . 'kWh x €' . $advantages["feed_in_normal_price"] . '/kWh)',
                      'value' => -$feedInNormalCost
                  ),
                  array(
                      'label' => 'Off-peak return delivery (-' . $advantages["feed_in_peak"] . 'kWh x €' . $advantages["feed_in_peak_price"] . '/kWh)',
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
                      'value' => $electricityCost
                  ),
              );

              foreach ($currentItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>€ ' . number_format($item['value'], 2) . '</div>';
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
                      'label' => 'Gas consumption (' . $advantages["gas_consume"] . 'm3 * €' . number_format($advantages["gas_price"] / 100, 2) . '/m3)',
                      'value' => $advantages["gas_consume"] * ($advantages["gas_price"] / 100)
                  ),
                  array(
                      'label' => 'Government levies* ' . $advantages["gas_consume"] . 'm3 x €' . number_format($advantages["government_levies_gas"] / 100, 2) . '/m3)',
                      'value' => $advantages["government_levies_gas"]
                  ),
                  array(
                      'label' => 'Fixed delivery costs gas',
                      'value' => $advantages["delivery_cost_gas"]
                  ),
                  array(
                      'label' => 'Network management costs',
                      'value' => $advantages["network_cost_gas"]
                  ),
                  array(
                      'label' => '<b>Gas Total</b>',
                      'value' => $gasTotal
                  ),
              );
              if($userRequest->no_gas == null){
              foreach ($gasItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>€ ' . number_format($item['value'], 2) . '</div>';
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
              <p class="mb-0">Subtotal electricity @if($userRequest->no_gas == null) + gas @endif</p>
            </div>
            <div class="col-6 text-right">
              <p class="mb-0">€ <?php echo number_format($totalCost, 2); ?></p>
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
              <p class="mb-0">€ -180.00</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>