<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leimo - Työajanseuranta</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=space-grotesk:600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-50:oklch(.971 .013 17.38);--color-red-100:oklch(.936 .032 17.717);--color-red-200:oklch(.885 .062 18.334);--color-red-300:oklch(.808 .114 19.571);--color-red-400:oklch(.704 .191 22.216);--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-red-700:oklch(.505 .213 27.518);--color-red-800:oklch(.444 .177 26.899);--color-red-900:oklch(.396 .141 25.723);--color-red-950:oklch(.258 .092 26.042);--color-orange-50:oklch(.98 .016 73.684);--color-orange-100:oklch(.954 .038 75.164);--color-orange-200:oklch(.901 .076 70.697);--color-orange-300:oklch(.837 .128 66.29);--color-orange-400:oklch(.75 .183 55.934);--color-orange-500:oklch(.705 .213 47.604);--color-orange-600:oklch(.646 .222 41.116);--color-orange-700:oklch(.553 .195 38.402);--color-orange-800:oklch(.47 .157 37.304);--color-orange-900:oklch(.408 .123 38.172);--color-orange-950:oklch(.266 .079 36.259);--color-amber-50:oklch(.987 .022 95.277);--color-amber-100:oklch(.962 .059 95.617);--color-amber-200:oklch(.924 .12 95.746);--color-amber-300:oklch(.879 .169 91.605);--color-amber-400:oklch(.828 .189 84.429);--color-amber-500:oklch(.769 .188 70.08);--color-amber-600:oklch(.666 .179 58.318);--color-amber-700:oklch(.555 .163 48.998);--color-amber-800:oklch(.473 .137 46.201);--color-amber-900:oklch(.414 .112 45.904);--color-amber-950:oklch(.279 .077 45.635);--color-yellow-50:oklch(.987 .026 102.212);--color-yellow-100:oklch(.973 .071 103.193);--color-yellow-200:oklch(.945 .129 101.54);--color-yellow-300:oklch(.905 .182 98.111);--color-yellow-400:oklch(.852 .199 91.936);--color-yellow-500:oklch(.808 .199 86.047);--color-yellow-600:oklch(.691 .172 75.446);--color-yellow-700:oklch(.576 .139 66.442);--color-yellow-800:oklch(.497 .115 60.587);--color-yellow-900:oklch(.437 .096 56.727);--color-yellow-950:oklch(.294 .066 50.406);--color-lime-50:oklch(.986 .028 120.757);--color-lime-100:oklch(.968 .067 122.873);--color-lime-200:oklch(.938 .127 124.321);--color-lime-300:oklch(.898 .196 126.665);--color-lime-400:oklch(.841 .238 128.85);--color-lime-500:oklch(.768 .233 130.85);--color-lime-600:oklch(.648 .202 131.684);--color-lime-700:oklch(.532 .157 131.589);--color-lime-800:oklch(.453 .124 130.933);--color-lime-900:oklch(.403 .101 131.063);--color-lime-950:oklch(.266 .074 132.361);--color-green-50:oklch(.982 .018 155.826);--color-green-100:oklch(.962 .044 156.743);--color-green-200:oklch(.925 .084 155.995);--color-green-300:oklch(.870 .143 154.449);--color-green-400:oklch(.792 .190 149.579);--color-green-500:oklch(.720 .197 149.214);--color-green-600:oklch(.599 .177 150.069);--color-green-700:oklch(.494 .141 150.885);--color-green-800:oklch(.416 .110 151.216);--color-green-900:oklch(.361 .087 152.535);--color-green-950:oklch(.240 .062 152.924);--color-emerald-50:oklch(.979 .024 166.114);--color-emerald-100:oklch(.952 .051 163.051);--color-emerald-200:oklch(.906 .096 164.369);--color-emerald-300:oklch(.849 .145 164.821);--color-emerald-400:oklch(.773 .170 162.399);--color-emerald-500:oklch(.698 .165 162.48);--color-emerald-600:oklch(.585 .145 164.699);--color-emerald-700:oklch(.489 .121 166.425);--color-emerald-800:oklch(.409 .096 167.23);--color-emerald-900:oklch(.354 .075 168.405);--color-emerald-950:oklch(.243 .054 170.289);--color-teal-50:oklch(.984 .020 180.72);--color-teal-100:oklch(.962 .045 180.801);--color-teal-200:oklch(.924 .086 180.426);--color-teal-300:oklch(.876 .134 181.071);--color-teal-400:oklch(.809 .157 182.503);--color-teal-500:oklch(.737 .148 185.77);--color-teal-600:oklch(.611 .129 189.623);--color-teal-700:oklch(.512 .106 192.629);--color-teal-800:oklch(.434 .084 194.479);--color-teal-900:oklch(.379 .067 196.727);--color-teal-950:oklch(.270 .052 200.873);--color-cyan-50:oklch(.984 .023 200.873);--color-cyan-100:oklch(.962 .051 203.388);--color-cyan-200:oklch(.925 .094 204.397);--color-cyan-300:oklch(.876 .148 207.078);--color-cyan-400:oklch(.804 .176 212.452);--color-cyan-500:oklch(.729 .155 218.76);--color-cyan-600:oklch(.607 .131 222.801);--color-cyan-700:oklch(.512 .108 224.406);--color-cyan-800:oklch(.441 .089 225.363);--color-cyan-900:oklch(.387 .072 227.392);--color-cyan-950:oklch(.283 .057 229.695);--color-sky-50:oklch(.980 .013 236.621);--color-sky-100:oklch(.957 .024 235.175);--color-sky-200:oklch(.918 .047 230.307);--color-sky-300:oklch(.865 .088 230.906);--color-sky-400:oklch(.787 .144 233.388);--color-sky-500:oklch(.709 .163 237.323);--color-sky-600:oklch(.601 .165 241.066);--color-sky-700:oklch(.506 .142 242.749);--color-sky-800:oklch(.445 .117 241.966);--color-sky-900:oklch(.392 .094 240.896);--color-sky-950:oklch(.293 .070 243.157);--color-blue-50:oklch(.970 .014 254.604);--color-blue-100:oklch(.940 .030 254.624);--color-blue-200:oklch(.898 .055 254.871);--color-blue-300:oklch(.832 .099 254.847);--color-blue-400:oklch(.729 .166 257.375);--color-blue-500:oklch(.637 .214 261.778);--color-blue-600:oklch(.552 .240 264.376);--color-blue-700:oklch(.482 .238 265.701);--color-blue-800:oklch(.424 .199 264.376);--color-blue-900:oklch(.384 .149 262.881);--color-blue-950:oklch(.287 .098 265.522);--color-indigo-50:oklch(.969 .012 272.314);--color-indigo-100:oklch(.941 .026 272.788);--color-indigo-200:oklch(.900 .048 272.548);--color-indigo-300:oklch(.837 .089 271.787);--color-indigo-400:oklch(.730 .153 272.548);--color-indigo-500:oklch(.627 .196 274.039);--color-indigo-600:oklch(.535 .214 276.802);--color-indigo-700:oklch(.469 .190 278.345);--color-indigo-800:oklch(.410 .152 277.117);--color-indigo-900:oklch(.368 .115 278.345);--color-indigo-950:oklch(.269 .078 281.128);--color-violet-50:oklch(.976 .014 293.756);--color-violet-100:oklch(.954 .029 294.706);--color-violet-200:oklch(.918 .053 293.349);--color-violet-300:oklch(.861 .100 293.691);--color-violet-400:oklch(.769 .173 293.07);--color-violet-500:oklch(.673 .233 292.563);--color-violet-600:oklch(.585 .260 293.581);--color-violet-700:oklch(.514 .245 294.822);--color-violet-800:oklch(.452 .211 295.384);--color-violet-900:oklch(.398 .172 295.384);--color-violet-950:oklch(.299 .132 294.822);--color-purple-50:oklch(.977 .013 308.299);--color-purple-100:oklch(.954 .030 307.174);--color-purple-200:oklch(.918 .056 306.703);--color-purple-300:oklch(.862 .106 305.504);--color-purple-400:oklch(.769 .183 304.986);--color-purple-500:oklch(.672 .239 303.9);--color-purple-600:oklch(.580 .258 302.321);--color-purple-700:oklch(.508 .241 301.924);--color-purple-800:oklch(.447 .203 302.321);--color-purple-900:oklch(.393 .165 302.321);--color-purple-950:oklch(.301 .134 301.924);--color-fuchsia-50:oklch(.977 .017 318.852);--color-fuchsia-100:oklch(.952 .038 318.337);--color-fuchsia-200:oklch(.910 .074 319.153);--color-fuchsia-300:oklch(.851 .141 320.878);--color-fuchsia-400:oklch(.761 .238 322.16);--color-fuchsia-500:oklch(.670 .295 322.16);--color-fuchsia-600:oklch(.580 .293 322.834);--color-fuchsia-700:oklch(.504 .258 323.949);--color-fuchsia-800:oklch(.448 .220 323.949);--color-fuchsia-900:oklch(.398 .179 323.949);--color-fuchsia-950:oklch(.285 .139 325.661);--color-pink-50:oklch(.971 .014 343.198);--color-pink-100:oklch(.948 .028 342.258);--color-pink-200:oklch(.909 .056 343.661);--color-pink-300:oklch(.848 .109 346.026);--color-pink-400:oklch(.756 .194 349.761);--color-pink-500:oklch(.669 .230 354.308);--color-pink-600:oklch(.592 .249 0.584);--color-pink-700:oklch(.525 .237 3.958);--color-pink-800:oklch(.463 .201 3.958);--color-pink-900:oklch(.418 .168 3.958);--color-pink-950:oklch(.294 .117 3.958);--color-rose-50:oklch(.969 .015 12.422);--color-rose-100:oklch(.941 .028 12.578);--color-rose-200:oklch(.901 .054 10.001);--color-rose-300:oklch(.838 .106 11.638);--color-rose-400:oklch(.740 .183 13.428);--color-rose-500:oklch(.645 .246 16.418);--color-rose-600:oklch(.569 .258 18.046);--color-rose-700:oklch(.500 .238 18.284);--color-rose-800:oklch(.447 .204 16.765);--color-rose-900:oklch(.408 .174 13.428);--color-rose-950:oklch(.270 .117 14.315);--color-slate-50:oklch(.984 .003 247.858);--color-slate-100:oklch(.968 .007 247.896);--color-slate-200:oklch(.929 .013 255.508);--color-slate-300:oklch(.870 .022 252.894);--color-slate-400:oklch(.649 .038 256.788);--color-slate-500:oklch(.533 .046 257.417);--color-slate-600:oklch(.442 .043 257.281);--color-slate-700:oklch(.369 .044 257.303);--color-slate-800:oklch(.279 .039 260.031);--color-slate-900:oklch(.217 .035 265.755);--color-slate-950:oklch(.145 .022 264.695);--color-gray-50:oklch(.985 .002 247.839);--color-gray-100:oklch(.968 .003 264.542);--color-gray-200:oklch(.929 .006 264.531);--color-gray-300:oklch(.870 .011 258.338);--color-gray-400:oklch(.649 .022 256.802);--color-gray-500:oklch(.539 .026 252.894);--color-gray-600:oklch(.448 .030 256.788);--color-gray-700:oklch(.375 .033 257.417);--color-gray-800:oklch(.283 .033 256.848);--color-gray-900:oklch(.221 .028 264.665);--color-gray-950:oklch(.147 .015 285.885);--color-zinc-50:oklch(.985 .001 286.375);--color-zinc-100:oklch(.967 .001 286.032);--color-zinc-200:oklch(.928 .004 286.033);--color-zinc-300:oklch(.869 .007 286.074);--color-zinc-400:oklch(.648 .014 285.938);--color-zinc-500:oklch(.534 .016 285.829);--color-zinc-600:oklch(.442 .017 285.803);--color-zinc-700:oklch(.370 .015 285.924);--color-zinc-800:oklch(.279 .013 285.885);--color-zinc-900:oklch(.220 .010 286.032);--color-zinc-950:oklch(.145 .005 285.823);--color-neutral-50:oklch(.985 0 0);--color-neutral-100:oklch(.970 0 0);--color-neutral-200:oklch(.922 0 0);--color-neutral-300:oklch(.871 0 0);--color-neutral-400:oklch(.648 0 0);--color-neutral-500:oklch(.536 0 0);--color-neutral-600:oklch(.442 0 0);--color-neutral-700:oklch(.371 0 0);--color-neutral-800:oklch(.279 0 0);--color-neutral-900:oklch(.223 0 0);--color-neutral-950:oklch(.145 0 0);--color-stone-50:oklch(.985 .002 106.424);--color-stone-100:oklch(.970 .003 106.424);--color-stone-200:oklch(.928 .006 49.248);--color-stone-300:oklch(.870 .010 56.366);--color-stone-400:oklch(.649 .018 56.366);--color-stone-500:oklch(.537 .020 56.366);--color-stone-600:oklch(.445 .020 56.366);--color-stone-700:oklch(.375 .018 56.366);--color-stone-800:oklch(.283 .014 49.248);--color-stone-900:oklch(.224 .010 56.366);--color-stone-950:oklch(.147 .006 49.248)}}
        </style>
    @endif

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Curved tab effect with modern styling */
        .curved-tab {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .curved-tab.active {
            transform: translateY(-2px);
        }

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

        /* Animated gradient background */
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }

        /* Card hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 antialiased" x-data="{ activeTab: 'about' }">
    <!-- Hero Section -->
    <div class="relative min-h-[600px] bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 overflow-hidden animated-gradient">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-200 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-emerald-200 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-6 h-full min-h-[600px] flex flex-col justify-center items-center text-center">
            <div class="glass rounded-3xl px-12 py-16 max-w-4xl backdrop-blur-xl">
                <div class="inline-block px-4 py-2 bg-white/20 rounded-full text-white text-sm font-semibold mb-6 backdrop-blur-sm">
                    ⚡ Suomen modernein työajanseuranta
                </div>
                <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold text-white mb-6 tracking-tight">
                    Leimo
                </h1>
                <p class="text-xl md:text-2xl text-white/90 max-w-2xl mx-auto leading-relaxed font-medium">
                    Älykäs työajanseuranta, joka tekee arjesta helpompaa
                </p>
                <div class="mt-10 flex flex-wrap gap-4 justify-center">
                    <button @click="activeTab = 'order'; window.scrollTo({top: document.querySelector('.content-area').offsetTop - 100, behavior: 'smooth'})" class="px-8 py-4 bg-white text-emerald-600 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        Aloita ilmainen kokeilu
                    </button>
                    <button @click="activeTab = 'about'; window.scrollTo({top: document.querySelector('.content-area').offsetTop - 100, behavior: 'smooth'})" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-xl font-bold text-lg border-2 border-white/30 hover:bg-white/20 transition-all duration-300">
                        Tutustu ominaisuuksiin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white shadow-sm sticky top-0 z-20">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-center relative">
                <div class="w-full bg-gradient-to-r from-emerald-400 to-teal-500">
                    <ul class="flex list-none m-0 p-0 gap-2 justify-center">
                        <!-- Tietoa ohjelmasta Tab -->
                        <li
                            class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                            :class="activeTab === 'about' ? 'active' : ''"
                            @click="activeTab = 'about'"
                            :style="'background: ' + (activeTab === 'about' ? '#d1f4e0' : 'transparent') + '; --tab-bg-color: #d1f4e0;'"
                        >
                            <a class="no-underline flex items-center gap-2" :class="activeTab === 'about' ? 'text-emerald-900' : 'text-white'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Tietoa ohjelmasta</span>
                            </a>
                        </li>

                        <!-- Hinnasto Tab -->
                        <li
                            class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                            :class="activeTab === 'pricing' ? 'active' : ''"
                            @click="activeTab = 'pricing'"
                            :style="'background: ' + (activeTab === 'pricing' ? '#d1f4e0' : 'transparent') + '; --tab-bg-color: #d1f4e0;'"
                        >
                            <a class="no-underline flex items-center gap-2" :class="activeTab === 'pricing' ? 'text-emerald-900' : 'text-white'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Hinnasto</span>
                            </a>
                        </li>

                        <!-- Yhteystiedot Tab -->
                        <li
                            class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                            :class="activeTab === 'contact' ? 'active' : ''"
                            @click="activeTab = 'contact'"
                            :style="'background: ' + (activeTab === 'contact' ? '#d1f4e0' : 'transparent') + '; --tab-bg-color: #d1f4e0;'"
                        >
                            <a class="no-underline flex items-center gap-2" :class="activeTab === 'contact' ? 'text-emerald-900' : 'text-white'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Yhteystiedot</span>
                            </a>
                        </li>

                        <!-- Tilaa Tab -->
                        <li
                            class="curved-tab inline-block list-none px-8 py-3 text-base rounded-t-3xl relative cursor-pointer transition-all duration-200"
                            :class="activeTab === 'order' ? 'active' : ''"
                            @click="activeTab = 'order'"
                            :style="'background: ' + (activeTab === 'order' ? '#d1f4e0' : 'transparent') + '; --tab-bg-color: #d1f4e0;'"
                        >
                            <a class="no-underline flex items-center gap-2" :class="activeTab === 'order' ? 'text-emerald-900' : 'text-white'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Tilaa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="container mx-auto px-6 py-16 content-area">
        <!-- Tietoa ohjelmasta -->
        <div x-show="activeTab === 'about'" x-cloak x-transition.opacity.duration.300>
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-5xl font-bold text-gray-900 mb-4">Miksi Leimo?</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Työajanseuranta, joka sopeutuu yrityksesi tarpeisiin</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 hover-lift border border-blue-200">
                        <div class="bg-blue-500 rounded-2xl p-4 w-16 h-16 flex items-center justify-center mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Salamannopea</h4>
                        <p class="text-gray-700 leading-relaxed">Leimaa sisään ja ulos yhdellä napin painalluksella</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-8 hover-lift border border-emerald-200">
                        <div class="bg-emerald-500 rounded-2xl p-4 w-16 h-16 flex items-center justify-center mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Älykäs raportointi</h4>
                        <p class="text-gray-700 leading-relaxed">Automaattiset raportit ja analytiikka</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 hover-lift border border-purple-200">
                        <div class="bg-purple-500 rounded-2xl p-4 w-16 h-16 flex items-center justify-center mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Turvallinen</h4>
                        <p class="text-gray-700 leading-relaxed">Pankki-tason tietoturva ja GDPR-yhteensopiva</p>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 hover-lift border border-orange-200">
                        <div class="bg-orange-500 rounded-2xl p-4 w-16 h-16 flex items-center justify-center mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Mobiilivalmis</h4>
                        <p class="text-gray-700 leading-relaxed">Toimii kaikilla laitteilla saumattomasti</p>
                    </div>
                </div>

                <!-- Feature highlight -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-12 text-white shadow-2xl">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h3 class="text-3xl font-bold mb-6">Tehosta työaikaseurantaa 10x</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-emerald-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-lg">Säästä 5+ tuntia viikossa hallinnollisissa tehtävissä</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-emerald-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-lg">Reaaliaikaiset tiedot työntekijöiden työajoista</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-emerald-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-lg">Integroituu suoraan palkanlaskentaan</span>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                            <div class="text-center">
                                <div class="text-6xl font-bold mb-2">98%</div>
                                <div class="text-xl text-emerald-300">Asiakastyytyväisyys</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hinnasto -->
        <div x-show="activeTab === 'pricing'" x-cloak x-transition.opacity.duration.300>
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Hinnasto</h2>
                <p class="text-center text-gray-600 mb-12">Valitse yrityksellesi sopiva paketti</p>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Basic Plan -->
                    <div class="bg-white rounded-lg shadow-md p-8 border-2 border-gray-200 hover:border-blue-500 transition-colors">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Perus</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">29€</span>
                            <span class="text-gray-600">/kk</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Jopa 10 käyttäjää
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Perustoiminnot
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sähköpostituki
                            </li>
                        </ul>
                        <button class="w-full bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            Valitse paketti
                        </button>
                    </div>

                    <!-- Pro Plan -->
                    <div class="bg-white rounded-lg shadow-xl p-8 border-2 border-blue-500 transform scale-105">
                        <div class="bg-blue-500 text-white text-sm font-semibold px-3 py-1 rounded-full inline-block mb-4">
                            Suosituin
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Pro</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">79€</span>
                            <span class="text-gray-600">/kk</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Jopa 50 käyttäjää
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Kaikki toiminnot
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Prioriteettituki
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                API-integraatiot
                            </li>
                        </ul>
                        <button class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Valitse paketti
                        </button>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="bg-white rounded-lg shadow-md p-8 border-2 border-gray-200 hover:border-blue-500 transition-colors">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Yritys</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">Tarjous</span>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Rajaton määrä käyttäjiä
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Räätälöidyt ratkaisut
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Oma tukihenkilö
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                SLA-sopimus
                            </li>
                        </ul>
                        <button class="w-full bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            Ota yhteyttä
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yhteystiedot -->
        <div x-show="activeTab === 'contact'" x-cloak x-transition.opacity.duration.300>
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Yhteystiedot</h2>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Ota yhteyttä</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-800">Sähköposti</p>
                                    <a href="mailto:info@leimo.fi" class="text-blue-600 hover:underline">info@leimo.fi</a>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-800">Puhelin</p>
                                    <a href="tel:+358401234567" class="text-blue-600 hover:underline">+358 40 123 4567</a>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-800">Osoite</p>
                                    <p class="text-gray-600">Esimerkkikatu 1<br>00100 Helsinki</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Lähetä viesti</h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nimi</label>
                                <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Sähköposti</label>
                                <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Viesti</label>
                                <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Lähetä viesti
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tilaa -->
        <div x-show="activeTab === 'order'" x-cloak x-transition.opacity.duration.300>
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Tilaa Leimo</h2>
                <p class="text-center text-gray-600 mb-8">Täytä alla oleva lomake, niin otamme sinuun yhteyttä</p>
                
                <div class="bg-white rounded-lg shadow-md p-8">
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Yrityksen nimi *</label>
                                <input type="text" id="company" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="business-id" class="block text-sm font-medium text-gray-700 mb-1">Y-tunnus</label>
                                <input type="text" id="business-id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">Yhteyshenkilö *</label>
                                <input type="text" id="contact-name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">Sähköposti *</label>
                                <input type="email" id="contact-email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-1">Puhelinnumero *</label>
                            <input type="tel" id="contact-phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="users-count" class="block text-sm font-medium text-gray-700 mb-1">Käyttäjien määrä *</label>
                            <select id="users-count" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Valitse...</option>
                                <option value="1-10">1-10 käyttäjää</option>
                                <option value="11-50">11-50 käyttäjää</option>
                                <option value="51-100">51-100 käyttäjää</option>
                                <option value="100+">Yli 100 käyttäjää</option>
                            </select>
                        </div>

                        <div>
                            <label for="package" class="block text-sm font-medium text-gray-700 mb-1">Kiinnostava paketti</label>
                            <select id="package" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Valitse...</option>
                                <option value="basic">Perus</option>
                                <option value="pro">Pro</option>
                                <option value="enterprise">Yritys</option>
                            </select>
                        </div>

                        <div>
                            <label for="additional-info" class="block text-sm font-medium text-gray-700 mb-1">Lisätietoja</label>
                            <textarea id="additional-info" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Kerro meille lisää tarpeistasi..."></textarea>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="terms" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                Hyväksyn <a href="#" class="text-blue-600 hover:underline">käyttöehdot</a> ja <a href="#" class="text-blue-600 hover:underline">tietosuojaselosteen</a> *
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Lähetä tilaus
                        </button>

                        <p class="text-sm text-gray-500 text-center">* Pakolliset kentät</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-6 py-8">
            <div class="text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Leimo. Kaikki oikeudet pidätetään.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
