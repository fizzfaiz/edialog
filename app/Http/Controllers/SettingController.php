<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-settings');
    }

    public function index(): View
    {
        $settings = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('user_settings')) {
            $settings = \App\Models\UserSetting::where('user_id', auth()->id())->first();
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'font_family' => ['required', 'string', 'in:Inter,Nunito,system-ui,Arial'],
            'font_size' => ['required', 'string', 'in:small,medium,large'],
        ]);

        $user = auth()->user();

        // Use updateOrCreate to handle both create and update
        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('settings.index')
            ->withSuccess('Tetapan berjaya disimpan.');
    }
}