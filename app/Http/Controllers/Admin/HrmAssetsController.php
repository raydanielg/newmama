<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrmAsset;
use Illuminate\Http\Request;

class HrmAssetsController extends Controller
{
    public function index(Request $request)
    {
        $query = HrmAsset::query()->with('assignedEmployee');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $assets = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $employees = Employee::query()->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.hrm.assets', [
            'title' => 'HRM Assets',
            'assets' => $assets,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_tag' => ['required', 'string', 'max:50', 'unique:hrm_assets,asset_tag'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['required', 'in:good,fair,poor'],
            'status' => ['required', 'in:available,assigned,maintenance,retired'],
            'assigned_employee_id' => ['nullable', 'exists:employees,id'],
            'assigned_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['purchase_cost'] = (float) ($data['purchase_cost'] ?? 0);
        $data['is_active'] = $request->has('is_active');

        if (empty($data['assigned_employee_id'])) {
            $data['assigned_date'] = null;
        }

        HrmAsset::create($data);

        return back()->with('status', 'Asset added successfully');
    }

    public function update(Request $request, HrmAsset $asset)
    {
        $data = $request->validate([
            'asset_tag' => ['required', 'string', 'max:50', 'unique:hrm_assets,asset_tag,' . $asset->id],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['required', 'in:good,fair,poor'],
            'status' => ['required', 'in:available,assigned,maintenance,retired'],
            'assigned_employee_id' => ['nullable', 'exists:employees,id'],
            'assigned_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['purchase_cost'] = (float) ($data['purchase_cost'] ?? 0);
        $data['is_active'] = $request->has('is_active');

        if (empty($data['assigned_employee_id'])) {
            $data['assigned_date'] = null;
        }

        $asset->update($data);

        return back()->with('status', 'Asset updated successfully');
    }

    public function destroy(HrmAsset $asset)
    {
        $asset->delete();
        return back()->with('status', 'Asset deleted successfully');
    }
}
