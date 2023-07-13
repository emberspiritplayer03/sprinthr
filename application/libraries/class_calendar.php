<?php
/**
* Calendar Generation Class
*
* This class provides a simple reuasable means to produce month calendars in valid html
*
* @version 2.7
* @author Jim Mayes <jim.mayes@gmail.com>
* @link http://style-vs-substance.com
* @copyright Copyright (c) 2008, Jim Mayes
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPL v2.0

		
			
			
//	js
//	
//	var element = "#calendar_harney";
//	var path = 'customer/_load_calendar';
//
//	var harneyCalendar = new createCalendar(element,path);
//	harneyCalendar.show();
//	
//	
//	controller
//	function _load_calendar() {
//	$date = (!empty($_POST['current_month'])) ? $_POST['current_month'] : date("Y-m-d");
/	$calendar = new Calendar($date);	
			$calendar->highlighted_dates = array(
						'2009-10-03',
						'2009-10-17',
						'2009-10-25'
						);
						
						$calendar->link_days = 2; // highlighted days are allowable to click
			
			$calendar->formatted_link_to = 'javascript:test(/%Y/%m/%d);';
			$calendar->height ='400px';
			$calendar->width ='700px';
			
			//$calendar->mark_passed = TRUE; //default true
			//$calendar->passedDateBgColor = 'red';
			//$calendar->passed_date_class = 'passed';
			
			//$calendar->mark_selected = TRUE; //default true
			//$calendar->selectedDateBgColor = 'blue';
			//$calendar->selected_date_class = 'selected'; //default selected
			
			
			//$calendar->highlightedDatesBgColor ='green';
			//$calendar->default_highlighted_class = 'highlighted';
			
			//$calendar->mark_today = TRUE; //DEFAULT TRUE
			//$calendar->todayDateBgColor =	'yellow';
			//$calendar->today_date_class = 'today'; //CLASS THAT YOU CAN IMPORT YOUR STYLE //default today
			
			//$calendar->week_start = '7'; //sunday

//	echo $calendar->output_calendar();
//	} 
//	view
//	
//	<div id="calendar_harney"></div>


*/

class Calendar{
	var $date;
	var $year;
	var $month;
	var $day;
	
	var $height = '500px';
	var $width = '700px';
	var $button_class;
	var $week_start_on = FALSE;
	var $week_start = 7;// sunday
	
	var $link_days = TRUE;
	var $link_to;
	var $formatted_link_to;
	
	
	var $mark_today = TRUE;
	var $todayDateBgColor = '#FFFFCD';
	var $today_date_class = 'today';
	
	var $mark_selected = TRUE;
	var $selectedDateBgColor; //= '#F0F0F0';
	var $selected_date_class = 'selected';
	
	var $mark_passed = TRUE;
	var $passedDateBgColor = '#F2F2F2';
	var $passed_date_class = 'passed';
	
	var $highlighted_dates;
	var $highlightedDatesBgColor = '#fc9';
	var $default_highlighted_class = 'highlighted';
	

	var $not_available_day; // = array('saturday');
	var $not_available_day_class = 'not_available_day';
	var $notAvailableDayBgColor; // = 'brown';
	
	
	/* CONSTRUCTOR */
	function Calendar($date = NULL, $year = NULL, $month = NULL){
		$self = htmlspecialchars($_SERVER['PHP_SELF']);
		$this->link_to = $self;
		
		if( is_null($year) || is_null($month) ){
			if( !is_null($date) ){
				//-------- strtotime the submitted date to ensure correct format
				$this->date = date("Y-m-d", strtotime($date));
			} else {
				//-------------------------- no date submitted, use today's date
				$this->date = date("Y-m-d");
			}
			$this->set_date_parts_from_date($this->date);
		} else {
			$this->year		= $year;
			$this->month	= str_pad($month, 2, '0', STR_PAD_LEFT);
		}	
	}
	
	function set_date_parts_from_date($date){
		$this->year		= date("Y", strtotime($date));
		$this->month	= date("m", strtotime($date));
		$this->day		= date("d", strtotime($date));
	}
	
	function day_of_week($date){
		$day_of_week = date("N", $date);
		if( !is_numeric($day_of_week) ){
			$day_of_week = date("w", $date);
			if( $day_of_week == 0 ){
				$day_of_week = 7;
			}
		}
		return $day_of_week;
	}
	
