<?php
/* clase_date_span.inc.php -   Date Span Calculator.
 * Copyright (C) 2004 Victor Hugo Cardenas Varon <victor_hugo_cardenas@yahoo.com>
 *
 * This  library  is  free  software;  you can redistribute it and/or modify it
 * under  the  terms  of the GNU Library General Public License as published by
 * the  Free  Software Foundation; either version 2 of the License, or (at your
 * option) any later version.
 *
 * This  library is distributed in the hope that it will be useful, but WITHOUT
 * ANY  WARRANTY;  without  even  the  implied  warranty  of MERCHANTABILITY or
 * FITNESS  FOR  A  PARTICULAR  PURPOSE.  See  the  GNU  Library General Public
 * License for more details.
 *
 * You  should  have  received a copy of the GNU Library General Public License
 * along  with  this  library;  if  not, write to the Free Software Foundation,
 * Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
*/

/**
 * Date Span Calculator.
 * This is a class, that calculates the span between a date and the current system date
 * or between two dates.
 * it is calculated not in mere numbers, but in common phrase.
 * example: 1 year, 6 months and 12 days
 * or
 * 3 months and 1 day
 *
 *
 *
 * @author Victor Hugo Cardenas Varon <victor_hugo_cardenas@yahoo.com>
 * @version 1.0
 */
class App_Util_DateSpan {

	/**
	 * Holds the words for the days,
	 * months and years in plural and singular tense.
	 */
	var $day_plural;
	var $day_singular;
	var $month_plural;
	var $month_singular;
	var $year_plural;
	var $year_singular;

	/**
	 * Holds the word o phrase for when the span is zero, it means that the initial and final dates are the same.
	 */
	var $today;

	/**
	 * Holds the word "and" that connect two phrases.
	 */
	var $and;

	/**
	 * Holds the phrase for when the dates cannot be processed, and there is no a span to return.
	 */
	var $incorrect;

	/**
	* Constructor of class
	* @param string $language Optional. two letters especifying the language to use inside the class
	*/
	function DateSpan($language = "sp") {
		$this->set_language($language);
	}//end function date_span
//==============================================================================================

