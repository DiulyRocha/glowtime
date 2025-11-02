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
        // Busca ou cria os valores padrão (10%)
        $birthdayDiscount = Setting::firstOrCreate(
            ['key' => 'birthday_discount'],
            ['value' => 10]
        );

        $inactiveDiscount = Setting::firstOrCreate(
            ['key' => 'inactive_discount'],
            ['value' => 10]
        );

        // Retorna para a view com ambos os valores
        return view('settings.index', [
            'birthday_discount' => $birthdayDiscount->value,
            'inactive_discount' => $inactiveDiscount->value,
        ]);
    }

    /**
     * Atualiza os descontos configurados.
     */
    public function update(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'birthday_discount' => 'required|numeric|min:0|max:100',
            'inactive_discount' => 'required|numeric|min:0|max:100',
        ]);

        // Atualiza ou cria o desconto de aniversário 🎂
        Setting::updateOrCreate(
            ['key' => 'birthday_discount'],
            ['value' => $request->birthday_discount]
        );

        // Atualiza ou cria o desconto de clientes inativas 💤
        Setting::updateOrCreate(
            ['key' => 'inactive_discount'],
            ['value' => $request->inactive_discount]
        );

        // Retorno com mensagem de sucesso
        return redirect()
            ->route('settings.index')
            ->with('success', '✅ Configurações de desconto atualizadas com sucesso!');
    }
}
