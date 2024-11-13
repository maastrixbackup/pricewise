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
use App\Models\SmartPhone;
use App\Models\Feature;
use App\Models\FeeSetting;
use App\Models\PostRequest;
use App\Models\InsuranceProduct;
use App\Models\Post;
use Validator;
use App\Http\Resources\EnergyResource;
use App\Http\Resources\LoanProductResource;
use App\Http\Resources\LoanTypeResource;
use App\Http\Resources\SecurityResource;
use App\Models\Caterer;
use App\Models\CyberSecurity;
use App\Models\Deal;
use App\Models\TvPackage;
use App\Models\TvOption;
use DB;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserRequest;
use App\Models\Review;
use App\Models\Event;
use App\Models\EventRoom;
use App\Models\EventTheme;
use App\Models\EventType;
use App\Models\insuranceCoverage;
use App\Models\LoanProduct;
use App\Models\LoanType;
use App\Models\SecurityFeature;
use Facade\Ignition\Support\Packagist\Package;

class RequestController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->input('user_id');
        $userData = UserRequest::with('service', 'advantagesData', 'userDetails', 'providerDetails', 'categoryDetails')->where('service_type', 'App\Models\EnergyProduct')->where('user_id', $request->user_id)->get();
        $userData = $userData->map(function ($item) {
            // Change the format of the desired columns here
            $item->shipping_address = json_decode($item->shipping_address);
            $item->advantages =  json_decode($item->advantages);
            return $item;
        });
        if (!$userData->isEmpty()) {
            return $this->jsonResponse(true,  'User requests retrieved successfully.', $userData);
        } else {
            return response()->json(['status' => false, 'data' => [], 'message' => 'Failed to get user request.'], 404);
        }
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
    {
        // return $request->all();
        $delivery = [];
        $contact_details = [];
        $company_details = [];

        $userId = $request->input('user_id');
        $providerID = $request->input('provider_id');
        $userType = $request->input('user_type');
        $categoryId = $request->input('category');
        // $sub_category_id = $request->input('sub_category');
        $serviceId = $request->input('service_id');
        $postalCode = $request->input('postal_code');
        $serviceType = $request->input('service_type');
        $total_price = $request->input('total_price');
        $discounted_price = $request->input('discounted_price');
        $discount_prct = $request->input('discount_prct');
        $commission_prct = $request->input('commission_prct');
        $commission_amt = $request->input('commission_amt');
        $request_status = $request->input('request_status');
        $advantages = $request->input('advantages');
        $targetGroup = $request->input('target_group');

        // $combos = json_encode($request->input('combos'));


        // $additionalQuestions = [
        //     "living_start" => $request->input('living_start'),
        //     "living_description" => $request->input('living_description'),
        //     "home_feature_valid" => $request->input('home_feature_valid'),
        //     "home_feature_description" => $request->input('home_feature_description'),
        //     "garden_garage_valid" => $request->input('garden_garage_valid'),
        //     "garden_garage_description" => $request->input('garden_garage_description'),
        //     "fire_hazard_valid" => $request->input('fire_hazard_valid'),
        //     "office_distance" => $request->input('office_distance'),
        //     "fire_hazard_description" => $request->input('fire_hazard_description'),
        //     "renovate_home" => $request->input('renovate_home'),
        //     "deal_with" => $request->input('deal_with'),
        //     "injury_validation" => $request->input('injury_validation'),
        //     "damage_amount" => $request->input('damage_amount'),
        //     "injury_damage_description" => $request->input('injury_damage_description'),
        //     "previous_insurance_validation" => $request->input('previous_insurance_validation'),
        //     "previous_insurance_description" => $request->input('previous_insurance_description'),
        //     "punishment_validation" => $request->input('punishment_validation'),
        //     "punishment_description" => $request->input('punishment_description'),

        // ];

        $verification = [
            "accept_term" => $request->input('accept_term'),
            "get_notified" => $request->input('get_notified'),
            "payment_method" => $request->input('payment_method'),
            "verification_message" => $request->input('verification_message')
        ];

        try {
            $data = new UserRequest();
            $data->user_id = $userId;
            $data->user_type = $userType;
            $data->category = $categoryId;
            $data->service_id = $serviceId;
            $data->service_type = $serviceType;
            $data->target_group = $targetGroup;
            $data->postal_code = $postalCode;
            $data->request_status = $request_status;
            $data->provider_id = $providerID;
            $data->total_price = $total_price;
            // Contact Details
            $data->sex = $request->input('sex');
            $data->initials = $request->input('initials');
            $data->first_name = $request->input('first_name');
            $data->interjections = $request->input('interjections');
            $data->surname = $request->input('surname');
            $data->age = $request->input('dob');
            $data->email = $request->input('email');
            $data->account_number = $request->input('account_number');
            $data->mobile_number = $request->input('mobile_number');
            $data->landline_number = $request->input('landline_number');

            switch ($targetGroup) {
                case "personal":
                    $delivery = [
                        "is_moving" => $request->input('is_moving'),
                        "live_or_work" => $request->input('live_or_work'),
                        "isPostalCodeDiffer" => $request->input('isPostalCodeDiffer'),
                        "differ_postal" => $request->input('differ_postal') ?? '',
                        "house_number" => $request->input('houseNumber'),
                    ];
                    // Contact Details Json
                    $contact_details = [
                        "sex" => $request->input('sex'),
                        "initials" => $request->input('initials'),
                        "first_name" => $request->input('first_name'),
                        "interjections" => $request->input('interjections'),
                        "surname" => $request->input('surname'),
                        "age" => $request->input('age'),
                        "dob" => $request->input('dob'),
                        "email" => $request->input('email'),
                        "account_number" => $request->input('account_number'),
                        "mobile_number" => $request->input('mobile_number'),
                        "landline_number" => $request->input('landline_number'),
                    ];
                    break;
                case "commercial":
                    $delivery = [
                        "live_or_work" => $request->input('live_or_work'),
                        "is_moving" => $request->input('is_moving'),
                        "isPostalCodeDiffer" => $request->input('isPostalCodeDiffer'),
                        "differ_postal" => $request->input('differ_postal'),
                        "preferred_date" => $request->input('starting_date'),
                    ];
                    // Contact Details Json
                    $contact_details = [
                        "sex" => $request->input('sex'),
                        "initials" => $request->input('initials'),
                        "first_name" => $request->input('first_name'),
                        "interjections" => $request->input('interjections'),
                        "surname" => $request->input('surname'),
                        "age" => $request->input('age'),
                        "dob" => $request->input('dob'),
                        "email" => $request->input('email'),
                        "account_number" => $request->input('account_number'),
                        "mobile_number" => $request->input('mobile_number'),
                        "landline_number" => $request->input('landline_number'),
                        "post_code" => $request->input('post_code'),
                        "house_number_add" => $request->input('house_number'),
                    ];

                    $data->post_code = $request->input('post_code');
                    $data->house_number_add = $request->input('house_number');
                    // Company Details
                    $company_details = [
                        "company_name" => $request->input('company_name'),
                        "chamber_of_commerce" => $request->input('chamber_of_commerce'),
                        "function" => $request->input('function'),
                        "branch" => $request->input('branch')

                    ];

                    $data->company_name = $request->input('company_name');
                    $data->chamber_of_commerce = $request->input('chamber_of_commerce');
                    $data->function = $request->input('function');
                    $data->branch = $request->input('branch');

                    break;
                case "large_business":
                    $delivery = [
                        "is_moving" => $request->input('is_moving'),
                        "want_technician" => $request->input('want_technician'),
                        "isPostalCodeDiffer" => $request->input('isPostalCodeDiffer'),
                        "differ_postal" => $request->input('differ_postal') ?? '',
                        "preferred_date" => $request->input('starting_date'),
                        "additional_location" => json_encode($request->input('additional_location')),
                        "keep_number" => json_encode($request->input('keep_number'))
                    ];

                    // Contact Details Json
                    $contact_details = [
                        "sex" => $request->input('sex'),
                        "initials" => $request->input('initials'),
                        "first_name" => $request->input('first_name'),
                        "interjections" => $request->input('interjections'),
                        "surname" => $request->input('surname'),
                        "age" => $request->input('age'),
                        "dob" => $request->input('dob'),
                        "email" => $request->input('email'),
                        "account_number" => $request->input('account_number'),
                        "mobile_number" => $request->input('mobile_number'),
                        "landline_number" => $request->input('landline_number'),
                        "post_code" => $request->input('post_code'),
                        "house_number_add" => $request->input('house_number'),
                    ];
                    $data->post_code = $request->input('post_code');
                    $data->house_number_add = $request->input('house_number');
                    // Company Details
                    $company_details = [
                        "company_name" => $request->input('company_name'),
                        "chamber_of_commerce" => $request->input('chamber_of_commerce'),
                        "function" => $request->input('function'),
                        "branch" => $request->input('branch')

                    ];
                    $data->company_name = $request->input('company_name');
                    $data->chamber_of_commerce = $request->input('chamber_of_commerce');
                    $data->function = $request->input('function');
                    $data->branch = $request->input('branch');

                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid target group']);
                    break;
            }

            // $data->combos = $combos;
            // $data->advantages = json_encode($advantages);

            // Verification
            $data->verification = json_encode($verification);
            $data->contact_details = json_encode($contact_details);
            $data->delivery = json_encode($delivery);
            $data->additional_details = json_encode($company_details);

            // $data->discounted_price = $discounted_price;
            // $data->discount_prct = $discount_prct;
            // $data->commission_prct = $commission_prct;
            // $data->commission_amt = $commission_amt;

            $data->solar_panels = $request->input('solar_panels');
            $data->fix_delivery = $request->input('fix_delivery');
            $data->grid_management = $request->input('grid_management');
            $data->power_cost_per_unit = $request->input('power_cost_per_unit');
            $data->gas_cost_per_unit = $request->input('gas_cost_per_unit');
            $data->tax_on_electric = $request->input('tax_on_electric');
            $data->tax_on_gas = $request->input('tax_on_gas');
            $data->ode_on_electric = $request->input('ode_on_electric');
            $data->ode_on_gas = $request->input('ode_on_gas');
            $data->vat = $request->input('vat');
            $data->discount = $request->input('discount');
            $data->feed_in_tariff = $request->input('feed_in_tariff');

            if ($data->save()) {
                $data->load('userDetails');
                $orderNo = $data->id + 1000;
                $data->order_no = $orderNo;
                $name = $data->userDetails ? $data->userDetails->name : '';
                $email = $data->userDetails ? $data->userDetails->email : '';

                $data->save();
                // if ($request->has('advantages')) {
                //     foreach ($request->advantages as $key => $value) {
                //         PostRequest::updateOrCreate(
                //             ['request_id' => $data->id, 'key' => $key],
                //             [
                //                 'value' => $value
                //             ]
                //         );
                //     }
                // }

                // $emailTemplate = EmailTemplate::where('email_of', '2')->first();

                // $body['body'] = str_replace(['{{ $name }}', '{{ $orderNo }}'], [$name, $orderNo], $emailTemplate->mail_body);
                // $body['name'] = $name;
                // $body['action_link'] = url('/') . '/api/view-order/' . $orderNo;
                // // return $body;
                // Mail::to($email)->send(new CustomerRequestSubmit($body));

                return response()->json(['status' => true, 'data' => ['name' => $name, 'email' => $email, 'order_no' => $orderNo], 'message' => 'User request saved successfully']);
            } else {
                return response()->json(['status' => false, 'message' => 'Failed to save user request']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $request_id)
    {
        // return 123;
        $userRequest = UserRequest::with('service', 'advantagesData', 'userDetails', 'providerDetails', 'categoryDetails')->find($request_id);

        // Change the format of the desired columns here
        $userRequest->shipping_address = json_decode($userRequest->shipping_address);
        $userRequest->advantages = json_decode($userRequest->advantages);
        return $this->jsonResponse(true, 'User request retrieved successfully.',  $userRequest);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRequest $userRequest, Post $post)
    {


        // if(\Auth::guard('admin')->user()->can('view',$post)){
        //     return view('admin.posts.edit',['post'=>$post]);
        // }

        $this->authorize('view', $post);
        return view('admin.posts.edit', ['post' => $post]);
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
        $this->authorize('update', $post);
        $post->update([
            'title' => $request->title,
            'description' => $request->description
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
        // return $request->order_no;
        $order_data = UserRequest::where('order_no', $id)->first();

        return $this->jsonResponse(true,  'Order data retrieved successfully.', $order_data);
        // return view('admin.requests.edit', compact('user_request'));
    }

    public function getUserData(Request $request)
    {
        $userData = UserData::where('user_id', $request->user_id)->get();
        return $this->jsonResponse(true, 'User data retrieved successfully.', $userData);
    }
    public function saveUserData(Request $request)
    {
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
            return response()->json(['status' => true, 'message' => 'User data saved successfully']);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        } catch (QueryException $e) {
            // Handle other database errors
            return response()->json(['status' => false, 'message' => 'Database error occurred']);
        }
    }
    public function reviewList(Request $request)
    {
        $query = Review::where('user_id', $request->user_id);

        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        $userData = $query->latest()->get();

        return $this->jsonResponse(true,  'Review data retrieved successfully.', $userData);
    }


    public function reviewSave(Request $request)
    {
        try {
            // $userRequest = UserRequest::where('user_id', $request->user_id)
            //     ->where('post_id', $request->post_id)->firstOrFail();

            $validatedData = $request->validate([
                'user_id' => 'required',
                'rating' => 'required|numeric',
                'post_id' => 'required|numeric',
            ]);


            Review::updateOrCreate(
                ['user_id' => $validatedData['user_id'], 'post_id' => $validatedData['post_id']],
                ['rating' => $validatedData['rating'], 'category' => $request->category, 'sub_category' => $request->sub_category, 'user_type' => $request->user_type, 'rating_type' => $request->rating_type]
            );



            return response()->json(['status' => true, 'message' => 'Review saved successfully']);
        } catch (ValidationException $e) {

            return response()->json(['status' => false, 'errors' => $e->errors()]);
        } catch (QueryException $e) {

            return response()->json(['status' => false, 'message' => 'Database error occurred']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(['error' => 'User request not found']);
        }
    }

    public function reviewShow(Request $request)
    {
        $review = Review::where('id', $request->review_id)->first();
        return $this->jsonResponse(true,  'User review retrieved successfully.', $review);
    }

    public function getDealsData()
    {
        $deals = Deal::latest()->take(3)->get();
        $deals = $deals->map(function ($deal) {
            // $deal->icon =  asset('deal_icons/' . $deal->icon);
            // $deal->categoryDetails;
            // $deal->products = json_decode($deal->products);
            // return $deal;
            return [
                'id' => $deal->id,
                'title' => $deal->title,
                'valid_till' => $deal->valid_till,
                'icon' => asset('deal_icons/' . $deal->icon),
                'category' => $deal->category,
                'sub_category' => $deal->sub_category,
                'products' => json_decode($deal->products, true),
                'category_details' => $deal->categoryDetails,
            ];
        });
        return $this->jsonResponse(true,  'Deals retrieved successfully.', $deals);
    }
    private function getProductsCategoryWise($product_ids, $category_id)
    {
        $request = new Request();
        $request['category_id'] = $category_id;
        $request['callFromExclusiveDeal'] = 1;

        if ($request->category_id == 1) {
            $internetTvObj = new InternetTvController();
            $products =  $internetTvObj->index($request);
            $products[0] = collect($products[0])->filter(function ($product) use ($product_ids) {
                return in_array($product['id'], $product_ids);
            });
            return $products;
        } elseif ($request->category_id == 2) {

            $products = [];
            return $products;
        } elseif ($request->category_id == 5) {

            $products = InsuranceProduct::whereIn('id', $product_ids)->get();
            return $products;
        } elseif ($request->category_id == 6) {

            $products = [];
            return $products;
        } elseif ($request->category_id == 13) {

            $products = [];
            return $products;
        } elseif ($request->category_id == 14) {

            $products = [];
            return $products;
        } elseif ($request->category_id == 16) {
            $energyObj = new EnergyController();
            $products = $energyObj->index($request);
            $products[0] = collect($products[0])->filter(function ($product) use ($product_ids) {
                return in_array($product['id'], $product_ids);
            });

            return $products;
        }
    }
    public function getExclusiveDeal(Request $request)
    {
        $deal = Deal::where('id', $request->id)->first();
        if ($deal) {
            $deal->icon =  asset('deal_icons/' . $deal->icon);
            $deal->categoryDetails;
            $dealData = $this->getProductsCategoryWise(json_decode($deal->products), $deal->category);
            return response()->json([
                'status' => true,
                'data' => $dealData[0],
                'filters' => $dealData[1],
                'message' => 'Deal retrieved successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'filters' => [],
                'message' => 'Deal not found in the requested id'
            ]);
        }
    }

    public function getTvPackages(Request $request)
    {
        $records = TvPackage::latest()->with('providerDetails')->get();
        if ($records) {
            return response()->json([
                'status' => true,
                'data' => $records,
                'message' => 'Packages retrieved successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Packages not found'
            ]);
        }
    }
    public function getTvInternetOptions()
    {
        $records = TvOption::latest()->with('providerDetails')->get();
        if ($records) {
            $records->map(function ($record) {

                $record->internet_options = array_map(function ($option) {
                    $option->package_name = $option->name;
                    $option->package_price = (int)$option->normal_cost;
                    unset($option->name);
                    unset($option->normal_cost);
                    return $option;
                }, json_decode($record->internet_options));

                $record->tv_options = array_map(function ($option) {
                    $option->package_name = $option->name;
                    $option->package_price = (int)$option->normal_cost;
                    unset($option->name);
                    unset($option->normal_cost);
                    return $option;
                }, json_decode($record->tv_options));

                $record->telephone_options = array_map(function ($option) {
                    $option->package_name = $option->name;
                    $option->package_price = (int)$option->normal_cost;
                    unset($option->name);
                    unset($option->normal_cost);
                    return $option;
                }, json_decode($record->telephone_options));
                return $record;
            });
            return response()->json([
                'status' => true,
                'data' => $records,
                'message' => 'TvInternetOptions retrieved successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'TvInternetOptions not found'
            ]);
        }
    }

    public function eventList(Request $request)
    {
        $code =  $request->postal_code;
        // Fetch all event from the database
        $events = Event::where('status', 'active')->with('roomDetails', 'catererDetails', 'eventTypes');
        if ($request->has('postal_code')) {
            $events->where('postal_code');
        }
        $dataEvent = $events->latest()->get();

        // return $dataEvent;
        $room_type = EventRoom::where('status', 'active')->get();
        $caterer = Caterer::where('status', 'active')->get();
        $event_type = EventType::where('status', 'active')->get();
        $event_theme = EventTheme::where('status', 'active')->get();


        $data['events'] = $dataEvent;
        $data['room_type'] = $room_type;
        $data['caterer'] = $caterer;
        $data['event_type'] = $event_type;
        $data['event_theme'] = $event_theme;

        return $data['events'];
        if ($data['events']) {
            // Return the data as a JSON response
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'Event retrieved successfully'
            ]);
        } else {
            // Return the data as a JSON response
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Event retrieved successfully'
            ]);
        }
    }


    public function getTopFourDeals(Request $request)
    {
        $deals = Deal::latest()->take(4)->get();
        if (count($deals) > 0) {
            $deals->map(function ($deal) {
                $deal->icon =  asset('deal_icons/' . $deal->icon);
                $deal->categoryDetails;
                $deal->products = json_decode($deal->products);
                return $deal;
            });
        }

        return $this->jsonResponse(true, 'Deals retrieved successfully.', $deals);
    }
    public function getEnergyDeals(Request $request)
    {
        $deals = Deal::where(['category' => 16, 'status' => 'active'])->latest()->take(4)->get();
        if (count($deals) > 0) {
            $deals->map(function ($deal) {
                $deal->icon =  asset('deal_icons/' . $deal->icon);
                $deal->categoryDetails;
                $deal->products = json_decode($deal->products);
                return $deal;
            });
            return $this->jsonResponse(true, 'Deals retrieved successfully.', $deals);
        }

        return $this->jsonResponse(false, 'Deals not found.');
    }

    public function getEnergyData(Request $request)
    {
        $providers = Provider::where('category', config('constant.category.energy'))->get();

        if (count($providers) > 0) {

            $providers = $providers->map(function ($provider) {
                $provider->image = asset('storage/images/providers/' . $provider->image);
                return $provider;
            });

            $filterData = [];
            foreach ($providers as $k => $v) {
                $filterData[$k] = [
                    'id' => $v->id,
                    'name' => $v->name,
                    'image' => $v->image,
                    'about' => $v->about,
                    'address' => $v->address,
                    'payment_options' => $v->payment_options,
                    'annual_accounts' => $v->annual_accounts,
                    'meter_readings' => $v->meter_readings,
                    'adjust_installments' => $v->adjust_installments,
                    'view_consumption' => $v->view_consumption,
                    'rose_scheme' => $v->rose_scheme,
                    'category' => $v->category,
                ];
            }
            return $this->jsonResponse(true, 'Data retrieved successfully.',  $filterData);
        }
        return $this->jsonResponse(false, 'Data not found.');
    }

    public function getInternetTvDeals(Request $request)
    {
        $deals = Deal::where(['category' => 1, 'status' => 'active'])->latest()->take(4)->get();
        if (count($deals) > 0) {
            $deals->map(function ($deal) {
                $deal->icon =  asset('deal_icons/' . $deal->icon);
                $deal->categoryDetails;
                $deal->products = json_decode($deal->products);
                return $deal;
            });
            return $this->jsonResponse(true, 'Deals retrieved successfully.',  $deals);
        }

        return $this->jsonResponse(false, 'Deals not found.');
    }

    public function getInternetTvPackages(Request $request)
    {
        $providers = Provider::where('category', config('constant.category.Internet & Tv'))->get();

        // if ($request->provider_id && $request->provider_id != '') {
        //     $packages = TvPackage::where('provider_id', $request->provider_id)->get();
        // }

        $packages = TvPackage::all();

        $filterPackages = [];
        $filterData = [];

        if (count($packages) > 0) {
            $packages = $packages->map(function ($package) {
                $package->image = asset('storage/images/tvPackages/' . $package->image);
                return $package;
            });
            foreach ($packages as $key => $value) {
                $filterPackages[] = [
                    'id' => $value->id,
                    'package_name' => $value->package_name,
                    'image' => $value->image,
                    'tv_channels' => $value->tv_channels,
                    'features' => $value->features,
                    'package_price' => $value->package_price,
                    'provider_id' => $value->provider_id,
                ];
            }
        }

        if (count($providers) > 0) {
            $providers = $providers->map(function ($provider) {
                $provider->image = asset('storage/images/providers/' . $provider->image);
                return $provider;
            });

            foreach ($providers as $k => $v) {
                $filterData[$k] = [
                    'id' => $v->id,
                    'name' => $v->name,
                    'image' => $v->image,
                    'about' => $v->about,
                    'address' => $v->address,
                    'payment_options' => $v->payment_options,
                    'annual_accounts' => $v->annual_accounts,
                    'meter_readings' => $v->meter_readings,
                    'adjust_installments' => $v->adjust_installments,
                    'view_consumption' => $v->view_consumption,
                    'rose_scheme' => $v->rose_scheme,
                    'category' => $v->category,
                ];
            }
        }

        $finalData['providers'] = $filterData;
        $finalData['packages'] = $filterPackages;

        return $this->jsonResponse(true, 'Data retrieved successfully.', $finalData);
    }
    public function getHomeInsuranceDeals(Request $request)
    {
        $deals = Deal::where(['sub_category' => config('constant.subcategory.HomeInsurance'), 'status' => 'active'])->latest()->take(4)->get();
        if (count($deals) > 0) {
            $deals->map(function ($deal) {
                $deal->icon =  asset('deal_icons/' . $deal->icon);
                $deal->categoryDetails;
                $deal->products = json_decode($deal->products);
                return $deal;
            });
            return $this->jsonResponse(true, 'Deals retrieved successfully.', $deals);
        }

        return $this->jsonResponse(false, 'Deals not found.');
    }

    public function orderView(Request $request, $order_no)
    {
        //  return 1234567989;
        return $user_request = UserRequest::where('order_no', $order_no)->first();
    }

    public function getSmartPhoneDeals()
    {
        $sp_deal = SmartPhone::orderBy('id', 'desc')->where('status', 'active')->with('featuresDetails', 'discountsDetails', 'faqDetails')->get();
        if (!$sp_deal->isEmpty()) {
            return response()->json([
                'status' => true,
                'data' => $sp_deal,
                'message' => 'SmartPhone Deals retrieved successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'SmartPhone Deals not found'
            ]);
        }

        // return $sp_deal;
    }

    public function getSearchData(Request $request)
    {
        $events = InsuranceProduct::latest()->get();

        // Return the data as a JSON response
        return response()->json([
            'status' => true,
            'data' => $events,
            'message' => 'SearchData retrieved successfully'
        ]);
    }

    public function getPurposeData(Request $request)
    {
        if ($request->input('p_id')) {
            $loanData = LoanType::whereJsonContains('p_id', $request->input('p_id'))->get();
        } else {
            $loanData = LoanType::limit(3)->get();
        }

        if (!$loanData->isEmpty()) {
            $mergedData = [];
            foreach ($loanData as $loan) {
                $formattedData = (new LoanTypeResource($loan))->toArray($request);
                $mergedData[] = $formattedData;
            }
            return response()->json([
                'status' => true,
                'data' => $mergedData,
                'message' => 'Data Retrieved Successfully.'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'No Data Found!.'
            ]);
        }
    }

    public function getLoanDetails(Request $request)
    {
        $postalCode = $request->postal_code;
        $purpose = $request->spend_purpose;
        $maxAmount = $request->max_amount;  // Ensure this is a single numeric value
        $expectedAmount = $request->exp_amount;  // Ensure this is a single numeric value
        $provider = $request->provider;
        $loanType = $request->input('loan_type');

        // return $maxAmount;
        $loanTypes = LoanProduct::latest()->with('purposeDetails', 'providerDetails')->where('status', 1);

        $loanTypes->when($postalCode, function ($query) use ($postalCode) {
            $query->whereJsonContains('pin_codes', $postalCode);
        })
            ->when($purpose, function ($query) use ($purpose) {
                $query->where('p_id', $purpose);
            })
            ->when($provider, function ($query) use ($provider) {
                $query->whereJsonContains('provider', $provider);
            })
            ->when($loanType, function ($query) use ($loanType) {
                $query->whereJsonContains('loan_type_id', $loanType);
            })
            ->when($maxAmount, function ($query) use ($maxAmount) {
                $query->where('borrow_amount', '>=', $maxAmount);
            })
            ->when($expectedAmount, function ($query) use ($expectedAmount) {
                $query->where('expected_amount', '>=', $expectedAmount);
            });

        $loanTypes = $loanTypes->get(); // Execute the query and get the collection

        // Check if the collection is empty
        if ($loanTypes->isEmpty()) {
            $recordsCount = $loanTypes->count();
            $message = $loanTypes->count() > 0 ? 'Loan Products retrieved successfully.' : 'Loan Products not found.';
            $code = $loanTypes->count() > 0 ? 200 : 404;

            return response()->json([
                'status' => true,
                'data' => [],
                'recordsCount' => $recordsCount,
                'message' => $message
            ]);
        } else {

            $mergedData = [];
            foreach ($loanTypes as $loans) {
                $formattedData = (new LoanProductResource($loans))->toArray($request);
                $mergedData[] = $formattedData;
            }

            $recordsCount = $loanTypes->count();
            $message = $loanTypes->count() > 0 ? 'Loan Products retrieved successfully.' : 'Loan Products not found.';
            $code = $loanTypes->count() > 0 ? 200 : 404;


            return response()->json([
                'status' => true,
                'data' => $mergedData,
                'recordsCount' => $recordsCount,
                'message' => $message
            ]);
        }
    }

    public function nominalFees(Request $request)
    {
        if ($request->input('category_id') != null) {
            $feeSetting = FeeSetting::where('category_id', $request->input('category_id'))->first();
            if ($feeSetting) {
                return response()->json([
                    'status' => true,
                    'feeAmount' => $feeSetting->amount,
                    'message' => 'Nominal Fee Retrieved.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Nominal Fee not found for the provided Category ID.'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Category ID required'
            ]);
        }
    }

    public function cyberSecurity(Request $request)
    {
        // return 123;
        $postalCode = $request->postal_code;
        $licenseDuration = $request->license_duration;
        $cloudBackUp = $request->cloud_backup;
        $noOfPCs = $request->no_of_pc;

        $security = CyberSecurity::with('featureDetails', 'providerDetails');

        $security->when($postalCode, function ($query) use ($postalCode) {
            $query->whereJsonContains('pin_codes', $postalCode);
        })
            ->when($licenseDuration, function ($query) use ($licenseDuration) {
                $query->where('license_duration', '>=', $licenseDuration);
            })
            ->when($noOfPCs, function ($query) use ($noOfPCs) {
                $query->where('no_of_pc', '>=', $noOfPCs);
            })
            ->when($cloudBackUp, function ($query) use ($cloudBackUp) {
                $query->where('cloud_backup', '>=', $cloudBackUp);
            });

        $securities = $security->get();


        // $insuranceProduct = InsuranceProduct::where('sub_category', config('constant.subcategory.HomeInsurance'))->get();


        // $insuranceProduct = $insuranceProduct->map(function ($coverage) {
        //     $coverage->image = asset('storage/images/insurance/' . $coverage->image);
        //     return $coverage;
        // });

        $coverages = insuranceCoverage::where('subcategory_id', config('constant.subcategory.HomeInsurance'))->get();

        $coverages = $coverages->map(function ($coverage) {
            $coverage->image = asset('storage/images/insurance_coverages/' . $coverage->image);
            return $coverage;
        });

        // Check if the collection is empty
        if ($securities->isEmpty()) {
            $recordsCount = $securities->count();
            $message = $securities->count() > 0 ? 'Product retrieved successfully.' : ' Products not found.';
            $code = $securities->count() > 0 ? 200 : 404;

            return response()->json([
                'status' => true,
                'data' => [],
                'recordsCount' => $recordsCount,
                'message' => $message
            ]);
        } else {

            $mergedData = [];
            foreach ($securities as $security) {
                $formattedData = (new SecurityResource($security))->toArray($request);
                $mergedData[] = $formattedData;
            }

            $recordsCount = $securities->count();
            $message = $securities->count() > 0 ? 'Products retrieved successfully.' : ' Products not found.';
            $code = $securities->count() > 0 ? 200 : 404;

            // $this->jsonResponse(true, $message, $mergedData);
            return response()->json([
                'status' => true,
                'data' => $mergedData,
                'recordsCount' => $recordsCount,
                'insurance' => $coverages,
                'message' => $message
            ]);
        }

        // return $securities;
    }

    private function jsonResponse($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }
}
