function checkAll(formObj, is_checked) 
{
	for (var i=0;i < formObj.length;i++) {
		fldObj = formObj.elements[i];
		if (fldObj.type == 'checkbox') {
			fldObj.checked = is_checked;
		}
	}
}

is_checked_all = false;
function toggleCheckAll(formObj) {

	is_checked_all = !is_checked_all;
	checkAll(formObj, is_checked_all);
}


function resetCheckAllValue(formObj, is_checked) {
	if(!is_checked) {
		main_checkbox = document.getElementById("checkall_checkbox");
		if(main_checkbox) {
			main_checkbox.checked = false;
		}
		is_checked_all = false;
	}
}
function hasStr(src,sample){
	return src.indexOf(sample)>=0
}

function addIfNotInStr(src,sample)
{
	if (hasStr(src,sample))
	{
		return src;
	}
	else
	{
		return src+sample;
	}
}

function removeOccurances(src,sample)
{
	splitstring = src.split(sample);
	tstring="";
	for(i = 0; i < splitstring.length; i++)
	{
		tstring += splitstring[i];
	}	
	return tstring;
}
function ghettoCheckAll(oldStr,formObj, is_checked) 
{
	for (var i=0;i < formObj.length;i++) 
	{
		fldObj = formObj.elements[i];
		if (fldObj.type == 'checkbox' && fldObj.checked != is_checked) 
		{
			fldObj.checked = is_checked;
			if (is_checked)
			{
				oldStr = addIfNotInStr(oldStr, fldObj.name+',');
			}
			else
			{
				oldStr = removeOccurances(oldStr, fldObj.name+',');
			}
		}
	}
	return oldStr
}
function confirmSubmit(formObj, message) {
	var agree=confirm(message);
	if (agree)
		return true ;
	else
		return false ;
}


