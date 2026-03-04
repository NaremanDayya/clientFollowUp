<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\ClientUpdate;
use Carbon\Carbon;
use Livewire\Component;

class ClientCalendar extends Component
{
    public Client $client;
    public int $year;
    public int $month;
    public bool $showModal = false;

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function openCalendar()
    {
        $this->showModal = true;
    }

    public function closeCalendar()
    {
        $this->showModal = false;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function getCalendarDataProperty(): array
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        // Get first day of calendar (might be from previous month)
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Get last day of calendar (might be from next month)
        $endDate = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        $weeks = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $week = [];
            
            for ($i = 0; $i < 7; $i++) {
                $dayOfWeek = $currentDate->dayOfWeek;
                $isFriday = $dayOfWeek === Carbon::FRIDAY;
                $isSaturday = $dayOfWeek === Carbon::SATURDAY;
                $isCurrentMonth = $currentDate->month === $this->month;
                
                // Get updates for this day
                $updates = [];
                if ($isCurrentMonth && !$isFriday && !$isSaturday) {
                    $updates = ClientUpdate::where('client_id', $this->client->id)
                        ->whereDate('created_at', $currentDate->toDateString())
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->toArray();
                }
                
                $week[] = [
                    'date' => $currentDate->copy(),
                    'day' => $currentDate->day,
                    'isCurrentMonth' => $isCurrentMonth,
                    'isFriday' => $isFriday,
                    'isSaturday' => $isSaturday,
                    'isWeekend' => $isFriday || $isSaturday,
                    'updates' => $updates,
                    'hasUpdates' => count($updates) > 0,
                ];
                
                $currentDate->addDay();
            }
            
            $weeks[] = $week;
        }
        
        return $weeks;
    }

    public function getMonthNameProperty(): string
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        
        return $months[$this->month];
    }

    public function render()
    {
        return view('livewire.client-calendar');
    }
}
