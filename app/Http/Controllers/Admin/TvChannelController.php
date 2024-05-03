<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvChannel;
use Brian2694\Toastr\Facades\Toastr;
use Validator;
class TvChannelController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:tv-channel', ['only' => ['index', 'store']]);
        $this->middleware('permission:tv-channel.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tv-channel.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tv-channel.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $records = TvChannel::latest()->get();
       return view('admin.tv_channel.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tv_channel.create');
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
            'channel_name' => 'required|unique:tv_channels,channel_name',
            'price' => 'required',
            'type' => 'required',
        ]);
 
        try {
            $newChannel = new TvChannel();
            $newChannel->channel_name = $request->channel_name;
            $newChannel->description = $request->description;
            $newChannel->price = (int)$request->price;
            $newChannel->type = $request->type;
            $newChannel->features = isset($request->features) && count($request->features) > 0 ? json_encode($request->features) : Null;
            $newChannel->save();
            Toastr::success('FAQ Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-channel.index');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
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
        $channel = TvChannel::where('id', $id)->first();
        return view('admin.tv_channel.edit', compact('channel'));
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
            'channel_name' => 'required|unique:tv_channels,channel_name,' . $id,
            'price' => 'required',
            'type' => 'required',
        ]);
 
        try {
            $channel = TvChannel::findOrFail($id);
            $channel->channel_name = $request->channel_name;
            $channel->description = $request->description;
            $channel->price = (int)$request->price;
            $channel->type = $request->type;
            $channel->features = isset($request->features) && count($request->features) > 0 ? json_encode($request->features) : $channel->features;
            $channel->save();
            Toastr::success('FAQ Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-channel.index');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
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
            TvChannel::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Channel deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
