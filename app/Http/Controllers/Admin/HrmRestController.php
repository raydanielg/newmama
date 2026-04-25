<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrmPerformanceReview;
use App\Models\HrmRecruitmentJob;
use App\Models\HrmEvent;
use Illuminate\Http\Request;

class HrmRestController extends Controller
{
    public function performance(Request $request)
    {
        $reviews = HrmPerformanceReview::with('employee')->latest()->paginate(20);
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.hrm.performance', compact('reviews', 'employees'));
    }

    public function performanceStore(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_date' => 'required|date',
            'reviewer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string'
        ]);
        HrmPerformanceReview::create($data);
        return back()->with('status', 'Performance review added');
    }

    public function recruitment(Request $request)
    {
        $jobs = HrmRecruitmentJob::latest()->paginate(20);
        return view('admin.hrm.recruitment', compact('jobs'));
    }

    public function recruitmentStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:open,closed'
        ]);
        HrmRecruitmentJob::create($data);
        return back()->with('status', 'Job opening added');
    }

    public function events(Request $request)
    {
        $events = HrmEvent::latest()->paginate(20);
        return view('admin.hrm.events', compact('events'));
    }

    public function eventsStore(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);
        HrmEvent::create($data);
        return back()->with('status', 'Event added');
    }

    public function settings()
    {
        return view('admin.hrm.settings');
    }
}
