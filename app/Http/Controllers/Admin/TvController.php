<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvPage;
use App\Models\TvLink;
use App\Models\PoptvBundle;
use App\Models\WhatInclude;
use App\Models\Discover;
use Brian2694\Toastr\Facades\Toastr;

class TvController extends Controller
{
  
    public function tvUpdate(Request $request, $id)
    {
        $tv = TvPage::find($id); 
        $tv->tv_deal_header1 = $request->tv_deal_header1 ? $request->tv_deal_header1 : $tv->tv_deal_header1;
        $tv->tv_deal_header2 = $request->tv_deal_header2 ? $request->tv_deal_header2 : $tv->tv_deal_header2;
        $tv->tv_deal_desc = $request->tv_deal_desc ? $request->tv_deal_desc : $tv->tv_deal_desc;
        $tv->pop_tv_title = $request->pop_tv_title ? $request->pop_tv_title : $tv->pop_tv_title;
        $tv->pop_tv_desc = $request->pop_tv_desc ? $request->pop_tv_desc : $tv->pop_tv_desc;
        $tv->entertainment_title = $request->entertainment_title ? $request->entertainment_title : $tv->entertainment_title;
        $tv->entertainment_desc = $request->entertainment_desc ? $request->entertainment_desc : $tv->entertainment_desc;
        if ($request->file('entertainment_img') == null) {
            $input['entertainment_img'] = $tv->entertainment_img;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('entertainment_img');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->entertainment_img = $image;
            
        }
        $tv->meta_tag = $request->meta_tag ? $request->meta_tag : $tv->meta_tag;
        $tv->meta_keyword = $request->meta_keyword ? $request->meta_keyword : $tv->meta_keyword;
        $tv->meta_desc = $request->meta_desc ? $request->meta_desc : $tv->meta_desc;
        $tv->og_title = $request->og_title ? $request->og_title :$tv->og_title;
        $tv->og_desc = $request->og_desc ? $request->og_desc :$tv->og_desc;
        $tv->og_site_name = $request->og_site_name ? $request->og_site_name :$tv->og_site_name;
        $tv->twitter_card = $request->twitter_card ? $request->twitter_card :$tv->twitter_card;
        $tv->twitter_site = $request->twitter_site ? $request->twitter_site :$tv->twitter_site;
        $tv->twitter_title = $request->twitter_title ? $request->twitter_title :$tv->twitter_title;
        $tv->twitter_desc = $request->twitter_desc ? $request->twitter_desc :$tv->twitter_desc;
        if ($request->file('tv_deal_img') == null) {
            $input['tv_deal_img'] = $tv->tv_deal_img;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('tv_deal_img');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->tv_deal_img = $image;
            
        }
        $tv->movie_title = $request->movie_title ? $request->movie_title : $tv->movie_title;
        $tv->movie_desc = $request->movie_desc ? $request->movie_desc : $tv->movie_desc;
        $tv->movie_price_tag = $request->movie_price_tag ? $request->movie_price_tag : $tv->movie_price_tag;
        $tv->buy_btn_name = $request->buy_btn_name ? $request->buy_btn_name : $tv->buy_btn_name;
        $tv->buy_btn_link = $request->buy_btn_link ? $request->buy_btn_link : $tv->buy_btn_link;
        $tv->besttv_title = $request->besttv_title ? $request->besttv_title : $tv->besttv_title;
        $tv->besttv_desc = $request->besttv_desc ? $request->besttv_desc : $tv->besttv_desc;
        if ($request->file('pop_telecom_img1') == null) {
            $input['pop_telecom_img1'] = $tv->pop_telecom_img1;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('pop_telecom_img1');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->pop_telecom_img1 = $image;
            
        }
        if ($request->file('pop_telecom_img2') == null) {
            $input['pop_telecom_img2'] = $tv->pop_telecom_img2;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('pop_telecom_img2');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->pop_telecom_img2 = $image;
            
        }
        $tv->sport_title = $request->sport_title ? $request->sport_title : $tv->sport_title;
        $tv->sport_desc = $request->sport_desc ? $request->sport_desc : $tv->sport_desc;
        $tv->sport_header = $request->sport_header ? $request->sport_header : $tv->sport_header;
        $tv->sport_btn_name = $request->sport_btn_name ? $request->sport_btn_name : $tv->sport_btn_name;
        $tv->sport_btn_link = $request->sport_btn_link ? $request->sport_btn_link : $tv->sport_btn_link;
        $tv->sound_title = $request->sound_title ? $request->sound_title : $tv->sound_title;
        $tv->sound_desc = $request->sound_desc ? $request->sound_desc : $tv->sound_desc;
        $tv->check_btn_name = $request->check_btn_name ? $request->check_btn_name : $tv->check_btn_name;
        $tv->check_btn_link = $request->check_btn_link ? $request->check_btn_link : $tv->check_btn_link;
        if ($request->file('sport_img') == null) {
            $input['sport_img'] = $tv->sport_img;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('sport_img');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->sport_img = $image;
            
        }
        if ($request->file('sound_img') == null) {
            $input['sound_img'] = $tv->sound_img;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('sound_img');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tv->sound_img = $image;
            
        }

        if ($tv->save()) {
            return redirect()->back()->with(Toastr::success('TV Page Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    public function tvLinkStore(Request $request)
    {
        $tvLinks = new TvLink();
        $tvLinks->name = $request->name;
        $tvLinks->url = $request->link;
        $tvLinks->status = $request->status;
        if ($request->file('image')) {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tvLinks->image = $image;
        }

        if($tvLinks->save()){
            return redirect()->back()->with(Toastr::success('TV Link Added Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function tvLinkEdit($id)
    {

        $tvLinks = TvLink::find($id);
        return view('admin.cmspages.tv.tvlink_edit', compact('tvLinks'));
    }

    public function tvLinkUpdate(Request $request, $id)
    {
        //dd($request->all());
        $tvLinks = TvLink::find($id);
        $tvLinks->name = $request->name;
        $tvLinks->url = $request->link;
        $tvLinks->status = $request->status;
        if ($request->file('image') == null) {
            $input['image'] = $tvLinks->image;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $tvLinks->image = $image;
            
        }
        if ($tvLinks->save()) {
            return redirect()->back()->with(Toastr::success('Tv Link Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function tvLinkDestroy($id)
    {
        $tvLinks = Tvlink::find($id);
        if ($tvLinks->delete()) {
            $message = array('message' => 'TV Link Deleted Succesfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    public function poptvStore(Request $request)
    {
        $poptv = new PoptvBundle();
        $poptv->title = $request->title;
        $poptv->description = $request->poptv_desc;
        $poptv->btn_name = $request->btn_name;
        $poptv->btn_link = $request->btn_link;
        $poptv->status = $request->status;
        

        if($poptv->save()){
            return redirect()->back()->with(Toastr::success('TV Bundle Added Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function poptvEdit($id)
    {

        $poptv = PoptvBundle::find($id);
        return view('admin.cmspages.tv.poptv_edit', compact('poptv'));
    }

    public function poptvUpdate(Request $request, $id)
    {
        //dd($request->all());
        $poptv = PoptvBundle::find($id);
        $poptv->title = $request->title;
        $poptv->description = $request->poptv_desc;
        $poptv->btn_name = $request->btn_name;
        $poptv->btn_link = $request->btn_link;
        $poptv->status = $request->status;
        
        if ($poptv->save()) {
            return redirect()->back()->with(Toastr::success('TV Bundle Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function poptvDestroy($id)
    {
        $poptv = PoptvBundle::find($id);
        if ($poptv->delete()) {
            $message = array('message' => 'Deleted Succesfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    public function includeStore(Request $request)
    {
        $include = new WhatInclude();
        $include->description = $request->include_desc;
        $include->status = $request->status;
        if ($request->file('image')) {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $include->image = $image;
        }

        if($include->save()){
            return redirect()->back()->with(Toastr::success('Added Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function includeEdit($id)
    {

        $include = WhatInclude::find($id);
        return view('admin.cmspages.tv.include_edit', compact('include'));
    }

    public function includeUpdate(Request $request, $id)
    {
        //dd($request->all());
        $include = WhatInclude::find($id);
        $include->description = $request->include_desc;
        $include->status = $request->status;
        if ($request->file('image') == null) {
            $input['image'] = $include->image;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $include->image = $image;
            
        }
        if ($include->save()) {
            return redirect()->back()->with(Toastr::success('Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function includeDestroy($id)
    {
        $include = WhatInclude::find($id);
        if ($include->delete()) {
            $message = array('message' => 'Deleted Succesfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    public function discoverStore(Request $request)
    {
        $discover = new Discover();
        
        $discover->title = $request->title;
        $discover->description = $request->discover_desc;
        $discover->status = $request->status;
        if ($request->file('image')) {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $discover->image = $image;
        }

        if($discover->save()){
            return redirect()->back()->with(Toastr::success('Added Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function discoverEdit($id)
    {

        $discover = Discover::find($id);
        return view('admin.cmspages.tv.discover_edit', compact('discover'));
    }

    public function discoverUpdate(Request $request, $id)
    {
        //dd($request->all());
        $discover = Discover::find($id);
        $discover->title = $request->title;
        $discover->description = $request->discover_desc;
        $discover->status = $request->status;
        if ($request->file('image') == null) {
            $input['image'] = $discover->image;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $discover->image = $image;
            
        }
        if ($discover->save()) {
            return redirect()->back()->with(Toastr::success('Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Something went wrong !! Please Try again later', '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function discoverDestroy($id)
    {
        $discover = Discover::find($id);
        if ($discover->delete()) {
            $message = array('message' => 'Deleted Succesfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }
}
