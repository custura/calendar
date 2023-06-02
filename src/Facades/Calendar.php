<?php

namespace Custura\Calendar\Facades;

use Custura\Calendar\Calendar as CalendarComponent;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Custura\Calendar\Calendar
 */
class Calendar extends Facade
{
	protected static function getFacadeAccessor() : string
	{
		return CalendarComponent::class;
	}
}
