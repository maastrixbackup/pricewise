<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\EnergyRateChat;
use App\Models\Category;
use App\Models\Provider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnergyRateChatController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:energy_rate_chat-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:energy_rate_chat-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:energy_rate_chat-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:energy_rate_chat-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rateChats = EnergyRateChat::latest()->get();
        if($request->provider_id && $request->provider_id != null){
            $ratechat = EnergyRateChat::where('provider', $request->provider_id)->latest()->get();
            return response()->json(['status' => true, 'data' => $ratechat]);
        }
        return view('admin.energy_rate_chat.index', compact('rateChats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();
       return view('admin.energy_rate_chat.add', compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objCategory = new EnergyRateChat();
        $objCategory->provider = $request->provider;
        $objCategory->gas_rate = $request->gas_rate;        
        $objCategory->electric_rate = $request->electric_rate;
        $objCategory->off_peak_electric_rate = $request->off_peak_electric_rate;
        if ($objCategory->save()) {
            return redirect()->route('admin.energy-rate-chat.index')->with(Toastr::success('Energy rate Created Successfully', '', ["positionClass" => "toast-top-right"]));
            // Toastr::success('Driver Created Successfully', '', ["positionClass" => "toast-top-right"]);
            // return response()->json(["status" => true, "redirect_location" => route("admin.drivers.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $providers = Provider::all();
        $rateChat = EnergyRateChat::find($id);
        //$parents = Reimbursement::whereNull('parent')->latest()->get();
        return view('admin.energy_rate_chat.edit', compact('rateChat', 'providers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //echo 123;exit;
        $objCategory = EnergyRateChat::find($id);
        $objCategory->provider = $request->provider;
        //$objCategory->slug = $request->slug;
        $objCategory->gas_rate = $request->gas_rate;
        $objCategory->electric_rate = $request->electric_rate;
        $objCategory->off_peak_electric_rate = $request->off_peak_electric_rate;
        if ($objCategory->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Energy Rate Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.energy-rate-chat.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $getCategory = EnergyRateChat::find($id);
        try {
            EnergyRateChat::find($id)->delete();
            return back()->with(Toastr::error(__('Energy rate deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.energy_rate_chat.index')->with($error_msg);
        }
    }
}

