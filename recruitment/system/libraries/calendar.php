<?php

/*

Usage:



<style>

.cal-today {

	font-weight:bold;

	background-color:#00FFFF;

	text-align:center;

}

.cal-table {

	width:200px;

	background-color:#EFEFEF;

	border:1px solid #CCCCCC;

	border-collapse:collapse;

}

.cal-table .cal-month, .cal-year {

	color:darkblue;

}

.cal-table tbody {

	background-color:#FFFFFF;

}

.cal-table td {

	border:1px solid #CCCCCC;

}

.cal-day {

	color:darkblue;

	text-align:center;

}

.cal-table thead {

	text-align:center;

	background-color:#EFEFEF;

}

.cal-day-title {

}

.cal-week {

}

.cal-has-event {



}

</style>



Loader::sysLibrary('calendar');

$c = new Calendar($_GET['year'], $_GET['month']);

$c->setPreviousLink('<a href=index.php?month=' . $c->getPreviousMonth() . '&year=' . $c->getPreviousYear() . '>pre</a>');

$c->setNextLink('<a href=index.php?month=' . $c->getNextMonth() . '&year=' . $c->getNextYear() . '>next</a>');

echo $c->display();



*/

class Calendar

{

	protected $print_week = false;

	protected $print_header = true;

	

	protected $year;

	protected $month;

	protected $day;

	

	protected $current_month;

	protected $current_day;

	protected $current_year;

	

	protected $day_name = array("S", "M", "T", "W", "T", "F", "S");

	protected $month_name = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	

	protected $previous_link;

	protected $next_link;

	

	protected $event;

	

	function __construct($year = null, $month = null)

	{

		$this->current_month = date('n');

		$this->current_day = date('j');

		$this->current_year = date('Y');

		

		if ($year == null && $month == null)

		{

			$this->month = $this->current_month;

			$this->year = $this->current_year;

		}

		else

		{

			$this->month = $month;

			$this->year = $year;

		}

	}

	

	private function getDaysInMonth()

	{

		return date('t', mktime(0, 0, 0, $this->month, 1, $this->year));

	}

	

	private function getStartDayOfWeek()

	{

		return date("w", mktime(0, 0, 0, $this->month, 1, $this->year));

	}

	

	public function setDayLabel($values)

	{

		$this->day_name = $values;

	}

	

	public function setMonthLabel($values)

	{

		$this->month_name = $values;

	}

	

	public function display()

	{

		$str = '<table class="cal-table">';

		

		$str .= '<thead>';

		if ($this->print_header)

		{

			$colspan = ($this->print_week) ? "8" : "7";

			$str .= "<tr>";

			$str .= "<td colspan='{$colspan}'>

						<span id='cal-previous-link'>" . $this->previous_link . "</span>

						<span class='cal-month'>" . $this->getActiveMonth() . "</span> 

						<span class='cal-year'>" . $this->getActiveYear() . "</span> 

						<span id='cal-next-link'>" . $this->next_link . "</span>

					</td>";

			$str .= "</tr>";

		}

		

		$str .= '<tr>';

		$str .= ($this->print_week) ? '<td>&nbsp;</td>' : '' ;

		

		for ($i = 1; $i <= 7; $i++)

			$str .= "<td class='cal-day-title'>" . $this->day_name[$i % 7] . "</td>";

			

		$str .= "</tr>";

		$str .= '</thead>';

		

		$str .= '<tbody>';

		

		$start_day_of_week = $this->getStartDayOfWeek();

		if ($start_day_of_week == 0)

			$start_day_of_week = 7;

	

		$days_in_month = $this->getDaysInMonth();

		for ($i = 1, $j = 1; $j <= $days_in_month; $i++, $j++) {

			while ($i < $start_day_of_week)

				$out[$i++] = 0;



			$out[$i] = $j;

		}

		while (count($out) % 7 != 0)

			array_push($out, 0);

			

		for ($i = 1; $i <= count($out); $i++) {

			if (($i - 1) % 7 == 0) {

				$str .= '<tr>';

				if ($this->print_week) {

					for ($j = 0; $j < 7; $j++)

						if ($out[$i + $j] != 0) {

							$week_num = date("W", mktime(0, 0, 0, $this->month, $out[$i + $j], $this->year));

							break;

						}

					$str .= "<td class='cal-week'>$week_num</td>";

				}

			}

			

			$day = ($out[$i] == 0) ? '&nbsp' : $out[$i];

			

			if ($events = $this->event->hasEvent($this->month, $day, $this->getActiveYear()))

			{

				$day = '<div class="cal-has-event" onclick="calendar_show_event(\''.$this->month.'\', \''.$day.'\', \''.$this->getActiveYear().'\')">' . $this->getEvents($day, $events) . '</div>';//"<b>$day</b>";

			}

						

			if ($this->isToday($day))

			{

				$str .= "<td class='cal-today' id='cal-today'>" . $day . "</td>";

			}

			else

			{

				$str .= "<td class='cal-day'>" . $day . "</td>";

			}

			

			if ($i % 7 == 0)

				$str .= "</tr>";

		}

		

		$str .= '</tbody>';

		$str .= "</table>";

	

		return $str;

	}

	

	protected function getEvents($day, $events)

	{

		return $day;

	}

	

	protected function getActiveYear()

	{

		return $this->year;

	}

	

	protected function getActiveMonth()

	{

		return $this->month_name[$this->month - 1];

	}

		

	private function isToday($day)

	{

		$return = false;

		if ($day == $this->current_day && $this->month == $this->current_month && $this->current_year == $this->year)

			$return = true;

		

		return $return;

	}

	

	//===============================

	

	public function getNextMonth()

	{

		$mktime = mktime(0, 0, 0, $this->month + 2, 0, $this->year);

		return date('n', $mktime);

	}

	

	public function getNextYear()

	{

		$mktime = mktime(0, 0, 0, $this->month + 2, 0, $this->year);

		return date('Y', $mktime);

	}

	

	public function getPreviousMonth()

	{

		$mktime = mktime(0, 0, 0, $this->month, 0, $this->year);

		return date('n', $mktime);

	}

	

	public function getPreviousYear()

	{

		$mktime = mktime(0, 0, 0, $this->month, 0, $this->year);

		return date('Y',$mktime);

	}

	

	public function setPreviousLink($value)

	{

		$this->previous_link = $value;

	}

	

	public function setNextLink($value)

	{

		$this->next_link = $value;

	}

	

	public function addEvents($event)

	{

		$this->event = $event;

	}

}

?>