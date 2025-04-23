<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BlankExcelExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.filemanager');
    }

    public function downloadExcel()
    {
        $headers = ['Range From', 'Range To', 'Cost Per Day', 'Cost Per Month']; // Customize your headers here
        return Excel::download(new BlankExcelExport($headers), 'feed_in_charges.xlsx');
    }
}
