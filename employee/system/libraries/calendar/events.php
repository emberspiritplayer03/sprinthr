<?php

/*

usage:



Loader::sysLibrary('calendar/events');

$event = new Events;

$event->addEvent(1, 4, 2008, 'This is my birthday');

$event->addEvent(1, 4, 2008, 'This is your birthday');

$event->addEvent(1, 6, 2008, 'This is my bad day');

$event->addEvent(1, 23, 2008, 'This is my bad day');



Loader::appLibrary('ajax_calendar');

$ca = new Calendar($_GET['year'], $_GET['month']);

$ca->addEvents($event);

echo $ca->display();



$events = $event->getEvents();

*/

class Events

{

	public $events = array();

	

	function __construct()

	{



	}

	

	function hasEvent($month, $day, $year)

	{

		foreach ($this->events as $date => $event)

		{

			$mktime = mktime(0, 0, 0, $month, $day, $year);

			if ($date == $mktime)

			{

				return $event;

			}

		}

		

		return false;

	}

	

	function addEvent($month, $day, $year, $event)

	{

		$mktime = mktime(0, 0, 0, $month, $day, $year);

		$this->events["$mktime"][] = $event;

	}

	

	function getEvents()

	{

		return $this->events;

	}

}

?>