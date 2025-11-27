<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $query = JobVacancy::query();

        if ($search = request('search')) {

            // Normalize search term: remove spaces
            $normalized = str_replace(' ', '', $search);

            $query->where(function ($query) use ($search, $normalized) {

                // Search in job vacancy fields
                $query->where(function ($q) use ($search, $normalized) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")

                        // Space-insensitive fallback
                        ->orWhereRaw("REPLACE(title, ' ', '') LIKE ?", ["%{$normalized}%"])
                        ->orWhereRaw("REPLACE(description, ' ', '') LIKE ?", ["%{$normalized}%"]);
                });

                // Search by company name
                $query->orWhereHas('company', function ($q) use ($search, $normalized) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereRaw("REPLACE(name, ' ', '') LIKE ?", ["%{$normalized}%"]);
                });

            });
        }

        // Apply type filter (Full-Time, Remote, etc)
        if ($filter = request('filter')) {
            $query->where('type', $filter);
        }

        $jobVacancies = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact('jobVacancies'));
    }


}
