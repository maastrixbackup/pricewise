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
use App\Models\PostRequest;
use App\Models\InsuranceProduct;
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
            return $this->sendResponse($userData, 'User requests retrieved successfully.');
        } else {
            return response()->json(['success' => false, 'data' => [], 'message' => 'Failed to get user request.'], 404);
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
        $additional_questions = [];
        $verification = [];
        $delivery = [];
        $contact_details = [];
        $company_details = [];
        $final_data = [];

        $additional_questions = [
            "living_start" => $request->input('living_start'),
            "living_description" => $request->input('living_description'),
            "home_feature_valid" => $request->input('home_feature_valid'),
            "home_feature_description" => $request->input('home_feature_description'),
            "garden_garage_valid" => $request->input('garden_garage_valid'),
            "garden_garage_description" => $request->input('garden_garage_description'),
            "fire_hazard_valid" => $request->input('fire_hazard_valid'),
            "office_distance" => $request->input('office_distance'),
            "fire_hazard_description" => $request->input('fire_hazard_description'),
            "renovate_home" => $request->input('renovate_home'),
            "deal_with" => $request->input('deal_with'),
            "injury_validation" => $request->input('injury_validation'),
            "damage_amount" => $request->input('damage_amount'),
            "injury_damage_description" => $request->input('injury_damage_description'),
            "previous_insurance_validation" => $request->input('previous_insurance_validation'),
            "previous_insurance_description" => $request->input('previous_insurance_description'),
            "punishment_validation" => $request->input('punishment_validation'),
            "punishment_description" => $request->input('punishment_description'),

        ];

        $verification = [
            "verification" => $request->input('verification'),
            "get_notified" => $request->input('get_notified'),
            "payment_method" => $request->input('payment_method'),
            "verification_message" => $request->input('verification_message')
        ];

        $delivery = [
            "postalAddressDifferent" => $request->input('postalAddressDifferent'),
            "differ_postal_addrr" => $request->input('differ_postal_addrr'),
            "starting_date" => $request->input('starting_date'),
            "service_cancel" => $request->input('service_cancel'),
            "house_tel" => $request->input('house_tel')

        ];

        $company_details = [
            "company_name" => $request->input('company_name'),
            "chamber_of_commerce" => $request->input('chamber_of_commerce'),
            "function" => $request->input('function'),
            "branch" => $request->input('branch')

        ];

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

        $final_data = [
            "additional_questions" => $additional_questions,
            "verification" => $verification,
            "delivery" => $delivery,
            "company_details" => $company_details,
            "contact_details" => $contact_details
        ];

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
        $contact_details = $contact_details;
        $company_details = $company_details;
        $additional_information = $request->input('additionalInfo');
        $additional_questions = $additional_questions;
        $delivery = $delivery;
        $verification = $verification;

        try {
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
                $name = $data->userDetails ? $data->userDetails->name : '';
                $email = $data->userDetails ? $data->userDetails->email : '';

                $data->save();
                if ($request->has('advantages')) {
                    foreach ($request->advantages as $key => $value) {
                        PostRequest::updateOrCreate(
                            ['request_id' => $data->id, 'key' => $key],
                            [
                                'value' => $value
                            ]
                        );
                    }
                }

                $emailTemplate = EmailTemplate::where('email_of', '2')->first();

                $body['body'] = str_replace(['{{ $name }}', '{{ $orderNo }}'], [$name, $orderNo], $emailTemplate->mail_body);
                $body['name'] = $name;
                $body['action_link'] = url('/') . '/api/view-order/' . $orderNo;
                // return $body;
                Mail::to($email)->send(new CustomerRequestSubmit($body));
                return response()->json(['success' => true, 'data' => ['name' => $name, 'email' => $email, 'order_no' => $orderNo], 'message' => 'User request saved successfully'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to save user request'], 422);
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            \Log::error('Validation error: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Handle other database errors
            \Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        } catch (\Exception $e) {
            // Handle all other errors
            \Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }


        // catch (ValidationException $e) {
        //     // Handle validation errors
        //     return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        // } catch (QueryException $e) {
        //     // Handle other database errors
        //     return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        // }
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

        return $this->sendResponse($order_data, 'Order data retrieved successfully.');
        // return view('admin.requests.edit', compact('user_request'));
    }

    public function getUserData(Request $request)
    {
        $userData = UserData::where('user_id', $request->user_id)->get();
        return $this->sendResponse($userData, 'User data retrieved successfully.');
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
            return response()->json(['success' => true, 'message' => 'User data saved successfully'], 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            // Handle other database errors
            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        }
    }
    public function reviewList(Request $request)
    {
        $query = Review::where('user_id', $request->user_id);

        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        $userData = $query->latest()->get();

        return $this->sendResponse($userData, 'Review data retrieved successfully.');
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



            return response()->json(['success' => true, 'message' => 'Review saved successfully'], 200);
        } catch (ValidationException $e) {

            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (QueryException $e) {

            return response()->json(['success' => false, 'message' => 'Database error occurred'], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(['error' => 'User request not found'], 422);
        }
    }

    public function reviewShow(Request $request)
    {
        $review = Review::where('id', $request->review_id)->first();
        return $this->sendResponse($review, 'User review retrieved successfully.');
    }

    public function getDealsData()
    {
        $deals = Deal::latest()->take(3)->get();
        $deals->map(function ($deal) {
            $deal->icon =  asset('deal_icons/' . $deal->icon);
            $deal->categoryDetails;
            $deal->products = json_decode($deal->products);
            return $deal;
        });
        return $this->sendResponse($deals, 'Deals retrieved successfully.');
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
                'success' => true,
                'data' => $dealData[0],
                'filters' => $dealData[1],
                'message' => 'Deal retrieved successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
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
                'success' => true,
                'data' => $records,
                'message' => 'Packages retrieved successfully'
            ]);
        } else {
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
                'success' => true,
                'data' => $records,
                'message' => 'TvInternetOptions retrieved successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
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
            $events->where('postal_code', $code);
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
                'success' => true,
                'data' => $data,
                'message' => 'Event retrieved successfully'
            ]);
        } else {
            // Return the data as a JSON response
            return response()->json([
                'success' => true,
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

        return $this->sendResponse($deals, 'Deals retrieved successfully.');
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
            return $this->sendResponse($deals, 'Deals retrieved successfully.');
        }

        return $this->sendError('Deals not found.');
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
            return $this->sendResponse($deals, 'Deals retrieved successfully.');
        }

        return $this->sendError('Deals not found.');
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
            return $this->sendResponse($deals, 'Deals retrieved successfully.');
        }

        return $this->sendError('Deals not found.');
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
                'success' => true,
                'data' => $sp_deal,
                'message' => 'SmartPhone Deals retrieved successfully.'
            ]);
        } else {
            return response()->json([
                'error' => false,
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
            'success' => true,
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
                'success' => true,
                'data' => $mergedData,
                'message' => 'Data Retrieved Successfully.'
            ]);
        } else {

            return response()->json([
                'success' => false,
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
                'success' => true,
                'data' => [],
                'recordsCount' => $recordsCount,
                'message' => $message
            ], $code);
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
                'success' => true,
                'data' => $mergedData,
                'recordsCount' => $recordsCount,
                'message' => $message
            ], $code);
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
                'success' => true,
                'data' => [],
                'recordsCount' => $recordsCount,
                'message' => $message
            ], $code);
        } else {

            $mergedData = [];
            foreach ($securities as $security) {
                $formattedData = (new SecurityResource($security))->toArray($request);
                $mergedData[] = $formattedData;
            }

            $recordsCount = $securities->count();
            $message = $securities->count() > 0 ? 'Products retrieved successfully.' : ' Products not found.';
            $code = $securities->count() > 0 ? 200 : 404;


            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'recordsCount' => $recordsCount,
                'insurance' => $coverages,
                'message' => $message
            ], $code);
        }

        // return $securities;
    }
}
