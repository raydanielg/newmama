<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmAutomation;
use App\Models\CrmFeedbackEntry;
use App\Models\CrmInboxMessage;
use App\Models\CrmLoyaltyAccount;
use App\Models\CrmLoyaltyTransaction;
use App\Models\CrmPreorder;
use App\Models\CrmReferral;
use App\Models\CrmUpsellCampaign;
use App\Models\CrmUpsellLead;
use App\Models\Customer;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function hub(Request $request)
    {
        $kpis = [
            'open_inbox' => (int) CrmInboxMessage::query()->where('status', 'open')->count(),
            'open_preorders' => (int) CrmPreorder::query()->where('status', 'open')->count(),
            'pending_referrals' => (int) CrmReferral::query()->where('status', 'pending')->count(),
            'open_feedback' => (int) CrmFeedbackEntry::query()->where('status', 'open')->count(),
            'active_automations' => (int) CrmAutomation::query()->where('is_active', true)->count(),
        ];

        $recentInbox = CrmInboxMessage::query()->with('customer')->orderByDesc('id')->limit(8)->get();
        $recentFeedback = CrmFeedbackEntry::query()->with('customer')->orderByDesc('id')->limit(8)->get();

        return view('admin.crm.hub', [
            'title' => 'CRM Hub',
            'kpis' => $kpis,
            'recentInbox' => $recentInbox,
            'recentFeedback' => $recentFeedback,
        ]);
    }

    public function inbox(Request $request)
    {
        $query = CrmInboxMessage::query()->with(['customer', 'assignee']);

        if ($status = $request->query('status')) {
            if (in_array($status, ['open', 'closed'], true)) {
                $query->where('status', $status);
            }
        }

        if ($channel = $request->query('channel')) {
            if (in_array($channel, ['whatsapp', 'sms', 'email', 'call'], true)) {
                $query->where('channel', $channel);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.crm.inbox', [
            'title' => 'CRM Inbox',
            'messages' => $messages,
            'customers' => $customers,
        ]);
    }

    public function inboxStore(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'channel' => ['required', 'in:whatsapp,sms,email,call'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
        ]);

        CrmInboxMessage::create([
            'customer_id' => $data['customer_id'] ?? null,
            'channel' => $data['channel'],
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'priority' => $data['priority'],
            'status' => 'open',
            'created_by' => optional(auth()->user())->id,
        ]);

        return redirect()->route('admin.crm.inbox')->with('status', 'Message logged');
    }

    public function inboxClose(CrmInboxMessage $message)
    {
        $message->update(['status' => 'closed']);

        return redirect()->route('admin.crm.inbox')->with('status', 'Message closed');
    }

    public function automations(Request $request)
    {
        $query = CrmAutomation::query();

        if (($active = $request->query('active')) !== null && $active !== '') {
            $query->where('is_active', (bool) ((int) $active));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('name', 'like', "%{$search}%");
        }

        $automations = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.crm.automations', [
            'title' => 'CRM Automations',
            'automations' => $automations,
        ]);
    }

    public function automationsStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'trigger_type' => ['required', 'string', 'max:50'],
            'action_type' => ['required', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        CrmAutomation::create([
            'name' => $data['name'],
            'trigger_type' => $data['trigger_type'],
            'action_type' => $data['action_type'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.crm.automations')->with('status', 'Automation created');
    }

    public function automationsToggle(CrmAutomation $automation)
    {
        $automation->update(['is_active' => !$automation->is_active]);

        return redirect()->route('admin.crm.automations')->with('status', 'Automation updated');
    }

    public function preorders(Request $request)
    {
        $query = CrmPreorder::query()->with('customer');

        if ($status = $request->query('status')) {
            if (in_array($status, ['open', 'fulfilled', 'cancelled'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $preorders = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.crm.preorders', [
            'title' => 'CRM Pre-Orders',
            'preorders' => $preorders,
            'customers' => $customers,
        ]);
    }

    public function preordersStore(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'product_name' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'expected_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        CrmPreorder::create([
            'customer_id' => $data['customer_id'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'product_name' => $data['product_name'],
            'qty' => (float) $data['qty'],
            'expected_date' => $data['expected_date'] ?? null,
            'status' => 'open',
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('admin.crm.preorders')->with('status', 'Pre-order created');
    }

    public function preordersClose(CrmPreorder $preorder, Request $request)
    {
        $status = $request->input('status', 'fulfilled');
        $status = in_array($status, ['fulfilled', 'cancelled'], true) ? $status : 'fulfilled';

        $preorder->update(['status' => $status]);

        return redirect()->route('admin.crm.preorders')->with('status', 'Pre-order updated');
    }

    public function referrals(Request $request)
    {
        $query = CrmReferral::query()->with('referrerCustomer');

        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('referrer_name', 'like', "%{$search}%")
                    ->orWhere('referee_name', 'like', "%{$search}%")
                    ->orWhere('referee_phone', 'like', "%{$search}%");
            });
        }

        $referrals = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.crm.referrals', [
            'title' => 'CRM Referrals',
            'referrals' => $referrals,
            'customers' => $customers,
        ]);
    }

    public function referralsStore(Request $request)
    {
        $data = $request->validate([
            'referrer_customer_id' => ['nullable', 'exists:customers,id'],
            'referrer_name' => ['nullable', 'string', 'max:255'],
            'referee_name' => ['required', 'string', 'max:255'],
            'referee_phone' => ['nullable', 'string', 'max:50'],
            'reward_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        CrmReferral::create([
            'referrer_customer_id' => $data['referrer_customer_id'] ?? null,
            'referrer_name' => $data['referrer_name'] ?? null,
            'referee_name' => $data['referee_name'],
            'referee_phone' => $data['referee_phone'] ?? null,
            'reward_amount' => (float) ($data['reward_amount'] ?? 0),
            'status' => 'pending',
        ]);

        return redirect()->route('admin.crm.referrals')->with('status', 'Referral logged');
    }

    public function referralsUpdateStatus(CrmReferral $referral, Request $request)
    {
        $status = $request->input('status', 'approved');
        $status = in_array($status, ['approved', 'rejected', 'pending'], true) ? $status : 'pending';

        $referral->update(['status' => $status]);

        return redirect()->route('admin.crm.referrals')->with('status', 'Referral updated');
    }

    public function loyalty(Request $request)
    {
        $query = CrmLoyaltyAccount::query()->with('customer');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderByDesc('points_balance')->paginate(20)->withQueryString();
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.crm.loyalty', [
            'title' => 'CRM Loyalty',
            'accounts' => $accounts,
            'customers' => $customers,
        ]);
    }

    public function loyaltyCreateAccount(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
        ]);

        CrmLoyaltyAccount::firstOrCreate([
            'customer_id' => $data['customer_id'],
        ], [
            'points_balance' => 0,
        ]);

        return redirect()->route('admin.crm.loyalty')->with('status', 'Loyalty account ready');
    }

    public function loyaltyAdjust(Request $request)
    {
        $data = $request->validate([
            'crm_loyalty_account_id' => ['required', 'exists:crm_loyalty_accounts,id'],
            'posting_date' => ['required', 'date'],
            'type' => ['required', 'in:earn,redeem,adjust'],
            'points' => ['required', 'numeric'],
            'reference' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $account = CrmLoyaltyAccount::query()->findOrFail($data['crm_loyalty_account_id']);
        $points = (float) $data['points'];

        CrmLoyaltyTransaction::create([
            'crm_loyalty_account_id' => $account->id,
            'posting_date' => $data['posting_date'],
            'type' => $data['type'],
            'points' => $points,
            'reference' => $data['reference'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $delta = $data['type'] === 'redeem' ? -abs($points) : $points;
        $account->update(['points_balance' => (float) $account->points_balance + $delta]);

        return redirect()->route('admin.crm.loyalty')->with('status', 'Points updated');
    }

    public function feedback(Request $request)
    {
        $query = CrmFeedbackEntry::query()->with('customer');

        if ($status = $request->query('status')) {
            if (in_array($status, ['open', 'resolved'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $entries = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $customers = Customer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.crm.feedback', [
            'title' => 'CRM Feedback',
            'entries' => $entries,
            'customers' => $customers,
        ]);
    }

    public function feedbackStore(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string'],
        ]);

        CrmFeedbackEntry::create([
            'customer_id' => $data['customer_id'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'rating' => (int) $data['rating'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return redirect()->route('admin.crm.feedback')->with('status', 'Feedback saved');
    }

    public function feedbackResolve(CrmFeedbackEntry $entry)
    {
        $entry->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.crm.feedback')->with('status', 'Feedback resolved');
    }

    public function upsell(Request $request)
    {
        $query = CrmUpsellCampaign::query();

        if ($status = $request->query('status')) {
            if (in_array($status, ['draft', 'active', 'closed'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('name', 'like', "%{$search}%");
        }

        $campaigns = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.crm.upsell', [
            'title' => 'CRM Upsell',
            'campaigns' => $campaigns,
        ]);
    }

    public function upsellStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'in:whatsapp,sms,email'],
            'offer_text' => ['required', 'string'],
        ]);

        CrmUpsellCampaign::create([
            'name' => $data['name'],
            'channel' => $data['channel'],
            'offer_text' => $data['offer_text'],
            'status' => 'draft',
        ]);

        return redirect()->route('admin.crm.upsell')->with('status', 'Campaign created');
    }

    public function upsellToggle(CrmUpsellCampaign $campaign)
    {
        $next = $campaign->status === 'active' ? 'closed' : 'active';
        if ($campaign->status === 'closed') {
            $next = 'draft';
        }

        $campaign->update(['status' => $next]);

        return redirect()->route('admin.crm.upsell')->with('status', 'Campaign updated');
    }

    public function customers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $customers = Customer::query()
            ->where('is_active', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                        ->orWhere('customer_number', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('whatsapp', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $loyalty = CrmLoyaltyAccount::query()->get()->keyBy('customer_id');

        return view('admin.crm.customers', [
            'title' => 'CRM Customers',
            'customers' => $customers,
            'q' => $q,
            'loyalty' => $loyalty,
        ]);
    }
}
