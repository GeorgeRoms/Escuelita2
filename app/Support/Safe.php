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
                'folio' => (string) $folio,
                'user_id' => auth()->id(),
                'ip' => request()?->ip(),
                'exception' => $e,
            ]);
            
            // 👇 Modo dev: si APP_DEBUG=true y pides ?debug=1 (o activas SAFE_RETHROW), re-lanza
            if (config('app.debug') && (request()->boolean('debug') || env('SAFE_RETHROW', false))) {
                throw $e;
            }
            return $onFail($folio, $e);
        }
    }
}
