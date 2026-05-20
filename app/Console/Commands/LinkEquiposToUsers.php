<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LinkEquiposToUsers extends Command
{
    protected $signature = 'equipos:link-users {--dry-run}';

    protected $description = 'Enlaza equipos sin user_id a usuarios por coincidencia de email o nombre';

    public function handle()
    {
        $dry = $this->option('dry-run');
        $this->info('Buscando equipos sin user_id...');
        $equipos = Equipo::whereNull('user_id')->get();
        $linked = [];
        $skipped = [];

        foreach ($equipos as $equipo) {
            $this->line("Procesando Equipo ID {$equipo->id} - '{$equipo->nombre}' (responsable: '{$equipo->responsable}')");

            $responsable = trim((string) $equipo->responsable);
            $foundUser = null;

            // 1) Si responsable contiene un email, buscar por email exacto
            if (strpos($responsable, '@') !== false) {
                // extraer posible email
                preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', $responsable, $m);
                if (!empty($m[0])) {
                    $email = strtolower($m[0]);
                    $foundUser = User::whereRaw('LOWER(email) = ?', [$email])->first();
                    if ($foundUser) {
                        $this->info(" -> Coincidencia por email: {$foundUser->email} (user_id={$foundUser->id})");
                    }
                }
            }

            // 2) Si no hay email, intentar coincidencia exacta por nombre
            if (!$foundUser && $responsable !== '') {
                $nameNormalized = mb_strtolower($responsable);
                $foundUser = User::whereRaw('LOWER(name) = ?', [$nameNormalized])->first();
                if ($foundUser) {
                    $this->info(" -> Coincidencia exacta por nombre: {$foundUser->name} (user_id={$foundUser->id})");
                }
            }

            // 3) intento de coincidencia parcial por nombre (primer apellido o palabra)
            if (!$foundUser && $responsable !== '') {
                $parts = preg_split('/\s+/', $responsable);
                if (!empty($parts)) {
                    $part = mb_strtolower($parts[0]);
                    $users = User::whereRaw('LOWER(name) LIKE ?', ["%{$part}%"])->get();
                    if ($users->count() === 1) {
                        $foundUser = $users->first();
                        $this->info(" -> Coincidencia parcial por nombre: {$foundUser->name} (user_id={$foundUser->id})");
                    } else {
                        if ($users->count() > 1) {
                            $this->line(" -> Coincidencias parciales múltiples (no se asigna)");
                        }
                    }
                }
            }

            if ($foundUser) {
                $linked[] = [
                    'equipo_id' => $equipo->id,
                    'equipo_nombre' => $equipo->nombre,
                    'user_id' => $foundUser->id,
                    'user_email' => $foundUser->email,
                    'user_name' => $foundUser->name,
                ];

                if (!$dry) {
                    $equipo->user_id = $foundUser->id;
                    $equipo->responsable = $foundUser->name;
                    $equipo->save();
                }
            } else {
                $skipped[] = [
                    'equipo_id' => $equipo->id,
                    'equipo_nombre' => $equipo->nombre,
                    'responsable' => $equipo->responsable,
                ];
            }
        }

        $timestamp = now()->format('Ymd_His');
        $logPath = storage_path("logs/equipos_link_{$timestamp}.log");
        $report = [
            'total_processed' => $equipos->count(),
            'linked_count' => count($linked),
            'skipped_count' => count($skipped),
            'linked' => $linked,
            'skipped' => $skipped,
        ];

        file_put_contents($logPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Hecho. Vinculados: " . count($linked) . ", sin asignar: " . count($skipped));
        $this->info("Reporte guardado en: {$logPath}");

        return 0;
    }
}
