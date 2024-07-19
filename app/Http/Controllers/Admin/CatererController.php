<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Caterer;
use Illuminate\Support\Facades\Validator;



class CatererController extends Controller
{
    public function addcaterer()
    {
        return view('admin.caterer.add');
    }

    public function postcaterer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }
        // Redirect back with errors and old input

        DB::table('caterers')
            ->insert(['caterer_name' => $request->get('name'), 'description' => $request->get('description'), 'status' => $request->get('isenable'), 'created_at' => NOW(), 'updated_at' => NOW()]);

        return redirect()->route('admin.list.caterer')->with(Toastr::success('Caterer Created Successfully', '', ["positionClass" => "toast-top-right"]));
    }

    public function listcaterer()
    {
        $objcaterers = DB::table('caterers')
            ->select('*')
            ->get();
        return view('admin.caterer.list', compact('objcaterers'));
    }


    public function editcaterer($id)
    {
        $editcaterer = DB::table('caterers')
            ->select('*')
            ->where('id', $id)
            ->first();
        return view('admin.caterer.edit', compact('editcaterer'));
    }

    public function updatecaterer(Request $request, $id)
    {
        // dd($request->all());

        DB::table('caterers')
            ->where('id', $id)
            ->update(['caterer_name' => $request->get('name'), 'status' => $request->get('isenable'), 'updated_at' => NOW()]);

        return redirect()->route('admin.list.caterer')->with(Toastr::success('Caterer Updated Successfully', '', ["positionClass" => "toast-top-right"]));
    }

    public function deletecaterer(Request $request, $id)
    {
        $id = $request->id;
        $getEvent = Caterer::find($id);
        try {
            Caterer::find($id)->delete();
            return back()->with(Toastr::error(__('Caterer deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return back()->with($error_msg);
        }
    }
}
