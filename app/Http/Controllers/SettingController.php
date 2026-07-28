<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $setting = Setting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'theme' => 'system',
                'timezone' => 'Asia/Jakarta',
                'language' => 'id',
                'reminder_notification' => true,
                'email_notification' => true,
            ]
        );

        return view('settings.index', compact('user', 'setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
            'timezone' => ['required', 'string'],
            'language' => ['required', 'string', 'in:id,en'],
        ]);

        Setting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'theme' => $request->theme,
                'timezone' => $request->timezone,
                'language' => $request->language,
                'reminder_notification' => $request->has('reminder_notification'),
                'email_notification' => $request->has('email_notification'),
            ]
        );

        return back()->with('status', 'settings-updated');
    }
}