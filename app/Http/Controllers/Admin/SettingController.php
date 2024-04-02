<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Models\Setting;
use App\Models\MailchimpSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $website->site_title = $request->site_title;        
        $website->description = $request->description;
        $website->address = $request->address;
        $website->phone = $request->phone;
        $website->email = $request->email;
        $website->facebook = $request->facebook;
        $website->twitter = $request->twitter;
        $website->instagram = $request->instagram;
        $website->linkedin = $request->linkedin;
        $website->telegram = $request->telegram;
        $website->youTube = $request->youTube;
        // $website->status = $request->status;
        if ($request->has('cropped_image')) {
        // Access base64 encoded image data directly from the request
        $croppedImage = $request->cropped_image;

        // Extract base64 encoded image data and decode it
        $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

        // Generate a unique file name for the image
        $imageName = 'logo_' . time() . '.png';

        // Specify the destination directory where the image will be saved
        $destinationDirectory = 'public/images/website';

        // Create the directory if it doesn't exist
        Storage::makeDirectory($destinationDirectory);

        // Save the image to the server using Laravel's file upload method
        $filePath = $destinationDirectory . '/' . $imageName;

        // Delete the old image if it exists
        if ($website->logo) {
            Storage::delete($destinationDirectory . '/' . $objCategory->image);
        }

        // Save the new image
        Storage::put($filePath, $imgData);

        // Set the image file name for the provider
        $website->logo = $imageName;
        }
        if ($website->save()) {
            $message = array('message' => 'Website Settings Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
        }
    }

    public function smtpEdit()
    {
        $smtpSettings = [
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];
        return view('admin.settings.smtp_edit', compact('smtpSettings'));
    }


    public function smtpStore(Request $request)
    {

        $validatedData = $request->validate([
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);
//logger()->debug('Request data:', $validatedData);
        // Update .env file
        // foreach ($validatedData as $key => $value) {
        //     file_put_contents(base_path('.env'), "$key=$value" . PHP_EOL, FILE_APPEND | LOCK_EX);
        // }
        try {
        // Read current .env content
        $envContent = File::get(base_path('.env'));

        // Replace existing values with new ones
        foreach ($validatedData as $key => $value) {
            $key_config = str_replace('mail_', '', $key);
            if($key == 'mail_from_address' || $key == 'mail_from_name'){
                $key_config = str_replace('_', '.', $key);
            $envContent = Str::replaceFirst(
                strtoupper("$key=") . config("$key_config"),
                strtoupper("MAIL_$key_config")."=$value",
                $envContent
            );         
           
            }else{
                $envContent = Str::replaceFirst(
                strtoupper("$key=") . config("mail.mailers.smtp.$key_config"),
                strtoupper("MAIL_$key_config")."=$value",
                $envContent
            );
            }
        }
//dd($envContent);
        // Rewrite the .env file with updated content
        File::put(base_path('.env'), $envContent);

        // Clear config cache
        Artisan::call('config:cache');

        $message = array("status" => true,'message' => 'SMTP Setting Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
    } catch (\Exception $e) {
        $message = ['message' => 'Failed to update SMTP settings', 'title' => 'Error'];
        return response()->json(['status' => false, 'message' => $message]);
    }
        
    }

    public function paymentEdit()
    {
        $paymentSetting = Setting::where('type', 'payment_setting')->orderBy('key', 'asc')->get()->groupBy('sub_type');
        //dd($paymentSetting);
        return view('admin.settings.payment_edit', compact('paymentSetting'));
    }


    public function paymentStore(Request $request){    
        try{
        foreach($request->input('payment') as $key => $value){          
           Setting::where('type','payment_setting')->where('key', $key)
        ->update(['value' => $value]);
        }
        $message = array('message' => 'Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        } catch (\Exception $e) {
            $errorMessage = 'Failed to update Payment settings: ' . $e->getMessage();
    // Log the error for further investigation
    \Log::error($errorMessage);
        $message = ['message' => 'Failed to update Payment settings', 'title' => 'Error'];
        return response()->json(['status' => false, 'message' => $message]);
    }
    }

}
