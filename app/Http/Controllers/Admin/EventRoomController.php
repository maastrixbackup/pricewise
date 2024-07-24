<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRoom;
use Illuminate\Http\Request;

class EventRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objRooms = EventRoom::orderBy('room', 'asc')->get();
        return view('admin.room_type.index', compact('objRooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.room_type.add');
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
            'name' => 'required',
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

            $evt_room = new EventRoom();
            $evt_room->room = $request->name;
            $evt_room->slug = $slug;
            $evt_room->description = $request->description;
            $evt_room->status = $request->status;


            // // Handle the image file upload
            // $filename = time() . '.' . $request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/images/event_type/'), $filename);

            // // Save the filename in the database
            // $evt_room->image = $filename;
            $evt_room->save();
            return redirect()->back()->with('success', 'Event Room Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        $edtObj =   EventRoom::where('id', $id)->first();
        return view('admin.room_type.edit', compact('edtObj'));
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
            'name' => 'required',
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

            $evt_room = EventRoom::find($id);
            $evt_room->room = $request->name;
            $evt_room->slug = $slug;
            $evt_room->description = $request->description;
            $evt_room->status = $request->status;


            // // Handle the image file upload
            // $filename = time() . '.' . $request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/images/event_type/'), $filename);

            // // Save the filename in the database
            // $evt_room->image = $filename;
            $evt_room->save();
            return redirect()->route('admin.room_type.index')->with('success', 'Event Room Added Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
            EventRoom::where('id', $id)->delete();
            return redirect()->back()->with('Event Room Deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with($e->getMessage());
        }
    }
}
