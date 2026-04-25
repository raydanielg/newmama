<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElmsCourse;
use App\Models\ElmsTrainer;
use App\Models\User;
use Illuminate\Http\Request;

class ElmsTrainersController extends Controller
{
    public function index(Request $request)
    {
        $filterActive = $request->query('active', 'active');
        $filterActive = in_array($filterActive, ['all', 'active', 'inactive'], true) ? $filterActive : 'active';

        $query = ElmsTrainer::query()->withCount('courses');

        if ($filterActive === 'active') {
            $query->where('is_active', true);
        } elseif ($filterActive === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $trainers = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.elms.trainers.index', [
            'title' => 'ELMS Trainers',
            'trainers' => $trainers,
            'filterActive' => $filterActive,
        ]);
    }

    public function create()
    {
        return view('admin.elms.trainers.form', [
            'title' => 'Add Trainer',
            'trainer' => null,
            'users' => User::query()->orderBy('name')->get(),
            'courses' => ElmsCourse::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:elms_courses,id'],
        ]);

        $trainer = ElmsTrainer::create($data);

        if (!empty($data['course_ids'])) {
            $trainer->courses()->sync($data['course_ids']);
        }

        return redirect()->route('admin.elms.trainers.index')->with('status', 'Trainer added successfully');
    }

    public function edit(ElmsTrainer $trainer)
    {
        return view('admin.elms.trainers.form', [
            'title' => 'Edit Trainer',
            'trainer' => $trainer,
            'users' => User::query()->orderBy('name')->get(),
            'courses' => ElmsCourse::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, ElmsTrainer $trainer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:elms_courses,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $trainer->update($data);

        if (isset($data['course_ids'])) {
            $trainer->courses()->sync($data['course_ids']);
        } else {
            $trainer->courses()->detach();
        }

        return redirect()->route('admin.elms.trainers.index')->with('status', 'Trainer updated successfully');
    }

    public function toggleStatus(ElmsTrainer $trainer)
    {
        $trainer->is_active = !$trainer->is_active;
        $trainer->save();

        return back()->with('status', $trainer->is_active ? 'Trainer activated' : 'Trainer deactivated');
    }

    public function destroy(ElmsTrainer $trainer)
    {
        $trainer->delete();
        return redirect()->route('admin.elms.trainers.index')->with('status', 'Trainer deleted successfully');
    }
}
