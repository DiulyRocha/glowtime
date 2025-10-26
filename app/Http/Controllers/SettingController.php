<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $discount = Setting::getValue('birthday_discount', 10); // valor padrão 10%
        return view('settings.edit', compact('discount'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'birthday_discount' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('birthday_discount', $request->birthday_discount);

        return redirect()->route('settings.edit')
            ->with('success', 'Configuração atualizada com sucesso!');
    }
}
