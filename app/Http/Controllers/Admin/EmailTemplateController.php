<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:email-template-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:email-template-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:email-template-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:email-template-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email_templates.index', compact('templates'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $templates = EmailTemplate::where('status', 1)->get();
        return view('admin.email_templates.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_of' => 'string|required',
            'mail_subject' => 'required',
            'mail_body' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = [
        'email_of' => $request->email_of,
        'mail_subject' => $request->mail_subject,
        'mail_body' => $request->mail_body,
        'status' => $request->status,
        'signature' => $request->mail_signature
        ];

        $emailTemplate = EmailTemplate::updateOrCreate(['email_of' => $request->email_of], $data);

        if ($emailTemplate) {
            //return response()->json(['success' => true, 'data' => $emailTemplate], 200);
            return redirect()->route('admin.email-templates.index')->with(Toastr::success('Email Template Created Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            //return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.'], 500);
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
       $template = EmailTemplate::where('id', $id)->first();
        return view('admin.email_templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email_template = EmailTemplate::where('id', $id)->first();
        return view('admin.email_templates.edit', compact('email_template'));
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
        $validator = Validator::make($request->all(), [
            'email_of' => 'string|required',
            'mail_subject' => 'required',
            'mail_body' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = [
        'email_of' => $request->email_of,
        'mail_subject' => $request->mail_subject,
        'mail_body' => $request->mail_body,
        'status' => $request->status,
        'signature' => $request->mail_signature
        ];

        $emailTemplate = EmailTemplate::updateOrCreate(['email_of' => $request->email_of], $data);

        if ($emailTemplate) {
            //return response()->json(['success' => true, 'data' => $emailTemplate], 200);
            Toastr::success('Email Template Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.'], 500);
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
       
        $getTemplate = EmailTemplate::find($id);
        try {
            EmailTemplate::find($id)->delete();
            return back()->with(Toastr::error(__('Template deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.categories.index')->with($error_msg);
        }
    }
}
