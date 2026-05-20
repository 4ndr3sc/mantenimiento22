<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IndusMaint | Gestión de Mantenimiento Industrial</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN (Para desarrollo rápido) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-900">

    <!-- Navbar / Barra de Navegación -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- Icono de Fábrica/Engranaje -->
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span class="text-xl font-bold tracking-tight text-gray-900">Indus<span class="text-blue-600">Maint</span></span>
            </div>

            <!-- Rutas de Autenticación de Laravel -->
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Panel de Control →</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition">Iniciar Sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section / Sección Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-4">
                    GMAO / CMMS Industrial Pro
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Maximiza el tiempo de actividad de tu planta
                </h1>
                <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                    Controla el mantenimiento preventivo, gestiona órdenes de trabajo y monitorea la salud de tus activos mecánicos y eléctricos en tiempo real desde una sola plataforma.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                        Ingresar a la Plataforma
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition">
                        Ver características
                    </a>
                </div>
            </div>
            
            <!-- Ilustración / Placeholder de Panel Visual de Planta -->
            <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 p-6 text-gray-400 font-mono text-sm hidden lg:block">
                <div class="flex items-center justify-between border-b border-gray-700 pb-3 mb-4">
                    <div class="flex space-x-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    </div>
                    <span class="text-xs text-gray-500">estado_activos_planta.log</span>
                </div>
                <p class="text-green-400">[OK] Línea de Ensamblaje A - Operando al 94% de eficiencia</p>
                <p class="text-yellow-400 class='mt-1'">[ALERTA] Caldera 02 - Vibración anómala detectada (Mantenimiento requerido)</p>
                <p class="text-blue-400 mt-1">[INFO] 3 Órdenes de trabajo preventivas programadas para hoy</p>
                <p class="text-gray-500 mt-4">// Próxima sincronización de sensores en 45s...</p>
            </div>
        </div>

        <!-- Características Clave (Features) -->
        <section id="features" class="mt-24 pt-16 border-t border-gray-200">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900">Diseñado para ingenieros y operadores de planta</h2>
                <p class="mt-2 text-lg text-gray-600">Herramientas ágiles para erradicar las fallas imprevistas y el papeleo innecesario.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Característica 1 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Órdenes de Trabajo (OT)</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Asigna, prioriza y haz seguimiento de fallas correctivas y tareas programadas en tiempo real.</p>
                </div>

                <!-- Característica 2 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Hoja de Vida de Activos</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Registro histórico completo, manuales, repuestos vinculados y criticidad por cada máquina en tu inventario.</p>
                </div>

                <!-- Característica 3 -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Métricas e Indicadores (KPIs)</h3>
                    <p class="mt-2 text-gray-600 text-sm leading-relaxed">Calcula de forma automática índices críticos como el MTBF (Tiempo medio entre fallos) y MTTR (Tiempo medio de reparación).</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} IndusMaint. Todos los derechos reservados.</p>
            <p>Hecho para la gestión y seguridad industrial.</p>
        </div>
    </footer>

</body>
</html>