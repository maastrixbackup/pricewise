<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRoom;
use App\Models\EventTheme;
use Illuminate\Http\Request;
use Brian2694\Toastr\Toastr;

class EventThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ObjThemes = EventTheme::orderBy('id', 'desc')->with('countryDetails')->get();
        // dd($ObjThemes);
        return view('admin.event_theme.index', compact('ObjThemes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.event_theme.add');
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
            'theme_id' => 'required|unique:event_themes,theme_type',
            // 'image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            // 'sub_category' => 'required',
        ]);

        try {
            // Create a new instance of EventType
            $ev_type = new EventTheme();
            $ev_type->theme_type = $request->theme_id;
            $ev_type->description = $request->description;
            $ev_type->status = $request->status;

            // Handle the image file upload
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/event_theme/'), $filename);

            // Save the filename in the database
            $ev_type->image = $filename;
            $ev_type->save();

            // Redirect back with a success message
            return redirect()->route('admin.event_theme.index')->with('success', ["positionClass" => "toast-top-right"]);
        } catch (\Exception $e) {
            // Redirect back with a warning message if an exception occurs
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
        $edtTheme = EventTheme::where('id', $id)->first();
        return view('admin.event_theme.edit', compact('edtTheme'));
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
            // Create a new instance of EventType
            $ev_theme_update = EventTheme::find($id);
            $ev_theme_update->theme_type = $request->theme_id;
            $ev_theme_update->description = $request->description;
            $ev_theme_update->status = $request->status;

            if (isset($request->image)) {
                $filename = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/event_theme/'), $filename);


                // Check if the dealProduct has an existing image
                if (!empty($ev_theme_update->image)) {
                    $existingFilePath = public_path('storage/images/event_theme/') . $ev_theme_update->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }

            $ev_theme_update->image = $filename ?? $ev_theme_update->image;
            $ev_theme_update->save();

            // Redirect back with a success message
            return redirect()->route('admin.event_theme.index')->with('success', ["positionClass" => "toast-top-right"]);
        } catch (\Exception $e) {
            // Redirect back with a warning message if an exception occurs
            return redirect()->back()->with($e->getMessage(), ["positionClass" => "toast-top-right"]);
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
            EventTheme::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Event Type Deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with($e->getMessage());
        }
    }
}
