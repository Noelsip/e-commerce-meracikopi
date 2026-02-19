<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'order_type_takeaway' => Setting::get('order_type_takeaway', '1'),
            'order_type_dine_in'  => Setting::get('order_type_dine_in', '1'),
            'order_type_delivery' => Setting::get('order_type_delivery', '1'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Setting::set('order_type_takeaway', $request->has('order_type_takeaway') ? '1' : '0');
        Setting::set('order_type_dine_in',  $request->has('order_type_dine_in')  ? '1' : '0');
        Setting::set('order_type_delivery', $request->has('order_type_delivery') ? '1' : '0');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
