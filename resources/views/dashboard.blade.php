<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaintFlow | Panel Técnico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .slider-wrapper { display: flex; transition: transform 0.5s ease-in-out; }
        .slide-item { flex: 0 0 100%; }
        /* Control dinámico de pantallas */
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        /* Light theme overrides (predominantly white with blue accents) */
        body { background: #ffffff; color: #0f172a; }
        .bg-gray-800, .bg-gray-900, .bg-gray-950, .bg-gray-850 { background: #ffffff !important; color: inherit !important; }
        .text-white { color: #0f172a !important; }
        .text-gray-400 { color: #6b7280 !important; }
        .border-gray-700, .border-gray-800, .border-gray-900 { border-color: #e5e7eb !important; }
        /* Give any element that originally used gray backgrounds a subtle blue tint to separate from pure white */
        [class*="bg-gray-"] {
            background: #f0f9ff !important;
            border-color: #dbeafe !important;
            color: #0f172a !important;
        }
        /* Force inner text/icons inside gray backgrounds to be dark for readability (override light-blue text classes) */
        [class*="bg-gray-"] .text-blue-400,
        [class*="bg-gray-"] .text-blue-500,
        [class*="bg-gray-"] .text-blue-600,
        [class*="bg-gray-"] .text-white,
        [class*="bg-gray-"] i,
        [class*="bg-gray-"] span,
        [class*="bg-gray-"] p,
        [class*="bg-gray-"] h2,
        [class*="bg-gray-"] h3 {
            color: #0f172a !important;
            opacity: 1 !important;
        }
        /* Ensure rounded chips on gray backgrounds use dark text */
        [class*="bg-gray-"] .rounded-full, [class*="bg-gray-"] .px-2\.5.py-0\.5 {
            color: #0f172a !important;
            background: #e6f0ff !important;
            border-color: #cfe6ff !important;
            font-weight: 700;
        }
        /* Make semi-transparent gray backgrounds a pale blue tint for legibility */
        .bg-gray-950\/50, .bg-gray-900\/50, .bg-gray-900\/40 { background: rgba(59,130,246,0.06) !important; }
        /* If an element used a small chip/badge, keep it readable with darker blue text by default */
        .px-2\.5.py-0\.5 { color: #0b3b71 !important; font-weight: 600; }
        /* Specific badge color adjustments to avoid low-contrast combos */
        .px-2\.5.py-0\.5[class*="bg-red-"] {
            background: #fee2e2 !important; /* pale red */
            border-color: #fecaca !important;
            color: #0f172a !important; /* dark text for readability */
            font-weight: 700;
        }
        .px-2\.5.py-0\.5[class*="bg-green-"] {
            background: #ecfdf5 !important; /* pale green */
            border-color: #bbf7d0 !important;
            color: #064e3b !important; /* dark green text */
            font-weight: 700;
        }
        .px-2\.5.py-0\.5[class*="bg-yellow-"] {
            background: #fffbeb !important; /* pale yellow */
            border-color: #fef3c7 !important;
            color: #78350f !important; /* dark amber text */
            font-weight: 700;
        }
        .px-2\.5.py-0\.5[class*="bg-blue-"] {
            background: #eff6ff !important; /* pale blue */
            border-color: #dbeafe !important;
            color: #0b3b71 !important; /* deep blue text */
            font-weight: 700;
        }
        /* Force card selects to display only the selected option (avoid showing full option list inline) */
        select.cardEstadoSelect option { display: none; }
        select.cardEstadoSelect option:checked { display: block; }
        select.cardEstadoSelect { min-width: 140px; }
        aside .tab-btn { color: #0f172a; }
        /* Ensure interactive controls (buttons) remain blue and visible */
        .bg-blue-600, .bg-blue-700, .bg-blue-500 { color: #ffffff !important; }
        button, .rounded-xl, .rounded-2xl { box-shadow: 0 1px 2px rgba(15,23,42,0.04); }
        /* Keep explicit red/green badges visible (don't override) */
    </style>
</head>
<body class="bg-white text-gray-900 min-h-screen font-sans antialiased flex">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between min-h-screen sticky top-0 z-40">
        <div>
            <div class="p-6 border-b border-gray-800 flex items-center gap-3">
                <i class="fas fa-screwdriver-wrench text-blue-500 text-2xl"></i>
                <span class="text-lg font-bold tracking-wider text-white">Maint<span class="text-blue-500">Flow</span></span>
            </div>

            <nav class="p-4 space-y-1">
                <button onclick="switchTab('inicio', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-blue-600 text-white transition">
                    <i class="fas fa-chart-pie w-5 text-center"></i> Inicio / Central
                </button>
                <button onclick="switchTab('equipos', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-industry w-5 text-center"></i> Equipos en Taller
                </button>
                <button onclick="switchTab('ordenes', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-list-check w-5 text-center"></i> Mis Órdenes (OT)
                </button>
                <button onclick="switchTab('historial', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-clock-rotate-left w-5 text-center"></i> Historial / Trazabilidad
                </button>
                <button onclick="switchTab('perfil', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-user-gear w-5 text-center"></i> Mi Perfil Técnico
                </button>
                @if(auth()->user()->isAdmin())
                <button onclick="switchTab('tecnicos', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-users w-5 text-center"></i> Técnicos
                </button>
                <button onclick="switchTab('arreglados', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition">
                    <i class="fas fa-check-to-slot w-5 text-center"></i> Arreglados
                </button>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-blue-700 hover:bg-blue-800 text-white transition shadow-md">
                    <i class="fas fa-right-from-bracket w-5 text-center"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-8 lg:p-12 max-w-7xl mx-auto w-full overflow-x-hidden">
        
        <div id="inicio" class="tab-content active space-y-8">
            <header class="flex justify-between items-center border-b border-gray-800 pb-5">
                <div>
                    <span class="text-blue-500 text-xs font-bold tracking-widest uppercase">Estación de Trabajo</span>
                    <h1 class="text-3xl font-extrabold text-white">Panel de Control de Mantenimiento</h1>
                </div>
                <span class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700 text-xs font-mono text-green-400 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Cuadrilla Activa
                </span>
            </header>

            @if(auth()->user()->isAdmin() && !empty($staleEquipos) && count($staleEquipos) > 0)
            <div class="mt-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
                <div class="flex justify-between items-center">
                    <div><strong>{{ count($staleEquipos) }}</strong> equipos sin reclamar por más de {{ $staleDays }} días.</div>
                    <div>
                        <button onclick="openEquiposFromAlert()" class="px-3 py-1 bg-red-600 text-white rounded">Ver equipos</button>
                    </div>
                </div>
                <div class="mt-2 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($staleEquipos->take(5) as $se)
                            <li>{{ $se->nombre }} — creado {{ $se->created_at->diffForHumans() }}</li>
                        @endforeach
                    </ul>
                    @if(count($staleEquipos) > 5)
                        <div class="mt-2 text-xs text-gray-600">... y {{ count($staleEquipos) - 5 }} más</div>
                    @endif
                </div>
            </div>
            @endif

            <div class="relative bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl overflow-hidden">
                <div class="overflow-hidden">
                    <div id="sliderWrapper" class="slider-wrapper">
                        <div class="slide-item p-8 sm:p-12">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                <div>
                                    <span class="bg-blue-900/50 text-blue-400 text-xs px-3 py-1 rounded-full font-semibold border border-blue-800">Eficiencia Mecánica</span>
                                    <h2 class="text-3xl font-bold text-white mt-3 mb-4">Tiempos de Respuesta Taller</h2>
                                    <p class="text-gray-400 text-sm leading-relaxed mb-6">Indicadores globales de reparación. El tiempo medio de diagnóstico ha disminuido gracias al reporte digital de fallas en sitio.</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-950 p-4 rounded-xl border border-gray-700"><div class="text-2xl font-bold text-blue-500">94.2%</div><div class="text-xs text-gray-500 mt-1">Éxito en Reparación</div></div>
                                        <div class="bg-gray-950 p-4 rounded-xl border border-gray-700"><div class="text-2xl font-bold text-green-500">45 Min</div><div class="text-xs text-gray-500 mt-1">Diagnóstico Promedio</div></div>
                                    </div>
                                </div>
                                <div class="bg-gray-950 p-6 rounded-xl border border-gray-700 flex flex-col justify-between h-48">
                                    <div class="flex justify-between items-end h-32 gap-2 border-b border-gray-800 pb-2">
                                        <div class="bg-blue-600 w-full rounded-t" style="height: 40%"></div>
                                        <div class="bg-blue-600 w-full rounded-t" style="height: 75%"></div>
                                        <div class="bg-emerald-500 w-full rounded-t" style="height: 90%"></div>
                                        <div class="bg-blue-600 w-full rounded-t" style="height: 65%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="equipos" class="tab-content space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-800 pb-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">Inventario de Maquinaria en Taller</h1>
                    <p class="text-gray-400 text-sm mt-1">Dispositivos bajo supervisión técnica asignados a reparación o calibración.</p>
                </div>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i> Registrar Reporte de Máquina
                </button>
            </div>

            <div id="listaEquipos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($equipos as $equipo)
                 <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow-md flex flex-col justify-between"
                     data-id="eq-{{ $equipo->id }}" data-tipo="{{ $equipo->tipo }}" data-nombre="{{ $equipo->nombre }}"
                     data-marca="{{ $equipo->marca }}" data-serie="{{ $equipo->serie }}" data-estado="{{ $equipo->estado }}"
                     data-falla="{{ $equipo->falla }}" data-responsable="{{ $equipo->user->name ?? $equipo->responsable ?? 'N/A' }}" data-user-id="{{ $equipo->user->id ?? '' }}">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2.5 py-0.5 text-xs font-semibold {{ $equipo->tipo === 'Correctivo' ? 'bg-red-950 text-red-400 border-red-900' : 'bg-green-950 text-green-400 border-green-900' }} border rounded-full">{{ $equipo->tipo }}</span>
                            <span class="text-xs font-mono text-gray-500">OT: #{{ str_pad($equipo->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">{{ $equipo->nombre }}</h3>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="text-xs px-2.5 py-0.5 rounded-full bg-gray-900 text-gray-300 border border-gray-700">Estado: <strong id="status-{{ $equipo->id }}">{{ $equipo->estado }}</strong></div>
                            <div>
                                <select onchange="cambiarEstadoCard({{ $equipo->id }}, this.value, this)" class="cardEstadoSelect text-xs bg-gray-900 text-white border border-gray-700 rounded px-2 py-1">
                                    <option value="En espera" {{ $equipo->estado === 'En espera' ? 'selected' : '' }}>En espera</option>
                                    <option value="En proceso" {{ $equipo->estado === 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                    <option value="Arreglado" {{ $equipo->estado === 'Arreglado' ? 'selected' : '' }}>Arreglado</option>
                                    <option value="Terminado" {{ $equipo->estado === 'Terminado' ? 'selected' : '' }}>Terminado</option>
                                    <option value="En espera de repuestos" {{ $equipo->estado === 'En espera de repuestos' ? 'selected' : '' }}>En espera de repuestos</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">S/N: {{ $equipo->serie ?? 'N/A' }} | Marca: {{ $equipo->marca ?? 'N/A' }}</p>
                        <div class="mt-4 bg-gray-900/50 p-3 rounded-lg border border-gray-700/50 flex items-center gap-2 text-xs text-gray-400">
                            <i class="fas fa-user text-blue-500"></i>
                            <span>Responsable: <strong>{{ $equipo->user->name ?? $equipo->responsable ?? 'Ing. Carlos Mendoza' }}</strong></span>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-gray-700 text-xs text-gray-500 flex justify-between items-center">
                        <span><i class="far fa-calendar mr-1"></i> Asignado: {{ $equipo->created_at->diffForHumans() }}</span>
                        <button onclick="verDetalles(this.closest('[data-id]'))" class="text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1.5 transition">
                            Ver detalles <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            @if(auth()->user()->isAdmin())
            <div class="pt-4">
                <h3 class="text-sm text-gray-600 mb-2">Reasignar equipo (selecciona un equipo y usa Ver detalles)</h3>
            </div>
            @endif

            <div id="modalEquipo" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gray-850 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-file-waveform text-blue-500"></i> Reporte Técnico de Entrada</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-white text-lg"><i class="fas fa-xmark"></i></button>
                    </div>
                    <form id="formNuevoEquipo" onsubmit="agregarEquipo(event)" class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Nombre del Equipo *</label>
                                <input type="text" id="inputNombre" required placeholder="Ej. Compresor, Torno..." class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Tipo Mantenimiento *</label>
                                <select id="inputTipo" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition">
                                    <option value="Correctivo">Correctivo</option>
                                    <option value="Preventivo">Preventivo</option>
                                    <option value="Calibración">Calibración</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Marca / Modelo *</label>
                                <input type="text" id="inputMarca" required placeholder="Ej. Caterpillar V2" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Número de Serie (S/N)</label>
                                <input type="text" id="inputSerie" placeholder="Ej. SN-4921" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Teléfono del dueño / contacto</label>
                                <input type="text" id="inputTelefono" placeholder="Ej. +52 55 1234 5678" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Diagnóstico de Falla / Síntomas *</label>
                            <textarea id="inputFalla" required rows="3" placeholder="Describe detalladamente los hallazgos iniciales o las anomalías mecánicas..." class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 transition resize-none"></textarea>
                        </div>
                        <div class="pt-4 border-t border-gray-700 flex justify-end gap-3">
                            <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-semibold text-gray-400 bg-gray-700/50 rounded-xl">Cancelar</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-lg">Abrir Orden</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalDetail" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden overflow-hidden">
                <div id="modalPanel" class="absolute right-0 top-0 bottom-0 bg-gray-800 border-l border-gray-700 w-full max-w-md transform translate-x-full transition-transform duration-300 shadow-2xl overflow-y-auto">
                    <div class="px-5 py-4 border-b border-gray-700 bg-gray-850 flex justify-between items-center sticky top-0">
                        <div class="flex items-center gap-2"><i class="fas fa-microchip text-blue-500 text-lg"></i><h3 class="text-base font-bold text-white">Ficha Técnica</h3></div>
                        <div class="flex items-center gap-2">
                            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-white text-lg px-2 py-1"><i class="fas fa-xmark"></i></button>
                        </div>
                    </div>
                    <div class="p-5 space-y-5">
                        <div class="flex justify-between items-center bg-gray-950 p-4 rounded-xl border border-gray-700">
                            <div><span class="text-xs text-gray-500 font-mono block">ACTIVO</span><h2 id="detNombre" class="text-xl font-bold text-white">---</h2></div>
                            <span id="detTipo" class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-950 text-blue-400 border border-blue-800">---</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-800"><span class="text-xs text-gray-500 block mb-0.5">Asignado A:</span><span id="detResponsable" class="font-semibold text-gray-200">---</span></div>
                            <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-800"><span class="text-xs text-gray-500 block mb-0.5">Código OT</span><span id="detOT" class="font-mono font-semibold text-blue-400">---</span></div>
                            <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-800"><span class="text-xs text-gray-500 block mb-0.5">Teléfono Contacto</span><span id="detTelefono" class="font-semibold text-gray-200">---</span></div>
                            <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-800"><span class="text-xs text-gray-500 block mb-0.5">Marca / Modelo</span><span id="detMarca" class="text-gray-200">---</span></div>
                            <div class="bg-gray-900/40 p-3 rounded-lg border border-gray-800"><span class="text-xs text-gray-500 block mb-0.5">Número de Serie</span><span id="detSerie" class="font-mono text-gray-200">---</span></div>
                        </div>
                        <div class="bg-gray-950 p-4 rounded-xl border border-gray-700">
                            <span class="text-xs text-blue-400 font-bold uppercase tracking-wider block mb-2"><i class="fas fa-notes-medical mr-1"></i> Estado / Hallazgos Mecánicos</span>
                            <div class="flex gap-2 items-start text-sm text-gray-300 leading-relaxed italic">
                                <i class="fas fa-quote-left text-gray-600 text-xs mt-1"></i>
                                <p id="detFalla">---</p>
                            </div>
                        </div>
                        <div class="mt-4 bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <label class="block text-xs text-gray-400 mb-2">Estado del Equipo</label>
                            <div class="flex gap-2">
                                <select id="modalEstadoSelect" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-sm text-white">
                                    <option value="En espera">En espera</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Arreglado">Arreglado</option>
                                    <option value="Terminado">Terminado</option>
                                    <option value="En espera de repuestos">En espera de repuestos</option>
                                </select>
                                <button onclick="cambiarEstadoDesdeModal(document.getElementById('modalEstadoSelect').value)" class="px-4 py-2 bg-blue-600 text-white rounded-xl">Actualizar</button>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs text-gray-400 mb-1">Comentario (opcional)</label>
                                <textarea id="modalComentario" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-sm text-white" placeholder="Agrega un comentario para la bitácora"></textarea>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="bg-gray-900 p-4 rounded-xl border border-gray-700">
                            <label class="block text-xs text-gray-400 mb-2">Reasignar Técnico</label>
                            <div class="flex gap-2">
                                <select id="selectReasignar" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-sm text-white"></select>
                                <button id="btnReasignar" onclick="reasignarEquipoDesdeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-xl">Reasignar</button>
                            </div>
                        </div>
                        @endif
                    </div>
                    </div>
                    <div class="px-5 py-4 bg-gray-850 border-t border-gray-700 flex justify-end sticky bottom-0">
                        <button onclick="closeDetailModal()" class="px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-700/50 rounded-xl mr-2">Cancelar</button>
                        <button onclick="cambiarEstadoDesdeModal(document.getElementById('modalEstadoSelect').value)" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="ordenes" class="tab-content space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Órdenes de Trabajo (OT) Asignadas</h1>
                <p class="text-gray-400 text-sm mt-1">Hoja de ruta diaria y tareas críticas asignadas a tu banco de trabajo mecánico.</p>
            </div>
            <div id="misOrdenesList" class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @foreach($misEquipos ?? collect([]) as $me)
                <div id="mis-eq-{{ $me->id }}" data-id="eq-{{ $me->id }}" data-nombre="{{ $me->nombre }}" data-tipo="{{ $me->tipo }}" data-marca="{{ $me->marca }}" data-serie="{{ $me->serie }}" data-estado="{{ $me->estado }}" data-falla="{{ $me->falla }}" data-telefono="{{ $me->telefono }}" data-responsable="{{ $me->user->name ?? $me->responsable ?? 'N/A' }}" data-user-id="{{ $me->user->id ?? '' }}" class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-xs font-mono font-bold text-blue-400 bg-gray-950 px-3 py-1 rounded-lg border border-gray-800">OT-{{ str_pad($me->id,3,'0',STR_PAD_LEFT) }}</span>
                            <span class="px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider bg-yellow-950/80 text-yellow-400 border border-yellow-900 rounded-full flex items-center gap-1.5">Estado: <strong id="status-{{ $me->id }}">{{ $me->estado }}</strong></span>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-white">{{ $me->nombre }}</h3>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">S/N: {{ $me->serie ?? 'N/A' }} | Marca: {{ $me->marca ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs font-mono text-gray-400 flex items-center gap-1.5 bg-gray-900/60 px-3 py-1.5 rounded-lg border border-gray-700/50">
                            <i class="far fa-clock text-blue-500"></i> Asignado: <strong class="text-blue-400 font-bold">{{ $me->created_at->diffForHumans() }}</strong>
                        </div>
                        <button onclick="verDetalles(this.closest('[data-id]'))" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-lg flex items-center justify-center gap-2">Ver Ficha</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="historial" class="tab-content space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">Historial General de Reparaciones</h1>
                    <p class="text-gray-400 text-sm mt-1">Bitácora completa de equipos que han pasado por mantenimiento técnico.</p>
                </div>
                
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500">
                        <i class="fas fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" id="inputBuscarHistorial" onkeyup="filtrarHistorial()" placeholder="Buscar por equipo o número de serie..." 
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-700 bg-gray-850 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="p-4">Equipo / Componente</th>
                            <th class="p-4">N° de Serie</th>
                            <th class="p-4 text-center">Intervenciones</th>
                            <th class="p-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaHistorialBody" class="divide-y divide-gray-700 text-sm text-gray-300">
                        @foreach($equipos as $equipo)
                        <tr id="hist-eq-{{ $equipo->id }}" data-hist-nombre="{{ $equipo->nombre }}" data-hist-serie="{{ $equipo->serie }}">
                            <td class="p-4 font-bold text-white">{{ $equipo->nombre }}</td>
                            <td class="p-4 font-mono text-gray-400">{{ $equipo->serie ?? 'N/A' }}</td>
                            <td class="p-4 text-center"><span class="bg-blue-950 text-blue-400 border border-blue-800 text-xs px-2.5 py-0.5 rounded-full font-bold">{{ $equipo->bitacoras->count() }} Veces</span></td>
                            <td class="p-4 text-right"><button onclick="verBitacora('eq-{{ $equipo->id }}')" class="bg-gray-700 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium">Ver Bitácora</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="sinResultados" class="hidden p-8 text-center text-gray-500 border-t border-gray-700 text-sm">
                    <i class="fas fa-folder-open text-2xl mb-2 block text-gray-600"></i> No se encontraron equipos que coincidan con la búsqueda.
                </div>
            </div>

            <div id="modalBitacora" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gray-850 flex justify-between items-center">
                        <h3 id="bitacoraEquipoNombre" class="text-base font-bold text-white">---</h3>
                        <button onclick="closeBitacoraModal()" class="text-gray-400 hover:text-white"><i class="fas fa-xmark"></i></button>
                    </div>
                    <div class="p-6"><div id="timelineContenedor" class="relative border-l border-gray-700 ml-3 space-y-4"></div></div>
                </div>
            </div>
        </div>

        <div id="arreglados" class="tab-content space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Equipos Arreglados</h1>
                <p class="text-gray-400 text-sm mt-1">Listado de equipos marcados como arreglados/terminados. Visible solo para administradores.</p>
            </div>

            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-700 bg-gray-850 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="p-4">Equipo</th>
                            <th class="p-4">S/N</th>
                            <th class="p-4">Marca</th>
                            <th class="p-4">Responsable Técnico</th>
                            <th class="p-4">Teléfono Contacto</th>
                            <th class="p-4 text-right">Última actualización</th>
                        </tr>
                    </thead>
                    <tbody id="arregladosBody" class="divide-y divide-gray-700 text-sm text-gray-300">
                        @foreach($arreglados ?? collect([]) as $a)
                        <tr>
                            <td class="p-4 font-bold text-white">{{ $a->nombre }}</td>
                            <td class="p-4 font-mono text-gray-400">{{ $a->serie ?? 'N/A' }}</td>
                            <td class="p-4">{{ $a->marca ?? 'N/A' }}</td>
                            <td class="p-4">{{ $a->user->name ?? $a->responsable ?? 'N/A' }}</td>
                            <td class="p-4">{{ $a->telefono ?? 'N/A' }}</td>
                            <td class="p-4 text-right">{{ $a->updated_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="perfil" class="tab-content space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Perfil Profesional del Trabajador</h1>
                <p class="text-gray-400 text-sm mt-1">Información corporativa, especialidades mecánicas y métricas del técnico activo.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 flex flex-col items-center text-center shadow-md text-gray-900">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-3xl font-bold text-gray-900 shadow-sm mb-4 border-2 border-gray-200">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U',0,2)) }}
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">{{ auth()->user()->name ?? 'Usuario' }}</h2>
                    <span class="text-xs text-gray-700 font-mono font-semibold uppercase bg-gray-100 px-3 py-1 rounded-full border border-gray-200 mt-1">{{ auth()->user()->role ?? 'Técnico' }}</span>

                    <div class="w-full border-t border-gray-200 my-4 pt-4 text-left space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Correo:</span><span class="font-mono text-gray-800">{{ auth()->user()->email }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Departamento:</span><span class="text-gray-800">{{ auth()->user()->department ?? 'Mantenimiento' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Turno:</span><span class="text-gray-800">{{ auth()->user()->shift ?? 'Rotativo' }}</span></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 lg:col-span-2 space-y-6 shadow-md text-gray-900">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2"><i class="fas fa-id-card text-blue-600 mr-2"></i> Datos del Puesto e Información Personal</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><label class="block text-gray-700 text-xs uppercase font-bold mb-1">Correo Corporativo</label><input type="text" disabled value="{{ auth()->user()->email }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-gray-800"></div>
                        <div><label class="block text-gray-700 text-xs uppercase font-bold mb-1">Área de Especialidad</label><input type="text" disabled value="{{ auth()->user()->specialty ?? 'General' }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-gray-800"></div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-xs uppercase font-bold mb-2">Habilitaciones y Certificaciones Activas</label>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs flex items-center gap-2"><i class="fas fa-certificate text-amber-500"></i> {{ auth()->user()->cert1 ?? 'Certificación NFPA 70E' }}</span>
                            <span class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs flex items-center gap-2"><i class="fas fa-shield text-emerald-500"></i> {{ auth()->user()->cert2 ?? 'Protocolo LOTO' }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Métricas de Desempeño Individual (Mes Actual)</h4>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200"><div class="text-xl font-black text-blue-600">18</div><div class="text-[10px] text-gray-600 mt-0.5">OTs Ejecutadas</div></div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200"><div class="text-xl font-black text-green-600">96%</div><div class="text-[10px] text-gray-600 mt-0.5">Tasa de Cierre</div></div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200"><div class="text-xl font-black text-purple-600">38 hrs</div><div class="text-[10px] text-gray-600 mt-0.5">Tiempo en Banco</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tecnicos" class="tab-content space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Técnicos / Cuentas</h1>
                <p class="text-gray-400 text-sm mt-1">Listado de todas las cuentas registradas y su rol actual. Sólo accesible por administradores.</p>
            </div>

            @php $users = \App\Models\User::orderBy('name')->get(); @endphp

            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-700 bg-gray-850 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="p-4">Nombre</th>
                            <th class="p-4">Correo</th>
                            <th class="p-4">Rol</th>
                            <th class="p-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-sm text-gray-300">
                        @foreach($users as $u)
                        <tr id="user-{{ $u->id }}">
                            <td class="p-4 font-bold text-white">{{ $u->name }}</td>
                            <td class="p-4 font-mono text-gray-400">{{ $u->email }}</td>
                            <td class="p-4" data-role>{{ $u->role ?? 'user' }}</td>
                            <td class="p-4 text-right">
                                @if(auth()->user()->id !== $u->id)
                                    <div class="flex items-center justify-end gap-2">
                                        <select id="role-select-dashboard-{{ $u->id }}" class="text-xs rounded px-2 py-1 bg-gray-900 border border-gray-700 text-white">
                                            <option value="user" {{ ($u->role ?? 'user') === 'user' ? 'selected' : '' }}>user</option>
                                            <option value="admin" {{ ($u->role ?? 'user') === 'admin' ? 'selected' : '' }}>admin</option>
                                        </select>
                                        <button onclick="setRoleDashboard({{ $u->id }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium">Actualizar</button>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs italic">(Cuenta propia)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        const baseBitacoras = {!! $bitacorasJson ?? '{}' !!};
        const usersList = {!! $usersJson ?? 'null' !!};
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};

        function switchTab(tabId, buttonElement) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.tab-btn').forEach(btn => btn.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-gray-400 hover:bg-gray-900 hover:text-white transition");
            buttonElement.className = "tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-blue-600 text-white transition";
        }

        function openEquiposFromAlert() {
            const btn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.textContent.includes('Equipos en Taller'));
            if (btn) switchTab('equipos', btn);
            else {
                const first = document.querySelector('.tab-btn');
                if (first) switchTab('equipos', first);
            }
        }

        const modal = document.getElementById('modalEquipo');
        const form = document.getElementById('formNuevoEquipo');
        const listaEquipos = document.getElementById('listaEquipos');
        const tablaHistorialBody = document.getElementById('tablaHistorialBody');
        let contadorOT = 3; 

        function openModal() { modal.classList.remove('hidden'); }
        function closeModal() { modal.classList.add('hidden'); form.reset(); }

        function agregarEquipo(event) {
            event.preventDefault();
            const nombre = document.getElementById('inputNombre').value;
            const tipo = document.getElementById('inputTipo').value;
            const marca = document.getElementById('inputMarca').value;
            const serie = document.getElementById('inputSerie').value || null;
            const falla = document.getElementById('inputFalla').value || null;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/equipos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nombre, tipo, marca, serie, falla })
            }).then(res => {
                if (!res.ok) throw new Error('Error al guardar');
                return res.json();
            }).then(data => {
                const eq = data.equipo;
                const nuevoId = `eq-${eq.id}`;
                const badgeColor = eq.tipo === 'Correctivo' ? 'bg-red-950 text-red-400 border-red-900' : 'bg-green-950 text-green-400 border-green-900';

                const nuevaTarjeta = document.createElement('div');
                nuevaTarjeta.className = "bg-gray-800 border border-gray-700 rounded-xl p-5 shadow-md flex flex-col justify-between";
                nuevaTarjeta.setAttribute('data-id', nuevoId);
                nuevaTarjeta.setAttribute('data-tipo', eq.tipo);
                nuevaTarjeta.setAttribute('data-nombre', eq.nombre);
                nuevaTarjeta.setAttribute('data-marca', eq.marca);
                nuevaTarjeta.setAttribute('data-serie', eq.serie || 'N/A');
                nuevaTarjeta.setAttribute('data-falla', eq.falla || '');
                nuevaTarjeta.setAttribute('data-telefono', eq.telefono || '');
                nuevaTarjeta.setAttribute('data-responsable', eq.responsable || 'N/A');
                nuevaTarjeta.setAttribute('data-user-id', eq.user && eq.user.id ? eq.user.id : '');

                nuevaTarjeta.innerHTML = `
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2.5 py-0.5 text-xs font-semibold ${badgeColor} border rounded-full">${eq.tipo}</span>
                            <span class="text-xs font-mono text-gray-500">OT: #${String(eq.id).padStart(3,'0')}</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">${eq.nombre}</h3>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="text-xs px-2.5 py-0.5 rounded-full bg-gray-900 text-gray-300 border border-gray-700">Estado: <strong id="status-${eq.id}">${eq.estado || 'En espera'}</strong></div>
                            <div>
                                <select onchange="cambiarEstadoCard(${eq.id}, this.value, this)" class="cardEstadoSelect text-xs bg-gray-900 text-white border border-gray-700 rounded px-2 py-1">
                                    <option value="En espera">En espera</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Arreglado">Arreglado</option>
                                    <option value="Terminado">Terminado</option>
                                    <option value="En espera de repuestos">En espera de repuestos</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">S/N: ${eq.serie || 'N/A'} | Marca: ${eq.marca || 'N/A'}</p>
                        <div class="mt-4 bg-gray-900/50 p-3 rounded-lg border border-gray-700/50 flex items-center gap-2 text-xs text-gray-400">
                            <i class="fas fa-user text-blue-500"></i> <span>Responsable: <strong>${eq.responsable || 'Ing. Carlos Mendoza'}</strong></span>
                        </div>
                        <div class="mt-2 bg-gray-900/40 p-2 rounded-lg border border-gray-700/50 text-xs text-gray-400 flex items-center gap-2">
                            <i class="fas fa-phone text-blue-500"></i> <span>Contacto: <strong>${eq.telefono || 'N/A'}</strong></span>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-gray-700 text-xs text-gray-500 flex justify-between items-center">
                        <span><i class="far fa-calendar mr-1"></i> Asignado: Reciente</span>
                        <button onclick="verDetalles(this.closest('[data-id]'))" class="text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1.5 transition">
                            Ver detalles <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    </div>
                `;
                listaEquipos.prepend(nuevaTarjeta);

                baseBitacoras[nuevoId] = data.bitacoras.map(b => ({ fecha: b.fecha || new Date().toLocaleDateString(), ot: b.ot, tarea: b.tarea, detalle: b.detalle }));

                const nuevaFila = document.createElement('tr');
                nuevaFila.id = `hist-${nuevoId}`;
                nuevaFila.setAttribute('data-hist-nombre', eq.nombre);
                nuevaFila.setAttribute('data-hist-serie', eq.serie || 'N/A');
                nuevaFila.className = "divide-y divide-gray-700 text-sm text-gray-300";
                nuevaFila.innerHTML = `
                    <td class="p-4 font-bold text-white">${eq.nombre}</td>
                    <td class="p-4 font-mono text-gray-400">${eq.serie || 'N/A'}</td>
                    <td class="p-4 text-center"><span class="bg-blue-950 text-blue-400 border border-blue-800 text-xs px-2.5 py-0.5 rounded-full font-bold">1 Vez</span></td>
                    <td class="p-4 text-right"><button onclick="verBitacora('${nuevoId}')" class="bg-gray-700 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium">Ver Bitácora</button></td>
                `;
                tablaHistorialBody.prepend(nuevaFila);

                closeModal();
            }).catch(err => {
                console.error(err);
                alert('No se pudo guardar el equipo.');
            });
        }

        const modalDetail = document.getElementById('modalDetail');
        const modalPanel = document.getElementById('modalPanel');
        function verDetalles(el) {
            document.getElementById('detNombre').innerText = el.getAttribute('data-nombre');
            document.getElementById('detTipo').innerText = el.getAttribute('data-tipo');
            document.getElementById('detMarca').innerText = el.getAttribute('data-marca');
            document.getElementById('detSerie').innerText = el.getAttribute('data-serie');
            document.getElementById('detFalla').innerText = el.getAttribute('data-falla');
            document.getElementById('detTelefono').innerText = el.getAttribute('data-telefono') || '---';
            const otEl = el.querySelector('.font-mono');
            document.getElementById('detOT').innerText = otEl ? otEl.innerText.replace('OT: ', '') : '---';
            document.getElementById('detResponsable').innerText = el.getAttribute('data-responsable') || (el.getAttribute('data-responsable') === '' ? '---' : '---');
            // store equipo id on modal for later actions
            const rawId = el.getAttribute('data-id') || '';
            const idMatch = rawId.match(/eq-(\d+)/);
            if (idMatch) modalDetail.dataset.equipoId = idMatch[1];
            else modalDetail.dataset.equipoId = '';

            // if admin, populate user select and set current selection
            if (usersList && document.getElementById('selectReasignar')) {
                const sel = document.getElementById('selectReasignar');
                sel.innerHTML = '';
                usersList.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.id; opt.text = u.name + ' <' + u.email + '>';
                    sel.appendChild(opt);
                });
                const currentUserId = el.getAttribute('data-user-id') || '';
                if (currentUserId) sel.value = currentUserId;
            }
            // set modal estado select to current estado if present
            const estadoSel = document.getElementById('modalEstadoSelect');
            if (estadoSel) {
                const currentEstado = el.getAttribute('data-estado') || 'En espera';
                // try to set, otherwise leave default
                try { estadoSel.value = currentEstado; } catch(e) { /* ignore */ }
            }
            // show overlay then slide panel in
            modalDetail.classList.remove('hidden');
            // allow next tick for transition
            setTimeout(() => {
                modalPanel.classList.remove('translate-x-full');
                modalPanel.classList.add('translate-x-0');
            }, 25);
        }
        function closeDetailModal() {
            // slide panel out then hide overlay after transition
            if (modalPanel) {
                modalPanel.classList.remove('translate-x-0');
                modalPanel.classList.add('translate-x-full');
            }
            setTimeout(() => { modalDetail.classList.add('hidden'); }, 320);
        }

        // close when clicking on overlay outside the panel
        if (modalDetail) {
            modalDetail.addEventListener('click', function(e) {
                if (e.target === modalDetail) closeDetailModal();
            });
        }
        // close on ESC
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') { if (!modalDetail.classList.contains('hidden')) closeDetailModal(); } });

        const modalBitacora = document.getElementById('modalBitacora');
        const timeline = document.getElementById('timelineContenedor');
        function verBitacora(id) {
            const f = document.getElementById(`hist-${id}`);
            document.getElementById('bitacoraEquipoNombre').innerText = f.getAttribute('data-hist-nombre');
            timeline.innerHTML = "";
            (baseBitacoras[id] || []).forEach(item => {
                const p = document.createElement('div');
                p.className = "relative pl-6 border-l border-gray-700 ml-2";
                p.innerHTML = `<span class="absolute -left-[5px] top-1.5 bg-blue-500 w-2 h-2 rounded-full"></span><div class="bg-gray-950 p-3 rounded-lg text-xs"><span class="text-blue-400 font-mono"><i class="far fa-clock text-[10px] mr-1"></i>${item.fecha} (OT: ${item.ot})</span><h5 class="text-white font-bold mt-1">${item.tarea}</h5><p class="text-gray-400 italic mt-0.5">${item.detalle}</p></div>`;
                timeline.appendChild(p);
            });
            modalBitacora.classList.remove('hidden');
        }
        function closeBitacoraModal() { modalBitacora.classList.add('hidden'); }

        function filtrarHistorial() {
            const textoBusqueda = document.getElementById('inputBuscarHistorial').value.toLowerCase().trim();
            const filas = document.querySelectorAll('#tablaHistorialBody tr');
            const divSinResultados = document.getElementById('sinResultados');
            let hayCoincidencias = false;

            filas.forEach(fila => {
                const nombreEquipo = fila.getAttribute('data-hist-nombre').toLowerCase();
                const numeroSerie = fila.getAttribute('data-hist-serie').toLowerCase();

                if (nombreEquipo.includes(textoBusqueda) || numeroSerie.includes(textoBusqueda)) {
                    fila.style.display = ""; 
                    hayCoincidencias = true;
                } else {
                    fila.style.display = "none"; 
                }
            });

            if (hayCoincidencias) { divSinResultados.classList.add('hidden'); } 
            else { divSinResultados.classList.remove('hidden'); }
        }

        function cambiarEstadoOrden(btn) {
            if (btn.classList.contains('bg-green-600')) {
                btn.className = "w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-lg flex items-center justify-center gap-2";
                btn.innerHTML = `<i class="fas fa-spinner animate-spin text-[10px]"></i> Pausar / Registrar Avance`;
            } else {
                btn.className = "w-full sm:w-auto bg-gray-700 text-gray-400 font-semibold text-xs px-4 py-2.5 rounded-xl cursor-not-allowed flex items-center justify-center gap-2";
                btn.innerHTML = `<i class="fas fa-circle-check text-[10px]"></i> Enviado a Control de Calidad`;
                btn.disabled = true;
            }
        }

        function setRoleDashboard(userId) {
            const sel = document.getElementById(`role-select-dashboard-${userId}`);
            if (!sel) return alert('Selector no encontrado');
            const role = sel.value;
            if (!confirm('Confirmar cambio de rol?')) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/admin/usuarios/${userId}/role`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept': 'application/json'},
                body: JSON.stringify({ role })
            }).then(r => {
                if (!r.ok) throw new Error('No autorizado');
                return r.json();
            }).then(data => {
                const row = document.getElementById(`user-${userId}`);
                if (row) {
                    const roleCell = row.querySelector('[data-role]');
                    roleCell.innerText = data.user.role || role;
                }
                alert('Rol actualizado');
            }).catch(e => { console.error(e); alert('No se pudo actualizar el rol'); });
        }

        function cambiarEstadoCard(equipoId, nuevoEstado, el) {
            if (!confirm('Confirmar cambio de estado?')) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const comentario = document.getElementById('modalComentario') ? document.getElementById('modalComentario').value : '';
            fetch(`/equipos/${equipoId}/estado`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept': 'application/json'},
                body: JSON.stringify({ estado: nuevoEstado, comentario })
            }).then(r => {
                if (!r.ok) throw new Error('Error al actualizar');
                return r.json();
            }).then(data => {
                // actualizar tarjeta principal
                // actualizar todas las tarjetas que correspondan a este equipo
                try {
                    const cards = document.querySelectorAll(`[data-id='eq-${equipoId}']`);
                    cards.forEach(card => {
                        card.setAttribute('data-estado', data.equipo.estado);
                        // actualizar select interno si lo tiene
                        const sel = card.querySelector('select');
                        if (sel) sel.value = data.equipo.estado;
                        // actualizar badge/estado por id si existe
                        const statusEl = document.getElementById(`status-${equipoId}`);
                        if (statusEl) statusEl.innerText = data.equipo.estado;
                        // actualizar posibles textos que contienen 'Estado:'
                        const nodes = card.querySelectorAll('span,div');
                        nodes.forEach(n => {
                            if (n.innerText && /estado\s*:/i.test(n.innerText)) {
                                n.innerText = n.innerText.replace(/Estado\s*:\s*.*/i, `Estado: ${data.equipo.estado}`);
                            }
                        });
                    });
                } catch(e) { console.error(e); }

                // actualizar panel si está abierto y es el mismo equipo
                if (modalDetail && modalDetail.dataset && String(modalDetail.dataset.equipoId) == String(equipoId)) {
                    const estadoSel = document.getElementById('modalEstadoSelect');
                    if (estadoSel) estadoSel.value = data.equipo.estado;
                }

                // actualizar Mis Órdenes (si existe la card en Mis Órdenes)
                const myCard = document.getElementById(`mis-eq-${equipoId}`);
                if (myCard) {
                    const badge = myCard.querySelector('span[class*="Estado:"]');
                    // fallback: buscar el span que contiene 'Estado:'
                    const spans = myCard.querySelectorAll('span');
                    spans.forEach(s => {
                        if (s.innerText && s.innerText.trim().toLowerCase().includes('estado')) {
                            // actualizar el texto después de ':'
                            s.innerHTML = s.innerHTML.replace(/Estado:\s*[^<]*/i, `Estado: ${data.equipo.estado}`);
                        }
                    });
                }

                // actualizar Historial: incrementar contador de intervenciones si hay nueva bitácora
                try {
                    const histRow = document.getElementById(`hist-eq-${equipoId}`);
                    if (histRow) {
                        const span = histRow.querySelector('td:nth-child(3) span');
                        if (span) {
                            // extraer numero actual
                            const txt = span.innerText || '';
                            const m = txt.match(/(\d+)/);
                            let n = m ? parseInt(m[1]) : 0;
                            // si el servidor devolvió una bitacora, incrementar
                            if (data.bitacora) n = n + 1;
                            span.innerText = `${n} Veces`;
                        }
                    }
                } catch(e) { console.error(e); }

                // sincronizar Arreglados: añadir o eliminar fila
                try {
                    const tbody = document.getElementById('arregladosBody');
                    if (tbody) {
                        const existingRow = document.getElementById(`arreglados-row-${equipoId}`);
                        if (data.equipo.estado && data.equipo.estado.toLowerCase().includes('arreg')) {
                            if (!existingRow) {
                                const tr = document.createElement('tr');
                                tr.id = `arreglados-row-${equipoId}`;
                                tr.innerHTML = `
                                    <td class="p-4 font-bold text-white">${data.equipo.nombre}</td>
                                    <td class="p-4 font-mono text-gray-400">${data.equipo.serie ?? 'N/A'}</td>
                                    <td class="p-4">${data.equipo.marca ?? 'N/A'}</td>
                                    <td class="p-4">${data.equipo.user ? data.equipo.user.name : data.equipo.responsable ?? 'N/A'}</td>
                                    <td class="p-4">${data.equipo.telefono ?? 'N/A'}</td>
                                    <td class="p-4 text-right">Ahora</td>
                                `;
                                tbody.prepend(tr);
                            }
                        } else {
                            if (existingRow) existingRow.remove();
                        }
                    }
                } catch(e) { console.error(e); }

                // actualizar cache local de bitacoras
                try {
                    const key = `eq-${equipoId}`;
                    baseBitacoras[key] = baseBitacoras[key] || [];
                    if (data.bitacora) {
                        baseBitacoras[key].unshift({ fecha: data.bitacora.fecha, ot: data.bitacora.ot, tarea: data.bitacora.tarea, detalle: data.bitacora.detalle });
                    }
                } catch(e) { console.error(e); }

                alert('Estado actualizado');
            }).catch(e => { console.error(e); alert('No se pudo actualizar el estado'); });
        }

        function cambiarEstadoDesdeModal(nuevoEstado) {
            const equipoId = modalDetail.dataset.equipoId;
            if (!equipoId) return alert('Equipo no identificado');
            if (!confirm('Confirmar cambio de estado?')) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const comentario = document.getElementById('modalComentario') ? document.getElementById('modalComentario').value : '';
            fetch(`/equipos/${equipoId}/estado`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept': 'application/json'},
                body: JSON.stringify({ estado: nuevoEstado, comentario })
            }).then(r => {
                if (!r.ok) throw new Error('Error al actualizar');
                return r.json();
            }).then(data => {
                // actualizar modal
                document.getElementById('detFalla').innerText = document.getElementById('detFalla').innerText; // no-op to keep field
                document.getElementById('modalEstadoSelect').value = data.equipo.estado;
                // actualizar todas las tarjetas que correspondan a este equipo
                try {
                    const cards = document.querySelectorAll(`[data-id='eq-${equipoId}']`);
                    cards.forEach(card => {
                        card.setAttribute('data-estado', data.equipo.estado);
                        const sel = card.querySelector('select');
                        if (sel) sel.value = data.equipo.estado;
                        const statusEl = document.getElementById(`status-${equipoId}`);
                        if (statusEl) statusEl.innerText = data.equipo.estado;
                        const nodes = card.querySelectorAll('span,div');
                        nodes.forEach(n => {
                            if (n.innerText && /estado\s*:/i.test(n.innerText)) {
                                n.innerText = n.innerText.replace(/Estado\s*:\s*.*/i, `Estado: ${data.equipo.estado}`);
                            }
                        });
                    });
                } catch(e) { console.error(e); }

                // sincronizar Arreglados: si estado es Arreglado -> añadir; si ya no es -> eliminar
                try {
                    const tbody = document.getElementById('arregladosBody');
                    if (!tbody) return;
                    const existingRow = document.getElementById(`arreglados-row-${equipoId}`);
                    if (data.equipo.estado && data.equipo.estado.toLowerCase().includes('arreg')) {
                        if (!existingRow) {
                            const tr = document.createElement('tr');
                            tr.id = `arreglados-row-${equipoId}`;
                            tr.innerHTML = `
                                <td class="p-4 font-bold text-white">${data.equipo.nombre}</td>
                                <td class="p-4 font-mono text-gray-400">${data.equipo.serie ?? 'N/A'}</td>
                                <td class="p-4">${data.equipo.marca ?? 'N/A'}</td>
                                <td class="p-4">${data.equipo.user ? data.equipo.user.name : data.equipo.responsable ?? 'N/A'}</td>
                                <td class="p-4">${data.equipo.telefono ?? 'N/A'}</td>
                                <td class="p-4 text-right">Ahora</td>
                            `;
                            tbody.prepend(tr);
                        }
                    } else {
                        if (existingRow) existingRow.remove();
                    }
                } catch(e) { console.error(e); }

                // agregar la bitácora recibida al cache local para que aparezca en verBitacora
                try {
                    const key = `eq-${equipoId}`;
                    baseBitacoras[key] = baseBitacoras[key] || [];
                    if (data.bitacora) {
                        baseBitacoras[key].unshift({ fecha: data.bitacora.fecha, ot: data.bitacora.ot, tarea: data.bitacora.tarea, detalle: data.bitacora.detalle });
                        // si el modal de bitacora está abierto, refrescar su contenido
                        if (!modalBitacora.classList.contains('hidden')) {
                            verBitacora(equipoId);
                        }
                    }
                } catch(e) { console.error(e); }

                alert('Estado actualizado');
            }).catch(e => { console.error(e); alert('No se pudo actualizar el estado'); });
        }

        function reasignarEquipoDesdeModal() {
            const equipoId = modalDetail.dataset.equipoId;
            if (!equipoId) return alert('Equipo no identificado');
            const sel = document.getElementById('selectReasignar');
            if (!sel) return alert('No hay lista de técnicos disponible');
            const userId = sel.value;
            if (!userId) return alert('Selecciona un técnico');
            if (!confirm('Confirmar reasignación del equipo?')) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/equipos/${equipoId}/reassign`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept': 'application/json'},
                body: JSON.stringify({ user_id: userId })
            }).then(r => {
                if (!r.ok) throw new Error('No autorizado o error');
                return r.json();
            }).then(data => {
                // actualizar tarjeta correspondiente
                const card = document.querySelector(`[data-id='eq-${equipoId}']`);
                if (card) {
                    const newName = data.equipo.user ? data.equipo.user.name : sel.options[sel.selectedIndex].text;
                    card.setAttribute('data-responsable', newName);
                    card.setAttribute('data-user-id', data.equipo.user ? data.equipo.user.id : userId);
                    // actualizar texto visible
                    const respSpan = card.querySelector('.mt-4 .fa-user') ? card.querySelector('.mt-4') : null;
                    // actualizar manualmente el elemento donde aparece el responsable en la tarjeta
                    const respBox = card.querySelector('.mt-4');
                    if (respBox) respBox.querySelector('strong').innerText = newName;
                }
                // actualizar modal
                document.getElementById('detResponsable').innerText = data.equipo.user ? data.equipo.user.name : sel.options[sel.selectedIndex].text;
                // actualizar Mis Órdenes: si el equipo fue asignado a mi, añadir; si fue removido de mi, quitar
                try {
                    const prevId = data.previous_user_id;
                    const newId = data.equipo.user ? data.equipo.user.id : null;
                    if (currentUserId && newId === currentUserId) {
                        // añadir si no existe
                        if (!document.getElementById(`mis-eq-${equipoId}`)) {
                            // clonar la tarjeta principal si existe
                            if (card) {
                                const clone = card.cloneNode(true);
                                clone.id = `mis-eq-${equipoId}`;
                                // remove possible duplicate event handlers by ensuring buttons use global functions
                                document.getElementById('misOrdenesList').prepend(clone);
                            }
                        }
                    }
                    if (currentUserId && prevId === currentUserId) {
                        const removed = document.getElementById(`mis-eq-${equipoId}`);
                        if (removed) removed.remove();
                    }
                } catch(e) { console.error(e); }
                alert('Reasignación realizada');
            }).catch(e => { console.error(e); alert('No se pudo reasignar'); });
        }
    </script>
</body>
</html>