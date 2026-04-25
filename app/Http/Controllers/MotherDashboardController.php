<?php

namespace App\Http\Controllers;

use App\Models\Mother;
use App\Models\MotherAppointment;
use App\Models\MotherBloodPressure;
use App\Models\MotherChecklistItem;
use App\Models\MotherDailyLog;
use App\Models\MotherHealthAlert;
use App\Models\MotherKickCount;
use App\Models\MotherWeightLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MotherDashboardController extends Controller
{
    protected function getMotherFromRequest(Request $request): ?Mother
    {
        // Support both authenticated users with mother profile
        // and access via MK number in session
        if (auth()->check() && auth()->user()->mother) {
            return auth()->user()->mother;
        }

        $mkNumber = session('mk_number') ?? $request->query('mk');
        if ($mkNumber) {
            return Mother::where('mk_number', $mkNumber)->first();
        }

        return null;
    }

    public function dashboard(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')
                ->with('error', 'Please register or enter your MK number to access your dashboard.');
        }

        // Load all necessary relationships
        $mother->load([
            'appointments' => fn($q) => $q->upcoming()->limit(5),
            'weightLogs' => fn($q) => $q->recent()->limit(10),
            'bloodPressures' => fn($q) => $q->recent()->limit(10),
            'kickCounts' => fn($q) => $q->recent()->limit(7),
            'healthAlerts' => fn($q) => $q->unresolved()->recent()->limit(5),
            'checklistItems' => fn($q) => $q->pending()->limit(5),
            'dailyLogs' => fn($q) => $q->recent()->limit(7),
            'region',
            'district',
        ]);

        // Calculate key metrics
        $metrics = [
            'weeks_pregnant' => $mother->weeks_pregnant,
            'trimester' => $mother->trimester,
            'days_until_edd' => $mother->edd_date ? now()->diffInDays($mother->edd_date, false) : null,
            'progress_percentage' => $mother->weeks_pregnant ? min(100, round(($mother->weeks_pregnant / 40) * 100, 1)) : 0,
            'unread_alerts' => $mother->unread_alerts_count,
            'critical_alerts' => $mother->critical_alerts->count(),
            'upcoming_appointments' => $mother->appointments->where('status', 'scheduled')->count(),
            'pending_checklist' => $mother->checklistItems->where('is_completed', false)->count(),
            'latest_weight' => $mother->latest_weight,
            'latest_bp' => $mother->latest_blood_pressure,
        ];

        // Get today's daily log if exists
        $todayLog = $mother->dailyLogs->where('log_date', today())->first();

        // Prepare chart data
        $weightChartData = $this->prepareWeightChartData($mother->weightLogs);
        $bpChartData = $this->prepareBpChartData($mother->bloodPressures);
        $kickChartData = $this->prepareKickChartData($mother->kickCounts);

        // Get pregnancy timeline
        $timeline = $this->getPregnancyTimeline($mother);

        // Get weekly tip
        $weeklyTip = $this->getWeeklyTip($mother->weeks_pregnant);

        return view('mother.dashboard', compact(
            'mother',
            'metrics',
            'todayLog',
            'weightChartData',
            'bpChartData',
            'kickChartData',
            'timeline',
            'weeklyTip'
        ));
    }

    public function profile(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $mother->load(['region', 'district', 'country']);

        return view('mother.profile', compact('mother'));
    }

    public function appointments(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $upcoming = $mother->appointments()->upcoming()->get();
        $past = $mother->appointments()->past()->limit(10)->get();

        return view('mother.appointments', compact('mother', 'upcoming', 'past'));
    }

    public function storeAppointment(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->back()->with('error', 'Session expired. Please login again.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'appointment_date' => 'required|date|after:now',
            'clinic_name' => 'nullable|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'type' => 'required|in:checkup,ultrasound,lab_test,vaccination,other',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check your inputs and try again.');
        }

        try {
            $mother->appointments()->create([
                'title' => $request->title,
                'description' => $request->description,
                'appointment_date' => $request->appointment_date,
                'clinic_name' => $request->clinic_name,
                'doctor_name' => $request->doctor_name,
                'type' => $request->type,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]);

            return redirect()->route('mother.appointments')
                ->with('success', 'Appointment scheduled successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create appointment', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to schedule appointment. Please try again.');
        }
    }

    public function healthData(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $weightLogs = $mother->weightLogs()->recent()->limit(30)->get();
        $bpLogs = $mother->bloodPressures()->recent()->limit(30)->get();
        $kickCounts = $mother->kickCounts()->recent()->limit(30)->get();

        // Statistics
        $stats = [
            'weight_change' => $this->calculateWeightChange($weightLogs),
            'avg_bp' => $this->calculateAvgBP($bpLogs),
            'total_kicks_recorded' => $kickCounts->sum('kick_count'),
        ];

        return view('mother.health-data', compact('mother', 'weightLogs', 'bpLogs', 'kickCounts', 'stats'));
    }

    public function storeWeight(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->back()->with('error', 'Session expired.');
        }

        $validator = Validator::make($request->all(), [
            'weight_kg' => 'required|numeric|min:30|max:200',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mother->weightLogs()->create([
            'weight_kg' => $request->weight_kg,
            'weeks_pregnant' => $mother->weeks_pregnant,
            'recorded_date' => today(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('mother.health-data')->with('success', 'Weight recorded successfully!');
    }

    public function storeBloodPressure(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->back()->with('error', 'Session expired.');
        }

        $validator = Validator::make($request->all(), [
            'systolic' => 'required|integer|min:70|max:200',
            'diastolic' => 'required|integer|min:40|max:130',
            'heart_rate' => 'nullable|integer|min:40|max:150',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bp = $mother->bloodPressures()->create([
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'heart_rate' => $request->heart_rate,
            'recorded_at' => now(),
            'notes' => $request->notes,
        ]);

        // Create alert if BP is high
        if ($bp->is_high) {
            $severity = $bp->is_severe ? 'critical' : 'high';
            $mother->healthAlerts()->create([
                'alert_type' => 'high_bp',
                'severity' => $severity,
                'message' => "Blood pressure reading of {$bp->systolic}/{$bp->diastolic} mmHg detected. This is above normal range.",
                'recommendation' => $bp->is_severe 
                    ? 'URGENT: Please contact your healthcare provider immediately or visit the nearest clinic.'
                    : 'Please monitor your blood pressure regularly and consult your doctor if it remains elevated.',
            ]);
        }

        return redirect()->route('mother.health-data')->with('success', 'Blood pressure recorded successfully!');
    }

    public function storeKickCount(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->back()->with('error', 'Session expired.');
        }

        $validator = Validator::make($request->all(), [
            'kick_count' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kickCount = $mother->kickCounts()->create([
            'kick_count' => $request->kick_count,
            'duration_minutes' => $request->duration_minutes,
            'started_at' => now()->subMinutes($request->duration_minutes),
            'ended_at' => now(),
            'recorded_date' => today(),
            'notes' => $request->notes,
        ]);

        // Alert if kick count is low
        if (!$kickCount->is_normal) {
            $mother->healthAlerts()->create([
                'alert_type' => 'low_kick_count',
                'severity' => 'medium',
                'message' => "Low fetal movement detected: {$request->kick_count} kicks in {$request->duration_minutes} minutes.",
                'recommendation' => 'Try drinking cold water, lying on your left side, and counting again. If movement remains low, contact your healthcare provider.',
            ]);
        }

        return redirect()->route('mother.health-data')->with('success', 'Kick count recorded successfully!');
    }

    public function alerts(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $alerts = $mother->healthAlerts()->recent()->paginate(20);
        $unreadCount = $mother->healthAlerts()->unread()->count();

        return view('mother.alerts', compact('mother', 'alerts', 'unreadCount'));
    }

    public function markAlertRead(Request $request, MotherHealthAlert $alert)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother || $alert->mother_id !== $mother->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $alert->markAsRead();

        return redirect()->back()->with('success', 'Alert marked as read.');
    }

    public function checklist(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $items = $mother->checklistItems()
            ->orderBy('recommended_week')
            ->orderBy('category')
            ->get()
            ->groupBy('category');

        $progress = [
            'total' => $mother->checklistItems()->count(),
            'completed' => $mother->checklistItems()->completed()->count(),
        ];
        $progress['percentage'] = $progress['total'] > 0 
            ? round(($progress['completed'] / $progress['total']) * 100, 1) 
            : 0;

        return view('mother.checklist', compact('mother', 'items', 'progress'));
    }

    public function toggleChecklistItem(Request $request, MotherChecklistItem $item)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother || $item->mother_id !== $mother->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        if ($item->is_completed) {
            $item->markAsIncomplete();
            $message = 'Item marked as pending.';
        } else {
            $item->markAsComplete();
            $message = 'Item completed! Great job!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function dailyLog(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->route('join')->with('error', 'Please register first.');
        }

        $logs = $mother->dailyLogs()->recent()->paginate(14);
        $todayLog = $mother->dailyLogs()->today()->first();

        return view('mother.daily-log', compact('mother', 'logs', 'todayLog'));
    }

    public function storeDailyLog(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        if (!$mother) {
            return redirect()->back()->with('error', 'Session expired.');
        }

        $validator = Validator::make($request->all(), [
            'mood' => 'required|in:great,good,okay,tired,sad,anxious',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'string',
            'sleep_hours' => 'nullable|numeric|min:0|max:24',
            'water_intake_glasses' => 'nullable|integer|min:0|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update or create today's log
        $mother->dailyLogs()->updateOrCreate(
            ['log_date' => today()],
            [
                'mood' => $request->mood,
                'symptoms' => $request->symptoms ?? [],
                'sleep_hours' => $request->sleep_hours,
                'water_intake_glasses' => $request->water_intake_glasses,
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('mother.daily-log')->with('success', 'Daily log updated!');
    }

    public function emergency(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);

        return view('mother.emergency', compact('mother'));
    }

    public function education(Request $request)
    {
        $mother = $this->getMotherFromRequest($request);
        $week = $mother?->weeks_pregnant ?? 1;

        $articles = $this->getWeeklyEducationContent($week);

        return view('mother.education', compact('mother', 'articles', 'week'));
    }

    // Helper methods

    private function prepareWeightChartData($logs)
    {
        $logs = $logs->sortBy('recorded_date');
        return [
            'labels' => $logs->map(fn($l) => $l->recorded_date->format('M d'))->toArray(),
            'data' => $logs->map(fn($l) => $l->weight_kg)->toArray(),
        ];
    }

    private function prepareBpChartData($logs)
    {
        $logs = $logs->sortBy('recorded_at');
        return [
            'labels' => $logs->map(fn($l) => $l->recorded_at->format('M d H:i'))->toArray(),
            'systolic' => $logs->map(fn($l) => $l->systolic)->toArray(),
            'diastolic' => $logs->map(fn($l) => $l->diastolic)->toArray(),
        ];
    }

    private function prepareKickChartData($logs)
    {
        $logs = $logs->sortBy('recorded_date');
        return [
            'labels' => $logs->map(fn($l) => $l->recorded_date->format('M d'))->toArray(),
            'data' => $logs->map(fn($l) => $l->kick_count)->toArray(),
        ];
    }

    private function calculateWeightChange($logs)
    {
        if ($logs->count() < 2) return null;
        $first = $logs->last();
        $latest = $logs->first();
        return round($latest->weight_kg - $first->weight_kg, 2);
    }

    private function calculateAvgBP($logs)
    {
        if ($logs->isEmpty()) return null;
        return [
            'systolic' => round($logs->avg('systolic')),
            'diastolic' => round($logs->avg('diastolic')),
        ];
    }

    private function getPregnancyTimeline(Mother $mother)
    {
        $timeline = [];
        $weeks = $mother->weeks_pregnant ?? 0;

        $milestones = [
            4 => ['title' => 'Pregnancy Confirmed', 'icon' => 'fa-check-circle', 'color' => 'green'],
            8 => ['title' => 'First Ultrasound', 'icon' => 'fa-baby', 'color' => 'blue'],
            12 => ['title' => 'First Trimester Complete', 'icon' => 'fa-star', 'color' => 'purple'],
            16 => ['title' => 'Gender Reveal Possible', 'icon' => 'fa-question', 'color' => 'pink'],
            20 => ['title' => 'Halfway Point!', 'icon' => 'fa-flag-checkered', 'color' => 'yellow'],
            24 => ['title' => 'Viability Milestone', 'icon' => 'fa-heart', 'color' => 'red'],
            28 => ['title' => 'Third Trimester Begins', 'icon' => 'fa-play', 'color' => 'orange'],
            32 => ['title' => 'Baby Positioning', 'icon' => 'fa-arrows-rotate', 'color' => 'teal'],
            36 => ['title' => 'Full Term', 'icon' => 'fa-crown', 'color' => 'gold'],
            40 => ['title' => 'Due Date', 'icon' => 'fa-calendar-check', 'color' => 'green'],
        ];

        foreach ($milestones as $week => $milestone) {
            $status = $weeks >= $week ? 'completed' : ($weeks >= $week - 2 ? 'current' : 'upcoming');
            $timeline[] = [
                'week' => $week,
                'title' => $milestone['title'],
                'icon' => $milestone['icon'],
                'color' => $milestone['color'],
                'status' => $status,
            ];
        }

        return $timeline;
    }

    private function getWeeklyTip(?int $weeks): array
    {
        $tips = [
            'default' => [
                'title' => 'Welcome to MamaCare',
                'content' => 'Track your pregnancy journey with our comprehensive tools. Stay healthy and informed!',
            ],
            1 => [
                'title' => 'Early Pregnancy',
                'content' => 'Start taking prenatal vitamins with folic acid. Avoid alcohol, smoking, and limit caffeine.',
            ],
            4 => [
                'title' => 'First Trimester',
                'content' => 'Schedule your first prenatal appointment. Rest when you feel tired - your body is working hard!',
            ],
            8 => [
                'title' => 'Growing Baby',
                'content' => 'Eat small, frequent meals to help with nausea. Stay hydrated with at least 8 glasses of water daily.',
            ],
            12 => [
                'title' => 'Second Trimester Begins',
                'content' => 'Many women feel more energetic now. Great time to start gentle exercises like walking or prenatal yoga.',
            ],
            16 => [
                'title' => 'Baby\'s Growth',
                'content' => 'Your baby can hear now! Talk, sing, or read to them. Continue eating a balanced diet rich in calcium.',
            ],
            20 => [
                'title' => 'Halfway There!',
                'content' => 'You may feel your baby\'s first movements (quickening). These feel like flutters or bubbles.',
            ],
            24 => [
                'title' => 'Viability Milestone',
                'content' => 'Your baby has a good chance of survival if born now. Continue regular checkups and monitor blood pressure.',
            ],
            28 => [
                'title' => 'Third Trimester',
                'content' => 'Start preparing your hospital bag. Attend childbirth classes if available. Practice breathing exercises.',
            ],
            32 => [
                'title' => 'Counting Kicks',
                'content' => 'Monitor your baby\'s movements daily. You should feel about 10 kicks every 2 hours when active.',
            ],
            36 => [
                'title' => 'Almost There!',
                'content' => 'Your baby is considered full term. Rest as much as possible. Watch for signs of labor.',
            ],
            40 => [
                'title' => 'Due Date',
                'content' => 'Your baby will arrive soon! Stay calm, pack your bag, and know the signs of labor.',
            ],
        ];

        return $tips[$weeks] ?? $tips['default'];
    }

    private function getWeeklyEducationContent(int $week): array
    {
        return [
            [
                'title' => "Week {$week}: Your Baby's Development",
                'content' => 'Learn about your baby\'s growth and development this week.',
                'type' => 'development',
            ],
            [
                'title' => 'Nutrition Tips',
                'content' => 'Healthy eating recommendations for this stage of pregnancy.',
                'type' => 'nutrition',
            ],
            [
                'title' => 'Exercise Guidelines',
                'content' => 'Safe exercises and activities you can do during pregnancy.',
                'type' => 'fitness',
            ],
            [
                'title' => 'Common Symptoms',
                'content' => 'What to expect and how to manage common pregnancy symptoms.',
                'type' => 'symptoms',
            ],
        ];
    }
}
