<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

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

    public function work_experience()
    {
        return view('admin.vacancy.work-experience');
    }

    public function educational_qualification()
    {
        return view('admin.vacancy.educational-qualification');
    }

    public function set_salary()
    {
        return view('admin.vacancy.price-per-hr');
    }

    public function job_location()
    {
        return view('admin.vacancy.job_location');
    }

    public function list_all_jobs()
    {
        return view('admin.vacancy.list-all-jobs');
    }

    public function submit(Request $request)
    {
        $job_industry = $request->input('job_industry');
        $job_role = $request->input('job_role');
        $job_location = $request->input('job_location');
        $job_type = $request->input('job_type');
        $job_exp = $request->input('job_exp');
        $edu_qual = $request->input('edu_qual');
        $salary = $request->input('salary');
        $job_title = $request->input('job_title');
        $job_desc = $request->input('job_desc');
        $remote_job = $request->input('remote_job');
        $job_status = $request->input('job_status');

        $insert = DB::table('job_all_jobs')->insert([
            'job_industry' => $job_industry,
            'job_role' => $job_role,
            'job_location' => $job_location,
            'job_type' => $job_type,
            'exp' => $job_exp,
            'qual' => $edu_qual,
            'pph' => $salary,
            'job_title' => $job_title,
            'job_desc' => $job_desc,
            'remote_job' => $remote_job,
            'job_status' => $job_status,
        ]);

        if ($insert) {
            // Entry was successful
            return redirect()->back()->with('success', 'Data Submitted Successfully!');
        } else {
            // Entry failed
            return "Failed to insert data.";
        }
        dd("Form will be submitted.");
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function job_type_submit(Request $request)
    {
        $job_type = $request->input('job_type');
        if ($job_type == "") {
            return redirect()->back()->with('error', 'Job Type Can not be Blank');
        } else {

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

        if ($industry_type == "") {
            return redirect()->back()->with('error', 'Industry Type Can not be Blank');
        } else {
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
        if ($role == "" || $industry == "") {
            return redirect()->back()->with('error', 'Field can not be blank');
        } else {
            DB::table('job_role')->insert([
                'job_industry' => $industry,
                'job_role' => $role
            ]);

            return redirect()->back()->with('success', 'Data Submitted Successfully!');
        }
    }
    public function destroy(Request $request, $id)
    {

        DB::table('job_type')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data Deleted Successfully!');
    }

    public function deleteRow(Request $request)
    {

        $id = $request->input('id');
        DB::table('job_role')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function deleteIndustry(Request $request)
    {

        $id = $request->input('id');
        DB::table('job_Industry')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function job_exp_submit(Request $request)
    {

        $job_exp = $request->input('job_exp');
        if ($job_exp == "") {
            return redirect()->back()->with('error', 'Field can not be blank.');
        } else {
            DB::table('job_exp')->insert([
                'exp' => $job_exp
            ]);

            return redirect()->back()->with('success', 'Data added successfully.');
        }
    }

    public function delete_exp(Request $request)
    {
        $id = $request->input('id');
        DB::table('job_exp')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function delete_job(Request $request)
    {
        $id = $request->input('id');
        DB::table('job_all_jobs')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function delete_education(Request $request)
    {
        $id = $request->input('id');
        DB::table('job_qualification')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function add_new_qual(Request $request)
    {
        $new_entry = $request->input('qual');
        if ($new_entry == "") {
            return redirect()->back()->with('error', 'Field can not be blank');
        } else {
            DB::table('job_qualification')->insert([
                'qual' => $new_entry
            ]);

            return redirect()->back()->with('success', 'Data added successfully.');
        }
    }

    public function delete_pph(Request $request)
    {
        $id = $request->input('id');
        DB::table('job_price')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function add_pph(Request $request)
    {
        $new_entry = $request->input('pph');
        $exists = DB::table('job_price')->where('pph', $new_entry)->exists();
        if ($new_entry == "") {
            return redirect()->back()->with('error', 'Field can not be blank');
        } elseif (!$exists) {
            // If it doesn't exist, insert the new entry
            DB::table('job_price')->insert([
                'pph' => $new_entry
            ]);
            return redirect()->back()->with('success', 'Data added successfully.');
        } else {
            // Handle the case where the pph already exists (e.g., show an error message)
            return redirect()->back()->with('error', 'Value already exists');
        }
    }

    public function add_location(Request $request)
    {
        $new_entry = $request->input('locate');
        $exists = DB::table('job_location')->where('job_location', $new_entry)->exists();
        if ($new_entry == "") {
            return redirect()->back()->with('error', 'Field can not be blank');
        } elseif (!$exists) {
            // If it doesn't exist, insert the new entry
            DB::table('job_location')->insert([
                'job_location' => $new_entry
            ]);
            return redirect()->back()->with(Toastr::success('Data added successfully.', '', ["positionClass" => "toast-top-right"]));

        } else {
            // Handle the case where the pph already exists (e.g., show an error message)
            // return redirect()->back()->with('error', 'Value already exists');
            return redirect()->back()->with(Toastr::error('Value already exists', '', ["positionClass" => "toast-top-right"]));

        }
    }

    public function delete_location(Request $request)
    {
        $id = $request->input('id');
        DB::table('job_location')->where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Row deleted successfully.');
    }

    public function getJobRoles($industry_id)
    {
        // Validate the input

        if (!$industry_id) {
            return response()->json(['error' => 'Invalid industry ID'], 400);
        }

        // Fetch job roles from the database
        $jobRoles = DB::table('job_role') // Use your table name
            ->where('job_industry', $industry_id) // Filter by job_industry
            ->get(['id', 'job_role']); // Adjust columns if necessary

        // Check if any job roles are found
        if ($jobRoles->isEmpty()) {
            return response()->json(['error' => 'No job roles found'], 404);
        }

        // Return the job roles as a JSON response
        return response()->json($jobRoles);
    }

    public function edit($id)
    {
        $job = DB::table('job_all_jobs')->where('id', $id)->first();
        return view('admin.vacancy.edit-job', compact('job'));
    }

    public function update(Request $request, $id)
    {
        dd("hello");
        DB::table('job_all_jobs')
            ->where('id', $id)
            ->update([
                'job_title' => $request->job_title,
                'job_location' => $request->job_location,
                'job_status' => $request->job_status
            ]);

        return redirect()->route('job.index')->with('success', 'Job updated successfully');
    }
}
