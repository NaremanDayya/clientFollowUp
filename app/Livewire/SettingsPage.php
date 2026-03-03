<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Settings')]
class SettingsPage extends Component
{
    public string $updateGapDays = '';

    public function mount(): void
    {
        $this->updateGapDays = Setting::get('update_gap_days', '3');
    }

    public function save(): void
    {
        $this->validate([
            'updateGapDays' => 'required|integer|min:1|max:365',
        ]);

        Setting::set('update_gap_days', $this->updateGapDays);

        session()->flash('settings-saved', 'Settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings-page');
    }
}
