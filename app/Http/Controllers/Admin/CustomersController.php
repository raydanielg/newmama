<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'cash');
        $type = in_array($type, ['cash', 'debtor'], true) ? $type : 'cash';

        $query = Customer::query()->where('is_active', true)->where('customer_type', $type);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_number', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (($segment = $request->query('segment')) && $segment !== 'all') {
            $query->where('segment', $segment);
        }

        $customers = $query->orderBy('name')->paginate(15)->withQueryString();

        $totalBalance = (float) (clone $query)->sum('balance');
        $totalCredit = (float) (clone $query)->sum('credit_limit');

        return view('admin.customers.index', compact('customers', 'type', 'totalBalance', 'totalCredit'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'cash');
        $type = in_array($type, ['cash', 'debtor'], true) ? $type : 'cash';

        return view('admin.customers.form', [
            'customer' => null,
            'type' => $type,
        ]);
    }

    public function store(Request $request)
    {
        $type = $request->input('customer_type', 'cash');
        $type = in_array($type, ['cash', 'debtor'], true) ? $type : 'cash';

        $rules = [
            'customer_type' => ['required', 'in:cash,debtor'],
            'segment' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'credit_period' => ['nullable', 'integer', 'min:0'],
            'payment_terms' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];

        if ($type === 'debtor') {
            $rules['company'] = ['required', 'string', 'max:255'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];
            $rules['name'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['company'] = ['nullable', 'string', 'max:255'];
            $rules['contact_person'] = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

        $data['name'] = $type === 'debtor' ? trim((string) ($data['company'] ?? '')) : trim((string) ($data['name'] ?? ''));
        $data['company'] = $data['company'] ?? null;
        $data['contact_person'] = $data['contact_person'] ?? null;
        $data['segment'] = isset($data['segment']) ? strtolower((string) $data['segment']) : null;

        $data['customer_number'] = $this->generateCustomerNumber($type);
        $data['is_active'] = true;

        $customer = Customer::create($data);

        return redirect()->route('admin.customers.ledger', $customer)->with('status', 'Customer created');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.form', [
            'customer' => $customer,
            'type' => $customer->customer_type,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $type = $customer->customer_type;

        $rules = [
            'segment' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'credit_period' => ['nullable', 'integer', 'min:0'],
            'payment_terms' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];

        if ($type === 'debtor') {
            $rules['company'] = ['required', 'string', 'max:255'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];
            $rules['name'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['company'] = ['nullable', 'string', 'max:255'];
            $rules['contact_person'] = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules);
        $data['name'] = $type === 'debtor' ? trim((string) ($data['company'] ?? '')) : trim((string) ($data['name'] ?? ''));
        $data['segment'] = isset($data['segment']) ? strtolower((string) $data['segment']) : null;

        $customer->update($data);

        return redirect()->route('admin.customers.ledger', $customer)->with('status', 'Customer updated');
    }

    public function ledger(Customer $customer)
    {
        $entries = $customer->ledgerEntries()->orderByDesc('posting_date')->orderByDesc('id')->get();

        $running = 0.0;
        $entriesWithBalance = $entries->reverse()->map(function ($e) use (&$running) {
            $running += (float) $e->amount;
            $e->running_balance = $running;
            return $e;
        })->reverse()->values();

        $openInvoices = $entriesWithBalance->filter(fn ($e) => $e->is_open && (float) $e->amount > 0);
        $totalOutstanding = (float) $openInvoices->sum('remaining_amount');

        return view('admin.customers.ledger', [
            'customer' => $customer,
            'entries' => $entriesWithBalance,
            'openInvoicesCount' => $openInvoices->count(),
            'totalOutstanding' => $totalOutstanding,
        ]);
    }

    private function generateCustomerNumber(string $type): string
    {
        if ($type === 'cash') {
            $count = Customer::where('customer_type', 'cash')->count();
            return 'CONT-' . (string) (($count ?: 0) + 10001);
        }

        $last = Customer::where('customer_type', 'debtor')->orderByDesc('customer_number')->value('customer_number');
        $lastNum = 0;
        if ($last) {
            $lastNum = (int) preg_replace('/\D+/', '', $last);
        }
        $next = $lastNum + 1;

        return 'DEB-10-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
