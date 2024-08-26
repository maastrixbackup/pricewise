<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    //
    public function show()
    {
        return view('admin.vacancy.create-new-job');
    }

    public function job_type()
    {
        return view('admin.vacancy.job-type');
    }

    public function job_industry()
    {
        return view('admin.vacancy.job-industry');
    }

    public function job_role()
    {
        return view('admin.vacancy.job-role');
    }

    public function submit(Request $request)
    {
        

        // Process the data (e.g., save to database)
        // Example: saving to the database (assuming you have a User model)
        // \App\Models\User::create($validatedData);

        // Redirect or return a response
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function job_type_submit(Request $request)
    {
        $job_type = $request->input('job_type');
        if ($job_type == "")
        {
            return redirect()->back()->with('error', 'Job Type Can not be Blank');
        }
        else{

            DB::table('job_type')->insert([
                'job_type' => $request->input('job_type')
            ]);
    
            // Redirect or return a response
            return redirect()->back()->with('success', 'Job type submitted successfully!');
        }
        
    }

    public function industry_type_submit(Request $request)
    {
        $industry_type = $request->input('industry_type');
        
        if($industry_type == "")
        {
            return redirect()->back()->with('error', 'Industry Type Can not be Blank');
        }
        else{
            DB::table('job_industry')->insert([
                'job_industry' => $industry_type
            ]);

            return redirect()->back()->with('success', 'Industry type submitted successfully!');
        }
        
    }

    public function role_submit(Request $request)
    {
        $industry = $request->input('industry'); 
        $role = $request->input('role');    
        if($role == "" || $industry == "")
        {
            return redirect()->back()->with('error','Field can not be blank');
        }
        else
        {
            DB::table('job_role')->insert([
                'job_industry' => $industry,
                'job_role' => $role
            ]);

            return redirect()->back()->with('success', 'Data Submitted Successfully!');
        }
    }
    public function destroy(Request $request,$id)
    {
        dd($id);
    }
}
