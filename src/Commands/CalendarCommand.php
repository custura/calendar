<?php

namespace Custura\Calendar\Commands;

use Illuminate\Console\Command;

// TODO: Handle that. See: https://laravel.com/docs/9.x/packages#about-artisan-command
class CalendarCommand extends Command
{
	public $signature = 'calendar';
	public $description = 'Generate a new Calendar component';

	public function handle() : int
	{
		$this->comment('Calendar generated!');

		return self::SUCCESS;
	}
}
