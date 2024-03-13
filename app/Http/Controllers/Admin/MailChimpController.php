<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterTemplate;
use Brian2694\Toastr\Facades\Toastr;

class MailChimpController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCampaign()
    {
        $newsletter = NewsletterTemplate::all();
        return view('admin.mailchimp.add', compact('newsletter'));
    }

    public function getTemplate(Request $request)
    {
        $template = NewsletterTemplate::find($request->template)->desc_html;
        return response()->json(['response' => $template,'id'=>$request->template]);
    }

    public function sendCampaign(Request $request)
    {
        $listId = 'f0303160e4';

        $mailchimp = new \Mailchimp('f100cf1a4091330a80c19be02d2a2952-us21');

        $campaign = $mailchimp->campaigns->create('regular', [
            'list_id' => $listId,
            'subject' => $request->subject,
            'from_email' => $request->from,
            'from_name' => 'POPTelecom',
            'to_name' => $request->to,

        ], [
            'html' => $request->input('message'),
            'text' => strip_tags($request->input('message'))
        ]);
    
        //Send campaign
        if ($mailchimp->campaigns->send($campaign['id'])) {
            $message = array('message' => 'Campaign Send Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function createSubscriber()
    {
        return view('admin.mailchimp.add_subscriber');
    }

    public function storeSubscriber(Request $request)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);

        $save = $mailchimp->lists->setListMember("f0303160e4", "subscriber_hash", [
            "email_address" => $request->email_id,
            "status_if_new" => "subscribed",
            "merge_fields" => [
                "FNAME" => $request->fname,
                "LNAME" => $request->lname,
                "ADDRESS" => [
                    "addr1" => $request->address,
                    "city" => $request->city,
                    "state" => $request->state,
                    "zip" => $request->zip
                ]

            ]
        ], ["skip_merge_validation" => false]);

        if ($save) {
            Toastr::success('Subscriber added Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, 'redirect_location' => route('admin.subscribers-list')]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function subscribersList()
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);

        $subscribers_list = $mailchimp->lists->getListMembersInfo('f0303160e4', $fields = null, $exclude_fields = null, $count = '10', $offset = '0', $email_type = null, $status = 'subscribed', $since_timestamp_opt = null, $before_timestamp_opt = null, $since_last_changed = null, $before_last_changed = null, $unique_email_id = null, $vip_only = null, $interest_category_id = null, $interest_ids = null, $interest_match = null, $sort_field = null, $sort_dir = null, $since_last_campaign = null, $unsubscribed_since = null);


        return view('admin.mailchimp.subscriberslist', compact('subscribers_list'));
    }

    public function editSubscriber(Request $request, $id)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);


        $member = $mailchimp->lists->getListMember("f0303160e4", md5($id));

        return view('admin.mailchimp.edit_subscriber', compact('member'));
    }

    public function updateSubscriber(Request $request, $id)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);

        $save = $mailchimp->lists->updateListMember("f0303160e4", md5($request->email_id), [
            "email_address" => $request->email_id,
            "status_if_new" => "subscribed",
            "merge_fields" => [
                "FNAME" => $request->fname,
                "LNAME" => $request->lname,
                "ADDRESS" => [
                    "addr1" => $request->address,
                    "city" => $request->city,
                    "state" => $request->state,
                    "zip" => $request->zip
                ]
            ]
        ], false);


        if ($save) {
            Toastr::success('Subscriber updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, 'redirect_location' => route('admin.subscribers-list')]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function deleteSubscriber(Request $request)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);

        try {
            $response = $mailchimp->lists->deleteListMember("f0303160e4", md5($request->id));
            $success_msg = Toastr::success(__('Subscriber deleted successfully.'));
            return redirect()->route('admin.subscribers-list')->with($success_msg);
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.subscribers-list')->with($error_msg);
        }
    }

    public function contactsList(Request $request)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();
        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);

        $contacts_list = $mailchimp->lists->getAllLists();
        //echo "<pre>" ; print_r($contacts_list) ; echo "</pre>" ;exit;
        return view('admin.mailchimp.contactslist', compact('contacts_list'));
    }

    public function createList()
    {
        return view('admin.mailchimp.add_list');
    }

    public function storeContactList(Request $request)
    {
        $mailchimp = new \MailchimpMarketing\ApiClient();
        $mailchimp->setConfig([
            'apiKey' => 'f100cf1a4091330a80c19be02d2a2952-us21',
            'server' => 'us21'
        ]);
        $response = $mailchimp->lists->createList([
            "name" => $request->name,
            "permission_reminder" => "permission_reminder",
            "email_type_option" => true,
            "contact" => [
                "company" => $request->company,
                "address1" => $request->address,
                "city" => $request->city,
                "country" => $request->country,
            ],
            "campaign_defaults" => [
                "from_name" =>  $request->from_name,
                "from_email" =>  $request->from_email,
                "subject" => '',
                "language" => 'en',
            ],
        ]);
       
        if ($response) {
            Toastr::success('Contact List added Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, 'redirect_location' => route('admin.contacts-list')]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }
}
