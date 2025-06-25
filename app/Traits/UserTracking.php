<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait UserTracking
{
    /**
     * Boot the trait
     */
    protected static function bootUserTracking()
    {
        static::creating(function (Model $model) {
            if (Auth::check() && !$model->created_by) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function (Model $model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function (Model $model) {
            if (Auth::check() && method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();

                // Verifica relacionamentos antes de permitir a exclusão
                $relationships = array_keys($model->getRelations());
                foreach ($relationships as $relationship) {
                    if ($model->{$relationship}()->count() > 0) {
                        throw new \Exception("Não é possível excluir o registro pois existem {$relationship} vinculados.");
                    }
                }
            }
        });
    }
}
