<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Mother;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MothersController extends Controller
{
    public function index(Request $request)
    {
        $query = Mother::query()->with(['region', 'district', 'country'])->withCount('whatsappMessages');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%")
                    ->orWhere('mk_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if (in_array($status, ['pregnant', 'new_parent', 'trying'], true)) {
                $query->where('status', $status);
            }
        }

        if ($request->has('approved') && $request->query('approved') !== '') {
            $query->where('is_approved', (bool)$request->query('approved'));
        }

        if ($request->has('preview')) {
            $mothers = $query->orderByDesc('created_at')->get();
            return view('admin.mothers.preview', compact('mothers'));
        }

        $perPage = $request->query('per_page', 15);
        if ($perPage === 'all') {
            $mothers = $query->orderByDesc('created_at')->get();
        } else {
            $mothers = $query->orderByDesc('created_at')->paginate((int)$perPage)->withQueryString();
        }

        if ($request->ajax()) {
            return view('admin.mothers.partials.table', compact('mothers'))->render();
        }

        return view('admin.mothers.index', compact('mothers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:50', 'unique:mothers,whatsapp_number'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'status' => ['required', 'string', 'in:pregnant,new_parent,trying'],
            'edd_date' => ['nullable', 'required_if:status,pregnant', 'date'],
            'baby_age' => ['nullable', 'required_if:status,new_parent', 'integer', 'min:0', 'max:24'],
            'trying_duration' => ['nullable', 'required_if:status,trying', 'string', 'max:255'],
        ]);

        $defaultCountryId = Country::where('iso2', 'TZ')->value('id');
        $data['country_id'] = $data['country_id'] ?: $defaultCountryId;
        $data['is_approved'] = true;
        $data['approved_at'] = now();

        $mother = Mother::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Mother added and approved successfully.',
            'mother' => $mother
        ]);
    }

    public function show(Mother $mother)
    {
        $mother->load(['region', 'district', 'country']);

        return view('admin.mothers.show', compact('mother'));
    }

    public function import()
    {
        return view('admin.mothers.import', [
            'title' => 'Import Mothers',
        ]);
    }

    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $content = file_get_contents($request->file('file')->getRealPath());
        $lines = preg_split('/\r\n|\n|\r/', trim((string) $content));

        $rows = [];
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $rows[] = str_getcsv($line);
        }

        if (count($rows) < 2) {
            return back()->with('error', 'CSV has no data rows')->withInput();
        }

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $rows[0]);
        $dataRows = array_slice($rows, 1);

        $required = ['full_name', 'whatsapp_number', 'status'];
        foreach ($required as $req) {
            if (!in_array($req, $header, true)) {
                return back()->with('error', "Missing required column: {$req}");
            }
        }

        $countryMap = Country::query()
            ->select(['id', 'name', 'iso2'])
            ->get()
            ->mapWithKeys(function ($c) {
                return [
                    strtolower($c->name) => $c->id,
                    strtolower((string) $c->iso2) => $c->id,
                ];
            })
            ->toArray();

        $defaultCountryId = Country::query()->where('iso2', 'TZ')->value('id');

        $preview = [];
        $errors = [];
        $max = min(count($dataRows), 200);

        for ($i = 0; $i < $max; $i++) {
            $r = $dataRows[$i];
            $assoc = [];
            foreach ($header as $idx => $key) {
                $assoc[$key] = $r[$idx] ?? null;
            }

            $rowNum = $i + 2;
            $status = trim((string) ($assoc['status'] ?? ''));
            $statusOk = in_array($status, ['pregnant', 'new_parent', 'trying'], true);

            $countryRaw = trim((string) ($assoc['country'] ?? $assoc['country_code'] ?? $assoc['country_iso2'] ?? ''));
            $countryId = $countryRaw !== '' ? ($countryMap[strtolower($countryRaw)] ?? null) : $defaultCountryId;

            $rowErrs = [];
            if (trim((string) ($assoc['full_name'] ?? '')) === '') $rowErrs[] = 'full_name required';
            if (trim((string) ($assoc['whatsapp_number'] ?? '')) === '') $rowErrs[] = 'whatsapp_number required';
            if (!$statusOk) $rowErrs[] = 'invalid status';

            $preview[] = [
                '_row' => $rowNum,
                'full_name' => trim((string) ($assoc['full_name'] ?? '')),
                'whatsapp_number' => trim((string) ($assoc['whatsapp_number'] ?? '')),
                'status' => $status,
                'edd_date' => trim((string) ($assoc['edd_date'] ?? '')),
                'baby_age' => trim((string) ($assoc['baby_age'] ?? '')),
                'trying_duration' => trim((string) ($assoc['trying_duration'] ?? '')),
                'country_id' => $countryId,
                'region_id' => trim((string) ($assoc['region_id'] ?? '')),
                'district_id' => trim((string) ($assoc['district_id'] ?? '')),
                '_errors' => $rowErrs,
            ];

            if (count($rowErrs) > 0) {
                $errors[] = "Row {$rowNum}: " . implode(', ', $rowErrs);
            }
        }

        session([
            'mothers_import_preview' => $preview,
        ]);

        return view('admin.mothers.import_preview', [
            'title' => 'Import Preview',
            'preview' => $preview,
            'errors' => $errors,
        ]);
    }

    public function importConfirm(Request $request)
    {
        $preview = session('mothers_import_preview');
        if (!is_array($preview) || count($preview) === 0) {
            return redirect()->route('admin.mothers.import')->with('error', 'No preview data found. Please re-upload CSV.');
        }

        $created = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($preview as $row) {
            if (!empty($row['_errors'])) {
                $skipped++;
                continue;
            }

            try {
                $exists = Mother::query()->where('whatsapp_number', $row['whatsapp_number'])->exists();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                $payload = [
                    'full_name' => $row['full_name'],
                    'whatsapp_number' => $row['whatsapp_number'],
                    'country_id' => $row['country_id'] ?: null,
                    'region_id' => (int) ($row['region_id'] ?: 0),
                    'district_id' => (int) ($row['district_id'] ?: 0),
                    'status' => $row['status'],
                    'edd_date' => $row['edd_date'] ?: null,
                    'baby_age' => $row['baby_age'] !== '' ? (int) $row['baby_age'] : null,
                    'trying_duration' => $row['trying_duration'] ?: null,
                    'current_step' => '3',
                    'metadata' => ['source' => 'import'],
                ];

                $v = Validator::make($payload, [
                    'full_name' => ['required', 'string', 'max:255'],
                    'whatsapp_number' => ['required', 'string', 'unique:mothers,whatsapp_number'],
                    'country_id' => ['nullable', 'exists:countries,id'],
                    'region_id' => ['required', 'exists:regions,id'],
                    'district_id' => ['required', 'exists:districts,id'],
                    'status' => ['required', 'string', 'in:pregnant,new_parent,trying'],
                    'edd_date' => ['nullable', 'date'],
                    'baby_age' => ['nullable', 'integer', 'min:0', 'max:24'],
                    'trying_duration' => ['nullable', 'string', 'max:255'],
                ]);

                if ($v->fails()) {
                    $failed++;
                    continue;
                }

                Mother::create($payload);
                $created++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        session()->forget('mothers_import_preview');

        return redirect()->route('admin.mothers')->with('status', "Import complete. Created {$created}, skipped {$skipped}, failed {$failed}.");
    }

    public function edit(Mother $mother)
    {
        $mother->load(['region', 'district', 'country']);

        return view('admin.mothers.edit', [
            'mother' => $mother,
            'title' => 'Edit Mother',
        ]);
    }

    public function update(Request $request, Mother $mother)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:50', 'unique:mothers,whatsapp_number,' . $mother->id],
            'country_id' => ['nullable', 'exists:countries,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'status' => ['required', 'string', 'in:pregnant,new_parent,trying'],
            'edd_date' => ['nullable', 'date'],
            'baby_age' => ['nullable', 'integer', 'min:0', 'max:24'],
            'trying_duration' => ['nullable', 'string', 'max:255'],
        ]);

        $mother->update($data);

        return redirect()->route('admin.mothers.show', $mother)->with('status', 'Mother updated');
    }

    public function approve(Mother $mother)
    {
        $mother->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('status', "Mother {$mother->full_name} has been approved successfully.");
    }

    public function destroy(Mother $mother)
    {
        $mother->delete();

        return redirect()->route('admin.mothers')->with('status', 'Mother deleted');
    }

    public function messages(Mother $mother)
    {
        $mother->load(['region', 'district', 'country']);

        $messages = WhatsappMessage::query()
            ->where('mother_id', $mother->id)
            ->orderBy('sent_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mothers.messages', [
            'title' => 'WhatsApp Messages',
            'mother' => $mother,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request, Mother $mother)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // 1. Log the message in our DB immediately as 'out'
        $msg = WhatsappMessage::create([
            'mother_id' => $mother->id,
            'direction' => 'out',
            'type' => 'text',
            'body' => $request->message,
            'sent_at' => now(),
        ]);

        // 2. Integration logic (Placeholder for actual WhatsApp API Provider)
        // In a real scenario, you'd call your WhatsApp API service here.
        // For example: $waService->send($mother->whatsapp_number, $request->message);

        return back()->with('status', 'Message sent successfully!');
    }
}
