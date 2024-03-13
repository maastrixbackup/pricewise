<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Legal;
use Brian2694\Toastr\Facades\Toastr;

class LegalController extends Controller
{
  
    public function legalUpdate(Request $request, $id)
    {
        
        $legal = Legal::find($id);
        $legal->title = $request->title? $request->title : $legal->title;
        $legal->description = $request->legal_desc? $request->legal_desc :$legal->description;
        $legal->meta_tag = $request->meta_tag ? $request->meta_tag : $legal->meta_tag;
        $legal->meta_keyword = $request->meta_keyword ? $request->meta_keyword :$legal->meta_keyword;
        $legal->meta_desc = $request->meta_desc ? $request->meta_desc : $legal->meta_desc;
        $legal->og_title = $request->og_title ? $request->og_title :$legal->og_title;
        $legal->og_desc = $request->og_desc ? $request->og_desc :$legal->og_desc;
        $legal->og_site_name = $request->og_site_name ? $request->og_site_name :$legal->og_site_name;
        $legal->twitter_card = $request->twitter_card ? $request->twitter_card :$legal->twitter_card;
        $legal->twitter_site = $request->twitter_site ? $request->twitter_site :$legal->twitter_site;
        $legal->twitter_title = $request->twitter_title ? $request->twitter_title :$legal->twitter_title;
        $legal->twitter_desc = $request->twitter_desc ? $request->twitter_desc :$legal->twitter_desc;
        if ($legal->save()) {
          
            return redirect()->back()->with(Toastr::success('legal Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }
}
