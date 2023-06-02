<div>

    {{-- Controls --}}
    <div class="p-2">
        <div class="relative z-0 inline-flex shadow-sm">
            <button
                wire:click.stop="goToPreviousMonth"
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                <
            </button>
            <button
                wire:click.stop="goToCurrentMonth"
                type="button"
                class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                Today
            </button>
            <button
                wire:click.stop="goToNextMonth"
                type="button"
                class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                >
            </button>
            {{-- TODO: Maybe use that as a fallback, in case Flatpickr could not be loaded? Else remove it... --}}
            <fieldset>
                <label class='hidden' for='month'>Month</label>
                <select id='month' class='bg-gray-700 text-white -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 transition ease-in-out duration-150'
                    name='month' wire:change="goToMonth($event.target.value, {{ $startsAt->year }})">
                    @foreach (range(1, 12) as $month)
                        <option value='{{ $month }}' @selected(old('month', $startsAt->month) == $month)>{{ $this->getMonthName($month) }}</option>
                    @endforeach
                </select>
                <label class='hidden' for='year'>Year</label>
                <select id='year' class='bg-gray-700 text-white -ml-px relative inline-flex items-center px-8 py-2 rounded-r-md border border-gray-300 text-sm leading-5 font-medium focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 transition ease-in-out duration-150'
                    name='year' wire:change="goToMonth({{ $startsAt->month }}, $event.target.value)">
                    @foreach (range($startsAt->year - 10, $startsAt->year + 10) as $year)
                        <option value='{{ $year }}' @selected(old('year', $startsAt->year) == $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </fieldset>
        </div>
    </div>

    {{-- Unscheduled Events --}}
    @if(!$unscheduledEvents->isEmpty())
    <div class="bg-orange-100 p-3 mb-2">
        <h1 class="text-lg font-medium">
            Unscheduled Events
        </h1>
        <div class="flex py-2">
            @foreach($unscheduledEvents as $event)
                <div
                    class="shadow p-2 border rounded bg-white mr-2"
                    ondragstart="onCalendarEventDragStart(event, '{{ $event->id }}')"
                    draggable="true">
                    <p class="font-medium text-sm">
                        {{ $event->title }}
                    </p>
                    <p class="text-xs">
                       {{-- {{ $event->notes }}  TODO: trebuie sa il pun ca si project id daca exista daca nu sa il scot --}}
                    </p>
                    <button
                        wire:click.stop="deleteEvent({{ $event->id }})"
                        type="button"
                        class="mt-2 inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-50 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-200 transition ease-in-out duration-150">
                        Delete
                    </button>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Modals --}}
    <div>
        <div>
            @if($isModalOpen)
                @include('calendar::calendar.create-appointment-modal')
            @endif
        </div>

        <div>
            @if($selectedAppointment)
                @include('calendar::calendar.appointment-details-modal')
            @endif
        </div>
    </div>
</div>
