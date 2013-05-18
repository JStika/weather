<% if Top.addMore %>
	<div class="accordion" id="weather">
<% end_if %>
<% loop getWeatherCurrentDay %>
	<% if Error %>
		<div class="alert alert-error">
			$Error
		</div>
	<% else %>
		<% loop CurrentDay %>
			<% if Top.addMore %>
				<div class="accordion-group">
					<div class="accordion-heading" style="text-align:center">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#weather" href="#collapse_0">$ForeDate</a>
					</div>
					<div id="collapse_0" class="accordion-body collapse in">
						<div class="accordion-inner">
							<% include WeatherWidgetCurrentDay %>
						</div>
				   </div>
				</div>
			<% else %>
				<% include WeatherWidgetCurrentDay %>
			<% end_if %>
		<% end_loop %>
	<% end_if %>
<% end_loop %>
<% if Top.addMore %>
	<% loop getWeatherNextDay %>
		<% if Error %>
			<div class="alert alert-error">
				$Error
			</div>
		<% else %>
			<% loop NextDay %>
			<div class="accordion-group">
				<div class="accordion-heading" style="text-align:center">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#weather" href="#collapse_{$Up.Pos}">$ForeDate</a>
				</div>
				<div id="collapse_{$Up.Pos}" class="accordion-body collapse">
					<div class="accordion-inner">
						<% include WeatherWidgetNextDay %>
					</div>
				</div>
			</div>
			<% end_loop %>
		<% end_if %>
	<% end_loop %>
<% end_if %>
<% if Top.addMore %>
	</div>
<% end_if %>