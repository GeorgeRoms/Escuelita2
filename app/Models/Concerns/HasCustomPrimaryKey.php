<?php

namespace App\Models\Concerns;

trait HasCustomPrimaryKey
{
    // Para que el route-model-binding use tu PK real
    public function getRouteKeyName(): string
    {
        return $this->getKeyName(); // == $this->primaryKey
    }

    // Alias: permite usar $modelo->id aunque la PK se llame id_xxx o no_control
    public function getIdAttribute()
    {
        $key = $this->getKeyName();
        return $this->attributes[$key] ?? null;
    }
}
