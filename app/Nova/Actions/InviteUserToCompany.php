<?php

namespace App\Nova\Actions;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;

class InviteUserToCompany extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Invite User';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $user = User::create([
                'name' => $fields->name,
                'email' => $fields->email,
                'password' => bcrypt(fake()->password),
                'is_owner' => $fields->is_owner,
                'is_redactor' => $fields->is_redactor,
                'is_reviewer' => $fields->is_reviewer,
                'is_consumer' => $fields->is_consumer,
                'company_id' => $model->id,
            ]);

            $token = app('auth.password.broker')->createToken($user);
            $user->sendPasswordResetNotification($token);
        }

        return Action::message("{$fields->name} will receive an email with instructions on how to set their password.");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Name')->required(),
            Text::make('Email')->required(),
            Boolean::make('Is Owner'),
            Boolean::make('Is Redactor'),
            Boolean::make('Is Reviewer'),
            Boolean::make('Is Consumer'),
        ];
    }
}
