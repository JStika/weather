<table class="weather">
	<tbody>
		<!--<tr><td colspan="2"><hr class="weather" /><div class="weather_center"><strong>$ForeDate.Full</strong></div><hr class="weather" /></td></tr>	-->
		<tr>
			<td>
			<% if ShowIcon %>
			<div class="weather_center">
				<% with DataIcon %>
				<img src="$UrlIcon" alt="$Alt.XML" title="$Title.XML" class="$ImgCssClass" />
				<% end_with %>
			</div>
			<% end_if %>
			</td>
			<td>
			<% if ShowTemp %>
			<span class="weather_temp_header">$Temperature</span><br />$Conditions
			<% end_if %>
			</td>
		</tr>
		<tr>
			<td class="weather_td_left"><% _t('WeatherPage.RAINCHANCE','Rain chance') %>:</td>
			<td class="weather_td_right">$RainChance</td>
		</tr>
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
		<% if ShowHumid %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.HUMIDITY','Humidity') %>:</td>
			<td class="weather_td_right">$Humidity</td>
		</tr>
		<% end_if %>
		<% if ShowWind %>
		<tr>
			<td class="weather_td_left"><% _t('widgets_weather.WIND','Wind') %>:</td>
			<td class="weather_td_right">$Wind,<br /><% _t('widgets_weather.WINDD','Direction') %>: $Direction</td>
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
	</tbody>
</table>