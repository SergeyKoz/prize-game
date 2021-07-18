<?php

namespace App\Actions\Fortify;

use App\Interfaces\Services\BonusServiceInterface;
use App\Models\User;
use App\Services\BankService;
use App\Services\BonusService;
use App\Services\LimitsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private BonusServiceInterface $bonusService;

    public function __construct(BonusServiceInterface $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'bankAccount' => ['required', 'string', 'max:32'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'bankAccount' => $input['bankAccount'],
        ]);

        BonusService::createAccount($user);
        BankService::createAccount($user);
        LimitsService::setUserLimits($user);

        return $user;
    }
}
