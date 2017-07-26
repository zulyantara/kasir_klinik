<?php
class Terbilang
{
	private $angka = 0;
	protected $curr = "rupiah";
	private $nol = "nol";
	private $arraySat = array("satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");
	private $arrayRat = array("ratus", "puluh", "belas");
	private $arrayRib = array("ribu", "juta", "milyar", "triliyun");

	function __construct($angka = 0)
	{
		$this->angka = $angka;
	}
	function __toString()
	{
		return $this->__($this->angka);
	}
	function _($angka)
	{
		$this->angka = $angka;
		return $this->__($this->angka);
	}
	private function __($angka)
	{
		if(!is_int($angka)) die("Anda hanya diperbolehkan memasukkan angka");
		return ($angka >0) ? $this->count($angka) : $this->not_count($angka);
	}
	private function not_count($angka)
	{
		if($this->checkThisNol($angka))
		{
			return $this->nol." ".$this->curr;
		}
		else
		{
			return "Tidak diperbolehkan memasukkan angka minus" ;
		}
	}
	private function count($angka)
	{
		$word = '';
		$indexRib = 0;
		foreach($this->getArrayOfAngka($angka) as $angka) {
			if(!$this->checkThisNol($angka))
			{
				$word = $this->getRatOfAngka($angka)
					.$this->arrayRib[$indexRib-1]. $word;
			}
			$indexRib++;
		};
		$word .= " $this->curr";
		return $word;
	}
	function without_rupiah($angka)
	{
		$word = '';
		$indexRib = 0;
		foreach($this->getArrayOfAngka($angka) as $angka) {
			if(!$this->checkThisNol($angka))
			{
				$word = $this->getRatOfAngka($angka)
					.$this->arrayRib[$indexRib-1]. $word;
			}
			$indexRib++;
		};
		//$word .= " $this->curr";
		return $word;
	}
	private function getArrayOfAngka($angka)
	{
		$angka = strrev($angka);
		$angka = str_split($angka, 3);
		foreach($angka as &$angkas)
		{
			$angkas = strrev($angkas);
			$angkas = (int) $angkas;
		}
		unset ($angkas);
		return $angka;
	}
	private function getRatOfAngka($angka)
	{
		$word = '';
		$angka = strrev($angka);
		$angka = str_split($angka);
		foreach($angka as &$angkas)
		{
			$angkas = strrev($angkas);
			$angkas = (int) $angkas;
		}
		unset ($angkas);
		$word .= ' ';
		$word .= ($this->checkThisNol($angka[2])) ? '' :
				( ($angka[2] == 1) ? 'se' :
					$this->arraySat[$angka[2]-1]." ").$this->arrayRat[0] ;
		$word .= ' ';
		if(!$this->checkThisNol($angka[1]))
		{
			if($angka[1] == 1)
			{
				if($this->checkThisNol($angka[0]) )
				{
					$word .= 'se'.$this->arrayRat[1];
				}
				else if($angka[0] == 1)
				{
					$word .= 'se'.$this->arrayRat[2];
				}
				else
				{
					$word .= $this->arraySat[$angka[2]-1]." ".$this->arrayRat[2];
				}
			}
			else
			{
				$word .= $this->arraySat[$angka[1]-1].' '.$this->arrayRat[1];
				$word .= ' ';
				$word .= $this->checkThisNol($angka[0]) ? '' :
					$this->arraySat[$angka[1]-1];
			}
		}
		else
		{
			$word .= $this->checkThisNol($angka[0]) ? '' :
				$this->arraySat[$angka[0]-1];
		}
		$word .= ' ';
		return $word;
	}
	private function checkThisNol($angka)
	{
		return ($angka == 0) ? true : false;
	}
}
