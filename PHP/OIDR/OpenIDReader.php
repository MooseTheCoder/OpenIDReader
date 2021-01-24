<?php

class OpenIDReader{
	public $ScanString;
	public $ReadType;
	public $ReadModels;
	public $Debug;

	public function __construct(){
		$this->ReadModels = [
			'passport'=>[
				'regex'=>"/([A-Z])([A-Z0-9<])([A-Z]{3})([A-Z<]{39})\s+([A-Z0-9<]{9})([0-9])([A-Z]{3})([0-9]{6})([0-9])([MF<])([0-9]{6})([0-9])([A-Z0-9<]{14})([0-9])([0-9])/",
				'postFind'=>function($Data){
					if(count($Data) !== 16){
						return 'Invalid MRZ';
					}
					$ReturnValue = [
						'issuer'=>$Data[3],
						'passport_number'=>$Data[5],
						'gender'=>$Data[10],
						'dob'=>DateTime::createFromFormat('ymd', $Data[8])->format('Y-m-d'),
						'expiry'=>DateTime::createFromFormat('ymd', $Data[11])->format('Y-m-d'),
						'surname'=>implode(' ', explode('<',explode('<<', $Data[4])[0])),
						'forename'=>implode(' ', explode('<',explode('<<',$Data[4])[1]))
					];
					return $ReturnValue;
				}
			]
		];
	}

	public function Read(){
		if(!isset($this->ReadModels[$this->ReadType])){
			return ($this->Debug ? 'The model was not found' : false);
		}
		$Model = $this->ReadModels[$this->ReadType];
		$Result = [];
		$RegexResponse;
		preg_match($Model['regex'], $this->ScanString, $RegexResponse);
		$Result = $Model['postFind']($RegexResponse);
		return $Result;
	}

	public function SetReadType($ReadType){
		$this->ReadType = $ReadType;
	}

	public function SetScanString($ScanString){
		$this->ScanString = $ScanString;
	}

	public function Debug($Debug){
		$this->Debug = $Debug;
	}
}