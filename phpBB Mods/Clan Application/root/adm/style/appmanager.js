<p><a href=”javascript:addElement();” >Add</a> <a href=”javascript:removeElement();” >Remove</a></p>

<script type="text/javascript" src="./appmanager.js"></script>

<script type=”text/javascript”>
var intTextBox={S_SECTIONS};

//FUNCTION TO ADD TEXT BOX ELEMENT
function addElement()
{
	intTextBox = intTextBox + 1;
	var contentID = document.getElementById(’content’);
	var newTBDiv = document.createElement(’div’);
	newTBDiv.setAttribute(’id’,’strText’+intTextBox);
	newTBDiv.innerHTML = “Text “+intTextBox+”: <input type=’text’ id=’” + intTextBox + “‘ name=’” + intTextBox + “‘/>”;
	contentID.appendChild(newTBDiv);
}

//FUNCTION TO REMOVE TEXT BOX ELEMENT
function removeElement()
{
	if(intTextBox != 0)
	{
		var contentID = document.getElementById(’content’);
		contentID.removeChild(document.getElementById(’strText’+intTextBox));
		intTextBox = intTextBox-1;
	}
}
</script>