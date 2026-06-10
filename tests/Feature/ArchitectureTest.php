<?php

arch('models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring(['App\Models\User', 'App\Models\Concerns']);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtend('App\Http\Controllers\Controller');

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toUseStrictTypes();

arch('policies')
    ->expect('App\Policies')
    ->toHaveMethods(['viewAny', 'view', 'create', 'update', 'delete'])
    ->ignoring('App\Policies\Concerns');

arch('form requests')
    ->expect('App\Http\Requests')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('traits')
    ->expect('App\Models\Concerns')
    ->toBeTraits();

arch('notifications')
    ->expect('App\Notifications')
    ->toExtend('Illuminate\Notifications\Notification');

arch('no dump')
    ->expect('App')
    ->not->toUse(['dd', 'dump', 'var_dump', 'print_r', 'exit']);
