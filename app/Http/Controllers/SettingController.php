<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Exibe o formulário de configurações.
     */
    public function index()
    {
        // Busca o valor atual do desconto (padrão 10%)
        $discountSetting = Setting::firstOrCreate(
            ['key' => 'birthday_discount'],
            ['value' => 10]
        );

        return view('settings.index', ['discount' => $discountSetting->value]);
    }

    /**
     * Atualiza o desconto de aniversário.
     */
    public function update(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        Setting::updateOrCreate(
            ['key' => 'birthday_discount'],
            ['value' => $request->discount]
        );

        return redirect()
            ->route('settings.index')
            ->with('success', '🎉 Percentual de desconto atualizado com sucesso!');
    }
}
