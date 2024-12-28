<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait UuidScopeTrait {
    public function scopeByUuid($query, $uuid) {
        return $query->where('uuid', $this->normalizeUuid($uuid));
    }

    public function scopeByUuids($query, $uuids) {
        return $query->whereIn('uuid', is_array($uuids) ? $uuids : [$uuids]);
    }

    public function scopeByUuidOrName($query, $uuid) {
        return $query->orWhere(['uuid' => $uuid, 'name' => $this->normalizeUuid($uuid)]);
    }

    public function scopeByUuidsOrNames($query, $value) {
        if ($value instanceof Collection) $value = $value->all();
        $value = is_array($value) ? $value : [$value];
        return $query->whereIn('uuid', $value)->orWhereIn('name', $value);
    }

    public function scopeByName($query, $name) {
        return $query->where(['name' => $name]);
    }

    public function normalizeUuid($uuid) {
        if (is_array($uuid)) $uuid = (!empty($uuid[0])) ? $this->normalizeUuid($uuid[0]) : ($uuid['uuid'] ?? $uuid['id'] ?? null);
        return $uuid;
    }

    protected static function bootUuidScopeTrait() {
        static::creating(function ($model) {
            if (empty($model->uuid)) $model->uuid = (string) Str::uuid();
        });
    }
}
