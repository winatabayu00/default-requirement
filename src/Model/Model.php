<?php

namespace Winata\Core\Model;
use Koffin\Core\Database\Eloquent\Model as BaseModel;
use Koffin\Core\Database\Eloquent\SoftDeletes;
use Koffin\Core\Foundation\Auth\User;

class Model extends BaseModel
{
    use SoftDeletes;
    protected function setPerformedBy(): void
    {
        if (auth()->user() && empty($this->performBy) && config('koffinate.core.model.use_perform_by')) {
            $user = auth()->user();
            if ($user instanceof User) {
                if ($this->performerMode == 'users') {
                    $this->performBy = $user->id;
                } else {
                    $this->performBy = $user->name ?? $user->username ?? $user->email ?? $user->id;
                }
            }
        }
    }
}