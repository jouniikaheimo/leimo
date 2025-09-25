<x-filament-panels::page>
    <style>
        /* Hide scrollbars for the left tab column while keeping scroll functionality */
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Curved tab effect that works with gaps between tabs - improved version */
        .curved-tab.active::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: -15px;
            width: 21px;
            height: 15px;
            background: radial-gradient(circle at 0 0, transparent 15px, var(--tab-bg-color, white) 15px);
            z-index: 1;
        }

        .curved-tab.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: -13px;
            width: 18px;
            height: 15px;
            background: radial-gradient(circle at 15px 0, transparent 15px, var(--tab-bg-color, white) 15px);
            z-index: 10;
        }

        /* Ensure the curved effect works for both active and inactive tabs */
        .curved-tab::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: -15px;
            width: 15px;
            height: 15px;
            background: radial-gradient(circle at 0 0, transparent 15px, var(--tab-bg-color, #ffffff) 15px);
            z-index: 1;
        }

        .curved-tab::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: -15px;
            width: 15px;
            height: 15px;
            background: radial-gradient(circle at 15px 0, transparent 15px, var(--tab-bg-color, #ffffff) 15px);
            z-index: 1;
        }
    </style>

    @if ($userSummaries->isEmpty())
        <x-filament::section>
            <p class="text-sm text-gray-500">
                Ei käyttäjiä tai kellokortin kirjauksia.
            </p>
        </x-filament::section>
    @else
        <x-filament::section class="mb-4">
            @php
                $from = $filters['from'] ?? now()->startOfMonth()->toDateString();
                $until = $filters['until'] ?? now()->toDateString();
                $shift = (string) ($filters['shift_hours'] ?? 8);
                $entryGroup = $filters['entry_group'] ?? 'all';
                $baseUrl = url()->current();
                $entriesTabBg = \App\Services\ThemeColorService::getAllColors()['entries_tab_bg'];
                $summaryTabBg = \App\Services\ThemeColorService::getAllColors()['summary_tab_bg'];
            @endphp

            <form method="GET" action="{{ $baseUrl }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="text-xs font-medium text-gray-500">Aikaväli</label>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['from' => now()->toDateString(), 'until' => now()->toDateString()]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Päivä</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => now()->startOfWeek()->toDateString(), 'until' => now()->endOfWeek()->toDateString()]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Viikko</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => now()->startOfMonth()->toDateString(), 'until' => now()->endOfMonth()->toDateString()]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Kuukausi</a>
                        @php
                            $startHalf = now()->startOfMonth()->toDateString();
                            $mid = now()->startOfMonth()->addDays(14)->toDateString();
                            $endMonth = now()->endOfMonth()->toDateString();
                            $lastWeekStart = now()->subWeek()->startOfWeek()->toDateString();
                            $lastWeekEnd = now()->subWeek()->endOfWeek()->toDateString();
                            $lastMonthStart = now()->subMonth()->startOfMonth()->toDateString();
                            $lastMonthEnd = now()->subMonth()->endOfMonth()->toDateString();
                            // Last pay period: if now <= 15th -> previous month 16..end, else current month 1..15
                            $todayDay = (int) now()->format('j');
                            if ($todayDay <= 15) {
                                $lppFrom = now()->subMonth()->startOfMonth()->addDays(15)->toDateString();
                                $lppUntil = now()->subMonth()->endOfMonth()->toDateString();
                            } else {
                                $lppFrom = now()->startOfMonth()->toDateString();
                                $lppUntil = now()->startOfMonth()->addDays(14)->toDateString();
                            }
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['from' => $startHalf, 'until' => $mid]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">1.–15.</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => Carbon\Carbon::parse($mid)->addDay()->toDateString(), 'until' => $endMonth]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">16.–kk loppu</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => $lastWeekStart, 'until' => $lastWeekEnd]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Viime viikko</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => $lastMonthStart, 'until' => $lastMonthEnd]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Viime kk</a>
                        <a href="{{ request()->fullUrlWithQuery(['from' => $lppFrom, 'until' => $lppUntil]) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:border-primary-400">Viime palkkajakso</a>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Alkaen</label>
                    <input type="date" name="from" value="{{ $from }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm" />
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500">Asti</label>
                    <input type="date" name="until" value="{{ $until }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm" />
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Oletusvuoro</label>
                    <select name="shift_hours" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm">
                        @foreach (['7.5' => '7 h 30 min', '8' => '8 h', '9' => '9 h'] as $value => $label)
                            <option value="{{ $value }}" @selected((string) $value === $shift)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Suodatus</label>
                    <select name="entry_group" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm">
                        <option value="all" @selected($entryGroup === 'all')>Kaikki</option>
                        <option value="work" @selected($entryGroup === 'work')>Työvuorot</option>
                        <option value="breaks" @selected($entryGroup === 'breaks')>Tauot</option>
                    </select>
                </div>

                <div class="mb-0.5">
                    <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-primary-700">Käytä</button>
                </div>
            </form>
        </x-filament::section>

        <div
                x-data="{
                activeUser: '{{ $firstUserId ?? '' }}',
            }"
                class="space-y-6 lg:space-y-0 lg:flex lg:items-start lg:gap-0"
        >
            <div
                    class="relative flex gap-2 overflow-x-auto pb-2 lg:w-20 lg:shrink-0 lg:flex-col lg:gap-2 lg:overflow-hidden lg:pb-0 lg:sticky lg:top-6 lg:h-[calc(100vh-7.5rem)] lg:-mr-1 no-scrollbar"
                    x-data="{
                    showTop: false,
                    showBottom: false,
                    update() {
                        const el = $refs.tabList;
                        if (!el) return;

                        this.showTop = el.scrollTop > 5;
                        this.showBottom = (el.scrollTop + el.clientHeight) < (el.scrollHeight - 5);
                    },
                    scrollBy(y) {
                        $refs.tabList?.scrollBy({ top: y, behavior: 'smooth' });
                    }
                }"
                    x-init="$nextTick(() => update())"
            >
                <!-- Top scroll button -->
                <div
                        x-show="showTop"
                        x-transition.opacity.duration.300
                        class="flex justify-end lg:justify-center"
                >
                    <button
                            type="button"
                            @click="scrollBy(-140)"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-gray-500 shadow-sm backdrop-blur-sm transition-colors hover:bg-white hover:text-primary-600 focus-visible:outline-none dark:border-slate-700/50 dark:bg-slate-800/90 dark:text-slate-300 lg:h-11 lg:w-11"
                            title="Vieritä ylös"
                            aria-label="Vieritä ylös"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M12 7.5a.75.75 0 0 1 .53.22l6 6a.75.75 0 1 1-1.06 1.06L12 9.31l-5.47 5.47a.75.75 0 0 1-1.06-1.06l6-6A.75.75 0 0 1 12 7.5z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <!-- User tabs list -->
                <div
                        class="relative flex h-full w-full flex-col items-end gap-2 overflow-y-auto pb-0 pr-0 pt-3 no-scrollbar"
                        x-ref="tabList"
                        @scroll.passive="update()"
                >
                    @foreach ($userSummaries as $index => $summary)
                        @php
                            $user = $summary['user'];

                            // Käytä asetuksista määriteltyjä käyttäjävärejä
                            $userColorData = \App\Services\ThemeColorService::getUserColor($index);
                            $bg = $userColorData['original'];
                            $border = $userColorData['original'];

                            // DEBUG: Tulosta värit konsoliin (poista tämä kun toimii)
                            if ($index < 3) {
                                \Log::info("EventLog User $index ({$user->name}): Original={$userColorData['original']}, BG=$bg, Border=$border");
                            }

                            // Build two-letter initials
                            $parts = array_values(array_filter(preg_split('/\s+/', trim((string) $user->name))));
                            $first = Str::upper(Str::substr($parts[0] ?? '', 0, 1));
                            $last = Str::upper(Str::substr($parts[count($parts) - 1] ?? '', 0, 1));
                            $initials = $first . (count($parts) > 1 ? $last : Str::upper(Str::substr($parts[0] ?? '', 1, 1)));
                        @endphp
                        <button
                                type="button"
                                @click="activeUser = '{{ $user->id }}'"
                                title="{{ $user->name }}"
                                class="relative flex h-28 min-w-[4.25rem] items-center justify-center rounded-xl text-left shadow-sm transition focus-visible:outline-none
           lg:h-32 lg:w-[4.25rem] lg:flex-col lg:items-center lg:justify-start lg:rounded-l-2xl lg:rounded-r-none lg:py-2 lg:gap-0 lg:transition-all lg:duration-200 lg:ease-out"
                                :class="activeUser === '{{ $user->id }}'
        ? 'lg:z-10 lg:translate-x-0 lg:opacity-100 lg:shadow'
        : 'lg:translate-x-[0.5rem] lg:opacity-80 hover:lg:translate-x-0 hover:lg:opacity-100'"
                                style="background-color: {{ $bg }}; border-color: {{ $border }}"
                        >
    <span class="flex h-9 w-9 items-center justify-center rounded-full border text-xs font-semibold uppercase tracking-wide"
          style="border-color: {{ $border }}; color:#334155; background-color:rgba(255,255,255,0.7)">
        {{ $initials }}
    </span>
                            <!-- Overtime indicator below avatar -->
                            <div class="hidden lg:flex lg:h-9 lg:w-9 lg:items-center lg:justify-center ">
                                @if (($summary['flags']['open_overtime'] ?? false) === true)
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                @endif
                            </div>

                            <span class="ml-3 block text-left text-sm font-medium text-gray-700 lg:hidden">
        {{ $user->name }}
    </span>
                        </button>

                    @endforeach
                </div>

                <!-- Bottom scroll button -->
                <div
                        x-show="showBottom"
                        x-transition.opacity.duration.300
                        class="flex justify-end lg:justify-center"
                >
                    <button
                            type="button"
                            @click="scrollBy(140)"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-gray-500 shadow-sm backdrop-blur-sm transition-colors hover:bg-white hover:text-primary-600 focus-visible:outline-none dark:border-slate-700/50 dark:bg-slate-800/90 dark:text-slate-300 lg:h-11 lg:w-11"
                            title="Vieritä alas"
                            aria-label="Vieritä alas"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M12 16.5a.75.75 0 0 1-.53-.22l-6-6A.75.75 0 1 1 6.53 9.2L12 14.69l5.47-5.47a.75.75 0 1 1 1.06 1.06l-6 6a.75.75 0 0 1-.53.22z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Right content area -->
            <div class="space-y-6 lg:flex-1">
                @foreach ($userSummaries as $index => $summary)
                    @php
                        $user = $summary['user'];

                        // Käytä asetuksista määriteltyjä käyttäjävärejä myös headerissa
                        $userColorData = \App\Services\ThemeColorService::getUserColor($index);
                        $headerBg = $userColorData['original'];
                        $headerBorder = $userColorData['original'];
                    @endphp
                    <div
                            x-show="activeUser === '{{ $user->id }}'"
                            x-cloak
                            x-transition.opacity.scale.90.duration.200
                            class="relative rounded-2xl shadow-lg lg:z-10 lg:-ml-1"
                            style="border-color: {{ $headerBorder }}"
                    >
                        <div class="px-6 py-6 rounded-tl-2xl  rounded-tr-2xl"
                             style="background-color: {{ $headerBg }}; border-color: {{ $headerBorder }}">
                            <x-filament::section>
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <x-slot name="heading">
                                    </x-slot>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">Nimi</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">Hetu</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->personal_identity_number ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">Puhelin</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->phone ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">Henkilönumero</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->id }}</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs uppercase tracking-wide text-gray-500">Kulkukortti</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->badge_number ?? '-' }}</p>
                                                </div>
                                                @php $cardValid = filled($user->badge_number); @endphp
                                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[11px] font-semibold {{ $cardValid ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700' }}">
                                                    {{ $cardValid ? 'Voimassa' : 'Ei voimassa' }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-600">
                                                Voimassa asti:
                                                <span class="font-medium text-gray-900">—</span>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">Työsuhteen alku</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $user->employment_started_at?->format('d.m.Y') ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </x-filament::section>
                        </div>

                        <div x-data="{ activeSection: 'entries' }" class="relative">
                            @php
                                $entriesCount = 0; $totalMinutes = 0;
                                foreach ($summary['days'] as $d) {
                                    $entriesCount += count($d['entries']);
                                    $totalMinutes += (int) ($d['shift']['duration_minutes'] ?? 0);
                                }
                                $totalDisplay = $totalMinutes > 0 ? sprintf('%d h %02d min', intdiv($totalMinutes, 60), $totalMinutes % 60) : '0 min';
                            @endphp

                                    <!-- Curved Tab Navigation -->
                            <div class="sticky top-16 z-10 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60">
                                <div class="flex items-center justify-between relative">
                                    <div class="w-full" style="background-color: {{ $headerBg }}; border-color: {{ $headerBorder }};">
                                        <ul class="flex list-none m-0 p-0 pl-10 gap-2">
                                            <!-- Entries Tab -->
                                            <li
                                                class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                                                :class="activeSection === 'entries' ? 'active' : ''"
                                                @click="activeSection = 'entries'"
                                                :style="'background: {{ $entriesTabBg }}; --tab-bg-color: {{ $entriesTabBg }};'"
                                            >
                                                <a class="no-underline flex items-center gap-2" :class="activeSection === 'entries' ? 'text-white' : 'text-white'">
                                                    <div class="w-4 h-4 border-2 border-current rounded"></div>
                                                    <span>Merkinnät &amp; leimaukset</span>
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-white/20 text-white">
                                                        {{ $entriesCount }}
                                                    </span>
                                                    @if (($summary['flags']['open_overtime'] ?? false) === true)
                                                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
                                                    @endif
                                                </a>
                                            </li>

                                            <!-- Summary Tab -->
                                            <li
                                                class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                                                :class="activeSection === 'summary' ? 'active' : ''"
                                                @click="activeSection = 'summary'"
                                                :style="'background: {{ $summaryTabBg }}; --tab-bg-color: {{ $summaryTabBg }};'"
                                            >
                                                <a class="no-underline flex items-center gap-2" :class="activeSection === 'summary' ? 'text-white' : 'text-white'">
                                                    <div class="w-4 h-4 rounded-full border-2 border-current">
                                                        <div class="w-1 h-1 bg-current rounded-full mt-1 ml-1"></div>
                                                    </div>
                                                    <span>Kuukausikertymä</span>
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-white/20 text-white">
                                                        {{ $totalDisplay }}
                                                    </span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="hidden text-xs text-gray-500 sm:block absolute top-3 right-6 bg-white/80 px-2 py-1 rounded">
                                        Yhteensä: <span class="font-semibold text-gray-900">{{ $totalDisplay }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div
                                class="px-6 pb-6 rounded-b-2xl"
                                :style="activeSection === 'entries' ? 'background-color: {{ $entriesTabBg }};' : 'background-color: {{ $summaryTabBg }};'"
                            >
                                <div x-show="activeSection === 'entries'" class="space-y-6 pt-6" x-cloak>
                                    <div class="space-y-6">
                                        @forelse ($summary['days'] as $day)
                                            <x-filament::section class="bg-white shadow-sm">
                                                <x-slot name="heading">{{ $day['date']->translatedFormat('l d.n.Y') }}</x-slot>

                                                <div class="mb-2 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-500">
                                                    <div>
                                                        @if ($day['shift']['duration_display'])
                                                            Työaika:
                                                            <span class="font-semibold text-gray-900">{{ $day['shift']['duration_display'] }}</span>
                                                        @endif
                                                        @if ($day['shift']['is_open'])
                                                            <span class="ml-2 inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                                Avoin vuoro
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($day['shift']['overtime_display'])
                                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                            Ylityö {{ $day['shift']['overtime_display'] }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if (($day['flags']['overlap'] ?? false) || ($day['flags']['incomplete'] ?? false))
                                                    <div class="mb-4 rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                                                        @if ($day['flags']['overlap'] ?? false)
                                                            <div>Varoitus: päällekkäisiä aikavälejä samana päivänä.</div>
                                                        @endif
                                                        @if ($day['flags']['incomplete'] ?? false)
                                                            <div>Huomio: joissakin tauoissa puuttuu aloitus tai lopetus.</div>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if ((config('timeclock.dev_bypass_admin') || in_array(auth()->user()?->email, (array) config('timeclock.admin_emails', []), true)) && ($day['shift']['is_open'] ?? false))
                                                    <div x-data="{ open: false }" class="mb-3">
                                                        <button type="button" @click="open = !open" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:border-primary-400 hover:text-primary-600">Leimaa työpäivä päättyneeksi</button>
                                                        <div x-show="open" x-cloak class="mt-2 flex flex-wrap items-end gap-2">
                                                            <label class="text-xs text-gray-500">Lopetusaika</label>
                                                            <input x-ref="endedAt" type="datetime-local" value="{{ $day['shift']['expected_end'] ? $day['shift']['expected_end']->format('Y-m-d\\TH:i') : ($day['shift']['start']?->copy()->addMinutes((int) round(($filters['shift_hours'] ?? 8) * 60))?->format('Y-m-d\\TH:i')) }}" class="rounded-lg border border-gray-200 px-2 py-1.5 text-sm" />
                                                            <input x-ref="notes" type="text" placeholder="Huomio (valinnainen)" class="min-w-[14rem] flex-1 rounded-lg border border-gray-200 px-2 py-1.5 text-sm" />
                                                            <button type="button" class="rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-primary-700"
                                                                    @click="$wire.closeShift({{ $summary['user']->id }}, '{{ $day['date']->format('Y-m-d') }}', $refs.endedAt.value, $refs.notes.value); open = false">
                                                                Tallenna
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="space-y-3">
                                                    @foreach ($day['entries'] as $entry)
                                                        @php
                                                            $group = $filters['entry_group'] ?? 'all';
                                                            $isBreak = in_array($entry['label'], ['Kahvitauko','Ruokatauko','Tauko']);
                                                            $isWork = in_array($entry['label'], ['Töihin tulo','Töistä lähtö']);
                                                            $show = $group === 'all' || ($group === 'breaks' && $isBreak) || ($group === 'work' && $isWork);
                                                        @endphp
                                                        @if (! $show)
                                                            @continue
                                                        @endif
                                                        @php
                                                            $badgeColors = [
                                                                'success' => 'bg-emerald-100 text-emerald-700',
                                                                'gray' => 'bg-gray-200 text-gray-700',
                                                                'warning' => 'bg-amber-100 text-amber-700',
                                                                'info' => 'bg-sky-100 text-sky-700',
                                                                'secondary' => 'bg-indigo-100 text-indigo-700',
                                                            ];
                                                            $badgeClass = $badgeColors[$entry['badge_color']] ?? 'bg-slate-200 text-slate-700';
                                                        @endphp
                                                        <div
                                                                @class([
																	'flex flex-wrap items-start justify-between gap-3 rounded-xl border px-4 py-3',
																	'border-red-200 bg-red-50 text-red-800' => $entry['status']['is_overtime_exit'] ?? false,
																	'border-amber-200 bg-amber-50 text-amber-800' => ($entry['status']['is_open_shift'] ?? false) || ($entry['status']['is_incomplete'] ?? false),
																	'border-gray-200 bg-gray-50 text-gray-800' => ! (($entry['status']['is_overtime_exit'] ?? false) || ($entry['status']['is_open_shift'] ?? false) || ($entry['status']['is_incomplete'] ?? false)),
																])
                                                        >
                                                            <div class="space-y-1">
                                                                <div class="flex items-center gap-2">
                                                                    <p class="text-sm font-semibold">{{ $entry['time_display'] }}</p>
                                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-wide {{ $badgeClass }}">
                                                                        {{ $entry['label'] }}
                                                                    </span>
                                                                </div>
                                                                @if ($entry['notes'])
                                                                    <p class="text-xs text-gray-500">{{ $entry['notes'] }}</p>
                                                                @endif
                                                            </div>
                                                            @if ($entry['duration_display'])
                                                                <div class="text-right text-sm font-semibold">
                                                                    {{ $entry['duration_display'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </x-filament::section>
                                        @empty
                                            <x-filament::section class="bg-white shadow-sm">
                                                <p class="text-sm text-gray-500">Ei kirjauksia tälle ajanjaksolle.</p>
                                            </x-filament::section>
                                        @endforelse
                                    </div>
                                </div>

                                <div x-show="activeSection === 'summary'" class="pt-6" x-cloak>
                                    <div class="space-y-4">
                                        @forelse ($summary['monthly'] as $month)
                                            <x-filament::section class="bg-white shadow-sm">
                                                <div class="flex flex-wrap items-center justify-between gap-4">
                                                    <div>
                                                        <p class="text-base font-semibold text-gray-900">{{ $month['label'] }}</p>
                                                        <p class="text-xs uppercase tracking-wide text-gray-400">{{ $month['worked_days'] }} työpäivää</p>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-5">
                                                        <div class="rounded-lg border border-gray-200 px-4 py-3 text-sm shadow-sm">
                                                            <p class="text-xs uppercase tracking-wide text-gray-400">Työaika yhteensä</p>
                                                            <p class="text-lg font-semibold text-gray-900">{{ $month['total_display'] }}</p>
                                                        </div>
                                                        <div class="rounded-lg border border-gray-200 px-4 py-3 text-sm shadow-sm">
                                                            <p class="text-xs uppercase tracking-wide text-gray-400">Arkipäivät</p>
                                                            <p class="text-lg font-semibold text-gray-900">{{ $month['weekday_display'] }}</p>
                                                        </div>
                                                        <div class="rounded-lg border border-gray-200 px-4 py-3 text-sm shadow-sm">
                                                            <p class="text-xs uppercase tracking-wide text-gray-400">Viikonloput</p>
                                                            <p class="text-lg font-semibold text-gray-900">{{ $month['weekend_display'] }}</p>
                                                        </div>
                                                        <div class="rounded-lg border border-gray-200 px-4 py-3 text-sm shadow-sm">
                                                            <p class="text-xs uppercase tracking-wide text-gray-400">Ylityöt</p>
                                                            <p class="text-lg font-semibold {{ $month['overtime_minutes'] ? 'text-red-600' : 'text-gray-900' }}">
                                                                {{ $month['overtime_display'] ?? '—' }}
                                                            </p>
                                                        </div>
                                                        <div class="rounded-lg border border-gray-200 px-4 py-3 text-sm shadow-sm">
                                                            <p class="text-xs uppercase tracking-wide text-gray-400">Maksettava ylityö</p>
                                                            <p class="text-lg font-semibold {{ $month['payable_overtime_minutes'] ? 'text-red-600' : 'text-gray-900' }}">
                                                                {{ $month['payable_overtime_display'] ?? '—' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 text-right text-xs text-gray-400">
                                                    Avoimet vuorot: <span class="font-semibold {{ $month['open_shifts'] ? 'text-amber-600' : '' }}">{{ $month['open_shifts'] }}</span>
                                                </div>
                                            </x-filament::section>
                                        @empty
                                            <x-filament::section class="bg-white shadow-sm">
                                                <p class="text-sm text-gray-500">Ei raportoitavia tietoja.</p>
                                            </x-filament::section>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-filament-panels::page>

