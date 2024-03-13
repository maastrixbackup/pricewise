<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Admin;
use App\Models\AvailableStatus;
use App\Models\Paymybill;
use App\Models\Affiliate;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class ReportController extends Controller
{

    public function index()
    {
      
        //$orderRecords = "";
        $objYear = DB::table('orders')
            ->select(DB::raw('YEAR(created_at) year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $objMonth = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) month, MONTHNAME(created_at) month_name'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->get();
        // dd($dates);
        return view('admin.reports.order-report', compact('objMonth', 'objYear'));
    }
    public function getReport(Request $request)
    {

        ## Read value
        $draw = $request->get('draw');
        $row = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        ## Read value

        $data = array();

        // DB::enableQueryLog();

        $totalRecords = Order::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Order::select('count(*) as allcount');

        if (isset($request->month)) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereMonth('created_at', $request->month);
        }
        if (isset($request->year)) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereRaw('YEAR(created_at) = ' . $request->year);
        }

        $totalRecordswithFilter = $totalRecordswithFilter->count();
        // dd(DB::getQueryLog());
        DB::enableQueryLog();
       // if (isset($request->month) || isset($request->year)) {
            $orderRecords = Order::orderBy('id', 'desc')->select('orders.*');
            if (isset($request->month)) {
                $orderRecords = $orderRecords->whereMonth('created_at', $request->month);
            }

            if (isset($request->year)) {
                $orderRecords = $orderRecords->whereRaw('YEAR(created_at) = ' . $request->year);
            }


            // Fetch records
            $orderRecords = $orderRecords->skip($row)->take($rowperpage)->get();

            //dd(DB::getQueryLog());


            foreach ($orderRecords as $key => $record) {

                $data[] = array(
                    "id" => $record->order_no ? $record->order_no : "NA",
                    "customer" => $record->first_name ? $record->first_name . ' ' . $record->last_name : "NA",
                    "purchased_date" =>  $record->created_at ? date("Y-m-d", strtotime($record->created_at)) : "NA",
                    "product" =>  $record->product_name,
                    "order_status" =>  $record->order_status ? str_replace('_', ' ', $record->order_status) : "NA"
                );
            }
       // }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "data" => $data
        );

        echo json_encode($response);
    }

    // public function Report(Request $request)
    // {

     
    //         $orderRecords = Order::orderBy('id', 'desc')->select('orders.*');
    //         if (isset($request->month)) {
    //             $orderRecords = $orderRecords->whereMonth('created_at', $request->month);
    //         }

    //         if (isset($request->year)) {
    //             $orderRecords = $orderRecords->whereRaw('YEAR(created_at) = ' . $request->year);
    //         }


    //         // Fetch records
    //         $orderRecords = $orderRecords->get();

    //         //dd(DB::getQueryLog());


    //         // foreach ($orderRecords as $key => $record) {

    //         //     $data[] = array(
    //         //         "id" => $record->order_no ? $record->order_no : "NA",
    //         //         "customer" => $record->first_name ? $record->first_name . ' ' . $record->last_name : "NA",
    //         //         "purchased_date" =>  $record->created_at ? date("Y-m-d", strtotime($record->created_at)) : "NA",
    //         //         "product" =>  $record->product_name,
    //         //         "order_status" =>  $record->order_status ? str_replace('_', ' ', $record->order_status) : "NA"
    //         //     );
    //         // }
    //    // }
    //     // $response = array(
    //     //     "draw" => intval($draw),
    //     //     "iTotalRecords" => $totalRecords,
    //     //     "iTotalDisplayRecords" => $totalRecordswithFilter,
    //     //     "data" => $data
    //     // );

    //     //echo json_encode($response);
    //     return redirect()->back()->with('$orderRecords');
    // }
    
    
    public function payBill()
    {
        return view('admin.reports.pay-bill-report');
    }

    public function getPayBillReport(Request $request)
    {

        ## Read value
        $draw = $request->get('draw');
        $row = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value
        ## Read value
       
        $data = array();

        // DB::enableQueryLog();

        $totalRecords = Paymybill::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Paymybill::select('count(*) as allcount')->where('account_no', 'like', '%' . $searchValue . '%')->orwhere('name', 'like', '%' . $searchValue . '%')->orwhere('amount', 'like', '%' . $searchValue . '%')->orwhere('postcode', 'like', '%' . $searchValue . '%')->orwhere('payment_mode', 'like', '%' . $searchValue . '%')->orwhere('created_at', 'like', '%' . $searchValue . '%');

        $totalRecordswithFilter = $totalRecordswithFilter->count();
        // dd(DB::getQueryLog());
        DB::enableQueryLog();
      
        $payBillRecords = Paymybill::where('account_no', 'like', '%' . $searchValue . '%')->orwhere('name', 'like', '%' . $searchValue . '%')->orwhere('amount', 'like', '%' . $searchValue . '%')->orwhere('postcode', 'like', '%' . $searchValue . '%')->orwhere('payment_mode', 'like', '%' . $searchValue . '%')->orwhere('created_at', 'like', '%' . $searchValue . '%')->orderBy('id', 'desc')->select('paymybills.*');
        

        // Fetch records
        $payBillRecords = $payBillRecords->skip($row)->take($rowperpage)->get();

        //dd(DB::getQueryLog());
        $i = 1;

        foreach ($payBillRecords as $key => $record) {
              $delete_btn = route('admin.paybill.destroy');
            $action =  '<div class="col">
            <a title="Delete" class="btn1 btn-outline-danger trash remove-pay-my-bill" data-id="' . $record->id . '" data-action="' . $delete_btn . '"><i class="bx bx-trash me-0"></i></a>
 
        </div>';
            $data[] = array(
                "id" => $i++,
                "akj_id" => $record->account_no ? $record->account_no : "NA",
                "customer_name" => $record->name ? $record->name : "NA",
                "customer_postcode" =>  $record->postcode ? $record->postcode : "NA",
                "amount" =>  '£ '.$record->amount,
                "payment_mode" =>  $record->payment_mode ? $record->payment_mode : "NA",
                "payment_date" =>  $record->created_at ? date("d-m-Y", strtotime($record->created_at)) : "NA",
                "action" => $action 
            );
        }
       
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "data" => $data
        );

        echo json_encode($response);
    }
    
     public function payBillDestroy(Request $request)
    {
         $id = $request->id;
         $Paymybill = Paymybill::find($id);
         
         try {
            Paymybill::find($id)->delete();
            return back()->with(Toastr::error(__('Paymybill removed successfully!')));
             
         } catch (Exception $e) {
              $error_msg = Toastr::error(__('There is an error! Please try later!'));
              return redirect()->route('admin.pay-bill-report')->with($error_msg);
         }
    }
    
    public function affiliateOrder(Request $request)
    {
        $objProductName = Order::where('product_name', '!=', '')->distinct()->get(['product_name']);
        $objAvailableStatus = AvailableStatus::latest()->get();
        $agents = Admin::where('is_agent', 1)->get();
        $objAffiliates = Affiliate::latest()->get();
        $objYear = DB::table('orders')
            ->select(DB::raw('YEAR(created_at) year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $objMonth = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) month, MONTHNAME(created_at) month_name'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->get();
        return view('admin.reports.affiliate-order-report', compact('objAffiliates', 'objProductName', 'objAvailableStatus', 'agents', 'objYear', 'objMonth'));
    }



    public function getAffiliateReport(Request $request)
    {

        ## Read value
        $draw = $request->get('draw');
        $row = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        ## Read value

        $data = array();

        //DB::enableQueryLog();

        //$totalRecords = Order::select('count(*) as allcount')->where('mark_as_aff_paid',0)->where('order_status','Checkout - Complete')->orwhere('order_status','Provisioning_CoolingOff')->count();
        
         $totalRecords = Order::select('count(*) as allcount')
            ->where('mark_as_aff_paid',0)
            ->where(function ($q) use ($searchValue) {
                $q->where('order_status', 'Checkout - Complete')
                    ->orwhere('order_status', 'Provisioning_CoolingOff')->orwhere('order_status', 'Totally Completed');
            });
         $totalRecords = $totalRecords->count();
        $totalRecordswithFilter = Order::select('count(*) as allcount');



        if (isset($request->affiliate_name)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('affiliate_name', strtolower($request->affiliate_name));
        }

        if (isset($request->month)) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereMonth('created_at', $request->month);
        }

        if (isset($request->year)) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereRaw('YEAR(created_at) = ' . $request->year);
        }

        $totalRecordswithFilter = $totalRecordswithFilter->count();

       // $orderRecords = Order::orderBy('id', 'desc')->select('orders.*')
            // ->where('order_no', 'like', '%' . $searchValue . '%')->where('mark_as_aff_paid',0)->where('order_status','Checkout - Complete')->orwhere('order_status','Provisioning_CoolingOff');
            
        \DB::enableQueryLog(); 
       $orderRecords = Order::orderBy($columnName, $columnSortOrder)
            ->where('order_no', 'like', '%' . $searchValue . '%')
            ->where('mark_as_aff_paid',0)
            ->where(function ($q) use ($searchValue) {
                $q->where('order_status', 'Checkout - Complete')
                    ->orwhere('order_status', 'Provisioning_CoolingOff')->orwhere('order_status', 'Totally Completed')->select('orders.*');
            });
            

        if (isset($request->affiliate_name)) {
            $orderRecords = $orderRecords->where('affiliate_name', strtolower($request->affiliate_name));
        }

        if (isset($request->month)) {
            $orderRecords = $orderRecords->whereMonth('created_at', $request->month);
        }

        if (isset($request->year)) {
            $orderRecords = $orderRecords->whereRaw('YEAR(created_at) = ' . $request->year);
        }
        // Fetch records
        $orderRecords = $orderRecords->skip($row)->take($rowperpage)->get();
        //dd(\DB::getQueryLog());



        $i = 1;

        foreach ($orderRecords as $key => $record) {


            $agent = Admin::where('is_agent', 1)->where('id', $record->agent_id)->first();

            $data[] = array(
                "id" =>  $record->order_no ? $record->order_no : "NA",
                "first_name" => $record->first_name ? $record->first_name . ' ' . $record->last_name : "NA",
                "created_at" =>  $record->created_at ? date("d-m-Y", strtotime($record->created_at)) : "NA",
                "monthly_total" =>  '£' . $record->monthly_total ? number_format($record->monthly_total, 2, '.', '') : number_format(0, 2, '.', ''),
                "product_id" =>  $record->product_id ? $record->product_id : 'NA',
                "product_name" =>  $record->product_name ? $record->product_name : 'NA',
                "akj_site_id" =>  $record->akj_site_id ? $record->akj_site_id : 'NA',
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "data" => $data
        );

        echo json_encode($response);
    }

    public function markAsAffPaid(Request $request)
    {
        //DB::enableQueryLog();
        // $orderRecords = Order::orderBy('id', 'desc')->select('orders.*');

        // if (isset($request->affiliate)) {
        //     $orderRecords = $orderRecords->where('affiliate_name', strtolower($request->affiliate));
        // }

        // if (isset($request->month)) {
        //     $orderRecords = $orderRecords->whereMonth('created_at', $request->month);
        // }

        // if (isset($request->year)) {
        //     $orderRecords = $orderRecords->whereYear('created_at',$request->year);
        // }
        // Fetch records
        //$orderRecords = $orderRecords->where('order_status','Checkout - Complete')->orwhere('order_status','Provisioning_CoolingOff')->update(['mark_as_aff_paid' => 1,'order_status'=>'Commission Paid']);
        
         //$orderRecords = $orderRecords->where('order_status','Checkout - Complete')->orwhere('order_status','Provisioning_CoolingOff')->update(['mark_as_aff_paid' => 0]);
         $searchValue = '';
        $orderRecords =  Order::where('affiliate_name', strtolower($request->affiliate))->whereMonth('created_at', $request->month)->whereYear('created_at',$request->year)
            ->where(function ($q) use ($searchValue) {
                $q->where('order_status', 'Checkout - Complete')
                    ->orwhere('order_status', 'Provisioning_CoolingOff')->orwhere('order_status', 'Totally Completed');
            }) ->update([
            'mark_as_aff_paid' =>1,
            'order_status'=>'Commission Paid'
        ]);
            
            
        // dd(DB::getQueryLog());
        Toastr::success('Mark as Paid Done Successfully', '', ["positionClass" => "toast-top-right"]);
        return response()->json(["status" => true, "redirect_location" => route("admin.affiliate-order-report")]);
  
    }
}
