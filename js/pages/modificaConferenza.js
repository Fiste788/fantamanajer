function ismaxlength(obj,maxLenght)
{
	var mlength=maxLenght;
	if (obj.getAttribute && obj.value.length>mlength) 
	{
		var cursor = obj.selectionEnd;
		var scroll = obj.scrollTop;
		alert("Hai raggiunto il massimo di caratteri consentito");
		obj.value=obj.value.substring(0,mlength);
		obj.selectionEnd = cursor;
		obj.scrollTop = scroll;
	}
	 document.getElementById(obj.name + 'Cont').value = mlength - obj.value.length
}

function addEmoticon(insert){
	obj = document.getElementById('text');
	var fullText = obj.value;
	var subText = fullText;
	var scroll = obj.scrollTop;
	var end = obj.selectionEnd;
	subText = subText.substring(0,obj.selectionStart);
	subText += ' '+insert;
	var appo = subText.length;
	subText += fullText.substring(end);
	obj.value = subText;
	obj.selectionEnd = appo;
	obj.scrollTop = scroll;
}