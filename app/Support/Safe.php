<?php
namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Safe
{
    public static function run(callable $action, callable $onOk, callable $onFail)
    {
        $folio = Str::uuid();

        try {
            $result = $action();
            return $onOk($result);
        } catch (\Throwable $e) {
            Log::error('Excepción en Safe::run', [
                'folio'   => (string) $folio,
                // auth()->id() devuelve null si no hay sesión (no rompe)
                'user_id' => auth()->id(),
                // En CLI/Jobs puede no haber request; nullsafe evita error
                'ip'      => request()?->ip(),
                'exception' => $e,
            ]);

            return $onFail($folio, $e);
        }
    }
}
