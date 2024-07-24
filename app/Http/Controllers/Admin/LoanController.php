<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use App\Models\LoanType;
use App\Models\Bank;
use App\Models\Category;
use App\Models\SpendingPurpose;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = LoanProduct::latest()->with('purposeDetails')->get();
        return view('admin.loan_products.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $objCategory = Category::whereNull('parent')->get();
        return view('admin.loan_products.add', compact('objCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required',
            'max_price_borrow' => 'required',
            'expected_amount' => 'required',
            'pin_codes' => 'required',
            'p_id' => 'required',
            'interest_rate' => 'required'
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        $loan = new LoanProduct();
        $loan->title = $request->title;
        $loan->slug = $slug;
        $loan->description = $request->description2;
        $loan->borrow_amount = $request->max_price_borrow;
        $loan->expected_amount = $request->expected_amount;
        $loan->p_id = $request->p_id;
        $loan->category = $request->category;
        $loan->product_type = $request->product_type;
        $loan->service_types = $request->service_type;
        $loan->rate_of_interest = $request->interest_rate;
        $loan->approval_time = $request->avg_delivery_time;
        $loan->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
        $loan->provider = $request->provider ? json_encode($request->provider) : [];
        $loan->loan_type_id = $request->loan_type_id ? json_encode($request->loan_type_id) : [];
        $loan->status = $request->status;

        if ($request->image) {
            // Handle the image file upload
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);
        }

        // Save the filename in the database
        $loan->image = $filename ?? '';

        try {
            if ($loan->save()) {
                return redirect()->back()->with(Toastr::success('Loan Added Successfully', '', ["positionClass" => "toast-top-right"]));
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
        $loan = LoanProduct::find($id);
        $objCategory = Category::whereNull('parent')->get();
        return view('admin.loan_products.edit', compact('objCategory', 'loan'));
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
            'title' => 'required',
            'max_price_borrow' => 'required',
            'expected_amount' => 'required',
            'pin_codes' => 'required',
            'p_id' => 'required',
            'interest_rate' => 'required'
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        $loan = LoanProduct::find($id);
        $loan->title = $request->title;
        $loan->slug = $slug;
        $loan->description = $request->description2;
        $loan->borrow_amount = $request->max_price_borrow;
        $loan->expected_amount = $request->expected_amount;
        $loan->p_id = $request->p_id;
        $loan->category = $request->category;
        $loan->product_type = $request->product_type;
        $loan->service_types = $request->service_type;
        $loan->rate_of_interest = $request->interest_rate;
        $loan->approval_time = $request->avg_delivery_time;
        $loan->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
        $loan->provider = $request->provider ? json_encode($request->provider) : [];
        $loan->loan_type_id = $request->loan_type_id ? json_encode($request->loan_type_id) : [];
        $loan->status = $request->status;


        if ($request->image) {
            // Handle the image file upload
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);
        }

        // Save the filename in the database
        $loan->image = $filename ?? $loan->image;

        try {
            if ($loan->save()) {
                return redirect()->back()->with(Toastr::success('Loan Updated Successfully', '', ["positionClass" => "toast-top-right"]));
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
            LoanProduct::find($id)->delete();
            return redirect()->back()->with(Toastr::success('Loan Product Deleted', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
}
