<?php

namespace Custura\Calendar;

use Carbon\CarbonInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Auth;

// TODO: Add proper types to properties
class Calendar extends Component
{
	public int $weekStart;
	public int $weekEnd;
	public Carbon $startsAt;
	public Carbon $endsAt;
	public Carbon $gridStartsAt;
	public Carbon $gridEndsAt;
	public ?string $calendarView;
	public ?string $navView;
	public ?string $dowView;
	public ?string $dayView;
	public ?string $eventView;
	public ?string $beforeCalendarView;
	public ?string $afterCalendarView;
	public ?string $dragAndDropClasses;
	public ?string $pollMillis;
	public ?string $pollAction;
	public bool $dragAndDropEnabled;
	public bool $dayClickEnabled;
	public bool $eventClickEnabled;

	protected array $casts = [
		'startsAt' => 'date',
		'endsAt' => 'date',
		'gridStartsAt' => 'date',
		'gridEndsAt' => 'date',
	];

	public function mount(
		$initialYear = null,
		$initialMonth = null,
		$weekStart = null,
		$calendarView = null,
		$navView = null,
		$dowView = null,
		$dayView = null,
		$eventView = null,
		$dragAndDropClasses = null,
		$beforeCalendarView = null,
		$afterCalendarView = null,
		$pollMillis = null,
		$pollAction = null,
		$dragAndDropEnabled = true,
		$dayClickEnabled = true,
		$eventClickEnabled = true,
		$extras = [])
	{
		// Set initial month & year
		$initialYear = $initialYear ?? Carbon::today()->year;
		$initialMonth = $initialMonth ?? Carbon::today()->month;

		// Set calendar week format
		$this->weekStart = $weekStart ?? CarbonInterface::SUNDAY;
		$this->weekEnd = $this->weekStart == CarbonInterface::SUNDAY ? CarbonInterface::SATURDAY : collect([0, 1, 2, 3, 4, 5, 6])->get($this->weekStart + 6 - 7);

		// Set calendar boundaries
		$this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1)->startOfDay();
		$this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
		$this->calculateGridBoundaries();

		// Setup views
		$this->setupViews($calendarView, $navView, $dowView, $dayView, $eventView, $beforeCalendarView, $afterCalendarView);

		// Setup polling
		$this->setupPoll($pollMillis, $pollAction);

		// Setup drag & drop
		$this->dragAndDropEnabled = $dragAndDropEnabled;
		$this->dragAndDropClasses = $dragAndDropClasses ?? 'border border-4 border-blue-400';

		// Setup click events
		$this->dayClickEnabled = $dayClickEnabled;
		$this->eventClickEnabled = $eventClickEnabled;

		$this->afterMount($extras);
	}

	public function afterMount($extras = [])
	{
		//
	}

	public function calculateGridBoundaries()
	{
		$this->gridStartsAt = $this->startsAt->clone()->startOfWeek($this->weekStart);
		$this->gridEndsAt = $this->endsAt->clone()->endOfWeek($this->weekEnd);
	}

	public function setupViews($calendarView = null, $navView = null, $dowView = null, $dayView = null, $eventView = null, $beforeCalendarView = null, $afterCalendarView = null)
	{
		$this->calendarView = $calendarView ?? 'calendar::calendar.calendar';
		$this->navView = $navView ?? 'calendar::calendar.nav';
		$this->dowView = $dowView ?? 'calendar::calendar.dow';
		$this->dayView = $dayView ?? 'calendar::calendar.day';
		$this->eventView = $eventView ?? 'calendar::calendar.event';

		$this->beforeCalendarView = $beforeCalendarView ?? null;
		$this->afterCalendarView = $afterCalendarView ?? null;
	}

	public function setupPoll($pollMillis, $pollAction)
	{
		$this->pollMillis = $pollMillis;
		$this->pollAction = $pollAction;
	}

	public function goToPreviousMonth()
	{
		$this->startsAt->subMonthNoOverflow();
		$this->endsAt->subMonthNoOverflow();

		$this->calculateGridBoundaries();
	}

	public function goToCurrentMonth()
	{
		$this->startsAt = Carbon::today()->startOfMonth()->startOfDay();
		$this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

		$this->calculateGridBoundaries();
	}

	public function goToNextMonth()
	{
		$this->startsAt->addMonthNoOverflow();
		$this->endsAt->addMonthNoOverflow();

		$this->calculateGridBoundaries();
	}

	public function goToMonth($month, $year = null)
	{
		$year ??= Carbon::today()->startOfYear()->year;

		$targetDate = Carbon::createMidnightDate($year, $month, 1);
		$diffMonths = $targetDate->month - $this->startsAt->month;
		$diffYears = $targetDate->year - $this->startsAt->year;

		$this->startsAt->addMonthsNoOverflow($diffMonths)->addYearsNoOverflow($diffYears);
		$this->endsAt->addMonthsNoOverflow($diffMonths)->addYearsNoOverflow($diffYears);

		$this->calculateGridBoundaries();
	}

	/**
	 * @throws Exception
	 */
	public function monthGrid() : Collection
	{
		$firstDayOfGrid = $this->gridStartsAt;
		$lastDayOfGrid = $this->gridEndsAt;

		$weeks = $lastDayOfGrid->diffInWeeks($firstDayOfGrid) + 1;
		$days = $lastDayOfGrid->diffInDays($firstDayOfGrid) + 1;

		if ($days % 7 != 0)
			throw new Exception('Calendar is not configured correctly. Please check initial inputs.');

		$monthGrid = collect();
		$currentDay = $firstDayOfGrid->clone();

		while (!$currentDay->greaterThan($lastDayOfGrid)) {
			$monthGrid->push($currentDay->clone());
			$currentDay->addDay();
		}

		$monthGrid = $monthGrid->chunk(7);
		if ($weeks != $monthGrid->count())
			throw new Exception('Calendar calculated the wrong number of weeks. Sorry... :(');

		return $monthGrid;
	}

	public function events() : Collection
	{
		return collect();
	}

	public function getMonthName($monthNumber) : string
	{
		return ucfirst(Carbon::create(0, $monthNumber)->translatedFormat('F'));
	}

	public function getEventsForDay($day, Collection $events) : Collection
	{
		return $events->filter(fn ($event) => Carbon::parse($event['date'])->isSameDay($day));
	}

    public function unscheduledEvents() : Collection
    {
        return collect();
    }

    public function projectBind() : Collection
    {
        return collect();
    }

	public function onDayClick($year, $month, $day)
	{
		//
	}

	public function onEventClick($eventId)
	{
		//
	}

	public function onEventDropped($eventId, $year, $month, $day)
	{
		//
	}

	public function deleteEvent($eventId)
    {
       //
    }

	public function render() : View
	{
        return view();
	}
}
