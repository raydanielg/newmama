<!DOCTYPE html>
<html lang="sw" class="h-full bg-[#fdfbf7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MamaCare - Welcome Journey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(238, 242, 255, 0.9) 100%);
            z-index: -1;
        }
        .step-content { display: none; }
        .step-content.active { display: block; }
        
        .option-card {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .option-card:hover {
            transform: translateY(-4px);
        }
        .option-card.selected {
            border-color: #4f46e5;
            background-color: #eef2ff;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.1);
        }
        .option-card.selected i {
            color: #4f46e5;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="w-full max-w-[550px] animate__animated animate__fadeIn">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-white p-10 md:p-12 relative overflow-hidden">
            
            <!-- Progress Bar -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-100">
                <div id="progress-bar" class="h-full bg-indigo-600 transition-all duration-500" style="width: 25%"></div>
            </div>

            <!-- STEP 1: Welcome -->
            <div id="step-1" class="step-content active animate__animated animate__fadeInRight">
                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-sparkles text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Karibu Mama!</h1>
                    <p class="text-slate-600 text-lg leading-relaxed font-medium">Tusaidie kukufahamu vizuri ili tukupe huduma bora zaidi ya safari yako.</p>
                </div>
                
                <div class="space-y-6">
                    <button onclick="nextStep(2)" class="w-full bg-slate-900 hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-slate-200 active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span>Tuanze Safari</span>
                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2: Status Selection -->
            <div id="step-2" class="step-content animate__animated">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Hali yako kwa sasa?</h2>
                    <p class="text-slate-500 text-sm font-medium">Chagua moja inayokuhusu zaidi</p>
                </div>

                <div class="grid gap-4">
                    <div onclick="selectStatus('pregnant')" class="option-card p-6 bg-white border-2 border-slate-100 rounded-2xl flex items-center gap-5" id="opt-pregnant">
                        <div class="w-14 h-14 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-baby text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-slate-800">Nina Ujauzito</h3>
                            <p class="text-xs text-slate-500 font-medium">Kufuatilia maendeleo ya mimba</p>
                        </div>
                    </div>

                    <div onclick="selectStatus('new_parent')" class="option-card p-6 bg-white border-2 border-slate-100 rounded-2xl flex items-center gap-5" id="opt-new_parent">
                        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-child-reaching text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-slate-800">Tayari nina Mtoto</h3>
                            <p class="text-xs text-slate-500 font-medium">Malezi na afya ya baada ya uzazi</p>
                        </div>
                    </div>

                    <div onclick="selectStatus('trying')" class="option-card p-6 bg-white border-2 border-slate-100 rounded-2xl flex items-center gap-5" id="opt-trying">
                        <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-heart-pulse text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-slate-800">Natafuta Mtoto</h3>
                            <p class="text-xs text-slate-500 font-medium">Ushauri wa kushika ujauzito</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button onclick="prevStep(1)" class="flex-1 py-4 font-black text-slate-400 hover:text-slate-600 transition-colors">Rudi</button>
                    <button id="btn-step-2" onclick="nextStep(3)" class="flex-[2] bg-slate-900 text-white font-black py-4 rounded-2xl opacity-50 cursor-not-allowed" disabled>Endelea</button>
                </div>
            </div>

            <!-- STEP 3: Details Input -->
            <div id="step-3" class="step-content animate__animated">
                <div id="details-pregnant" class="details-substep hidden">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Hongera sana Mama!</h2>
                        <p class="text-slate-500 text-sm font-medium">Lini ni tarehe yako ya makadirio ya kujifungua (EDD)?</p>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Tarehe ya EDD</label>
                        <input type="date" id="edd_date" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 text-sm font-bold">
                        <p class="text-[11px] text-slate-400 font-medium italic">Kama huijui, unaweza kukadiria miezi 9 tangu siku ya kwanza ya hedhi yako ya mwisho.</p>
                    </div>
                </div>

                <div id="details-new_parent" class="details-substep hidden">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Karibu kwenye Uzazi!</h2>
                        <p class="text-slate-500 text-sm font-medium">Mtoto wako ana umri gani sasa?</p>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Umri wa Mtoto (Miezi)</label>
                        <input type="number" id="baby_age" placeholder="Mfano: 3" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 text-sm font-bold">
                    </div>
                </div>

                <div id="details-trying" class="details-substep hidden">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Tuko nawe!</h2>
                        <p class="text-slate-500 text-sm font-medium">Umekuwa ukijaribu kwa muda gani?</p>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest ml-1">Muda wa Kujaribu</label>
                        <select id="trying_duration" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500/30 text-sm font-bold">
                            <option value="under_6_months">Chini ya miezi 6</option>
                            <option value="6_to_12_months">Miezi 6 - 12</option>
                            <option value="over_1_year">Zaidi ya mwaka 1</option>
                        </select>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button onclick="prevStep(2)" class="flex-1 py-4 font-black text-slate-400 hover:text-slate-600 transition-colors">Rudi</button>
                    <button onclick="completeOnboarding()" class="flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2">
                        <span>Kamilisha</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentStatus = '';
        const progressBar = document.getElementById('progress-bar');

        function nextStep(step) {
            document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active', 'animate__fadeInRight'));
            const nextStepEl = document.getElementById('step-' + step);
            nextStepEl.style.display = 'block';
            nextStepEl.classList.add('active', 'animate__fadeInRight');
            
            progressBar.style.width = (step * 33.33) + '%';

            if (step === 3) {
                document.querySelectorAll('.details-substep').forEach(d => d.classList.add('hidden'));
                document.getElementById('details-' + currentStatus).classList.remove('hidden');
            }
        }

        function prevStep(step) {
            document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active', 'animate__fadeInRight'));
            const prevStepEl = document.getElementById('step-' + step);
            prevStepEl.style.display = 'block';
            prevStepEl.classList.add('active', 'animate__fadeInLeft');
            
            progressBar.style.width = (step * 33.33) + '%';
        }

        function selectStatus(status) {
            currentStatus = status;
            document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('opt-' + status).classList.add('selected');
            
            const btn = document.getElementById('btn-step-2');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'shadow-lg', 'shadow-indigo-100');
        }

        async function completeOnboarding() {
            const data = {
                status: currentStatus,
                edd_date: document.getElementById('edd_date').value,
                baby_age: document.getElementById('baby_age').value,
                trying_duration: document.getElementById('trying_duration').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route('mother.onboarding.complete') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    window.location.href = result.redirect;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Kuna tatizo limetokea. Tafadhali jaribu tena.');
            }
        }
    </script>
</body>
</html>
