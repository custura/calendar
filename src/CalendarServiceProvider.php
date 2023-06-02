<?php

namespace Custura\Calendar;

use Custura\Calendar\Commands\CalendarCommand;
use Custura\Calendar\Components\Flatpickr;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CalendarServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package) : void
	{
		$package->name('calendar')
			->hasConfigFile()
			->hasViews()
			->hasViewComponent('calendar', Flatpickr::class)
			->hasMigration('create_team_appointments_table')
			->hasCommand(CalendarCommand::class);
	}

	public function bootingPackage()
	{
		Blade::componentNamespace('Custura\\Calendar\\Components', 'calendar');

        Blade::directive('calendarScripts', function () {
			return <<<'HTML'
            <script>
                function onCalendarEventDragStart(event, eventId) {
                    event.dataTransfer.setData('id', eventId);
                }
                function onCalendarEventDragEnter(event, componentId, dateString, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();
                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className + ` ${dragAndDropClasses} `;
                }
                function onCalendarEventDragLeave(event, componentId, dateString, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();
                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className.replace(dragAndDropClasses, '');
                }
                function onCalendarEventDragOver(event) {
                    event.stopPropagation();
                    event.preventDefault();
                }
                function onCalendarEventDrop(event, componentId, dateString, year, month, day, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();
                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className.replace(dragAndDropClasses, '');
                    const eventId = event.dataTransfer.getData('id');
                    window.Livewire.find(componentId).call('onEventDropped', eventId, year, month, day);
                }
            </script>
            HTML;
		});
	}

}
