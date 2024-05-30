@php


$discount = $advantages["discount"]??0;
$regular_price = $advantages["price"]??0;
$shipping_cost = $advantages["shipping_cost"]??0;
$connection_cost = $advantages["connection_cost"]??0;
$discounted_price = $advantages["discounted_price"]??0;
$discounted_till = $advantages["discounted_till"]??0;
$contract_length = $advantages["contract_length"]??0;
$one_off_cost = $connection_cost + $shipping_cost - $discount;
$total_price = ($regular_price * ($contract_length - $discounted_till)) + ($discounted_price * $discounted_till) + $one_off_cost;
$total_discount = $discount + ($regular_price * $contract_length) - $total_price;

@endphp
<div class="container">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-between align-items-center">
            Overview of Request
            
              <i class="fas fa-caret-down"></i>
            
          </h5>
          <div class="" id="currentCollapse">
            <ul class="list-unstyled">
              <?php
              $currentItems = array(
                  array(
                        'label' => $userRequest->service->title,
                        'value' => '€'.$regular_price
                    ),
                  array(
                        'label' => $discounted_till .' Months for €' . $discounted_price,
                        'value' => ''
                    ),
                  
              );
              

              foreach ($currentItems as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div> ' . $item['value'] . '</div>';
                  echo '</li>';
              }
              ?>
            </ul>
          </div>
          <hr>
          <h5 class="card-title d-flex justify-content-between align-items-center">
            Monthly Cost
            <a class="collapsed" data-toggle="collapse" href="#gasCollapse" aria-expanded="false" aria-controls="gasCollapse">€ {{$regular_price}}
              <i class="fas fa-caret-down"></i>            
            </a>
          </h5>
          
          <div class="collapse" id="gasCollapse">
            <ul class="list-unstyled">
              <?php
              $discountBrk = array(
                  array(
                      'label' => 'First ' . $discounted_till. ' months',
                      'value' => '€' . $discounted_price
                  )
                 
              );
              
              foreach ($discountBrk as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div>' . $item['value'] . '</div>';
                  echo '</li>';
              }
            
              ?>
            </ul>
          </div>
          <hr>
          <h5 class="card-title d-flex justify-content-between align-items-center">
            One-off costs
            <a class="collapsed" data-toggle="collapse" href="#otherCollapse" aria-expanded="false" aria-controls="gasCollapse">€ {{$one_off_cost}}
              <i class="fas fa-caret-down"></i>         
          
            </a>
          </h5>
          <div class="collapse" id="otherCollapse">
            <ul class="list-unstyled">
              <?php
              $others = array(
                  array(
                      'label' => 'Connection Cost ',
                      'value' => '<strike>€' . $connection_cost . '</strike>'
                  ),
                  array(
                      'label' => 'One-time €' . $discount. ' Discount',
                      'value' => 'Free'
                  ),
                  array(
                      'label' => 'Shipping and handling charges ',
                      'value' =>  '€'. $shipping_cost
                  )
                                   
              );
              
              foreach ($others as $item) {
                  echo '<li class="d-flex justify-content-between">';
                  echo '<div>' . $item['label'] . '</div>';
                  echo '<div> '.  $item['value'] . '</div>';
                  echo '</li>';
              }
            
              ?>
            </ul>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col">
      <div class="card">
        <div class="card-body">
          
          
          <div class="row">
            <div class="col-6">
              <p class="mb-0"><b>Total Per Year</b></p>
            </div>
            <div class="col-6 text-right">
              <p class="mb-0"><b>€ {{$total_price}}</b></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>