	function output_calendar($year = NULL, $month = NULL, $calendar_class = 'jim_calendar'){
		
		if( $this->week_start_on !== FALSE ){
			echo "The property week_start_on is replaced due to a bug present in version before 2.6. of this class! Use the property week_start instead!";
			exit;
		}
		
		//--------------------- override class methods if values passed directly
		$year = ( is_null($year) )? $this->year : $year;
		$month = ( is_null($month) )? $this->month : str_pad($month, 2, '0', STR_PAD_LEFT);
	
		//------------------------------------------- create first date of month
		$month_start_date = strtotime($year . "-" . $month . "-01");
		//------------------------- first day of month falls on what day of week
		$first_day_falls_on = $this->day_of_week($month_start_date);
		//----------------------------------------- find number of days in month
		$days_in_month = date("t", $month_start_date);
		//-------------------------------------------- create last date of month
		$month_end_date = strtotime($year . "-" . $month . "-" . $days_in_month);
		//----------------------- calc offset to find number of cells to prepend
		$start_week_offset = $first_day_falls_on - $this->week_start;
		$prepend = ( $start_week_offset < 0 )? 7 - abs($start_week_offset) : $first_day_falls_on - $this->week_start;
		//-------------------------- last day of month falls on what day of week
		$last_day_falls_on = $this->day_of_week($month_end_date);
		//height: 400px;
		//width: 700px;
		
		
		
		
		
	
		//------------------------------------------------- start table, caption
		$output = "<button class=".$this->button_class." onClick=\"javascript:loadPreviousMonth();\">Previous</button>&nbsp;&nbsp;";
		$output .="<button class=".$this->button_class."  onClick=\"javascript:loadCurrentMonth();\">Today</button>&nbsp;&nbsp;";
		$output .="<button class=".$this->button_class."  onClick=\"javascript:loadNextMonth();\">Next</button>&nbsp;&nbsp;";
		$output .= "<br><table height=\"". $this->height ."\" width=\"". $this->width ."\" class=\"" . $calendar_class . "\">\n";
		$output .= "<caption>" . ucfirst(strftime("%B %Y", $month_start_date)) . "</caption>\n";
		
		$col = '';
		$th = '';
		for( $i=1,$j=$this->week_start,$t=(3+$this->week_start)*86400; $i<=7; $i++,$j++,$t+=86400 ){
			$localized_day_name = gmstrftime('%A',$t);
		
			$col .= "<col class=\"" . strtolower($localized_day_name) ."\" />\n";
			$th .= "\t<th title=\"" . ucfirst($localized_day_name) ."\">" . ucfirst($localized_day_name) ."</th>\n";
			$j = ( $j == 7 )? 0 : $j;
		}
		
		//------------------------------------------------------- markup columns
		$output .= $col;
		
		//----------------------------------------------------------- table head
		$output .= "<thead>\n";
		$output .= "<tr>\n";
		
		$output .= $th;
		
		$output .= "</tr>\n";
		$output .= "</thead>\n";
		
		//---------------------------------------------------------- start tbody
		$output .= "<tbody>\n";
		$output .= "<tr>\n";
		
		//---------------------------------------------- initialize week counter
		$weeks = 1;
		
		//--------------------------------------------------- pad start of month
		
		//------------------------------------ adjust for week start on saturday
		for($i=1;$i<=$prepend;$i++){
			$output .= "\t<td class=\"pad\">&nbsp;</td>\n";
		}
		
		//--------------------------------------------------- loop days of month
		for($day=1,$cell=$prepend+1; $day<=$days_in_month; $day++,$cell++){
			
			/*
			if this is first cell and not also the first day, end previous row
			*/
			if( $cell == 1 && $day != 1 ){
				$output .= "<tr>\n";
			}
			
			//-------------- zero pad day and create date string for comparisons
			$day = str_pad($day, 2, '0', STR_PAD_LEFT);
			$day_date = $year . "-" . $month . "-" . $day;
			
			//-------------------------- compare day and add classes for matches
			if( $this->mark_today == TRUE && $day_date == date("Y-m-d") ){
				$classes[] = $this->today_date_class;
				$bgcolor = $this->todayDateBgColor;
			}
			
			if( $this->mark_selected == TRUE && $day_date == $this->date ){
				$classes[] = $this->selected_date_class;
				$bgcolor = $this->selectedDateBgColor;		
			}
			
			
			if( is_array($this->highlighted_dates) ){
				if( in_array($day_date, $this->highlighted_dates) ){
					$classes[] = $this->default_highlighted_class;
					$bgcolor = $this->highlightedDatesBgColor;
				}
			}
	
			if( is_array($this->not_available_dates) ){
				if( in_array($day_date, $this->not_available_dates) ){
					$classes[] = $this->not_available_class;
					$bgcolor = $this->notAvailableDatesBgColor;
				}
			}
			
			list($year, $month, $day) = split('[/.-]', $day_date);
			$day_name = strtolower(date("l", mktime(0, 0, 0, $month, $day, $year)));
			
			if( is_array($this->not_available_day) ){
				if( in_array($day_name, $this->not_available_day) ){
					$classes[] = $this->not_available_day_class;
					$bgcolor = $this->notAvailableDayBgColor;
					
					$not_available_day= 'true';
				}else
				{
					$not_available_day= 'false';
				}
			}
			
			if( $this->mark_passed == TRUE && $day_date < date("Y-m-d") ){
				$classes[] = $this->passed_date_class;
				$bgcolor = $this->passedDateBgColor;
			}		
			
			//----------------- loop matching class conditions, format as string
			if( isset($classes) ){
				$day_class = ' class="';
				foreach( $classes AS $value ){
					$day_class .= $value . " ";
				}
				$day_class = substr($day_class, 0, -1) . '"';
			} else {
				$day_class = '';
			}
			
			//---------------------------------- start table cell, apply classes
			$output .= "\t<td" . $day_class . " bgcolor=\"" . $bgcolor . "\" title=\"" . ucwords(strftime("%A, %B %e, %Y", strtotime($day_date))) . "\">";
			$bgcolor='';
			//----------------------------------------- unset to keep loop clean
			unset($day_class, $classes);
			
			//-------------------------------------- conditional, start link tag 
			switch( $this->link_days ){
				case 0 :
					$output .= $day;
				break;
				
				case 1 :
					if( empty($this->formatted_link_to) ){
						$output .= "<a href=\"" . $this->link_to . "?date=" . $day_date . "\">" . $day . "</a>";
					} else {
						$output .= "<a href=\"" . strftime($this->formatted_link_to, strtotime($day_date)) . "\">" . $day . "</a>";
					}
				break;
				
				case 2 :
					if( is_array($this->highlighted_dates) ){
						if( in_array($day_date, $this->highlighted_dates) ){
							if( empty($this->formatted_link_to) ){
								$output .= "<a href=\"" . $this->link_to . "?date=" . $day_date . "\">";
							} else {
								$output .= "<a href=\"" . strftime($this->formatted_link_to, strtotime($day_date)) . "\">";
							}
						}
					}
					
					$output .= $day;
					
					if( is_array($this->highlighted_dates) ){
						if( in_array($day_date, $this->highlighted_dates) ){
							if( empty($this->formatted_link_to) ){
								$output .= "</a>";
							} else {
								$output .= "</a>";
							}
						}
					}
				break;
				
				case 3 :
					if( $this->mark_passed == TRUE && $day_date < date("Y-m-d") ){
							$output .= $day;
						
					}else {
						if( is_array($this->not_available_dates) ){
							if($not_available_day!='true')
							{
								if( in_array($day_date, $this->not_available_dates) ){
										
								}else {
									if( empty($this->formatted_link_to) ){
										$output .= "<a href=\"" . $this->link_to . "?date=" . $day_date . "\">";
									} else {
										$output .= "<a href=\"" . strftime($this->formatted_link_to, strtotime($day_date)) . "\">";
									}
								}
							}
						}
						
						$output .= $day;
							
						if( is_array($this->not_available_dates) ){
							if($not_available_day!='true')
							{
								if( in_array($day_date, $this->not_available_dates) ){
									
								}else {
									if( empty($this->formatted_link_to) ){
										$output .= "</a>";
									} else {
										$output .= "</a>";
									}
								}
							}
						}
						
						$not_available_day='';
				
					}	
				break;
			}
			
			//------------------------------------------------- close table cell
			$output .= "</td>\n";
			
			//------- if this is the last cell, end the row and reset cell count
			if( $cell == 7 ){
				$output .= "</tr>\n";
				$cell = 0;
			}
			
		}
		
		//----------------------------------------------------- pad end of month
		if( $cell > 1 ){
			for($i=$cell;$i<=7;$i++){
				$output .= "\t<td class=\"pad\">&nbsp;</td>\n";
			}
			$output .= "</tr>\n";
		}
		
		//--------------------------------------------- close last row and table
		$output .= "</tbody>\n";
		$output .= "</table>\n";
		
		//--------------------------------------------------------------- return
		return $output;
		
	}
	
}
?>