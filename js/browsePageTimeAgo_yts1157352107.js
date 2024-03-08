function format_time_ago(timeUploaded) {
	var thisdate = new Date();
	currentTime = thisdate.getTime();
	diffInMilliseconds = currentTime - timeUploaded;
    return formatOutput(diffInMilliseconds);
}


function formatOutput(timeDIffInMilliseconds) {
	var sec = Math.floor(timeDIffInMilliseconds/1000);
	var min = Math.floor(sec/60);
	var hr = Math.floor(min/60);
	var day = Math.floor(hr/24);
	var week = Math.floor(day/7);
	var month = Math.floor(day/30);
	var year = Math.floor(day/365);
	var pluralString = "s";
	min = min % 60;
	hr = hr % 24;
	if (year > 0)
	   {
	   return (year + " year" + printS(year) + " ago");
	   }
	else if (month > 0)
	   {
	   return (month + " month" + printS(month) + " ago");
	   }

	else if (week > 0)
	   {
	   return (week + " week" + printS(week) + " ago");

	   }
	else if (day > 0)
	   {
	   return (day + " day" + printS(day) + " ago");
	   } 
	else if (hr > 0)
	   {
	   return (hr + " hour" + printS(hr) + " ago");
	   } 
	else if (min > 0)
	   {
	   return (min + " minute" + printS(min) + " ago");
	   }         
	else
	   {
	   return ("1 minute ago");
	   }

}

function printS(num_value) {
	if(num_value>1) {
		return "s";
	}
	else return "";
}
