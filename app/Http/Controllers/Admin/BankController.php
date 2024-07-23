<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Validator;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objBanks = Bank::with('countryDetails')->where('status', 'active')->orderBy('id', 'desc')->get();
        return view('admin.banks.index', compact('objBanks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banks.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|unique:banks,bank_name',
            'swift_code' => 'required',
            'country_id' => 'required',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->bank_name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $banks = new Bank();
        $banks->bank_name = $request->bank_name;
        $banks->swift_code = $request->swift_code;
        $banks->slug = $slug;
        $banks->country_id = $request->country_id;
        $banks->status = $request->isenable;

        if ($request->image) {
        // Handle the image file upload
        $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('storage/images/bank_images/'), $filename);
        }
        $banks->image = $filename ?? '';

        try {
            if ($banks->save()) {
                return redirect()->back()->with(Toastr::success('Bank Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
        $edit_bank = Bank::find($id);
        return view('admin.banks.edit', compact('edit_bank'));
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
        $request->validate([
            'bank_name' => 'required',
            'swift_code' => 'required',
            'country_id' => 'required',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->bank_name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $banks = Bank::find($id);
        $banks->bank_name = $request->bank_name;
        $banks->swift_code = $request->swift_code;
        $banks->slug = $slug;
        $banks->country_id = $request->country_id;
        $banks->status = $request->isenable;

        if ($request->image) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/bank_images/'), $filename);
        }

        $banks->image = $filename ?? $banks->image;

        try {
            if ($banks->save()) {
                return redirect()->back()->with(Toastr::success('Bank Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Bank::find($id)->delete();
            return back()->with(Toastr::error(__('Bank deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.events.index')->with($error_msg);
        }
    }
}
