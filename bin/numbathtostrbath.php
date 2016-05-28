<?php 
	function TH_LessThan_1000000($M) {
		$LM = strlen($M);
		$TH_LessThan_1000000 = "";
		for ($CM = 0; $CM < $LM; $CM++) {
			if ($LM - $CM == 2) {
				$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 2);
			}
			else {
				if ($LM - $CM == 1) {
					if (substr($M, $CM, 1) == "1") {
						if ($LM > 1) {
							if (substr($M, $CM - 1, 1) != "0") {
								$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 1);
							}
							else {
								$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 0);
							}
						}
						else {
							$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 0);
						}
					}
					else {
						$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 0);
					}
				}
				else {
					$TH_LessThan_1000000 = $TH_LessThan_1000000 . Retn_TH(substr($M, $CM, 1), 0);
				}
			}
			
			switch ($LM - $CM) {
				case 7:
					if (substr($M, $CM, 1) != 0) {$TH_LessThan_1000000 = $TH_LessThan_1000000 . "ล้าน";}
					break;
				case 6:
					if (substr($M, $CM, 1) != 0) {$TH_LessThan_1000000 = $TH_LessThan_1000000 . "แสน";}
					break;
				case 5:
					if (substr($M, $CM, 1) != 0) {$TH_LessThan_1000000 = $TH_LessThan_1000000 . "หมื่น";}
					break;
				case 4:
					if (substr($M, $CM, 1) != 0) {$TH_LessThan_1000000 = $TH_LessThan_1000000 . "พัน";}
					break;
				case 3:
					if (substr($M, $CM, 1) != 0) {$TH_LessThan_1000000 = $TH_LessThan_1000000 . "ร้อย";}
					break;
				case 2:
					if (substr($M, $CM, 1) != 0) {
						if (substr($TH_LessThan_1000000, -3) != "สิบ") {
							$TH_LessThan_1000000 = $TH_LessThan_1000000 . "สิบ";
						}
					}
					break;
			}
		}
		return $TH_LessThan_1000000;
	}
	
	function Retn_TH($PV, $Pos) {
		$Retn_TH = "";
		switch ($PV) {
			case 1:
				if ($Pos == 2) {
					$Retn_TH = "สิบ";
					$Pos2_S = "สิบ";
				}
				else {
					if ($Pos == 1) {
						$Retn_TH = "เอ็ด";
					}
					else {
						$Retn_TH = "หนึ่ง";
					}
				}
				break;
			case 2:
				if ($Pos == 2) {
					$Retn_TH = "ยี่";
				}
				else {
					$Retn_TH = "สอง";
				}
				break;
			case 3: $Retn_TH = "สาม"; break;
			case 4: $Retn_TH = "สี่"; break;
			case 5: $Retn_TH = "ห้า"; break;
			case 6: $Retn_TH = "หก"; break;
			case 7: $Retn_TH = "เจ็ด"; break;
			case 8: $Retn_TH = "แปด"; break;
			case 9: $Retn_TH = "เก้า"; break;
		}	
		return $Retn_TH;
	}

	function Cnv_TH_Money($intMoney) {
		if (!is_numeric($intMoney)) {
			echo "Invalid Numberic";
			exit;
		}
		if ($intMoney == 0) {
			$Cnv_Eng_Money = "ศูนย์บาทถ้วน";
			return $Cnv_Eng_Money;
			exit;
		}
		$intMoney = number_format($intMoney, 2, '.', '');
		$S = substr($intMoney, -2);
		$M = substr($intMoney, 0, strlen($intMoney) - 3);
		$MLen = strlen($M);
		$Cnv_TH_Money = "";
		if ($MLen <= 7) {
			$Cnv_TH_Money = TH_LessThan_1000000($M);
		}
		else {
			//---------- < 10,000,000,000,000 ------------
			$Cnv_TH_Money = $Cnv_TH_Money . TH_LessThan_1000000(substr($M, 0, $MLen - 6));
			$Cnv_TH_Money = $Cnv_TH_Money . "ล้าน";
			if (substr($M, -6) != "000000") {
				$Cnv_TH_Money = $Cnv_TH_Money . TH_LessThan_1000000(substr($M, -6));
			}
		}
		
		$Cnv_TH_Money = $Cnv_TH_Money . "บาท";
		
		if ($S != "00") {
			$Cnv_TH_Money = $Cnv_TH_Money . TH_LessThan_1000000($S);
			$Cnv_TH_Money = $Cnv_TH_Money . "สตางค์";
		}
		else {
			$Cnv_TH_Money = $Cnv_TH_Money . "ถ้วน";
		}
		return $Cnv_TH_Money;
	}

	function Cnv_Eng_Money($intMoney) {
		if (!is_numeric($intMoney)) {
			echo "Invalid Numberic";
			exit;
		}
		if ($intMoney == 0) {
			$Cnv_Eng_Money = "ZERO ONLY";
			return $Cnv_Eng_Money;
			exit;
		}
		$intMoney = number_format($intMoney, 2, '.', '');
		$S = substr($intMoney, -2);
		$M = substr($intMoney, 0, strlen($intMoney) - 3);
		$MLen = strlen($M);
		$Cnv_Eng_Money = "";
		if ($MLen < 5) {
			$Cnv_Eng_Money = ENG_LessThan_10000($M);
		}
		else {
			if ($MLen < 7) {
				$Cnv_Eng_Money = ENG_LessThan_10000(substr($M, 0, $MLen - 3));
				$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "THOUSAND";
				$Cnv_Eng_Money = $Cnv_Eng_Money . ENG_LessThan_10000(substr($M, -3));
			}
			else {
				if ($MLen < 10) {
					$Cnv_Eng_Money = ENG_LessThan_10000(substr($M, 0, $MLen - 6));
					$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "MILLION";
					if (substr($M, $MLen - 6, 3) != "000") {
						$Cnv_Eng_Money = $Cnv_Eng_Money . ENG_LessThan_10000(substr($M, $MLen - 6, 3));
						$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "THOUSAND";
					}
					if (substr($M, -3) != "000") {
						$Cnv_Eng_Money = $Cnv_Eng_Money . ENG_LessThan_10000(substr($M, -3));
					}
				}
				else {
				}
			}
		}
		$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "";
		if ($S != "00") {
			$Cnv_Eng_Money = $Cnv_Eng_Money . " AND " . ENG_LessThan_100($S);
			$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "";
		}
		else {
			$Cnv_Eng_Money = $Cnv_Eng_Money . " " . "ONLY";
		}
		return $Cnv_Eng_Money;
	}
	
	function ENG_LessThan_10000($M) {
		$ENG_LessThan_10000 = "";
		$MLen = strlen($M);
		if ($MLen == 1) {$ENG_LessThan_10000 = " " . Retn_Eng($M);}
		if ($MLen == 2) {$ENG_LessThan_10000 = " " . Eng_LessThan_100($M);}
		if ($MLen > 2) {
			for ($CountM = 0; $CountM < $MLen - 2; $CountM++) {
				if (substr($M, $CountM, 1) != 0) {
					$ENG_LessThan_10000 = $ENG_LessThan_10000 . " " . Retn_Eng(substr($M, $CountM, 1));
				}
				switch ($MLen - $CountM) {
					case 4:
						if (substr($M, $CountM, 1) != 0) {$ENG_LessThan_10000 = $ENG_LessThan_10000 . " " . "THOUSAND";}
						break;
					case 3:
						if (substr($M, $CountM, 1) != 0) {$ENG_LessThan_10000 = $ENG_LessThan_10000 . " " . "HUNDRED";}
						break;
				}
			}
			$ENG_LessThan_10000 = $ENG_LessThan_10000 . " " . ENG_LessThan_100(substr($M, -2));
		}
		
		return $ENG_LessThan_10000;
	}
	
	function ENG_LessThan_100($M) {
		$ENG_LessThan_100 = "";
		if (substr($M, 0, 1) == 0) {
			$ENG_LessThan_100 = Retn_Eng($M, -1);
		}
		else {
			if (substr($M, 0, 1) > 1) {
				$ENG_LessThan_100 = Between20To90(substr($M, 0, 1)) . " " . Retn_Eng(substr($M, -1));
			}
			else {
				$ENG_LessThan_100 = Between10To19(substr($M, -1));
			}
		}
		return $ENG_LessThan_100;
	}
	
	function Retn_Eng($PV) {
		$Retn_Eng = "";
		switch ($PV) {
			case 1: $Retn_Eng = "ONE"; break;
			case 2: $Retn_Eng = "TWO"; break;
			case 3: $Retn_Eng = "THREE"; break;
			case 4: $Retn_Eng = "FOUR"; break;
			case 5: $Retn_Eng = "FIVE"; break;
			case 6: $Retn_Eng = "SIX"; break;
			case 7: $Retn_Eng = "SEVEN"; break;
			case 8: $Retn_Eng = "EIGHT"; break;
			case 9: $Retn_Eng = "NINE"; break;			
		}	
		return $Retn_Eng;
	}
	
	function Between20To90($V) {
		$Between20To90 = "";
		switch ($V) {
			case 2: $Between20To90 = "TWENTY"; break;
			case 3: $Between20To90 = "THIRTY"; break;
			case 4: $Between20To90 = "FORTY"; break;
			case 5: $Between20To90 = "FIFTY"; break;
			case 6: $Between20To90 = "SIXTY"; break;
			case 7: $Between20To90 = "SEVENTY"; break;
			case 8: $Between20To90 = "EIGHTY"; break;
			case 9: $Between20To90 = "NINETY"; break;
		}		
		return $Between20To90;
	}
	
	function Between10To19($V) {
		$Between10To19 = "";
		switch ($V) {
			case 0: $Between10To19 = "TEN"; break;
			case 1: $Between10To19 = "ELEVEN"; break;
			case 2: $Between10To19 = "TWELVE"; break;
			case 3: $Between10To19 = "THIRTEEN"; break;
			case 4: $Between10To19 = "FOURTEEN"; break;
			case 5: $Between10To19 = "FIFTEEN"; break;
			case 6: $Between10To19 = "SIXTEEN"; break;
			case 7: $Between10To19 = "SEVENTEEN"; break;
			case 8: $Between10To19 = "EIGHTEEN"; break;
			case 9: $Between10To19 = "NINETEEN"; break;
		}	
		return $Between10To19;
	}
	//echo Cnv_Eng_Money(999999999); // ได้ถึง หลัก ร้อยล้าน
	//echo "<br>";
	//echo Cnv_TH_Money(8543751678545);

?>