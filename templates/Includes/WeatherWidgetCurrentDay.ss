<table class="weather">
	<tbody>
		<% if ShowRegion %>
		<tr>
			<td colspan="2">
			<div class="weather_center">
				<strong>$Region</strong>
			</div>
			</td>
		</tr>
		<% end_if %>
		<tr>
			<td>
			<% if ShowIcon %>
			<div class="weather_center">
				<img src="$DataIcon.UrlIcon" alt="$DataIcon.Alt.XML" title="$DataIcon.Title.XML" class="$DataIcon.ImgCssClass" />
			</div>
			<% end_if %>
			</td>
			<td>
			<% if ShowTemp %>
			<span class="weather_temp_header">$Temperature</span>
			<% end_if %>
			<% if ShowFeelsLike %>
			<br /><span class="weather_feelslike"><% _t('widgets_weather.FEELSLIKE','Feels like') %><br />$FeelsLike</span>
			<% end_if %>
			</td>
		</tr>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.RAINCHANCE','Rain chance') %>:</td>
			<td class="weather_td_right">$RainChance</td>
		</tr>
		<% if ShowRegion %>
		<!--
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.REGION','Region') %>:</td>
			<td class="weather_td_right">$Region</td>
		</tr>
		-->
		<% end_if %>
		<% if ShowTemp %>
		<!--
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.TEMPERATURE','Temperature') %>:</td>
			<td class="weather_td_right">$Temperature</td>
		</tr>
		-->
		<% end_if %>
		<% if ShowTempMax %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.MAX','Max') %>:</td>
			<td class="weather_td_right">$TempMax</td>
		</tr>
		<% end_if %>
		<% if ShowTempMin %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.MIN','Min') %>:</td>
			<td class="weather_td_right">$TempMin</td>
		</tr>
		<% end_if %>
		<% if ShowBaro %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.BAROMETER','Barometer') %>:</td>
			<td class="weather_td_right">$Barometer</td>
		</tr>
		<% end_if %>
		<% if ShowDewP %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.DEWPOINT','Dew. point') %>:</td>
			<td class="weather_td_right">$DewPoint</td>
		</tr>
		<% end_if %>
		<% if ShowHumid %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.HUMIDITY','Humidity') %>:</td>
			<td class="weather_td_right">$Humidity</td>
		</tr>
		<% end_if %>
		<% if ShowVis %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.VISIBILITY','Visibility') %>:</td>
			<td class="weather_td_right">$Visibility</td>
		</tr>
		<% end_if %>
		<% if ShowWind %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.WIND','Wind') %>:</td>
			<td class="weather_td_right">$Wind<br /><% _t('widgets_weather.WINDD','Direction') %>: $Direction</td>
		</tr>
		<% end_if %>
		<% if ShowUVI %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.UVINDEX','UV Index') %>:</td>
			<td class="weather_td_right">$UVIndex</td>
		</tr>
		<% end_if %>
		<% if ShowSunRise %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.SUNRISE','Sunrise') %>:</td>
			<td class="weather_td_right">$Sunrise</td>
		</tr>
		<% end_if %>
		<% if ShowSunSet %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.SUNSET','Sunset') %>:</td>
			<td class="weather_td_right">$Sunset</td>
		</tr>
		<% end_if %>
		<% if ShowLastUp %>
		<tr>
			<td colspan="2">
			<div class="weather_center_small">
				<% _t('widgets_weather.LASTUP','Updated') %>:</br />$LastUpdate.Nice
			</div>
			</td>
		</tr>
		<% end_if %>
	</tbody>
</table>