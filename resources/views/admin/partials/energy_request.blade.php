@php
$advantages = array(
    "gas_consume" => 50,
    "gas_price" => 100,
    "delivery_cost_gas" => 10,
    "delivery_cost_electric" => 10,
    "normal_electric_price" => 10,
    "peak_electric_price" => 10,
    "normal_electric_consume" => 100,
    "peak_electric_consume" => 100,
    "feed_in_peak" => 10,
    "feed_in_normal" => 100,
    "network_cost_gas" => 10,
    "network_cost_electric" => 10,
    "no_of_person" => 5,
    "mechanic_charge" => 50,
    "government_levies_gas" => 120,
    "government_levies_electric" => 120,
    "reduction_of_energy_tax" => 110
);

// Calculate total cost
$gasCost = ($advantages["gas_consume"] * $advantages["gas_price"]) + $advantages["government_levies_gas"];
$normalElectricCost = $advantages["normal_electric_consume"] * $advantages["normal_electric_price"];
$peakElectricCost = $advantages["peak_electric_consume"] * $advantages["peak_electric_price"];
$electricityCost = $normalElectricCost + $peakElectricCost;
$feedInPeakCost = $advantages["feed_in_peak"];
$feedInNormalCost = $advantages["feed_in_normal"];
$feedInCost = $feedInPeakCost + $feedInNormalCost;
$deliveryCostGas = $advantages["delivery_cost_gas"];
$deliveryCostElectric = $advantages["delivery_cost_electric"];
$deliveryCost = $deliveryCostGas + $deliveryCostElectric;
$networkCostGas = $advantages["network_cost_gas"];
$networkCostElectric = $advantages["network_cost_electric"];
$networkCost = $networkCostGas + $networkCostElectric;
$mechanicCharge = $advantages["mechanic_charge"];
$totalCost = $gasCost + $electricityCost - $feedInCost + $deliveryCost + $networkCost + $mechanicCharge;
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
                      'label' => 'Normal return supply (-1,300kWh x € 0.04500/kWh)',
                      'value' => -58.50
                  ),
                  array(
                      'label' => 'Off-peak feed-in (-' . $advantages["feed_in_normal"] . 'kWh x €0.04500/kWh)',
                      'value' => -($advantages["feed_in_normal"] * 0.0450)
                  ),
                  array(
                      'label' => 'Fixed delivery costs',
                      'value' => $deliveryCost
                  ),
                  array(
                      'label' => 'Feed-in costs (scale 1000 - 1999 kWh)',
                      'value' => $feedInCost
                  ),
                  array(
                      'label' => 'Network management costs',
                      'value' => $networkCost
                  ),
                  array(
                      'label' => 'Reduction of energy tax',
                      'value' => -$reductionOfEnergyTax
                  )
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
                      'label' => 'Fixed delivery costs',
                      'value' => $advantages["delivery_cost_gas"]
                  ),
                  array(
                      'label' => 'Network management costs',
                      'value' => $advantages["network_cost_gas"]
                  )
              );

              foreach ($gasItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>€ ' . number_format($item['value'], 2) . '</div>';
                  echo '</li>';
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