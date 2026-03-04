<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\ClientUpdate;
use Carbon\Carbon;
use Livewire\Component;

class DashboardCalendar extends Component
{
    public int $year;
    public int $month;
    public ?int $selectedClientId = null;
    public ?string $selectedDate = null;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->selectedDate = null;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->selectedDate = null;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function filterByClient($clientId)
    {
        $this->selectedClientId = $clientId === 'all' ? null : (int)$clientId;
        $this->selectedDate = null;
    }

    public function resetFilter()
    {
        $this->selectedClientId = null;
        $this->selectedDate = null;
    }

    public function getCalendarDataProperty(): array
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
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
                
                // Get updates count for this day
                $updatesCount = 0;
                if ($isCurrentMonth && !$isFriday && !$isSaturday) {
                    $query = ClientUpdate::whereDate('created_at', $currentDate->toDateString());
                    
                    // Apply client filter if set
                    if ($this->selectedClientId) {
                        $query->where('client_id', $this->selectedClientId);
                    }
                    
                    // Filter by user role
                    if (auth()->user()->isFollower()) {
                        $query->whereHas('client', function ($q) {
                            $q->where('assigned_to', auth()->id());
                        });
                    }
                    
                    $updatesCount = $query->count();
                }
                
                $week[] = [
                    'date' => $currentDate->copy(),
                    'day' => $currentDate->day,
                    'dateString' => $currentDate->toDateString(),
                    'isCurrentMonth' => $isCurrentMonth,
                    'isFriday' => $isFriday,
                    'isSaturday' => $isSaturday,
                    'isWeekend' => $isFriday || $isSaturday,
                    'updatesCount' => $updatesCount,
                    'hasUpdates' => $updatesCount > 0,
                ];
                
                $currentDate->addDay();
            }
            
            $weeks[] = $week;
        }
        
        return $weeks;
    }

    public function getSelectedDateUpdatesProperty()
    {
        if (!$this->selectedDate) {
            return collect();
        }

        $query = ClientUpdate::whereDate('created_at', $this->selectedDate)
            ->with(['client', 'user'])
            ->orderBy('created_at', 'desc');

        // Apply client filter if set
        if ($this->selectedClientId) {
            $query->where('client_id', $this->selectedClientId);
        }

        // Filter by user role
        if (auth()->user()->isFollower()) {
            $query->whereHas('client', function ($q) {
                $q->where('assigned_to', auth()->id());
            });
        }

        return $query->get();
    }

    public function getClientsProperty()
    {
        $query = Client::orderBy('name');
        
        if (auth()->user()->isFollower()) {
            $query->where('assigned_to', auth()->id());
        }
        
        return $query->get();
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
        return view('livewire.dashboard-calendar');
    }
}
