<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\CustomerRequestSubmit;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\EnergyController;
use App\Http\Controllers\Api\InternetTvController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use App\Models\PostRequest;
use App\Models\InsuranceProduct;
use Validator;
use App\Http\Resources\EnergyResource;
use App\Models\Deal;
use App\Models\TvPackage;
use App\Models\TvOption;
use DB;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserRequest;
use App\Models\Review;
use App\Models\Event;


class RequestController extends BaseController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userData = UserRequest::with('service','advantagesData','userDetails','providerDetails','categoryDetails')->where('service_type', 'App\Models\EnergyProduct')->where('user_id', $request->user_id)->get();
        $userData = $userData->map(function ($item) {
            // Change the format of the desired columns here
            $item->shipping_address = json_decode($item->shipping_address);
            $item->advantages =  json_decode($item->advantages);
            return $item;
        });
        return $this->sendResponse($userData, 'User requests retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {//return response()->json($request->input());
      
        try {
            $user_id = $request->input('user_id');
            $user_type = $request->input('user_type');
            $category_id = $request->input('category');
            $sub_category_id = $request->input('sub_category');
            $service_id = $request->input('service_id');
            $postal_code = $request->input('postal_code');
            $service_type = $request->input('service_type');
            $combos = json_encode($request->input('combos'));
            $total_price = $request->input('total_price');
            $discounted_price = $request->input('discounted_price');
            $discount_prct = $request->input('discount_prct');
            $commission_prct = $request->input('commission_prct');
            $commission_amt = $request->input('commission_amt');
            $request_status = $request->input('request_status');
            $advantages = $request->input('advantages');
            $contact_details = $request->input('contactDetails');
            $additional_information = $request->input('additionalInfo');
            $additional_questions = $request->input('additionalQuestion');
            $delivery = $request->input('delivery');
            $verification = $request->input('verification');

            $data = new UserRequest();
            
            $data->user_id = $user_id;
            $data->user_type = $user_type;
            $data->category = $category_id;
            $data->sub_category = $sub_category_id;
            $data->service_id = $service_id;
            $data->service_type = $service_type;
            $data->combos = $combos;
            $data->postal_code = $postal_code;
            $data->advantages = json_encode($advantages);
            $data->total_price = $total_price;
            $data->discounted_price = $discounted_price;
            $data->discount_prct = $discount_prct;
            $data->commission_prct = $commission_prct;
            $data->commission_amt = $commission_amt;
            $data->request_status = $request_status;
            $data->provider_id = $request->provider_id;
            $data->solar_panels = $request->solar_panels;
            $data->no_gas = $request->no_gas;
            $data->shipping_address = json_encode($request->shipping_address);
            $data->billing_address = json_encode($request->billing_address);
            $data->contact_details = json_encode($contact_details);
            $data->additional_information = json_encode($additional_information);
            $data->additional_questions = json_encode($additional_questions);
            $data->delivery = json_encode($delivery);
            $data->verification = json_encode($verification);
            if ($data->save()) {
                $data->load('userDetails'); 
                $orderNo = $data->id + 1000;
                $data->order_no = $orderNo;
                $name = $data->userDetails?$data->userDetails->name:'';
                
                $data->save();
                if($request->has('advantages')){
                    foreach($request->advantages as $key => $value){
                        PostRequest::updateOrCreate(['request_id' => $data->id, 'key' => $key],
                            [
                                'value' => $value
                            ]);
                    }
                }
                               
                $emailTemplate = EmailTemplate::where('email_of', '2')->first();
                
                $body['body'] = str_replace(['{{ $name }}', '{{ $orderNo }}'], [$name, $orderNo], $emailTemplate->mail_body);
                $body['name'] = $name;
                $body['action_link'] = url('/').'/api/view-order/'.$orderNo;

                // Mail::to('bijay.behera85@gmail.com')->send(new CustomerRequestSubmit($body));
                return response()->json(['success' => true, 'message' => 'User request saved successfully'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to save user request'], 422);
            }

            
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Handle other database errors
            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        }
    
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,  $id)
    {
        $userRequest = UserRequest::with('service','advantagesData','userDetails','providerDetails','categoryDetails')->find($id);

        // Change the format of the desired columns here
        $userRequest->shipping_address = json_decode($userRequest->shipping_address);
        $userRequest->advantages = json_decode($userRequest->advantages);
        return $this->sendResponse($userRequest, 'User request retrieved successfully.');
    }
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRequest $userRequest)
    {
        

        // if(\Auth::guard('admin')->user()->can('view',$post)){
        //     return view('admin.posts.edit',['post'=>$post]);            
        // }
        
        $this->authorize('view',$post);
        return view('admin.posts.edit',['post'=>$post]);            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update',$post);
        $post->update([
            'title'=>$request->title,
            'description'=>$request->description
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function viewOrder(Request $request, $id)
    {
        $user_request = UserRequest::find($id);
        return view('admin.requests.edit', compact('user_request'));
    }

    public function getUserData(Request $request){
        $userData = UserData::where('user_id', $request->user_id)->get();
        return $this->sendResponse($userData, 'User data retrieved successfully.');
    }
    public function saveUserData(Request $request){
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'user_id' => 'required',
                'data.*.key' => 'required|string',
                'data.*.value' => 'required|string',
            ]);

            // Loop through each key-value pair in the data array
            foreach ($validatedData['data'] as $item) {
                // Use updateOrCreate to update or create the record
                UserData::updateOrCreate(
                    ['user_id' => $validatedData['user_id'], 'key' => $item['key']],
                    ['value' => $item['value']]
                );
            }

            // Return a response indicating success
            return response()->json(['success' => true, 'message' => 'User data saved successfully'], 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) { 
            // Handle other database errors
            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        }
    }

    public function reviewList(Request $request){
        $userData = Review::where('user_id', $request->user_id)->get();
        if($request->has('post_id')){
            $userData = Review::where('user_id', $request->user_id)
            ->where('post_id', $request->post_id)->latest()->get();
        }
        return $this->sendResponse($userData, 'Review data retrieved successfully.');
    }
    public function reviewSave(Request $request){
        try {
            $userRequest = UserRequest::where('user_id', $request->user_id)
                                    ->where('post_id', $request->post_id)->firstOrFail();
            
            $validatedData = $request->validate([
                'user_id' => 'required',
                'rating' => 'required|numeric',
                'post_id' => 'required|numeric',
            ]);

                    
                
                Review::updateOrCreate(
                    ['user_id' => $validatedData['user_id'], 'post_id' => $validatedData['post_id']],
                    ['rating' => $validatedData['rating'], 'category' => $request->category, 'sub_category' => $request->sub_category, 'user_type' => $request->user_type, 'rating_type' => $request->rating_type]
                );
            

            
            return response()->json(['success' => true, 'message' => 'Review saved successfully'], 200);
        } catch (ValidationException $e) {
            
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            
            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            return response()->json(['error' => 'User request not found'], 422);
        }
    }

    public function reviewShow($id)
    {
        $review = Review::find($id);
        return $this->sendResponse($review, 'User review retrieved successfully.');
    }

    public function getDealsData()
    {
        $deals = Deal::latest()->take(3)->get();
        $deals->map(function($deal) {
            $deal->icon =  asset('deal_icons/'.$deal->icon);
            $deal->categoryDetails;
            $deal->products = json_decode($deal->products);
            return $deal;
        });
        return $this->sendResponse($deals, 'Deals retrieved successfully.');
    }
    private function getProductsCategoryWise($product_ids,$category_id)
    {
            $request = new Request();
            $request['category_id'] = $category_id;
            $request['callFromExclusiveDeal'] = 1 ; 

        if ($request->category_id == 1) {
            $internetTvObj = new InternetTvController();
            $products =  $internetTvObj->index($request);
            $products[0] = collect($products[0])->filter(function($product) use ($product_ids){
                return in_array($product['id'] , $product_ids);
              });
            return $products;

        }elseif ($request->category_id == 2) {

            $products = []; 
            return $products;

        }elseif ($request->category_id == 5) {

            $products = InsuranceProduct::whereIn('id',$product_ids)->get(); 
            return $products;

        }elseif ($request->category_id == 6) {

            $products = []; 
            return $products;

        }elseif ($request->category_id == 13) {

            $products = []; 
            return $products;

        }elseif ($request->category_id == 14) {

             $products = []; 
             return $products;

        }elseif ($request->category_id == 16) {
              $energyObj = new EnergyController() ;
              $products = $energyObj->index($request);
              $products[0] = collect($products[0])->filter(function($product) use ($product_ids){
                return in_array($product['id'] ,$product_ids);
              });
            
              return $products;
        }
    }
    public function getExclusiveDeal(Request $request)
    {
        $deal = Deal::where('id',$request->id)->first();
        if ($deal) {
            $deal->icon =  asset('deal_icons/'.$deal->icon);
            $deal->categoryDetails;
            $dealData = $this->getProductsCategoryWise(json_decode($deal->products),$deal->category);
            return response()->json([
                'success' => true,
                'data' => $dealData[0],
                'filters' => $dealData[1],
                'message' => 'Deal retrieved successfully'
            ]);
        }else {
            return response()->json([
                'success' => false,
                'data' => [],
                'filters' =>[],
                'message' => 'Deal not found in the requested id'
            ]);
        }
      
    }

    public function getTvPackages(Request $request)
    {
    $records = TvPackage::latest()->with('providerDetails')->get();
    if ($records) {
        return response()->json([
                'success' => true ,
                'data' => $records ,
                'message' => 'Packages retrieved successfully'
            ]);
    }else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Packages not found'
            ]);
        }
    }
    public function getTvInternetOptions()
    {
    $records = TvOption::latest()->with('providerDetails')->get();
    if ($records) {
        $records->map(function($record){

          $record->internet_options = array_map(function($option){
            $option->package_name = $option->name ;
            $option->package_price = (int)$option->normal_cost;
            unset($option->name);
            unset($option->normal_cost);
            return $option;
          },json_decode($record->internet_options)) ;

          $record->tv_options = array_map(function($option){
            $option->package_name = $option->name ;
            $option->package_price = (int)$option->normal_cost;
            unset($option->name);
            unset($option->normal_cost);
            return $option;
          },json_decode($record->tv_options)) ;

          $record->telephone_options =array_map(function($option){
            $option->package_name = $option->name ;
            $option->package_price = (int)$option->normal_cost;
            unset($option->name);
            unset($option->normal_cost);
            return $option;
          },json_decode($record->telephone_options))  ;
          return $record;
        });
        return response()->json([
                'success' => true,
                'data' => $records,
                'message' => 'TvInternetOptions retrieved successfully'
            ]);
    }else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'TvInternetOptions not found'
            ]);
        }
    }
      public function eventlist(Request $request)
        {
            // Fetch all event from the database
            $events = Event::latest()->get();
    
            // Return the data as a JSON response

           
                return response()->json([
                    'success' => true,
                    'data' => $events,
                    'message' => 'Deal retrieved successfully'
                ]);
        }
        public function getTopFourDeals(Request $request)
        {
            $deals = Deal::latest()->take(4)->get();
            if (count($deals) > 0) {
                $deals->map(function($deal) {
                    $deal->icon =  asset('deal_icons/'.$deal->icon);
                    $deal->categoryDetails;
                    $deal->products = json_decode($deal->products);
                    return $deal;
                });
            }
      
        return $this->sendResponse($deals, 'Deals retrieved successfully.');
        }
        public function getEnergyDeals(Request $request)
        {
            $deals = Deal::where(['category'=>16,'status'=>'active'])->latest()->take(4)->get();
            if (count($deals) > 0) {
                $deals->map(function($deal) {
                    $deal->icon =  asset('deal_icons/'.$deal->icon);
                    $deal->categoryDetails;
                    $deal->products = json_decode($deal->products);
                    return $deal;
                });
                return $this->sendResponse($deals, 'Deals retrieved successfully.');
            }
      
            return $this->sendError('Deals not found.');
        }
        public function getInternetTvDeals(Request $request)
        {
            $deals = Deal::where(['category'=>1,'status'=>'active'])->latest()->take(4)->get();
            if (count($deals) > 0) {
                $deals->map(function($deal) {
                    $deal->icon =  asset('deal_icons/'.$deal->icon);
                    $deal->categoryDetails;
                    $deal->products = json_decode($deal->products);
                    return $deal;
                });
                return $this->sendResponse($deals, 'Deals retrieved successfully.');
            }
      
            return $this->sendError('Deals not found.');
        }
        public function getHomeInsuranceDeals(Request $request)
        {
            $deals = Deal::where(['sub_category'=>config('constant.subcategory.HomeInsurance'),'status'=>'active'])->latest()->take(4)->get();
            if (count($deals) > 0) {
                $deals->map(function($deal) {
                    $deal->icon =  asset('deal_icons/'.$deal->icon);
                    $deal->categoryDetails;
                    $deal->products = json_decode($deal->products);
                    return $deal;
                });
                return $this->sendResponse($deals, 'Deals retrieved successfully.');
            }
      
            return $this->sendError('Deals not found.');
        }
    }

