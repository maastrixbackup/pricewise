<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterTemplate;
use Brian2694\Toastr\Facades\Toastr;

class NewsletterTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletter = NewsletterTemplate::latest()->get();
        return view('admin.newsletter.index', compact('newsletter'));
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
        $newsletter = NewsletterTemplate::find($request->submitBtn);
        $newsletter->desc_html = $request->newcont;
        if($newsletter->save()){
            Toastr::success('Newsletter updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, 'redirect_location' => route('admin.create-campaign')]);
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
        $newsletter = NewsletterTemplate::find($id);
        if ($newsletter->id == 1) {
            $newsletter = NewsletterTemplate::find(1);
          $heading = json_decode($newsletter->desc)->heading;
          $description = json_decode($newsletter->desc)->description;
          $image = json_decode($newsletter->desc)->image;
          $title = json_decode($newsletter->desc)->title;
          $desc1 = json_decode($newsletter->desc)->desc1;
          $btnlink = json_decode($newsletter->desc)->btnlink;
          $desc2 = json_decode($newsletter->desc)->desc2;
          $footer = json_decode($newsletter->desc)->footer;
         // dd(json_decode($newsletter->desc)->heading[0]);
            return view('admin.newsletter.newsletter-email-tetrapis',compact('newsletter','heading','description','image','title','desc1','btnlink','desc2','footer'));
        }
        if ($newsletter->id == 2) {
            return view('admin.newsletter.newsletter-email-dineos');
        }
        if ($newsletter->id == 3) {
            return view('admin.newsletter.newsletter-email-orthipis');
        }
        if ($newsletter->id == 4) {
            return view('admin.newsletter.newsletter-email-aleos');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsletter = NewsletterTemplate::find($id);
        if ($newsletter->id == 1) {
            $newsletter = NewsletterTemplate::find(1);
          $heading = json_decode($newsletter->desc)->heading;
          $description = json_decode($newsletter->desc)->description;
          $image = json_decode($newsletter->desc)->image;
          $title = json_decode($newsletter->desc)->title;
          $desc1 = json_decode($newsletter->desc)->desc1;
          $btnlink = json_decode($newsletter->desc)->btnlink;
          $desc2 = json_decode($newsletter->desc)->desc2;
          $footer = json_decode($newsletter->desc)->footer;
          //dd(json_decode($newsletter->desc)->heading);
            return view('admin.newsletter.newsletter-email-tetrapis-edit',compact('newsletter','heading','description','image','title','desc1','btnlink','desc2','footer'));
        }
        if ($newsletter->id == 2) {
            return view('admin.newsletter.newsletter-email-dineos-edit');
        }
        if ($newsletter->id == 3) {
            return view('admin.newsletter.newsletter-email-orthipis-edit');
        }
        if ($newsletter->id == 4) {
            return view('admin.newsletter.newsletter-email-aleos-edit');
        }
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
        $newsletter = NewsletterTemplate::find($id);
        if ($newsletter->id == 1) {
            $newsletter = NewsletterTemplate::find(1);
            $newsletter_desc =json_encode($request->only('heading','description','image','title','desc1','btnlink','desc2','footer'));
            $newsletter->desc = $newsletter_desc;
        //   $description = json_decode($newsletter->desc)->description[0];
        //   $image = json_decode($newsletter->desc)->image[0];
        //   $title = json_decode($newsletter->desc)->title[0];
        //   $desc1 = json_decode($newsletter->desc)->desc1[0];
        //   $btnlink = json_decode($newsletter->desc)->btnlink[0];
        //   $desc2 = json_decode($newsletter->desc)->desc2[0];
        //   $footer = json_decode($newsletter->desc)->footer[0];
         // dd(json_decode($newsletter->desc)->heading[0]);
         
         if ($newsletter->save()) {
            Toastr::success('Subscriber updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, 'redirect_location' => route('admin.newsletter-template')]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
        }
        if ($newsletter->id == 2) {
            return view('admin.newsletter.newsletter-email-dineos-edit');
        }
        if ($newsletter->id == 3) {
            return view('admin.newsletter.newsletter-email-orthipis-edit');
        }
        if ($newsletter->id == 4) {
            return view('admin.newsletter.newsletter-email-aleos-edit');
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
        //
    }
}
