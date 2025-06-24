<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait UserTrackable
{
    protected static function bootUserTrackable()
    {
        // Antes de criar, define o usuário que está criando
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        // Antes de atualizar, define o usuário que está atualizando
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    // Relacionamento com o usuário que criou
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relacionamento com o usuário que atualizou por último
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Método para obter informações do criador formatadas
    public function getCreatorInfoAttribute()
    {
        if ($this->creator) {
            return sprintf(
                'Criado por %s em %s',
                $this->creator->name,
                $this->created_at->format('d/m/Y H:i:s')
            );
        }
        return null;
    }

    // Método para obter informações do último atualizador formatadas
    public function getUpdaterInfoAttribute()
    {
        if ($this->updater) {
            return sprintf(
                'Última atualização por %s em %s',
                $this->updater->name,
                $this->updated_at->format('d/m/Y H:i:s')
            );
        }
        return null;
    }
}
