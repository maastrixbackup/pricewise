<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\MailchimpSetting;

class SettingController extends Controller
{
    public function websiteEdit()
    {
        $website = WebsiteSetting::find(1);
        return view('admin.settings.website_edit', compact('website'));
    }

    public function websiteStore(Request $request)
    {

        $website = WebsiteSetting::find(1);
        $website->description = $request->description;
        $website->address = $request->address;
        $website->phone = $request->phone;
        $website->email = $request->email;
        $website->facebook = $request->facebook;
        $website->twitter = $request->twitter;
        $website->instagram = $request->instagram;
        $website->linkedin = $request->linkedin;
        $website->tiktok = $request->tiktok;
        $website->youTube = $request->youTube;
        if ($website->save()) {
            $message = array('message' => 'Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function mailchimpEdit()
    {
        $mailchimp = MailchimpSetting::find(1);
        return view('admin.settings.mailchimp_edit', compact('mailchimp'));
    }


    public function mailchimpStore(Request $request)
    {

        $mailchimp = MailchimpSetting::find(1);
        $mailchimp->mailchimp_key = $request->mailchimp_key;
        $mailchimp->listId = $request->listId;
        if ($mailchimp->save()) {
            $message = array('message' => 'Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

}
