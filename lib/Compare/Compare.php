<?php
	
	class Compare {
		
		public $separatorSentences;
		public $separatorWords;

		function __construct() {
			$this->separatorSentences = '<!--split_sentences-->';
			$this->separatorWords = ' ';
		}
		
		public function clearText($text) {
			$text = trim($text);
			$text = preg_replace('/[ ]+/', ' ', $text);
			$text = str_replace("\r", "", $text);
			$text = preg_replace('/([\n\?\!\.]+)\s+(?=[\n\?\!\.]+)/', '$1', $text);
			return $text;
		}
		
		public function getSentencesArray($text) {
			$text = str_replace("\n", $this->separatorSentences, $text);
			$text = preg_replace('/([\?\!\.]+)\s+/', '$1'.$this->separatorSentences, $text);
			$arr = array_map('trim', explode($this->separatorSentences, $text));
			return $arr;
		}
		
		public function getTextDiffPercent($textA, $textB) {
			$sim = similar_text($textA, $textB, $percent);
			return $percent;
		}

		public function levenshteinAlt($s,$t) {
			$m = strlen($s);
			$n = strlen($t);
			
			for($i=0;$i<=$m;$i++) $d[$i][0] = $i;
			for($j=0;$j<=$n;$j++) $d[0][$j] = $j;
			
			for($i=1;$i<=$m;$i++) {
				for($j=1;$j<=$n;$j++) {
					$c = ($s[$i-1] == $t[$j-1])?0:1;
					$d[$i][$j] = min($d[$i-1][$j]+1,$d[$i][$j-1]+1,$d[$i-1][$j-1]+$c);
				}
			}
			
			return $d[$m][$n];
		}
			
		public function getLevenshteinPercent($textA, $textB) {
			$percent = ( 1 - $this->levenshteinAlt($textB, $textA) / max(strlen($textB), strlen($textA)) ) * 100;
			return $percent;
		}
		
		public function getIndexSimmilarSentencesInArray($text, $arr, $index, $lastIndex = null, $lastOrigIndex = null, $lastAddedIndex = null) {
			$simm = [];
			$tempPercentArr = [];
			foreach ($arr as $key => $value) {
				$percent = $this->getLevenshteinPercent($value, $text);
				$tempPercentArr[$key] = $percent;
			}

			$maxPercent = 0;
			$maxPercentKey = null;
			foreach ($tempPercentArr as $key => $value) {
				if ($value > $maxPercent) {
					$maxPercent = $value;
					$maxPercentKey = $key;
				}
			}
			$testIndex = isset($lastAddedIndex) ? $lastAddedIndex : (isset($lastOrigIndex) ? $lastOrigIndex : 0);
			if ( $maxPercent == 100 && $testIndex <= $maxPercentKey ) {
				$simm['index'] = $maxPercentKey;
				$simm['action'] = 'none';
				$simm['orig_index'] = $maxPercentKey;
				$simm['orig'] = $arr[$maxPercentKey];
				$simm['lastOrigIndex'] = $maxPercentKey;
				$simm['lastIndex'] = $index;
				$simm['lastAddedIndex'] = null;
			} elseif ( $maxPercent > 50 && $testIndex <= $maxPercentKey ) {
				$simm['index'] = $maxPercentKey;
				$simm['action'] = 'changed';
				$simm['orig_index'] = $maxPercentKey;
				$simm['orig'] = $arr[$maxPercentKey];
				$simm['lastOrigIndex'] = $maxPercentKey;
				$simm['lastIndex'] = $index;
				$simm['lastAddedIndex'] = null;
			} else {
				$simm['index'] = isset($lastAddedIndex) ? $lastAddedIndex : (isset($lastOrigIndex) ? $lastOrigIndex : 0);
				$simm['action'] = 'added';
				$simm['orig_index'] = null;
				$simm['orig'] = null;
				$simm['lastOrigIndex'] = null;
				$simm['lastIndex'] = $index;
				$simm['lastAddedIndex'] = isset($lastAddedIndex) ? $lastAddedIndex : (isset($lastOrigIndex) ? $lastOrigIndex : 0);
			}
			$simm['maxPercent'] = $maxPercent;
			return $simm;
		}
		
		public function sortCompareSentences($arrA, $arrB) {
			$tempArrA = $arrA;
			$lastIndex = 0;
			$lastOrigIndex = null;
			$lastAddedIndex = null;

			$diffArrB = [];
			foreach ($arrB as $key => $value) {
				$simm = $this->getIndexSimmilarSentencesInArray($value, $tempArrA, $key, $lastIndex, $lastOrigIndex, $lastAddedIndex);
				$diffArrB[$key]['index'] = $simm['index'];
				$diffArrB[$key]['value'] = $value;
				$diffArrB[$key]['action'] = $simm['action'];
				$diffArrB[$key]['orig'] = $simm['orig'];
				$diffArrB[$key]['orig_index'] = $simm['orig_index'];

				$lastIndex = $simm['lastIndex'];
				$lastOrigIndex = $simm['lastOrigIndex'];
				$lastAddedIndex = $simm['lastAddedIndex'];
			}
			$delKeys = array_diff(array_column($diffArrB, 'orig_index'), array(''));
			foreach($delKeys as $value) {
				unset($tempArrA[$value]);
			}
			$diffArrA = [];
			foreach($tempArrA as $key => $value) {
				$diffArrA[$key]['index'] = $key == 0 ? $key - 1 : $key;
				$diffArrA[$key]['value'] = $value;
				$diffArrA[$key]['action'] = 'deleted';
				$diffArrA[$key]['orig'] = $value;
				$diffArrA[$key]['orig_index'] = $key;
			}
			$diffArrAB = array_merge($diffArrB, $diffArrA);
			$temp = $diffArrAB;
			uksort($diffArrAB, function ($a,$b) use ($temp) {
				if ($a == $b && $temp[$a]['index'] == $temp[$b]['index']) {
					return 0;
				} elseif ($a == $b && $temp[$a]['index'] < $temp[$b]['index']) {
					return -1;
				} elseif ($a < $b && $temp[$a]['index'] == $temp[$b]['index']) {
					return 0;
				} elseif ($a < $b && $temp[$a]['index'] < $temp[$b]['index']) {
					return -1;
				} elseif ($a > $b && $temp[$a]['index'] < $temp[$b]['index']) {
					return -1;
				} else {
					return 1;
				}
			});
			unset($temp);
			return $diffArrAB;
		}
		
		public function wrapSentences($arr) {
			$arrWrap = [];
			foreach ($arr as $value) {
				$arrWrap[] = '<span class="'.$value['action'].'" data-value="'.$value['value'].'" data-orig="'.$value['orig'].'">'.$value['value'].'</span>';
			}
			return implode(' ', $arrWrap);
		}

		public function startCompare($textA, $textB) {
			$arrSentencestextA = $this->getSentencesArray( $this->clearText($textA) );
			$arrSentencestextB = $this->getSentencesArray( $this->clearText($textB) );
			$sortedArr = $this->sortCompareSentences($arrSentencestextA, $arrSentencestextB);
			$result = $this->wrapSentences($sortedArr);
			return $result;
		}
		
		
	}		