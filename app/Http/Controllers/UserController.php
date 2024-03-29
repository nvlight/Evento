<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAvatarCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected string $avatarPathPrefix = 'uploads';

    public function setAvatar(UserAvatarCreateRequest $request)
    {
        $user = User::find(Auth::user()->id);

        // delete old
        if ($user->avatar){
            try {
                $avatar = Storage::disk('public')->exists($user->avatar);
                if ($avatar){
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            catch (\Throwable $e ){
                $this->saveToLog(__METHOD__, $e);
                return response()->json([
                    'success' => false,
                    'message' => 'some error',
                ]);
            }
        }

        // save new
        try {
            $fileName = time().'_'.$request->avatar->getClientOriginalName();
            $filePath = $request->file('avatar')->storeAs($this->avatarPathPrefix, $fileName, 'public');
            $fullPath = Storage::disk('public')->url($filePath);
        }
        catch (\Throwable $e ){
            $this->saveToLog(__METHOD__, $e);
            return response()->json([
                'success' => false,
                'message' => 'some error',
            ]);
        }

        $user->avatar = $filePath;
        $user->save();
        $user->full_avatar = $fullPath;

        return response()->json([
            'success' => true,
            'message' => 'Аватар для пользователя успешно установлен.',
            'user' => $user,

        ]);
    }

    public function delAvatar()
    {
        $user = User::find(Auth::user()->id);

        if ($user->avatar){
            try {
                $avatar = Storage::disk('public')->exists($user->avatar);
                if ($avatar){
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = null;
                $user->save();
            }
            catch (\Throwable $e ){
                $this->saveToLog(__METHOD__, $e);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Аватар пользователя удален.',
        ]);
    }

    protected function saveToLog($method, $e)
    {
        logger('error in method: ' . $method. '! '
            . implode(' | ', [
                'msg: '  . $e->getMessage(),
                'line: ' . $e->getLine(),
                'file: ' . $e->getFile(),
                'code: ' . $e->getCode(),
            ])
        );
    }
}
