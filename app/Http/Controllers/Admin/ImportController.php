<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function form()
    {
        return view('admin.import');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Excel::import(new QuestionsImport, $request->file('file'));
            
            return redirect()->back()->with('success', 'นำเข้าข้อมูลสำเร็จ!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
}