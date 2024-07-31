<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use App\Models\EventRoom;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evs_type = EventType::orderBy('id', 'desc')->get();
        return view('admin.events_type.index', compact('evs_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events_type.add');
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
            'name' => 'required|unique:event_types,title',
            // 'image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            // 'sub_category' => 'required',
        ]);

        try {

            // Convert to lowercase
            $slug = strtolower($request->name);

            // Remove special characters
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

            // Replace spaces and multiple hyphens with a single hyphen
            $slug = preg_replace('/[\s-]+/', '-', $slug);

            // Trim hyphens from the beginning and end of the string
            $slug = trim($slug, '-');

            // Create a new instance of EventType
            $ev_type = new EventType();
            $ev_type->title = $request->name;
            $ev_type->slug = $slug;
            $ev_type->description = $request->description;
            $ev_type->status = $request->status;

            // Handle the image file upload
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/event_type/'), $filename);

            // Save the filename in the database
            $ev_type->image = $filename;
            $ev_type->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', ["positionClass" => "toast-top-right"]);
        } catch (\Exception $e) {
            // Redirect back with a warning message if an exception occurs
            return redirect()->back()->with($e->getMessage(), ["positionClass" => "toast-top-right"]);
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
        $objEvent = EventType::where('id', $id)->first();
        return view('admin.events_type.edit', compact('objEvent'));
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

        try {

            // Convert to lowercase
            $slug = strtolower($request->name);

            // Remove special characters
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

            // Replace spaces and multiple hyphens with a single hyphen
            $slug = preg_replace('/[\s-]+/', '-', $slug);

            // Trim hyphens from the beginning and end of the string
            $slug = trim($slug, '-');

            // Create a new instance of EventType
            $ev_type_update =  EventType::find($id);
            $ev_type_update->title = $request->name;
            $ev_type_update->slug = $slug;
            $ev_type_update->description = $request->description;
            $ev_type_update->status = $request->status;

            if (isset($request->image)) {
                $filename = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/event_type/'), $filename);


                $existingFilePath = public_path('storage/images/event_type/') . $ev_type_update->image;

                if (file_exists($existingFilePath)) {
                    // Delete the file
                    unlink($existingFilePath);
                }
            }

            $ev_type_update->image = $filename ?? $ev_type_update->image;
            $ev_type_update->save();

            // Toastr::success('Event Type Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.events_type.index');
        } catch (\Exception $e) {
            // Redirect back with a warning message if an exception occurs
            return redirect()->back()->with($e->getMessage(),  ["positionClass" => "toast-top-right"]);
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
            EventType::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Event Type Deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with($e->getMessage());
        }
    }
}