	/**
	* Calculates the span between a past date and a second earlier date.
	* if the second date is omitted, the current system date is used
	*
	* @param string $given_date string containing a past date
	* @param string $given_date_2 Optional. string containing the second date which to calculate span. must be earlier than $given_date
	* @return string $ret_value Returns the text of the span
	*/
	function  calculate_span($given_date, $given_date_2 = "") {
//		$temp = explode("-",date("Y-m-j",strtotime($given_date)));
		$temp = explode("-",$given_date);



		$past_date[0] = intval($temp[0]);	//the year
		$past_date[1] = intval($temp[1]);	//the month
		$past_date[2] = intval($temp[2]);	//the day
//		$past_date[0] = intval($temp[2]);	//the year
//		$past_date[1] = intval($temp[1]);	//the month
//		$past_date[2] = intval($temp[0]);	//the day

//		echo "<pre>";
//		print_r($past_date);
//		echo "</pre>";


		//if a second date is not given, the current system date is used
		if(!strcmp($given_date_2,""))
//			$temp = explode("-",date("Y-m-j"));
			$temp = explode("/",date("j/m/Y"));
		else
			$temp = explode("/",$given_date_2);
//			$temp = explode("-",date("Y-m-j",strtotime($given_date_2)));

		//now on, the current date is the second given date or the system date
		//however, is expected to be earlier than past date
		$current_date[0] = intval($temp[2]);
		$current_date[1] = intval($temp[1]);
		$current_date[2] = intval($temp[0]);

//		echo "<pre>";
//		print_r($current_date);
//		echo "</pre>";

		//the difference between the years, months, and days of the past dates and the current date
		$diff_years = $current_date[0] - $past_date[0];
		$diff_months = $current_date[1] - $past_date[1];
		$diff_days = $current_date[2] - $past_date[2];

		//initialize return var
		$ret_value = "";

		if($diff_years == 0) {
			if($diff_months==0) {
				if($diff_days==0)
					$ret_value = $this->today;
				elseif($diff_days > 0)
					$ret_value = $this->to_string($diff_days, "d");
			}
			elseif($diff_months > 0) {
				if($diff_days==0)
					$ret_value = $this->to_string($diff_months, "m");
				elseif($diff_days > 0)
					$ret_value = $this->to_string($diff_months, "m")." ".$this->and." ".$this->to_string($diff_days, "d");
				elseif($diff_days < 0) {
					if($diff_months == 1)
						$ret_value = $this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
					else
						$ret_value = $this->to_string($diff_months-1, "m")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
				}
			}
		}
		elseif($diff_years > 0) {
			if($diff_months==0) {
				if($diff_days==0)
					$ret_value = $this->to_string($diff_years, "y");
				elseif($diff_days > 0)
					$ret_value = $this->to_string($diff_years, "y")." ".$this->and." ".$this->to_string($diff_days, "d");
				elseif($diff_days < 0) {
					if($diff_years == 1)
						$ret_value = "11 ".$this->month_plural." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
					else
						$ret_value = $this->to_string($diff_years-1, "y").", 11 ".$this->month_plural." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
				}
			}
			elseif($diff_months > 0) {
				if($diff_days==0)
					$ret_value = $this->to_string($diff_years, "y")." ".$this->and." ".$this->to_string($diff_months, "m");
				elseif($diff_days > 0)
					$ret_value = $this->to_string($diff_years, "y").", ".$this->to_string($diff_months, "m")." ".$this->and." ".$this->to_string($diff_days, "d");
				elseif($diff_days < 0) {
					if(($diff_months) == 1)
						$ret_value = $this->to_string($diff_years, "y")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
					else
						$ret_value = $this->to_string($diff_years, "y").", ".$this->to_string($diff_months-1, "m")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
				}
			}
			elseif($diff_months < 0) {
				if($diff_years == 1) {
					if($diff_days==0)
						$ret_value = $this->to_string(12 - $past_date[1] + $current_date[1], "m");
					elseif($diff_days > 0)
						$ret_value = $this->to_string(12 - $past_date[1] + $current_date[1], "m")." ".$this->and." ".$this->to_string($diff_days, "d");
					elseif($diff_days < 0)
						$ret_value = $this->to_string(11 - $past_date[1] + $current_date[1], "m")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
				}
				else {
					if($diff_days==0)
						$ret_value = $this->to_string($diff_years-1, "y")." ".$this->and." ".$this->to_string(12 - $past_date[1] + $current_date[1], "m");
					elseif($diff_days > 0)
						$ret_value = $this->to_string($diff_years-1, "y").", ".$this->to_string(12 - $past_date[1] + $current_date[1], "m")." ".$this->and." ".$this->to_string($diff_days, "d");
					elseif($diff_days < 0)
						if(($diff_months) == -11)
							$ret_value = $this->to_string($diff_years-1, "y")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
						else
							$ret_value = $this->to_string($diff_years-1, "y").", ".$this->to_string(11 - $past_date[1] + $current_date[1], "m")." ".$this->and." ".$this->to_string($this->days_in_month($current_date[1]-1,$current_date[0]) - $past_date[2] + $current_date[2], "d");
				}
			}
		}

		if(empty($ret_value))
			return  $this->incorrect;
		if($ret_value == $this->today)
			return $ret_value;

		return "hace ".$ret_value;
	}//end function calculate_span
//==============================================================================================
	/**
	* Converts a integer value into a string which concatenate with the unit in plural o singular
	*
	* @param integer $value numeric integer value
	* @param string $unit string the unit to concatenate.
			"d" - days
			"m" - months
			"y" - years
	* @return string $ret the $value converted to string and the unit corresponding
	*/
	function to_string($value,$unit) {
		$ret = strval($value)." ";
		switch($unit) {
			case "y":
				$ret .= ($value==1)?$this->year_singular:$this->year_plural;
				break;
			case "m":
				$ret .= ($value==1)?$this->month_singular:$this->month_plural;
				break;
			case "d":
				$ret .= ($value==1)?$this->day_singular:$this->day_plural;
				break;
		}
		return $ret;
	}//end function to_string
//==============================================================================================
	/**
	* it determine if a given year is leap or not
	*
	* @param integer $year the year to determine
	* @return boolean TRUE if the year is leap, FALSE otherwise
	*/
	function is_leapyear($year) {
		if ($year%4 == 0) {
			if ($year%100 == 0 && $year%400 <>0)
				return false;
			else
				return true;
		}
		else
			return false;
	}//end function is_leapyear
//==============================================================================================
	/**
	* it determine the number of days in a given month
	*
	* @param integer $month the month to determine the number of days
	* @param integer $year the year of the used month (is used to know if the year is leap or not)
	* @return integer the number of days of the month
	*/
	function days_in_month($month,$year) {
		if($month==0) $month=12;
		$ndays = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		if ($month==2 && $this->is_leapyear($year)) {
			return 29;
		}
		else return $ndays[$month-1];
	}//end function days_in_month
//==============================================================================================
	/**
	* it set the language to use in the texdt messages of the class
	*
	* @param string $language Optional. two letters of the language to use.
			the language must be implemented to be used, by now, it supports
			 english (en),
			 german (de),
			 french (fr)
			 spanish (sp), spanish is by default.
	*/
	function set_language($language = "sp") {
		switch($language) {
			//*** French language added thanks to "Benoit Dausse"
			case "fr":
				$this->day_plural = "jours";
				$this->day_singular = "jour";
				$this->month_plural = "mois";
				$this->month_singular = "mois";
				$this->year_plural = "ans";
				$this->year_singular = "an";

				$this->today = "aujourd'hui";
				$this->and = "et";
				$this->incorrect = "La premi�re date donn�e n'est pas inf�rieure � la date actuelle ou la seconde date donn�e.";
				break;

			//*** german terms added thanks to "J�rgen Fehr" and "jo t"
			case "de":
				$this->day_plural = "Tage";
				$this->day_singular = "Tag";
				$this->month_plural = "Monate";
				$this->month_singular = "Monat";
				$this->year_plural = "Jahre";
				$this->year_singular = "Jahr";

				$this->today = "heute";
				$this->and = "und";
				$this->incorrect = "Das erste angegebene Datum ist nicht kleiner als das aktuelle oder das zweite angegebene Datum.";
				break;
			case "en":
				$this->day_plural = "days";
				$this->day_singular = "day";
				$this->month_plural = "months";
				$this->month_singular = "month";
				$this->year_plural = "years";
				$this->year_singular = "year";

				$this->today = "today";
				$this->and = "and";
				$this->incorrect = "The first given date is not lesser than current or than second given date";
				break;

			case "sp":
			default:
				$this->day_plural = "dias";
				$this->day_singular = "dia";
				$this->month_plural = "meses";
				$this->month_singular = "mes";
				$this->year_plural = "años";
				$this->year_singular = "año";

				$this->today = "hoy mismo";
				$this->and = "y";
				$this->incorrect = "La fecha dada no es mayor que la fecha actual";
				//$this->incorrect = "La primera fecha dada no es menor que la fecha actual o que la segunda fecha dada";
		}//end switch
	}//end function set_language
}//end class date_span