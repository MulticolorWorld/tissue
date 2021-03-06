<?php

namespace App\Http\Controllers;

use App\DeactivatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function profile()
    {
        return view('setting.profile');
    }

    public function updateProfile(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'display_name' => 'required|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::user()->email, 'email')
            ],
            'bio' => 'nullable|string|max:160',
            'url' => 'nullable|url|max:2000'
        ], [], [
            'display_name' => '名前',
            'email' => 'メールアドレス',
            'bio' => '自己紹介',
            'url' => 'URL'
        ]);

        if ($validator->fails()) {
            return redirect()->route('setting')->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $user->display_name = $inputs['display_name'];
        $user->email = $inputs['email'];
        $user->bio = $inputs['bio'] ?? '';
        $user->url = $inputs['url'] ?? '';
        $user->save();

        return redirect()->route('setting')->with('status', 'プロフィールを更新しました。');
    }

    public function privacy()
    {
        return view('setting.privacy');
    }

    public function updatePrivacy(Request $request)
    {
        $inputs = $request->all(['is_protected', 'accept_analytics', 'private_likes']);

        $user = Auth::user();
        $user->is_protected = $inputs['is_protected'] ?? false;
        $user->accept_analytics = $inputs['accept_analytics'] ?? false;
        $user->private_likes = $inputs['private_likes'] ?? false;
        $user->save();

        return redirect()->route('setting.privacy')->with('status', 'プライバシー設定を更新しました。');
    }

    public function deactivate()
    {
        return view('setting.deactivate');
    }

    public function destroyUser(Request $request)
    {
        // パスワードチェック
        $validated = $request->validate([
            'password' => 'required|string'
        ]);

        if (!Hash::check($validated['password'], Auth::user()->getAuthPassword())) {
            throw ValidationException::withMessages([
                'password' => 'パスワードが正しくありません。'
            ]);
        }

        // データの削除
        set_time_limit(0);
        DB::transaction(function () {
            $user = Auth::user();

            // 関連レコードの削除
            // TODO: 別にDELETE文相当のクエリを一発発行するだけでもいい？
            foreach ($user->ejaculations as $ejaculation) {
                $ejaculation->delete();
            }
            foreach ($user->likes as $like) {
                $like->delete();
            }

            // 先にログアウトしないとユーザーは消せない
            Auth::logout();

            // ユーザーの削除
            $user->delete();

            // ユーザー名履歴に追記
            DeactivatedUser::create(['name' => $user->name]);
        });

        return view('setting.deactivated');
    }

    // ( ◠‿◠ )☛ここに気づいたか・・・消えてもらう ▂▅▇█▓▒░(’ω’)░▒▓█▇▅▂うわあああああああ
//    public function password()
//    {
//        abort(501);
//    }
}
