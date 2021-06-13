<?php

namespace App\Modules\Api\V1\Services;

use App\Jobs\SendUserWelcomeMail;
use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\File;
use App\Modules\Api\V1\Models\User;
use App\Modules\Api\V1\Repositories\UserRepository;
use App\Modules\Api\V1\Resources\UserResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService implements UserRepository
{
    public function users()
    {
        return UserResource::collection(User::all());
    }
    
    public function signUp(array $data)
    {
        $is_valid_email = ApiUtility::validate_email($data['email_address']);

        if (!$is_valid_email) {
            throw new CustomApiErrorResponseHandler("Kindly provide a valid email address");
        }
        
        $user = User::getUserByEmail($data['email_address']);
        
        if ($user) {
            throw new CustomApiErrorResponseHandler("User already exists. Kindly use a different email address");
        }

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        $user->email_address = $data['email_address'];
        $user->bearer_token = ApiUtility::generate_bearer_token();
        $user->token_expires_at = ApiUtility::next_one_month();
        $user->password = bcrypt($data['password']);
        $user->save();

        //SendUserWelcomeMail::dispatch($user);

        return [
            'user' => $user,
            'message' => 'Registration successful.'
        ];
    }

    public function signIn(array $data, $user_type)
    {
        $data = array_merge($data, ['type' => $user_type, 'active_status' => ActiveStatus::ACTIVE]);
        
        if (!Auth::attempt($data)) {
            throw new CustomApiErrorResponseHandler("Incorrect login credentials. Try again.");
        }

        $user = auth()->user();
        $user->token_expires_at = ApiUtility::next_one_month();
        $user->save();

        return [
            'message' => 'Login successful',
            'user' => auth()->user(),
            'token' => auth()->user()->bearer_token
        ];
    }

    public function profile(string $token)
    {
        $user = User::where([
            'bearer_token' => $token,
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$user) {
            throw new CustomApiErrorResponseHandler("User does not exist.");
        }

        return new UserResource($user);
    }

    public function userProfile(string $user)
    {
        $user = User::where([
            'id' => $user,
            'active_status' => ActiveStatus::ACTIVE
        ])->orWhere('business_slug_url', $user)->first();

        if (!$user) {
            throw new CustomApiErrorResponseHandler("User does not exist.");
        }

        return new UserResource($user);
    }

    public function updatePersonalInformation(array $data)
    {
        $user = $data['auth_user'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        // $user->state_id = $data['state'];
        // $user->city_id = $data['city'];
        $user->save();

        return [
            'user' => new UserResource($user),
            'message' => 'Your profile was successfully updated.'
        ];
    }

    public function updateBusinessInformation(array $data)
    {
        $user_exists = User::where([
            'business_slug' => $data['business_slug'],
            'active_status' => ActiveStatus::ACTIVE
        ])->where('id', '<>', $data['auth_user']->id)
        ->exists();

        if ($user_exists) {
            throw new CustomApiErrorResponseHandler("User with this business slug exists.");
        }

        $user = $data['auth_user'];
        $user->business_name = $data['business_name'];
        $user->business_slug = $data['business_slug'];
        $user->business_slug_url = strtolower(Str::snake($data['business_slug']));
        $user->business_description = $data['business_description'];
        $user->business_address = $data['business_address'];
        $user->save();

        return [
            'user' => new UserResource($user),
            'message' => 'Your business profile was successfully updated.'
        ];
    }

    public function changePassword(array $data)
    {
        $user = $data['auth_user'];
        $current_password = $data['current_password'];
        $new_password = $data['new_password'];
        
        if (!Hash::check($current_password, $user->password)) {
            throw new CustomApiErrorResponseHandler("Incorrect current password.");
        }

        if ($current_password === $new_password) {
            throw new CustomApiErrorResponseHandler("Your new password should be different from your current password.");
        }
        
        $user->password = bcrypt($new_password);
        $user->save();

        return [
            'user' => new UserResource($user),
            'message' => 'Your password was successfully updated.'
        ];
    }

    public function updateProfilePicture(array $data)
    {
        $user = $data['auth_user'];
        $picture = $data['picture'];
        $size = ceil($picture->getSize()/1024);
        
        if ($size > File::MAX_FILESIZE) {
            throw new CustomApiErrorResponseHandler("Picture should not be more than 5MB.");
        }

        $user_fullname = $data['auth_user']->first_name." ".$data['auth_user']->last_name;
        $timestamp = ApiUtility::generateTimeStamp();
        $filename = "{$timestamp}_{$user_fullname}";
        $filename = Str::slug($filename, "_");
        $profile_picture = "{$filename}.{$picture->clientExtension()}";

        Storage::disk('users')->put($profile_picture, file_get_contents($picture->getRealPath()));

        DB::beginTransaction();
        $file = new File();
        $file->filename = $filename;
        $file->type = File::USER_FILE_TYPE;
        $file->save();

        $user->file_id = $file->id;
        $user->save();
        
        DB::commit();

        return [
            'user' => new UserResource($user),
            'message' => 'Your profile picture was successfully updated.'
        ];
    }
}
