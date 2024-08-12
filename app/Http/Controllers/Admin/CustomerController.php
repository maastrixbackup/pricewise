<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:customer-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objUsers = User::latest()->get();
        return view('admin.customers.index', compact('objUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = Country::all();
        return view('admin.customers.add', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objUser = new User();
        $objUser->name = $request->name;
        $objUser->email = $request->email;
        $objUser->mobile =  $request->mobile;
        $objUser->address =  $request->address;
        $objUser->country =  $request->country;
        $objUser->status =  $request->status;
        $objUser->password = Hash::make('password');


        if ($request->image) {
            // Handle the image file upload
            $filename = 'customer_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/customers/'), $filename);
        }
        // Save the filename in the database
        $objUser->photo = $filename ?? '';


        if ($objUser->save()) {
            return redirect()->route('admin.customers.index')->with(Toastr::success('Customer Added Successfully', '', ["positionClass" => "toast-top-right"]));
            // Toastr::success('Customer Added Successfully', '', ["positionClass" => "toast-top-right"]);
            // return response()->json(["status" => true, "redirect_location" => route("admin.customers.index")]);
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));

            // $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            // return response()->json(["status" => false, 'message' => $message]);
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
        $objUser = User::find($id);
        $country = Country::all();
        return view('admin.customers.edit', compact('objUser', 'country'));
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
        $objUser = User::where('id', $id)->first();
        $objUser->name = $request->name;
        $objUser->email = $request->email;
        $objUser->mobile =  $request->mobile;
        $objUser->address =  $request->address;
        $objUser->country =  $request->country;
        $objUser->status =  $request->status;

        if ($request->image) {
            // Handle the image file upload
            $filename = 'customer_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/customers/'), $filename);

            // Check if the user has an existing image
            if (!empty($objUser->photo)) {
                $existingFilePath = public_path('storage/images/customers/') . $objUser->photo;
                if (file_exists($existingFilePath)) {
                    // Delete the file if it exists
                    unlink($existingFilePath);
                }
            }
        }
        // Save the filename in the database
        $objUser->photo = $filename ?? $objUser->photo;


        if ($objUser->save()) {
            return redirect()->route('admin.customers.index')->with(Toastr::success('Customer Updated Successfully', '', ["positionClass" => "toast-top-right"]));

            // return response()->json(["status" => true, "redirect_location" => route("admin.customers.index")]);
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));

            // $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            // return response()->json(["status" => false, 'message' => $message]);
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
        $getUser = User::find($id);
        try {
            User::find($id)->delete();
            return back()->with(Toastr::error(__('Customer deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.customers.index')->with($error_msg);
        }
    }

    public function pw_generate($id)
    {
        $objUser = User::find($id);
        return view('admin.customers.pw_generate', compact('objUser'));
    }

    public function pw_update(Request $request, $id)
    {
        $objUser = User::where('id', $id)->first();
        $objUser->password =  Hash::make($request->password);
        if ($objUser->save()) {

            //Mail Send starts here

            $email = $objUser->email;
            $body = [
                'user' =>  $objUser,
                'password' => $request->password
            ];

            Mail::to($email)->send(new WelcomeMail($body));


            //Mail Send Ends here

            Toastr::success('Password Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.customers.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function statusChange(Request $request)
    {
        $objUser = User::where('id', $request->id)->first();
        if ($objUser->status == 'Rejected') {
            $objUser->status = 'Approved';
        } elseif ($objUser->status == 'Approved') {
            $objUser->status = 'Rejected';
        } else {
            $objUser->status = $request->approve ? 'Approved' : 'Rejected';
        }
        if ($objUser->save()) {
            return back()->with(Toastr::success(__('Status Changed successfully!')));
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function approve()
    {
        $objUsers = User::where('status', 'Approved')->latest()->get();
        return view('admin.customers.approve_customers', compact('objUsers'));
    }

    public function reject()
    {
        $objUsers = User::where('status', 'Rejected')->latest()->get();
        return view('admin.customers.reject_customers', compact('objUsers'));
    }
}
