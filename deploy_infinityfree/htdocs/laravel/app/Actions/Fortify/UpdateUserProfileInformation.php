<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:L,P'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ])->validateWithBag('updateProfileInformation');

        $extra = [
            'phone' => $input['phone'] ?? null,
            'address' => $input['address'] ?? null,
            'gender' => $input['gender'] ?? null,
        ];

        if (! empty($input['avatar']) && is_object($input['avatar'])) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $extra['avatar'] = $input['avatar']->store('avatars', 'public');
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input, $extra);
        } else {
            $user->forceFill(array_merge([
                'name' => $input['name'],
                'email' => $input['email'],
            ], $extra))->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     * @param  array<string, mixed>  $extra
     */
    protected function updateVerifiedUser(User $user, array $input, array $extra = []): void
    {
        $user->forceFill(array_merge([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ], $extra))->save();

        $user->sendEmailVerificationNotification();
    }
}
