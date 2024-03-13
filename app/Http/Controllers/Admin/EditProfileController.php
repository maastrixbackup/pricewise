<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class EditProfileController extends Controller
{

    public function edit($id)
    {
        $admin = Admin::find($id);
        return view('admin.profilesettings.edit_profile', compact('admin'));
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        $admin = Admin::find($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone_no = $request->phone_no;
        if ($request->file('image') == null) {
            $input['image'] = $admin->profileImage;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $admin->profileImage = $image;
        }

        if ($admin->save()) {
            return redirect()->back()->with('success',  Toastr::success('Profile Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    public function passwordEdit($id)
    {
        $admin = Admin::find($id);
        return view('admin.profilesettings.change_password', compact('admin'));
    }

    public function passwordUpdate(request $request, $id)
    {

        $admin = Admin::find($id);
        if ($request->newPassword == $request->confirmPassword) {
            $admin->password = Hash::make($request->newPassword);
            if ($admin->save()) {
                return redirect()->back()->with('success',  Toastr::success('Password Changed Successfully', '', ["positionClass" => "toast-top-right"]));
            } else {
                $message = array('message' => 'Something Went wrong', 'title' => '');
                return response()->json(["status" => false, 'message' => $message]);
            }
        } else {
            return redirect()->back()->with('error',  Toastr::error('Confirm Password Does not Match', '', ["positionClass" => "toast-top-right"]));
        }
    }
}
