@extends('admin.layouts.app')
@section('title','Enerzise - Dashboard')
@section('content')
<style type="text/css">
  .content{
    width:100% !important;
  }
</style>
<div class="bg-image d-none" style="background-image: url('assets/media/various/bg_dashboard.jpg');">
          <div class="bg-primary-dark-op">
            <div class="content content-full">
              <div class="row my-3">
                <div class="col-md-6 d-md-flex align-items-md-center">
                  <div class="py-4 py-md-0 text-center text-md-start">
                    <h1 class="fs-2 text-white mb-2">Dashboard</h1>
                    <h2 class="fs-lg fw-normal text-white-75 mb-0">Welcome to your overview</h2>
                  </div>
                </div>
                <div class="col-md-6 d-md-flex align-items-md-center">
                  <div class="row w-100 text-center">
                    <div class="col-6 col-xl-4 offset-xl-4">
                      <p class="fs-3 fw-semibold text-white mb-0">
                        860
                      </p>
                      <p class="fs-sm fw-semibold text-white-75 text-uppercase mb-0">
                        <i class="far fa-chart-bar opacity-75 me-1"></i> Sales
                      </p>
                    </div>
                    <div class="col-6 col-xl-4">
                      <p class="fs-3 fw-semibold text-white mb-0">
                        $8.960
                      </p>
                      <p class="fs-sm fw-semibold text-white-75 text-uppercase mb-0">
                        <i class="far fa-chart-bar opacity-75 me-1"></i> Earnings
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END Hero -->

                <div class="content mb-4">
                   <section id="minimal-statistics">
                      <div class="row">
                        <div class="col-12 mt-3 mb-1">
                          <h4 class="text-uppercase mb-0">Dashboard</h4>
                          <p>Welcome to your overview</p>
                        </div>
                      </div>
                      <div class="row">



                        <div class="col-md-6 col-xl-3">
                          <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                              <div>
                                <!-- Sparkline Container -->
                                <span class="js-sparkline" data-type="line"
                                      data-points="[7,9,5,2,3,4,8,3]"
                                      data-width="90px"
                                      data-height="40px"
                                      data-line-color="#3c90df"
                                      data-fill-color="transparent"
                                      data-spot-color="transparent"
                                      data-min-spot-color="transparent"
                                      data-max-spot-color="transparent"
                                      data-highlight-spot-color="#3c90df"
                                      data-highlight-line-color="#3c90df"
                                      data-tooltip-suffix="New Customer"></span>
                              </div>
                              <div class="ms-3 text-end">
                                <p class="text-muted mb-0">
                                  New Customer
                                </p>
                                <p class="fs-3 fw-medium mb-0">
                                  2708
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-6 col-xl-3">
                          <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                              <div>
                                <!-- Sparkline Container -->
                                <span class="js-sparkline" data-type="line"
                                      data-points="[68,25,36,62,59,80,75,89]"
                                      data-width="90px"
                                      data-height="40px"
                                      data-line-color="#343a40"
                                      data-fill-color="transparent"
                                      data-spot-color="transparent"
                                      data-min-spot-color="transparent"
                                      data-max-spot-color="transparent"
                                      data-highlight-spot-color="#343a40"
                                      data-highlight-line-color="#343a40"
                                      data-tooltip-suffix="New Offers"></span>
                              </div>
                              <div class="ms-3 text-end">
                                <p class="text-muted mb-0">
                                  New Offers
                                </p>
                                <p class="fs-3 fw-medium mb-0">
                                  +156
                                </p>
                              </div>
                            </div>
                          </a>
                        </div> 

                        <div class="col-md-6 col-xl-3">
                          <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                              <div>
                                <!-- Sparkline Container -->
                                <span class="js-sparkline" data-type="line"
                                      data-points="[340,330,360,340,360,350,370,360]"
                                      data-width="90px"
                                      data-height="40px"
                                      data-line-color="#82b54b"
                                      data-fill-color="transparent"
                                      data-spot-color="transparent"
                                      data-min-spot-color="transparent"
                                      data-max-spot-color="transparent"
                                      data-highlight-spot-color="#82b54b"
                                      data-highlight-line-color="#82b54b"
                                      data-tooltip-suffix="Request Approval"></span>
                              </div>
                              <div class="ms-3 text-end">
                                <p class="text-muted mb-0">
                                  Request Approval
                                </p>
                                <p class="fs-3 fw-medium mb-0">
                                  4800
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-6 col-xl-3">
                          <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                              <div>
                                <!-- Sparkline Container -->
                               <span class="js-sparkline" data-type="line"
                                data-points="[340,230,360,140,260,350,370,460]"
                                data-width="100%"
                                data-height="40px"
                                data-fill-color="transparent"
                                data-spot-color="transparent"
                                data-min-spot-color="transparent"
                                data-max-spot-color="transparent"
                                data-tooltip-prefix="Total Visits"></span>
                              </div>
                              <div class="ms-3 text-end">
                                <p class="text-muted mb-0">
                                  Total Visits
                                </p>
                                <p class="fs-3 fw-medium mb-0">
                                  400023
                                </p>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="row">
                      <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                          <div class="block-content block-content-full d-flex align-items-center justify-content-between text-white  bg-primary">
                            <div>
                              <!-- Sparkline Container -->
                              <span class="js-sparkline" data-type="line"
                                    data-points="[68,25,36,122,59,80,75,89]"
                                    data-width="80px"
                                    data-height="40px"
                                    data-line-color="#FFF"
                                    data-fill-color="transparent"
                                    data-spot-color="transparent"
                                    data-min-spot-color="transparent"
                                    data-max-spot-color="transparent"
                                    data-highlight-spot-color="#343a40"
                                    data-highlight-line-color="#343a40"
                                    data-tooltip-suffix="New Providers"></span>
                            </div>
                            <div class="ms-3 text-end">
                              <p class="mb-0">
                                New Providers
                              </p>
                              <p class="fs-3 fw-medium mb-0">
                                278
                              </p>
                            </div>
                          </div>
                        </a>
                      </div>
                        
                      <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                          <div class="block-content block-content-full d-flex align-items-center text-white bg-success justify-content-between">
                            <div>
                              <!-- Sparkline Container -->
                              <span class="js-sparkline" data-type="bar"
                                         data-points="[7,9,5,2,3,4,8,3]"
                                         data-height="40px"
                                         data-bar-width="6"
                                         data-bar-color="#fff"
                                         data-bar-spacing="3"
                                         data-tooltip-suffix="New Request"></span>
                            </div>
                            <div class="ms-3 text-end">
                              <p class="mb-0">
                                New Request
                              </p>
                              <p class="fs-3 fw-medium mb-0">
                                278
                              </p>
                            </div>
                          </div>
                        </a>
                      </div>

                      <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                          <div class="block-content block-content-full d-flex align-items-center bg-gd-sublime text-white justify-content-between">
                            <div>
                              <!-- Sparkline Container -->
                              <span class="js-sparkline" data-type="line"
                                    data-points="[68,25,36,10,59,80,75,10]"
                                    data-width="80px"
                                    data-height="40px"
                                    data-line-color="#fff"
                                    data-fill-color="transparent"
                                    data-spot-color="transparent"
                                    data-min-spot-color="transparent"
                                    data-max-spot-color="transparent"
                                    data-highlight-spot-color="#343a40"
                                    data-highlight-line-color="#343a40"
                                    data-tooltip-suffix="Pending"></span>
                            </div>
                            <div class="ms-3 text-end">
                              <p class=" mb-0">
                                Request Pending
                              </p>
                              <p class="fs-3 fw-medium mb-0">
                                40
                              </p>
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                          <div class="block-content block-content-full d-flex align-items-center bg-gd-aqua text-white justify-content-between">
                            <div>
                              <!-- Sparkline Container -->
                              <span class="js-sparkline" data-type="line"
                                    data-points="[68,25,36,10,59,80,60,100]"
                                    data-width="80px"
                                    data-height="40px"
                                    data-line-color="#fff"
                                    data-fill-color="transparent"
                                    data-spot-color="transparent"
                                    data-min-spot-color="transparent"
                                    data-max-spot-color="transparent"
                                    data-highlight-spot-color="#343a40"
                                    data-highlight-line-color="#343a40"
                                    data-tooltip-suffix="Pending"></span>
                            </div>
                            <div class="ms-3 text-end">
                              <p class="mb-0">
                                Total Services
                              </p>
                              <p class="fs-3 fw-medium mb-0">
                                50
                              </p>
                            </div>
                          </div>
                        </a>
                      </div>
                      </div>
                    
                      <div class="row">
                          <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                              <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                  <!-- Sparkline Container -->
                                  <span class="js-sparkline" data-type="line"
                                        data-points="[68,25,36,10,59,80,60,100]"
                                        data-width="80px"
                                        data-height="40px"
                                        data-line-color="#044792"
                                        data-fill-color="transparent"
                                        data-spot-color="transparent"
                                        data-min-spot-color="transparent"
                                        data-max-spot-color="transparent"
                                        data-highlight-spot-color="#343a40"
                                        data-highlight-line-color="#343a40"
                                        data-tooltip-suffix="Pending"></span>
                                </div>
                                <div class="ms-3 text-end">
                                  <p class="text-muted mb-0">
                                    Tickets Open
                                  </p>
                                  <p class="fs-3 fw-medium mb-0">
                                    155
                                  </p>
                                </div>
                              </div>
                            </a>
                          </div>

                          <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                              <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                  <!-- Sparkline Container -->
                                  <span class="js-sparkline" data-type="line"
                                        data-points="[68,25,36,10,59,80,60,100]"
                                        data-width="80px"
                                        data-height="40px"
                                        data-line-color="#d262e3"
                                        data-fill-color="transparent"
                                        data-spot-color="transparent"
                                        data-min-spot-color="transparent"
                                        data-max-spot-color="transparent"
                                        data-highlight-spot-color="#343a40"
                                        data-highlight-line-color="#343a40"
                                        data-tooltip-suffix="Pending"></span>
                                </div>
                                <div class="ms-3 text-end">
                                  <p class="text-muted mb-0">
                                    Tickets Close
                                  </p>
                                  <p class="fs-3 fw-medium mb-0">
                                    80
                                  </p>
                                </div>
                              </div>
                            </a>
                          </div>
                          <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                              <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div>
                                  <!-- Sparkline Container -->
                                  <span class="js-sparkline" data-type="line"
                                        data-points="[68,25,36,10,59,80,60,100]"
                                        data-width="80px"
                                        data-height="40px"
                                        data-line-color="#3db708"
                                        data-fill-color="transparent"
                                        data-spot-color="transparent"
                                        data-min-spot-color="transparent"
                                        data-max-spot-color="transparent"
                                        data-highlight-spot-color="#343a40"
                                        data-highlight-line-color="#343a40"
                                        data-tooltip-suffix="Pending"></span>
                                </div>
                                <div class="ms-3 text-end">
                                  <p class="text-muted mb-0">
                                    Total Active Users
                                  </p>
                                  <p class="fs-3 fw-medium mb-0">
                                    2365
                                  </p>
                                </div>
                              </div>
                            </a>
                          </div>

                           <div class="col-md-6 col-xl-3">
                             <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                               <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                 <div>
                                   <!-- Sparkline Container -->
                                   <span class="js-sparkline" data-type="line"
                                         data-points="[68,25,36,10,59,80,60,100]"
                                         data-width="80px"
                                         data-height="40px"
                                         data-line-color="#ff0000"
                                         data-fill-color="transparent"
                                         data-spot-color="transparent"
                                         data-min-spot-color="transparent"
                                         data-max-spot-color="transparent"
                                         data-highlight-spot-color="#343a40"
                                         data-highlight-line-color="#343a40"
                                         data-tooltip-suffix="Pending"></span>
                                 </div>
                                 <div class="ms-3 text-end">
                                   <p class="text-muted mb-0">
                                     Total Inactive Users
                                   </p>
                                   <p class="fs-3 fw-medium mb-0">
                                     325
                                   </p>
                                 </div>
                               </div>
                             </a>
                           </div>
                      </div>
                    </section>

                    <!-- Main Chart -->
                    <div class="block block-rounded block-mode-loading-refresh">
                      <div class="block-header block-header-default">
                        <h3 class="block-title">Earnings</h3>
                        <div class="block-options">
                          <div class="btn-group btn-group-sm me-2" role="group" aria-label="Earnings Select Date Group">
                            <input type="radio" class="btn-check" name="dashboard-chart-options" id="dashboard-chart-options-week" autocomplete="off">
                            <label class="btn btn-primary" for="dashboard-chart-options-week" data-toggle="dashboard-chart-set-week">Week</label>

                            <input type="radio" class="btn-check" name="dashboard-chart-options" id="dashboard-chart-options-month" autocomplete="off">
                            <label class="btn btn-primary" for="dashboard-chart-options-month" data-toggle="dashboard-chart-set-month">Month</label>

                            <input type="radio" class="btn-check" name="dashboard-chart-options" id="dashboard-chart-options-year" autocomplete="off" checked>
                            <label class="btn btn-primary" for="dashboard-chart-options-year" data-toggle="dashboard-chart-set-year">Year</label>
                          </div>
                          <button type="button" class="btn-block-option align-middle" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                          </button>
                        </div>
                      </div>
                      <div class="block-content px-0 overflow-hidden">
                        <div class="m-n1" style="height: 380px">
                          <!-- Chart.js Dashboard Earnings Container -->
                          <!-- Chart.js Chart is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js -->
                          <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                          <canvas id="js-chartjs-dashboard-earnings"></canvas>
                        </div>
                      </div>
                    </div>
                    <!-- END Main Chart -->
                    
                    <section id="stats-subtitle">
                    <div class="row">
                      <div class="col-12 mt-3 mb-1">
                        <h4 class="text-uppercase mt-5 mb-0">Statistics With Subtitle</h4>
                        <p>Statistics on minimal cards with Title &amp; Sub Title.</p>
                      </div>
                    </div>
                   <div class="row">
                  <div class="col-xl-6 col-md-12">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                          <div class="block-content block-content-full d-flex align-items-center justify-content-between text-white bg-gd-sea">
                            <div>
                              <!-- Sparkline Container -->
                              <span class="js-sparkline" data-type="line"
                                    data-points="[63,52,14,36,48,17,53,64]"
                                    data-width="100%"
                                    data-height="120px"
                                    data-line-color="#fff"
                                    data-fill-color="transparent"
                                    data-spot-color="transparent"
                                    data-min-spot-color="transparent"
                                    data-max-spot-color="transparent"
                                    data-highlight-spot-color="#ff0000"
                                    data-highlight-line-color="#ff0000"
                                    data-tooltip-suffix="Sales"></span>
                            </div>
                            <div class="ms-3 text-end">
                              <h4>Last Month Sale</h4>
                               <span>Total</span>
                              <p class="fs-3 fw-medium mb-0">
                               <h1>18,000</h1>
                              </p>
                            </div>
                          </div>
                        </a>
                      </div>

                      <div class="col-xl-6 col-md-12">
                            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                              <div class="block-content block-content-full d-flex align-items-center bg-gd-leaf text-white justify-content-between">
                                <div>
                                  <!-- Sparkline Container -->
                                  <span class="js-sparkline" data-type="line"
                                        data-points="[63,52,14,36,48,17,53,64]"
                                        data-width="100%"
                                        data-height="120px"
                                        data-line-color="#fff"
                                        data-fill-color="transparent"
                                        data-spot-color="transparent"
                                        data-min-spot-color="transparent"
                                        data-max-spot-color="transparent"
                                        data-highlight-spot-color="#ff0000"
                                        data-highlight-line-color="#ff0000"
                                        data-tooltip-suffix="Sales"></span>
                                </div>
                                <div class="ms-3 text-end">
                                  <h4>Last Month Commission</h4>
                                   <span>Total</span>
                                  <p class="fs-3 fw-medium mb-0">
                                   <h1>84,000</h1>
                                  </p>
                                </div>
                              </div>
                            </a>
                          </div>
                    </div>

                    <div class="row">



               <div class="col-xl-6 col-md-12">
                            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                              <div class="block-content block-content-full d-flex align-items-center bg-gd-dusk-op text-white justify-content-between">
                                <div>
                                  <!-- Sparkline Container -->
                                  <span class="js-sparkline" data-type="line"
                                        data-points="[10,2,14,36,48,0,53,64]"
                                        data-width="100%"
                                        data-height="120px"
                                        data-line-color="#fff"
                                        data-fill-color="transparent"
                                        data-spot-color="transparent"
                                        data-min-spot-color="transparent"
                                        data-max-spot-color="transparent"
                                        data-highlight-spot-color="#ff0000"
                                        data-highlight-line-color="#ff0000"
                                        data-tooltip-suffix="Sales"></span>
                                </div>
                                <div class="ms-3 text-end">
                                  <h4>Total Revenue Generated</h4>
                                   <span>Total Sales</span>
                                  <p class="fs-3 fw-medium mb-0">
                                   <h1>$76,456.00</h1>
                                  </p>
                                </div>
                              </div>
                            </a>
                          </div>


                    <div class="col-xl-6 col-md-12">
                                 <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                                   <div class="block-content block-content-full d-flex align-items-center text-white justify-content-between bg-gd-fruit-op">
                                     <div>
                                       <!-- Sparkline Container -->
                                       <span class="js-sparkline" data-type="line"
                                             data-points="[50,20,35,45,48,0,53,64]"
                                             data-width="100%"
                                             data-height="120px"
                                             data-line-color="#fff"
                                             data-fill-color="transparent"
                                             data-spot-color="transparent"
                                             data-min-spot-color="transparent"
                                             data-max-spot-color="transparent"
                                             data-highlight-spot-color="#ff0000"
                                             data-highlight-line-color="#ff0000"
                                             data-tooltip-suffix="Sales"></span>
                                     </div>
                                     <div class="ms-3 text-end">
                                       <h4>Total Commission Generated</h4>
                                        <span>Total Sales</span>
                                       <p class="fs-3 fw-medium mb-0">
                                        <h1>$26,456.00</h1>
                                       </p>
                                     </div>
                                   </div>
                                 </a>
                               </div>
                    </div>
                  </section>

                  <!-- Visitors Growth -->
                  <div class="d-flex justify-content-between align-items-center pt-5 pb-3">
                    <h2 class="h3 fw-normal mb-0">Visitors Growth</h2>
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm btn-alt-secondary px-3" id="dropdown-analytics-visitors-growth" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        This Week <i class="fa fa-fw fa-angle-down"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-visitors-growth">
                        <a class="dropdown-item" href="javascript:void(0)">Last 30 days</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)">Previous Week</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)">This Month</a>
                        <a class="dropdown-item" href="javascript:void(0)">Previous Month</a>
                      </div>
                    </div>
                  </div>
                  <div class="block block-rounded block-fx-pop">
                    <div class="block-content block-content-full">
                      <div class="row">
                        <div class="col-md-5 col-lg-4 d-md-flex align-items-md-center">
                          <div class="p-md-2 p-lg-3">
                            <div class="display-4 fw-bold">3,687</div>
                            <div class="fs-lg fw-bold">Your new website visitors</div>
                            <div class="py-3 d-flex align-items-center">
                              <div class="bg-success-light px-2 py-1 rounded me-3">
                                <i class="fa fa-fw fa-caret-up text-success"></i>
                              </div>
                              <p class="mb-0">
                                You have a <span class="fw-semibold text-success">25% Growth</span> in the last 30 days. Keep it up!
                              </p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-7 col-lg-8">
                          <div class="p-md-2 p-lg-3 w-100" style="height: 350px;">
                            <!-- Bars Chart Container -->
                            <!-- Chart.js Chart is initialized in js/pages/db_analytics.min.js which was auto compiled from _js/pages/db_analytics.js -->
                            <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                            <canvas id="js-chartjs-analytics-bars"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- END Visitors Growth -->


                  <!-- Users and Purchases -->
                  <div class="row items-push">
                    <div class="col-xl-6">
                      <!-- Users -->
                      <div class="block block-rounded block-mode-loading-refresh h-100 mb-0">
                        <div class="block-header block-header-default">
                          <h3 class="block-title">Users</h3>
                          <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                              <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                              <i class="si si-cloud-download"></i>
                            </button>
                            <div class="dropdown">
                              <button type="button" class="btn-block-option" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="si si-wrench"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="far fa-fw fa-user me-1"></i> New Users
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="far fa-fw fa-bookmark me-1"></i> VIP Users
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="fa fa-fw fa-pencil-alt"></i> Manage
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-body-dark">
                          <form action="be_pages_dashboard.html" method="POST" onsubmit="return false;">
                            <input type="text" class="form-control form-control-alt" placeholder="Search Users..">
                          </form>
                        </div>
                        <div class="block-content">
                          <table class="table table-striped table-hover table-borderless table-vcenter fs-sm">
                            <thead>
                              <tr class="text-uppercase">
                                <th class="fw-bold text-center" style="width: 120px;">Avatar</th>
                                <th class="fw-bold">Name</th>
                                <th class="d-none d-sm-table-cell fw-bold">Access</th>
                                <th class="fw-bold text-center" style="width: 60px;"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="text-center">
                                  <img class="img-avatar img-avatar32 img-avatar-thumb" src="assets/media/avatars/avatar7.jpg" alt="">
                                </td>
                                <td>
                                  <div class="fw-semibold fs-base">Amanda Powell</div>
                                  <div class="text-muted">carol@example.com</div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-base">
                                  <span class="badge bg-dark">VIP</span>
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit User">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td class="text-center">
                                  <img class="img-avatar img-avatar32 img-avatar-thumb" src="assets/media/avatars/avatar13.jpg" alt="">
                                </td>
                                <td>
                                  <div class="fw-semibold fs-base">Ralph Murray</div>
                                  <div class="text-muted">smith@example.com</div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-base">
                                  <span class="badge bg-black-50">Pro</span>
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit User">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td class="text-center">
                                  <img class="img-avatar img-avatar32 img-avatar-thumb" src="assets/media/avatars/avatar14.jpg" alt="">
                                </td>
                                <td>
                                  <div class="fw-semibold fs-base">Ralph Murray</div>
                                  <div class="text-muted">john@example.com</div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-base">
                                  <span class="badge bg-dark">VIP</span>
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit User">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td class="text-center">
                                  <img class="img-avatar img-avatar32 img-avatar-thumb" src="assets/media/avatars/avatar8.jpg" alt="">
                                </td>
                                <td>
                                  <div class="fw-semibold fs-base">Andrea Gardner</div>
                                  <div class="text-muted">lori@example.com</div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-base">
                                  <span class="badge bg-black-50">Pro</span>
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit User">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td class="text-center">
                                  <img class="img-avatar img-avatar32 img-avatar-thumb" src="assets/media/avatars/avatar13.jpg" alt="">
                                </td>
                                <td>
                                  <div class="fw-semibold fs-base">Thomas Riley</div>
                                  <div class="text-muted">jack@example.com</div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-base">
                                  <span class="badge bg-success">Free</span>
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit User">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- END Users -->
                    </div>
                    <div class="col-xl-6">
                      <!-- Purchases -->
                      <div class="block block-rounded block-mode-loading-refresh h-100 mb-0">
                        <div class="block-header block-header-default">
                          <h3 class="block-title">Purchases</h3>
                          <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                              <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                              <i class="si si-cloud-download"></i>
                            </button>
                            <div class="dropdown">
                              <button type="button" class="btn-block-option" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="si si-wrench"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="fa fa-fw fa-sync fa-spin text-warning me-1"></i> Pending
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="far fa-fw fa-times-circle text-danger me-1"></i> Cancelled
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="far fa-fw fa-check-circle text-success me-1"></i> Completed
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="fa fa-fw fa-eye me-1"></i> View All
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-body-dark">
                          <form action="be_pages_dashboard.html" method="POST" onsubmit="return false;">
                            <input type="text" class="form-control form-control-alt" placeholder="Search Purchases..">
                          </form>
                        </div>
                        <div class="block-content">
                          <table class="table table-striped table-hover table-borderless table-vcenter fs-sm">
                            <thead>
                              <tr class="text-uppercase">
                                <th class="fw-bold">Product</th>
                                <th class="d-none d-sm-table-cell fw-bold">Date</th>
                                <th class="fw-bold">Status</th>
                                <th class="d-none d-sm-table-cell fw-bold text-end" style="width: 120px;">Price</th>
                                <th class="fw-bold text-center" style="width: 60px;"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <span class="fw-semibold">iPhone X</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">today</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-warning">Pending..</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $999,99
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">MacBook Pro 15"</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">today</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-warning">Pending..</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $2.299,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">Nvidia GTX 1080 Ti</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">today</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-warning">Pending..</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $1200,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">Playstation 4 Pro</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">today</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-danger">Cancelled</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $399,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">Nintendo Switch</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">yesterday</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-success">Completed</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $349,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">iPhone X</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">yesterday</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-success">Completed</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $999,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">Echo Dot</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">yesterday</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-success">Completed</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $39,99
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="fw-semibold">Xbox One X</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                  <span class="fs-sm text-muted">yesterday</span>
                                </td>
                                <td>
                                  <span class="fw-semibold text-success">Completed</span>
                                </td>
                                <td class="d-none d-sm-table-cell text-end">
                                  $499,00
                                </td>
                                <td class="text-center">
                                  <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="left" title="Manage">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- END Purchases -->
                    </div>
                  </div>
                  <!-- END Users and Purchases -->

                </div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/pages/be_pages_dashboard_v1.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/js/pages/db_analytics.min.js') }}"></script>
<script>Dashmix.helpersOnLoad('jq-sparkline');</script>
@endpush