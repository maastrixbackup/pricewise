<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Event;
use App\Models\EventType;
use App\Models\EventDoc;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $objEvent = Event::latest()->get();
        return view('admin.events.index', compact('objEvent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.events.add');
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
        $objEvent = new Event();
        $objEvent->name = $request->name;
        $objEvent->slug = $request->url;
        $objEvent->event_type = $request->event_type;
        $objEvent->caterer_id = $request->caterer_id;
        $objEvent->description = $request->description;
        $objEvent->location = $request->location;
        $objEvent->postal_code = $request->postal_code;
        $objEvent->house_no = $request->house_no;
        $objEvent->room_type = $request->room_type;
        $objEvent->start_date = $request->start_date;
        $objEvent->end_date = $request->end_date;
        $objEvent->start_time = $request->start_time;
        $objEvent->end_time = $request->end_time;
        $objEvent->catering_price = $request->cateringprice;
        $objEvent->decoration_price = $request->decorationprice;
        $objEvent->photoshop_price = $request->photoshopprice;
        $objEvent->status = $request->status;
        $objEvent->country_id = $request->country_id;
        $objEvent->save();


        if ($objEvent->save()) {
            // if($request->hasfile('image'))
            // {
            //    foreach($request->file('image') as $key => $file)
            //    {
            //        $path = $file->store('public/event_documents');
            //        $name = $file->getClientOriginalName();
            //        $insert[$key]['event_id'] = $objEvent->id;
            //        $insert[$key]['name'] = $name;
            //        $insert[$key]['path'] = $path;
            //    }
            //    EventDoc::insert($insert);
            // }

            return redirect()->route('admin.events.index')->with(Toastr::success('Event Created Successfully', '', ["positionClass" => "toast-top-right"]));
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

        $objEvent = Event::find($id);
        return view('admin.events.edit', compact('objEvent'));
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
        // dd($request->all());
        $objEvent = Event::find($id);
        $objEvent->name = $request->name;
        $objEvent->slug = $request->url;
        $objEvent->event_type = $request->event_type;
        $objEvent->caterer_id = $request->caterer_id;
        $objEvent->description = $request->description;
        $objEvent->location = $request->location;
        $objEvent->postal_code = $request->postal_code;
        $objEvent->house_no = $request->house_no;
        $objEvent->room_type = $request->room_type;
        $objEvent->start_date = $request->start_date;
        $objEvent->end_date = $request->end_date;
        $objEvent->start_time = $request->start_time;
        $objEvent->end_time = $request->end_time;
        $objEvent->catering_price = $request->cateringprice;
        $objEvent->decoration_price = $request->decorationprice;
        $objEvent->photoshop_price = $request->photoshopprice;
        $objEvent->status = $request->status;
        $objEvent->country_id = $request->country_id;

        if ($objEvent->save()) {
            // EventDoc::where('id',$id)->delete();
            // if($request->hasfile('image'))
            // {
            //    foreach($request->file('image') as $key => $file)
            //    {
            //        $path = $file->store('public/event_documents');
            //        $name = $file->getClientOriginalName();
            //        $insert[$key]['event_id'] = $objEvent->id;
            //        $insert[$key]['name'] = $name;
            //        $insert[$key]['path'] = $path;
            //    }

            //    EventDoc::insert($insert);
            // }
            return redirect()->route('admin.events.index')->with(Toastr::success('Event Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            // Toastr::success('Event Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            // return redirect()->route("admin.events.index");
        } else {
            return redirect()->route('admin.events.index')->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
            // $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            // return response()->json(["status" => true, 'message' => $message]);
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
        $getEvent = Event::find($id);
        try {
            Event::find($id)->delete();
            return back()->with(Toastr::error(__('Event deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.events.index')->with($error_msg);
        }
    }

}

