<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nms\Oauth\Controllers\LoggerController;
use Nms\Oauth\Controllers\OauthController as BaseOauthController;
use Nms\Oauth\Helpers\OauthHelper;
use Nms\Oauth\Helpers\RequestHelper;
use Nms\Oauth\Models\NmsOauthUsersModel;
use Nms\Oauth\Services\OauthService;

class NmsOAuthController extends BaseOauthController
{
    public function callback(Request $request)
    {
        // replicate the parent callback logic but add user creation
        $redirect = \Session::pull('original_url');
        try {
            $oauthHelper = new OauthHelper(new NmsOauthUsersModel);
            $callbackResult = $oauthHelper->callback($request);

            // login update data
            $this->handleFirstTimeUserCreation($callbackResult);

            $storeTokenRecordResult = $oauthHelper->storeTokenRecord($callbackResult);

            \Auth::login($storeTokenRecordResult->user, true);

            return ($redirect) ? redirect($redirect) : redirect('/admin');
        } catch (\Throwable $throwable) {
            LoggerController::log('OAuth login failed: '.$throwable->getMessage(), 'error');

            return redirect(app(OauthService::class)->getUrlOauthAccess());
        }
    }

    private function handleFirstTimeUserCreation($callbackResult)
    {
        try {
            // Use the same endpoint that OauthHelper uses to get user data
            $userTokenStatusResult = RequestHelper::makeRequestWithAccessToken([
                'token' => $callbackResult['accessToken'],
                'api' => '/api/v1/user/check-user-token-status',
                'type' => 'GET',
            ]);

            $userTokenStatusContent = json_decode($userTokenStatusResult->getContent());

            // This endpoint should return user data based on OauthHelper logic
            if (isset($userTokenStatusContent->data->data->user)) {
                $userData = $userTokenStatusContent->data->data->user;
            } elseif (isset($userTokenStatusContent->data->user)) {
                $userData = $userTokenStatusContent->data->user;
            } else {
                throw new \Exception('User data not found in token status response: '.json_encode($userTokenStatusContent));
            }

            // Check if user exists by user_account_id
            $localUser = User::where('user_account_id', $callbackResult['userId'])->first();

            // Always update or create the user, regardless of whether they exist
            $newUser = User::updateOrCreate(
                ['user_account_id' => $callbackResult['userId']],
                [
                    'name' => ($userData->first_name ?? '').' '.($userData->last_name ?? ''),
                    'hash' => $userData->hash ?? Str::uuid(),
                    'last_name' => $userData->last_name ?? null,
                    'first_name' => $userData->first_name ?? null,
                    'middle_name' => $userData->middle_name ?? null,
                    'suffix' => $userData->suffix ?? null,
                    'email' => $userData->company_email ?? $userData->personal_email ?? null,
                    'personal_email' => $userData->personal_email ?? null,
                    'company_email' => $userData->company_email ?? null,
                    'status' => 'active',
                    'md5_personal_email' => isset($userData->personal_email) ? md5($userData->personal_email) : null,
                    'md5_company_email' => isset($userData->company_email) ? md5($userData->company_email) : null,
                ]
            );

            if (! $localUser) {
                LoggerController::log('First-time user created: '.$newUser->id, 'info');
            } else {
                LoggerController::log('User data updated: '.$newUser->id, 'info');
            }

            // If the userData contains a 'role', sync the role
            // if (isset($userData->role)) {
            //     $this->syncUserRole($newUser, $userData->role);
            // }
        } catch (\Exception $e) {
            LoggerController::log('Error creating user: '.$e->getMessage(), 'error');
        }
    }

    // private function syncUserRole($user, $roleIdentifier)
    // {
    //     if (!$roleIdentifier) {
    //         return;
    //     }

    //     // Attempt to find the role using name
    //     $roleModel = Role::where('name', $roleIdentifier)->first();

    //     if (!$roleModel) {
    //         // Create the role if it doesn't exist
    //         $roleModel = Role::updateOrCreate(
    //             [
    //                 'name' => $roleIdentifier,
    //                 'guard_name' => 'web',
    //             ]
    //         );
    //         LoggerController::log('Auto-created role: ' . $roleModel->name, 'info');
    //     }

    //     // Only assign the callback role, remove any others
    //     $user->roles()->sync([$roleModel->id]);
    // }
}
