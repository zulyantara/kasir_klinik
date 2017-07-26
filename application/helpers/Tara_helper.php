<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('tara_humanize'))
{
    function tara_humanize($str)
    {
        return strtoupper(preg_replace('/[_]+/', ' ', strtolower(trim($str))));
    }
}

if ( ! function_exists('arr_bulan_indonesia'))
{
    function arr_bulan_indonesia()
    {
        return array("1"=>"Januari","2"=>"Pebruari","3"=>"Maret","4"=>"April","5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober","11"=>"Nopember","12"=>"Desember");
    }
}

if ( ! function_exists('arr_hari_indonesia'))
{
    function arr_hari_indonesia()
    {
        return array(1=>"Senin",2=>"Selasa",3=>"Rabu",4=>"Kamis",5=>"Jumat",6=>"Sabtu",7=>"Minggu");
    }
}

/**
 * Terbilang Helper
 *
 * @package	CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author	Gede Lumbung
 * @link	http://gedelumbung.com
 */

if ( ! function_exists('number_to_words'))
{
	function number_to_words($number)
	{
		$before_comma = trim(to_word($number));
		// $after_comma = trim(comma($number));
		// return ucwords($results = $before_comma.' koma '.$after_comma);
        return ucwords($results = $before_comma.' rupiah');
	}

	function to_word($number)
	{
		$words = "";
		$arr_number = array(
		"",
		"satu",
		"dua",
		"tiga",
		"empat",
		"lima",
		"enam",
		"tujuh",
		"delapan",
		"sembilan",
		"sepuluh",
		"sebelas");

		if($number<12)
		{
			$words = " ".$arr_number[$number];
		}
		else if($number<20)
		{
			$words = to_word($number-10)." belas";
		}
		else if($number<100)
		{
			$words = to_word($number/10)." puluh ".to_word($number%10);
		}
		else if($number<200)
		{
			$words = "seratus ".to_word($number-100);
		}
		else if($number<1000)
		{
			$words = to_word($number/100)." ratus ".to_word($number%100);
		}
		else if($number<2000)
		{
			$words = "seribu ".to_word($number-1000);
		}
		else if($number<1000000)
		{
			$words = to_word($number/1000)." ribu ".to_word($number%1000);
		}
		else if($number<1000000000)
		{
			$words = to_word($number/1000000)." juta ".to_word($number%1000000);
		}
		else
		{
			$words = "undefined";
		}
		return $words;
	}

	function comma($number)
	{
		$after_comma = stristr($number,',');
		$arr_number = array(
		"nol",
		"satu",
		"dua",
		"tiga",
		"empat",
		"lima",
		"enam",
		"tujuh",
		"delapan",
		"sembilan");

		$results = "";
		$length = strlen($after_comma);
		$i = 1;
		while($i<$length)
		{
			$get = substr($after_comma,$i,1);
			$results .= " ".$arr_number[$get];
			$i++;
		}
		return $results;
	}
}

if ( ! function_exists('hitung_umur'))
{
    function hitung_umur($tgl)
    {
        $t_umur = date("Y") - date("Y",strtotime($tgl));
        if (date("n",strtotime($tgl)) > date("n"))
        {
            $t_umur = date("Y") - date("Y",strtotime($tgl)) - 1;
        }

        if (date("n",strtotime($tgl)) - date("n") == 0)
        {
            $b_umur = 0;
        }
        else
        {
            if (date("n") > date("n",strtotime($tgl)))
            {
                $b_umur =  date("n") - date("n",strtotime($tgl));
            }
            else
            {
                $b_umur = 12 - (date("n",strtotime($tgl)) - date("n"));
            }
        }
        return $t_umur." Tahun ".$b_umur." Bulan";
    }
}

/* End of file tara_helper.php */
/* Location: ./application/helpers/tara_helper.php */
?>
