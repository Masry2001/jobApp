<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobApplicationsController extends Controller
{
    public function index()
    {

        $jobApplications = auth()->user()->jobApplications()->latest()->paginate(10);
        return view('job-applications.index', compact('jobApplications'));
    }


}
