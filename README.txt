Weather Widget 0.0.1 for SilverStripe
May 17 2013
By jaroslav.stika@gmail.com

Require Silverstripe 3.05

Released under the BSD license.

Google silently kills popular API, breaks weather apps everywhere, so you need to sign up here http://www.wunderground.com/weather/api/?ref=twc 
to obtain License Key and Partner ID.  
To find location ID go to http://www.weather.com/forecast, e.g.: Kosice, Slovakia. 
You'll see inn address bar 
http://www.weather.com/weather/today/Kosice+LOXX0003:1:LO, so location ID is LOXX0003. 
Then you can lookup anywhere in the world and provide wether for it.

Installation:
Read carefully https://github.com/silverstripe/silverstripe-widgets.
Make sure you have enabled widgets first to use (http://doc.silverstripe.org/widgets?s[]=widget#adding_widgets_to_other_pages)

1. extract this to your SilverStripe root directory widget_weather
2. run "dev/build?flush=all"
3. visit your page and drag and drop the widget to the right

I'm using https://github.com/twitter/bootstrap/, so template assumes bootstrap is loaded (jquery, js, css), becouse it uses bootstrap accordion.
You can rewrite template to your needs.