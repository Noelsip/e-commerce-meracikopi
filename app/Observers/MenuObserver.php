<?php

namespace App\Observers;

use App\Models\Menus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MenuObserver
{
    /**
     * Trigger ketika Menus::create() dipanggil pada controller
     */
    public function created(Menus $menu): void
    {
        // Log activity
        Log::info("New Menu created: {$menu->name}", [
            'menu_id' => $menu->id,
            'user_id' => auth()->id() ?? 'system',
        ]);
    }

    /**
     * Triggered ketika $menu->update() atau $menu->save() dipanggil pada controller
     */
    public function updated(Menus $menus): void
    {
        // Log activity
        Log::info("Menu updated: {$menus->name}", [
            'menu_id' => $menus->id,
            'changes' => $menus->getChanges(),
            'user_id' => auth()->id() ?? 'system',
        ]);
    }

    /**
     * Triggered ketika $menu->delete() dipanggil pada controller
     */
    public function deleted(Menus $menus): void
    {
        // Log activity
        Log::info("Menu deleted: {$menus->name}", [
            'menu_id' => $menus->id,
            'user_id' => auth()->id() ?? 'system',
        ]);
    }

    /**
     * Triggered ketika $menu->restore() dipanggil dari controller
     */
    public function restored(Menus $menus): void
    {
        Log::info("Menu restored: {$menus->name}", [
            'menu_id' => $menus->id,
            'user_id' => auth()->id() ?? 'system',
        ]);
    }

}
