<?php
/**
 * Weather widget type shows an weather from weather.com URL into the page.
 */
class WeatherWidget extends Widget {
	public static $title = "Forecast weather";
	public static $cmsTitle = "Forecast weather";
	static $description = "Displays weather forecast from weather.com";
	static $db = array(
		'LicKey' => 'Varchar(20)',
		'ParID' => 'Varchar(12)',
		'LocID' => 'Varchar(12)',
		'ForeDays' => "Enum('1,2,3,4,5','1')",
		'Unit' => "Enum('m,s','m')",
		'ShowRegion' => 'Boolean(1)',
		'ShowFeelsLike' => 'Boolean(1)',
		'ShowIcon' => 'Boolean(1)',
		'DirIcon' => 'Varchar(16)',
		'SizeIcon' => "Enum('64,48','64')",
		'ShowTemp' => 'Boolean(1)',
		'ShowTempMax' => 'Boolean(1)',
		'ShowTempMin' => 'Boolean(1)',
		'ShowBaro' => 'Boolean(1)',
		'ShowDewP' => 'Boolean(1)',
		'ShowHumid' => 'Boolean(1)',
		'ShowVis' => 'Boolean(1)',
		'ShowWind' => 'Boolean(1)',
		'ShowUVI' => 'Boolean(1)',
		'ShowSunRise' => 'Boolean(1)',
		'ShowSunSet' => 'Boolean(1)',
		'ShowLastUp' => 'Boolean(1)'	
	);
	static $defaults = array(
		'LocID' => 'LOXX0003',
		'Unit' => 'm',
		'ShowRegion' => '1',
		'ShowFeelsLike' => '1',
		'ShowIcon' => '1',
		'DirIcon' => 'acqua',
		'SizeIcon' => '64',
		'ShowTemp' => '1',
		'ShowTempMax' => '1',
		'ShowTempMin' => '1',
		'ShowBaro' => '1',
		'ShowDewP' => '1',
		'ShowHumid' => '1',
		'ShowVis' => '1',
		'ShowWind' => '1',
		'ShowUVI' => '1',
		'ShowSunRise' => '1',
		'ShowSunSet' => '1',
		'ShowLastUp' => '1'
	);
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$bools = array(
			'0' => _t('widgets_weather.HIDE',"Hide"),
			'1' => _t('widgets_weather.SHOW',"Show")
		);
		$dirIcon = array();
		$dirsIcons = array_diff(scandir(WIDGETS_WEATHER_ICONS), array('..', '.'));
		sort($dirsIcons);
		foreach ($dirsIcons as $k => $v) $dirIcon[$v] = $v;
		$metrics = $this->dbObject('Unit')->enumValues();
		foreach ($metrics as $k => $v) $metrics[$k] = _t('widgets_weather.U' . strtoupper($v), ucfirst($v));
		return new FieldList(
				TextField::create('LicKey', _t('widgets_weather.LICENSEKEY',"License Key"))
					->setRightTitle(_t('widgets_weather.LICENSEKEYDESCRIPTION',"License key from www.weather.com")),
				TextField::create('ParID', _t('widgets_weather.PARTNERID',"Partner ID"))
					->setRightTitle(_t('widgets_weather.PARTNERIDDESCRIPTION',"Partner ID from www.weather.com")),
				TextField::create('LocID',_t('widgets_weather.CITYID',"City ID"))
					->setRightTitle(_t('widgets_weather.CITYIDDESCRIPTION',"City, location ID from www.weather.com, e. g.: LOXX0003 (Kosice, Slovakia)")),
				DropdownField::create('ForeDays', _t('widgets_weather.FORECASTDAYS', "Forecast days"), $this->dbObject('ForeDays')->enumValues()),
				DropdownField::create('Unit', _t('widgets_weather.METRICUNIT', "Metric unit"), $metrics),// $this->dbObject('Unit')->enumValues()),
				DropdownField::create('ShowRegion', _t('widgets_weather.REGION', "Region"), $bools),
				DropdownField::create('ShowFeelsLike', _t('widgets_weather.FEELSLIKE', "Feels Like"), $bools),
				DropdownField::create('ShowIcon', _t('widgets_weather.ICONS', "Icons"), $bools),
				DropdownField::create('DirIcon', _t('widgets_weather.SELECTICON', "Select icon"), $dirIcon),
				DropdownField::create('SizeIcon', _t('widgets_weather.SIZEICON', "Icon size (pixels)"), $this->dbObject('SizeIcon')->enumValues()),
				DropdownField::create('ShowTemp', _t('widgets_weather.TEMPERATURE', "Temperature"), $bools),
				DropdownField::create('ShowTempMax', _t('widgets_weather.MAX', "Max"), $bools),
				DropdownField::create('ShowTempMin', _t('widgets_weather.MIN', "Min"), $bools),
				DropdownField::create('ShowBaro', _t('widgets_weather.BAROMETER', "Barometer"), $bools),
				DropdownField::create('ShowDewP', _t('widgets_weather.DEWPOINT', "Dew. point"), $bools),
				DropdownField::create('ShowHumid', _t('widgets_weather.HUMIDITY', "Humidity"), $bools),
				DropdownField::create('ShowVis', _t('widgets_weather.VISIBILITY', "Visibilty"), $bools),
				DropdownField::create('ShowWind', _t('widgets_weather.WIND', "Wind"), $bools),
				DropdownField::create('ShowUVI', _t('widgets_weather.UVI', "UV Index"), $bools),
				DropdownField::create('ShowSunRise', _t('widgets_weather.SUNRISE', "Sunrise"), $bools),
				DropdownField::create('ShowSunSet', _t('widgets_weather.SUNSET', "Sunset"), $bools),
				DropdownField::create('ShowLastUp', _t('widgets_weather.LASTUP', "Updated"), $bools)
		);
	}
	/*
	public function WidgetHolder() {
		//Debug::message('Looky!');
		//if(count(Controller::curr()->dataRecord->getTranslations()) > 1) {
		return parent::WidgetHolder();
	}
	*/
	public function Title() {
		return _t('widgets_weather.TITLE', Object::get_static($this->class, 'title'));
	}
	public function CMSTitle() {
		return _t('widgets_weather.CMSTITLE', Object::get_static($this->class, 'cmsTitle'));
	}
	public function Description() {
		return _t('widgets_weather.DESCRIPTION', Object::get_static($this->class, 'description'));
	}
	private $weather = null;
	public function addMore() {
		return ((int) $this->ForeDays > 1 ? true : false);
	}
	public function getWeatherCurrentDay() {
		if ($this->weather == null) {
			$this->weather = new WeatherHelper($this->LocID, $this->ForeDays, $this->Unit, $this->ParID, $this->LicKey, i18n::get_locale());
		}
		$output = new ArrayList();
		if ($this->weather->getError()) {
			$output->push(new ArrayData(array(
				"Error" => $this->weather->getError()
			)));
		}
		else {
			$output->push(new ArrayData(array(
				"CurrentDay" => new ArrayData(array(
					"ShowRegion" => $this->ShowRegion,
					"ShowIcon" => $this->ShowIcon,
					"ShowTemp" => $this->ShowTemp,
					"ShowFeelsLike" => $this->ShowFeelsLike,
					"ShowRegion" => $this->ShowRegion,
					"ShowTemp" => $this->ShowTemp,
					"ShowTempMax" => $this->ShowTempMax,
					"ShowTempMin" => $this->ShowTempMin,
					"ShowBaro" => $this->ShowBaro,
					"ShowDewP" => $this->ShowDewP,
					"ShowHumid" => $this->ShowHumid,
					"ShowVis" => $this->ShowVis,
					"ShowWind" => $this->ShowWind,
					"ShowUVI" => $this->ShowUVI,
					"ShowSunRise" => $this->ShowSunRise,
					"ShowSunSet" => $this->ShowSunSet,
					"ShowLastUp" => $this->ShowLastUp,
					"LastUpdate" => DBField::create_field('SS_Datetime', $this->weather->getLastUpdate()), //$weather->getLastUpdate(),
					"ForeDate" => str_replace(date('Y'), '', DBField::create_field('Date', $this->weather->getNextDate(0))->Full()),
					"Region" => $this->weather->getRegion(),
					"RainChance" => $this->weather->getRainChance(0),
					"Temperature" => $this->weather->getTemp(0),
					"TempMax" => $this->weather->getTemp(0,'h'),
					"TempMin" => $this->weather->getTemp(0,'l'),
					"FeelsLike" => $this->weather->getFeelslike(),
					"Barometer" => $this->weather->getBarometer(),
					"DewPoint" => $this->weather->getDewPoint(),
					"Humidity"=> $this->weather->getHumidity(0),
					"Visibility" => $this->weather->getVisibility(),
					"Wind" => $this->weather->getWind(0),
					"Direction" => $this->weather->getDirection(0),
					"UVIndex" => $this->weather->getUVI(),
					"Sunrise" => $this->weather->getSun(0,"r"),
					"Sunset" => $this->weather->getSun(0,"s"),
					"Conditions" => $this->weather->getConditions(0),
					"DataIcon" => array(
						"UrlIcon" => $this->weather->getUrlIcon(0,WIDGETS_WEATHER_ICONS_URL . '/' . $this->DirIcon, $this->SizeIcon),
						"Alt" => $this->weather->getConditions(0),
						"Title" => $this->weather->getConditions(0),
						"ImgCssClass" => ($this->SizeIcon == 48 ? 'weatherIconSmall' : 'weatherIconLarge')
					)
				))
			)));
		}
		return $output;
	}
	public function getWeatherNextDay() {
		$output = new ArrayList();
		if ($this->weather->getError()) {
			$output->push(new ArrayData(array(
				"Error" => $this->weather->getError()
			)));
		}
		else {
			for($i = 1; $i < $this->ForeDays; $i++) {
				$output->push(new ArrayData(array(
					"NextDay" => new ArrayData(array(
						"ShowIcon" => $this->ShowIcon,
						"ShowTemp" => $this->ShowTemp,
						"ShowTempMax" => $this->ShowTempMax,
						"ShowTempMin" => $this->ShowTempMin,
						"ShowHumid" => $this->ShowHumid,
						"ShowWind" => $this->ShowWind,
						"ShowUVI" => $this->ShowUVI,
						"ShowSunRise" => $this->ShowSunRise,
						"ShowSunSet" => $this->ShowSunSet,
						"ForeDate" => str_replace(date('Y'), '', DBField::create_field('Date', $this->weather->getNextDate($i))->Full()),
						"RainChance" => $this->weather->getRainChance($i),
						"Temperature" => $this->weather->getTemp($i),
						"TempMax" => $this->weather->getTemp($i,'h'),
						"TempMin" => $this->weather->getTemp($i,'l'),
						"Humidity"=> $this->weather->getHumidity($i),
						"Wind" => $this->weather->getWind($i),
						"Direction" => $this->weather->getDirection($i),
						"Sunrise" => $this->weather->getSun($i,"r"),
						"Sunset" => $this->weather->getSun($i,"s"),
						"Conditions" => $this->weather->getConditions($i),
						"DataIcon" => array(
							"UrlIcon" => $this->weather->getUrlIcon($i,WIDGETS_WEATHER_ICONS_URL . '/' . $this->DirIcon, $this->SizeIcon),
							"Alt" => $this->weather->getConditions($i),
							"Title" => $this->weather->getConditions($i),
							"ImgCssClass" => ($this->SizeIcon == 48 ? 'weatherIconSmall' : 'weatherIconLarge')
						)
					))
				)));
			}
		}
		return $output;
	}
}