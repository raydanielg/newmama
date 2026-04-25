<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElmsCourse;
use App\Models\ElmsCourseFee;
use Illuminate\Http\Request;

class ElmsCoursesController extends Controller
{
    public function index(Request $request)
    {
        $filterActive = $request->query('active', 'active');
        $filterActive = in_array($filterActive, ['all', 'active', 'inactive'], true) ? $filterActive : 'active';

        $query = ElmsCourse::query();

        if ($filterActive === 'active') {
            $query->where('is_active', true);
        }

        if ($filterActive === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($category = trim((string) $request->query('category', ''))) {
            $query->where('category', $category);
        }

        $courses = $query->orderBy('title')->paginate(15)->withQueryString();

        $categories = ElmsCourse::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalBase = (float) ElmsCourse::query()->sum('base_price');
        $filteredBase = (float) (clone $query)->sum('base_price');

        return view('admin.elms.courses.index', [
            'title' => 'ELMS Courses',
            'courses' => $courses,
            'filterActive' => $filterActive,
            'categories' => $categories,
            'totalBase' => $totalBase,
            'filteredBase' => $filteredBase,
        ]);
    }

    public function create()
    {
        return view('admin.elms.courses.form', [
            'title' => 'Add Course',
            'course' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:50'],
            'duration_hours' => ['nullable', 'integer', 'min:0'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $data['code'] = $this->generateCode();
        $data['is_active'] = true;
        $data['base_price'] = (float) ($data['base_price'] ?? 0);
        $data['currency'] = strtoupper($data['currency'] ?? 'TZS');

        $course = ElmsCourse::create($data);

        return redirect()->route('admin.elms.courses.show', $course)->with('status', 'Course created');
    }

    public function show(ElmsCourse $course)
    {
        $course->load(['fees' => function ($q) {
            $q->orderBy('sort_order')->orderBy('id');
        }]);

        $feesTotal = (float) $course->activeFees()->sum('amount');
        $grandTotal = (float) $course->base_price + $feesTotal;

        return view('admin.elms.courses.show', [
            'title' => 'Course Details',
            'course' => $course,
            'feesTotal' => $feesTotal,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function edit(ElmsCourse $course)
    {
        return view('admin.elms.courses.form', [
            'title' => 'Edit Course',
            'course' => $course,
        ]);
    }

    public function update(Request $request, ElmsCourse $course)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:50'],
            'duration_hours' => ['nullable', 'integer', 'min:0'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['base_price'] = (float) ($data['base_price'] ?? 0);
        $data['currency'] = strtoupper($data['currency'] ?? $course->currency ?? 'TZS');
        $data['is_active'] = (bool) ($data['is_active'] ?? $course->is_active);

        $course->update($data);

        return redirect()->route('admin.elms.courses.show', $course)->with('status', 'Course updated');
    }

    public function toggleStatus(ElmsCourse $course)
    {
        $course->is_active = !$course->is_active;
        $course->save();

        return back()->with('status', $course->is_active ? 'Course activated' : 'Course deactivated');
    }

    public function feeStore(Request $request, ElmsCourse $course)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['currency'] = strtoupper($data['currency'] ?? $course->currency ?? 'TZS');
        $data['is_required'] = (bool) ($data['is_required'] ?? true);
        $data['is_active'] = true;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $course->fees()->create($data);

        return back()->with('status', 'Additional price added');
    }

    public function feeUpdate(Request $request, ElmsCourse $course, ElmsCourseFee $fee)
    {
        if ((int) $fee->course_id !== (int) $course->id) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['currency'] = strtoupper($data['currency'] ?? $fee->currency ?? ($course->currency ?? 'TZS'));
        $data['is_required'] = (bool) ($data['is_required'] ?? $fee->is_required);
        $data['is_active'] = (bool) ($data['is_active'] ?? $fee->is_active);
        $data['sort_order'] = (int) ($data['sort_order'] ?? $fee->sort_order);

        $fee->update($data);

        return back()->with('status', 'Additional price updated');
    }

    public function feeToggle(ElmsCourse $course, ElmsCourseFee $fee)
    {
        if ((int) $fee->course_id !== (int) $course->id) {
            abort(404);
        }

        $fee->is_active = !$fee->is_active;
        $fee->save();

        return back()->with('status', $fee->is_active ? 'Fee activated' : 'Fee deactivated');
    }

    public function feeDelete(ElmsCourse $course, ElmsCourseFee $fee)
    {
        if ((int) $fee->course_id !== (int) $course->id) {
            abort(404);
        }

        $fee->delete();

        return back()->with('status', 'Fee removed');
    }

    private function generateCode(): string
    {
        $last = ElmsCourse::orderByDesc('code')->value('code');
        $lastNum = 0;
        if ($last) {
            $lastNum = (int) preg_replace('/\D+/', '', $last);
        }

        return 'CRS-' . str_pad((string) ($lastNum + 1), 3, '0', STR_PAD_LEFT);
    }
}
