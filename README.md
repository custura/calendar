It is heavily inspired by Andrés Santibáñez's [Livewire Calendar](https://github.com/asantibanez/livewire-calendar).

## Preview

![preview](https://raw.githubusercontent.com/custura/calendar/main/preview.gif)

## Table of contents

* [Installation](#installation)
* [Requirements](#requirements)
* [Usage](#usage)
	* [Initialization](#initialization)
	* [Loading events (using arrays)](#loading-events-using-arrays)
	* [Loading events (using Eloquent query)](#loading-events-using-eloquent-query)
	* [Rendering the calendar](#rendering-the-calendar)
	* [Choosing the starting month](#choosing-the-starting-month)
	* [Enabling drag & drop](#enabling-drag--drop)
	* [Handling navigation](#handling-navigation)
* [Advanced usage](#advanced-usage)
	* [Blade customization](#blade-customization)
	* [Custom views](#custom-views)
	* [Interactivity](#interactivity)
	* [Automatic polling](#automatic-polling)
* [Flatpickr](#flatpickr)
	* [Importing](#importing)
	* [Customization](#customization)
* [Testing](#testing)

## Installation

You can install the package via composer:


composer require custura/calendar


You can publish the config file with:

```bash
php artisan vendor:publish --tag="calendar-config"
```

This is the contents of the published config file:

```php
return [

];
```

Optionally, you can publish the views and migrations using

```bash
php artisan vendor:publish --tag="calendar-views"

php artisan vendor:publish --tag="calendar-migrations"
```

## Requirements

This package uses `livewire/livewire` (https://laravel-livewire.com) under the hood.

It also uses TailwindCSS (https://tailwindcss.com) for styling. Please make sure you include both of these dependencies before using this component.

## Usage

### Initialization

First, create a new Livewire component that extends `Calendar`.

You can use `make:livewire` to create this component. For example:

```bash
php artisan make:livewire AppointmentsCalendar
```

Then, in the created `AppointmentsCalendar` class, extend `Calendar` instead of extending from the base `Component` Livewire class:

```php
class AppointmentsCalendar extends \Custura\Calendar\Calendar
{
    //
}
```

In this class, you must override the following method in order to implement custom logic for loading events:

```php
public function events() : Collection
{
    // Return a Laravel collection
}
```

Also remove the `render` method, as it will be handled for you.

In the `events()` method, you should return a collection containing the events that should be displayed on the calendar.

### Loading events (using arrays)

Events must be keyed arrays holding at least the following keys: `id`, `title`, `description`, `date` (which must be a `Carbon\Carbon` instance).

```php
public function events() : Collection
{
    return collect([
        [
            'id' => 1,
            'title' => 'Breakfast',
            'description' => 'Pancakes! 🥞',
            'date' => Carbon::today(),
        ],
        [
            'id' => 2,
            'title' => 'Meeting with Camille',
            'description' => 'Random chit-chat.',
            'date' => Carbon::tomorrow(),
        ],
    ]);
}
```

The `date` value will be used to determine to which day the event will be displayed.

### Loading events (using Eloquent query)

You can also load values dynamically in the `events()` method. You can use the following component properties to filter your events:

- `startsAt`: starting date of the month
- `endsAt`: ending date of the month
- `gridStartsAt`: starting date of calendar grid (can be a date from the previous month).
- `endingStartsAt`: ending date of calendar grid (can be a date from the previous month).

```php
public function events(): Collection
{
    return Model::query()
        ->whereDate('scheduled_at', '>=', $this->gridStartsAt)
        ->whereDate('scheduled_at', '<=', $this->gridEndsAt)
        ->get()
        ->map(function (Model $model) {
            return [
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->notes,
                'date' => $model->scheduled_at,
            ];
        });
}
```

### Rendering the calendar

Now you can include your calendar in any view using Blade components:

```blade
<livewire:appointments-calendar/>
```

### Choosing the starting month

By default, the component will render the current month. If you want to change the
starting month, you can set the `year` and `month` props.

```blade
<livewire:appointments-calendar year='2023' month='03' />
```

### Enabling drag & drop

To enable drag & drop, include `@calendarScripts` after your `@livewireScripts` directive.

```blade
@livewireScripts
@livewireCalendarScripts
```

### Handling navigation

The component has 4 public methods used to navigate between months:

```php
public function goToPreviousMonth()
public function goToCurrentMonth()
public function goToNextMonth()
public function goToMonth($month, $year = null)
```

For example, these methods can be used to build a navigation system using additional views. Check out below section for example usage.

## Advanced usage

### Blade customization

When rendering your Blade component, several additional attributes are available to customize the behavior of your calendar:

- `week-start` to indicate the starting day of the week. It can be a number from 0 to 6 according to `Carbon` days of week (0 = sunday).


- `drag-and-drop-classes` can be any CSS class used to render the hover effect when dragging & dropping an event in the calendar. By default, this value
  is `border border-4 border-blue-400`.

```blade
<livewire:appointments-calendar week-start='1' drag-and-drop-classes='bg-orange-500' />
```

### Custom views

You can also use custom Blade views to render different parts of the calendar.

It is recommended to publish the base Blade views used by the component and extend their behavior and styling to your liking. To do this, please check out
the [Installation](#installation) section.

Those views can be specified using the following attributes:

- `calendar-view` used to render the whole component. Please check out the package's `calendar.blade.php` view to know which attributes are available.


- `dow-view` used to render each column header of the calendar (typically containing days of week). This view will receive the `$day`property, which is a `Carbon`
  instance of the associated day of the week.


- `day-view` used to render each day of the month. This view will receive the following attributes:
	- `componentId` (the id of the Livewire component)
	- `day` (the day of the month as a `Carbon` instance)
	- `dayInMonth` (boolean indicating if the day is part of the current month or not)
	- `isToday` (boolean indicating if the day is today)
	- `events` (events collection that corresponds to the day)


- `event-view` used to render the event card. This view will receive an `$event` variable containing its data.


- `before-calendar-view` and `after-calendar-view` can be any Blade views that will be rendered before or after the calendar itself. These can be used to add extra features (
  e.g. navigation system) to your component.

```blade
<livewire:appointments-calendar
	calendar-view='path/to/view/calendar.blade.php'
	dow-view='path/to/view/dow.blade.php'
	day-view='path/to/view/day.blade.php'
	event-view='path/to/view/event.blade.php'
	before-calendar-view='path/to/view/before-calendar.blade.php'
	after-calendar-view='path/to/view/after-calendar.blade.php'
/>
```

All custom views paths must be relative to the `resources/views` directory.

### Interactivity

Several methods are available to interact with the calendar:

```php
public function onDayClick($year, $month, $day)
{
	// This event is triggered when a day is clicked
}

public function onEventClick($eventId)
{
	// This event is triggered when an event card is clicked
}

public function onEventDropped($eventId, $year, $month, $day)
{
	// This event is triggered when an event is dragged & dropped onto another calendar day
}
```

You can override any of them to implement your custom logic.

By default, click and drag & drop events are enabled. To disable them you can use the following attributes when rendering the component

```blade
<livewire:appointments-calendar
	:day-click-enabled='false'
	:event-click-enabled='false'
	:drag-and-drop-enabled='false'
/>
```

### Automatic polling

You can add automatic polling if need be by defining a `$pollMillis` property in the Livewire component.

You can also combine it with `$pollAction` in order to call a specific action in your component at the desired polling interval.

To learn more about polling, please check out https://laravel-livewire.com/docs/2.x/polling.

## Flatpickr

To handle navigation between months, this package makes use of the [Flatpickr](https://flatpickr.js.org) library. It displays a calendar picker which is way more powerful than
simple `<select>`.

### Importing

First, add the Flatpickr library to your `package.json`'s dependencies:

```json
{
	"dependencies": {
		"flatpickr": "^4.6.13"
	}
}
```

Then import this package's `flatpickr.scss` and `flatpickr.tsx` files to you main stylesheet & script.

`app.scss`:

```scss
@use '../vendor/custura/calendar/resources/sass/flatpickr';
```

`app.js`:

```tsx
import calendar from '../../../vendor/custura/calendar/resources/scripts/flatpickr';

calendar();
```

### Customization

You can customize the colors of the calendar picker to match your theme. To do so, simply override the Sass variables when importing `flatpickr.scss`:

```scss
@use '../vendor/custura/calendar/resources/sass/flatpickr' with (
	$color-primary: saddlebrown,			// Basic color
	$color-accent: #FDBA74,				// Standard CSS color
	$color-contrast: rgb(118, 89, 71, 0.8),		// RGB color
	$color-range: theme('colors.orange.100')	// Tailwind theme color
);
```

## Todo

- [ ] Ideally, users should not have to mess with their sass & script files in order to import the proper dependencies. Everything should be done inside Blade (at the very
  least importing styles & script directly in Blade layout). See: https://github.com/Laratipsofficial/laravel-flatpickr.
- [ ] Find a way to highlight events in Flatpickr component (so that users see immediately which days have events).
- [ ] Handle Shields properly in README.md. Check out: https://shields.io/.
- [X] ~~Create tests for Flatpickr component~~
- [X] ~~Update README.md for how to properly setup Flatpickr component~~
- [X] ~~Add default navigation, ideally using a dynamic calendar picker (instead of basic `<select>`).~~

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](https://github.com/custura/calendar/security/policy) on how to report security vulnerabilities.

## Credits
- [Custura](https://github.com/custura)
- [Florian PLAMONT](https://github.com/Keysaw)
- [Andrés Santibáñez](https://github.com/asantibanez)
- [All Contributors](https://github.com/custura/calendar/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.