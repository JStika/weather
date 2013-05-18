<?php
class WeatherHelper {
	//$this->WeatherUrl = 'http://xoap.weather.com/weather/local/' . $this->locid . '?cc=*&dayf=' . $this->numdays . '&unit=' . $this->ut . '&prod=xoap&par=' . $this->parid . '&key=' . $this->key;
	private $WeatherUrl	= 'http://xoap.weather.com/weather/local/';
	private $LocID = null;
	private $ForeDays = 1;
	private $Unit = 'm';
	private $ParID = null;
	private $LicKey = null;
	private $FORECAST = null;
	private $Current = null;
	private $Future = null;
	private $CacheTime = 3600;
	private $Error = null;
	private $Lang = null;
	function __construct($locid, $foredays, $unit, $parid, $lickey, $lang) {
		if ($locid != '' && $parid != '' && $lickey != '') {
			$this->LocID = $locid;
			$this->ForeDays = $foredays;
			$this->Unit = $unit;
			$this->ParID = $parid;
			$this->LicKey = $lickey;
			$this->Lang = $lang;
			$this->getWeather();
		}
		else {
			$this->setError(_t('widgets_weather.PARAMSERROR',"Parameters not valid"));
		}
	}
	private function getWeather() {
		$params = array(
			"cc" => "*",
			"dayf" => $this->ForeDays,
			"unit" => $this->Unit,
			"prod" => "xoap",
			"par" => $this->ParID,
			"key" => $this->LicKey
		);
		$service = new RestfulService($this->WeatherUrl . $this->LocID, $this->CacheTime);
		$service->setQueryString($params);
		$response = $service->request();
		//print_r(json_decode($response->getBody()));
		if ($response->isError()) {
			$this->setError($response->getStatusDescription());
		}
		else {
			$rbody = $response->getBody();
			$this->FORECAST = $this->XmlToArray($rbody);
		}
		return $this;
	}
	private function XmlToArray($data) {
		$vals = array();
		$index = array();
		$params_array = array();
		$level = array();
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);
		foreach ($vals as $xml_elem) {
			if ($xml_elem['type'] == 'open') {
				$level[$xml_elem['level']] = (array_key_exists('attributes',$xml_elem) ? array_shift($xml_elem['attributes']) : $xml_elem['tag']);
			}
			if ($xml_elem['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params_array';
				while ($start_level < $xml_elem['level']) {
					$php_stmt .= '[$level[' . $start_level.']]';
					$start_level++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval ($php_stmt);
			}
		}
		return $params_array['2.0'];
	}
	public function setError($sd) {
		$this->Error = $sd;
	}
	public function getError() {
		return $this->Error;
	}
	/**
	* C = (F-32)*5/9
	* F = C*9/5+32
	* @return float
	*/
	private function convert_FahrenheitCelsius($F = null, $C = null) {
		$converted = null;
		if ($F) $converted = round(($F - 32) * (5/9));
		if ($C) $converted = round((($C * 9) / 5) + 32);
		return $converted;
	}
	/**
	* 1 km = .6213 miles
	* 1 mile = 1.6093 km
	* @return float
	*/
	private function convert_MilesMeters($Miles = null, $Meters = null) {
		$converted = null;
		if ($Miles) $converted = round($Miles * 1.6093);
		if ($Meters) $converted = round($Meters / 0.6213);
		return $converted;
	}
	/**
	* 1 millibar = 1 hPa
	* 1 in Hg = 0.02953 hPa
	* 1 mm Hg = 25.4 in Hg = 0.750062 hPa
	* 1 lb/sq in = 0.491154 in Hg = 0.014504 hPa
	* 1 atm = 0.33421 in Hg = 0.0009869 hPa
	* @return float
	*/
	private function convert_hPaHg($hPa = null, $Hg = null) {
		$converted = null;
		if ($hPa) $converted = round(0.02953 * $hPa);
		if ($Hg) $converted = round($Hg / 0.02953,2);
		return $converted;
	}
	public function getLastUpdate() {
		$LSUP = (string) $this->FORECAST['CC']['LSUP'];
		preg_match('/(\\d+\\/\\d+\\/\\d+)\\s+(\\d+:\\d+)\\s+(\\w+)/', $LSUP, $regs);
		list ($M, $D, $Y) = explode("/", $regs[1]);
		if (strlen($Y) == 2 ) $Y = '20' . $Y;
		list ($HH,$MM) = explode(":", $regs[2]);
		if ($regs[3] == 'PM' && ($HH < 12)) {
			$HH += 12;
		}
		$dt = sprintf("%4d-%02d-%02d %02d:%02d:%02d", $Y, $M, $D, $HH, $MM, 0);
		return $dt;
	}
	public function getNextDate($fd = 0) {
		return strftime('%Y-%m-%d', mktime(0, 0, 0, date("m"), date("d")+$fd, date("Y")));
	}
	public function getRegion() {
		list ($city,$country) = explode(',',(string) $this->FORECAST[$this->LocID]['DNAM']);
		//return (preg_match("/(en)/i", $this->Lang) ? $city . ',' . $country : $city . ',' . $country );
		return $city . ',' . $country;
	}
	public function getRainChance($fd = 0) {
		return $this->FORECAST['DAYF'][$fd]['d']['PPCP'] . ' %';			
	}
	public function getTemp($fd = 0, $lowhigh = null) {
		if ($lowhigh == null) {
			$t = (string) $this->FORECAST['CC']['TMP'];
		}
		if ($lowhigh == strtolower('h')) {
			$t = (string) $this->FORECAST['DAYF'][$fd]['HI'];
		}
		if ($lowhigh == strtolower('l')) {
			$t = (string) $this->FORECAST['DAYF'][$fd]['LOW'];
		}
		return ($this->Unit == "m" ? $t . ' &deg;C' : $this->convert_FahrenheitCelsius('', $t) . ' &deg;F');
	}
	public function getFeelslike() {
		return ($this->Unit == "m" ? $this->FORECAST['CC']['FLIK'] . ' &deg;C' : $this->convert_FahrenheitCelsius('', $this->FORECAST['CC']['FLIK']) . ' &deg;F');
	}
	public function getBarometer() {
		$baro[] = (preg_match("/(m)/i", $this->Unit ) ? $this->FORECAST['CC']['BAR']['R'] . ' hPa' : $this->convert_hPaHg( $this->FORECAST['CC']['BAR']['R'],'') . ' Hg');
		if ($this->FORECAST['CC']['BAR']['D'] != 'N/A') $baro[] = _t('widgets_weather.' . strtoupper($this->FORECAST['CC']['BAR']['D']), $this->FORECAST['CC']['BAR']['D']);
		return implode(', ', $baro);
	}
	public function getDewPoint() {
		return ($this->Unit == "m" ? (int) $this->FORECAST['CC']['DEWP'] : $this->convert_FahrenheitCelsius('',(int) $this->FORECAST['CC']['DEWP'])) . ' &deg;'; 
	}
	public function getHumidity($fd = 0) {
		if ($fd == 0) {
			return $this->FORECAST['CC']['HMID'] . ' %';
		}
		else {
			return $this->FORECAST['DAYF'][$fd]['d']['HMID'] . ' %';			
		}
	}
	public function getVisibility() {
		return ($this->Unit == "m" ? (int) $this->FORECAST['CC']['VIS'] . ' km' : $this->convert_MilesMeters('', (int) $this->FORECAST['CC']['VIS'] ) . ' miles');
	}
	public function getWind($fd = 0) {
		if ($fd == 0) {
			$WS = $this->FORECAST['CC']['WIND']['S'];
			$WG = $this->FORECAST['CC']['WIND']['GUST'];
		}
		else {
			$WS = $this->FORECAST['DAYF'][$fd]['d']['WIND']['S'];
			$WG = $this->FORECAST['DAYF'][$fd]['d']['WIND']['GUST'];
		}
		if (preg_match("/(\\d+)/i",$WS)) $wind[] = (preg_match("/(m)/i",$this->Unit) ? $WS . ' km/h' : $this->convert_MilesMeters('', $WS ) . ' mph');
		if (!empty($WG) && $WG != 'N/A') $wind[] = _t('widgets_weather.GUST','gust') . ' ' . (preg_match("/(m)/i",$this->Unit) ? $WG . ' km/h' : $this->convert_MilesMeters('', $WG) . ' mph');
		return implode('<br />', $wind);
	}
	public function getDirection($fd = 0) {
		if ($fd == 0) {
			$WD = $this->FORECAST['CC']['WIND']['D'];
			$WT = $this->FORECAST['CC']['WIND']['T'];
		}
		else {
			$WD = $this->FORECAST['DAYF'][$fd]['d']['WIND']['D'];
			$WT = $this->FORECAST['DAYF'][$fd]['d']['WIND']['T'];
		}
		$d = '';
		if (!empty($WT)) $d .= _t('widgets_weather._' . strtoupper($WT), $WT);
		if ($WD != 0) {
			if (!empty($WT)) $d .= ', ';
			$d .= $WD . ' &deg;';
		}                               
		return $d;
	}
	public function getUVI() {
		$uv = array();
		if (isset($this->FORECAST['CC']['UV']['I'])) {
			$uv[] = $this->FORECAST['CC']['UV']['I'];
			$uv[] = _t('widgets_weather.UVI' . strtoupper($this->FORECAST['CC']['UV']['T']),$this->FORECAST['CC']['UV']['T']);
		}
		return implode(', ', $uv);
	}
	public function getSun($fd = 0,$p = 'r') {
		if ($p == 'r') {
			$SS = ( $fd == 0 ? $this->FORECAST[$this->LocID]['SUNR'] : $this->FORECAST['DAYF'][$fd]['SUNR'] );
		}
		if ($p == 's') {
			$SS = ( $fd == 0 ? $this->FORECAST[$this->LocID]['SUNS'] : $this->FORECAST['DAYF'][$fd]['SUNS'] );
		}
		preg_match('/(\d+)(:)(\d+)\s+(\w+)/', $SS, $regs);
		$h = $regs[1];
		$d = $regs[2];
		$m = $regs[3];
		$ap = $regs[4];
		if ($p == 's') {
			if (!preg_match("/(en)/i", $this->Lang)) {
				$h = $h + 12;
			}
		}
		$wt = $h . $d . $m;
		return $wt . ' ' . (preg_match("/(en)/i", $this->Lang) ? $ap : _t('widgets_weather.HOUR','hour'));
	}
	public function getConditions($fd = 0) {
		if ($fd == 0) {
			return $this->FORECAST['CC']['T'];
		}
		else {
			return $this->FORECAST['DAYF'][$fd]['d']['T'];
		}
	}
	public function getUrlIcon($fd = 0,$dir_icon, $size) {
		if ($fd == 0) {
			$image = (int) $this->FORECAST['CC']['ICON'];
		}
		else {
			$image = (int) $this->FORECAST['DAYF'][$fd]['d']['ICON'];		
		}
		$dir_size = 'large_icons';
		if ($size == 48) {
			$dir_size = 'small_icons';
		}
		return $dir_icon . '/' . $dir_size . '/' . $image . '.png';
	}
}