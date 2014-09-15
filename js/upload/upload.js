function readURL(input,blnking)
{
	var f=input.files[0]
	/*if(f.size>3007200)
	{
		alert("Supported formats : JPEG , PNG , GIF  Max File Size : 300Kb ");
		document.getElementById('photo').value='';
		$('#image').attr('src',blnking);
		return;
	}*/
	i=document.getElementById('photo').value;
	i = i.substr(i.length - 4, i.length).toLowerCase();
	i = i.replace('.','');
	switch(i)
	{
		case 'jpeg':
		case 'png':
		case 'gif':
		// do OK stuff
		break;
		default:
			alert("Supported formats : JPEG , PNG , GIF  Max File Size : 300Kb ");
			document.getElementById('photo').value='';
			$('#image').attr('src',blnking);
			return;
		break;
	}
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			$('#image')
				.attr('src',e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}
function cheack_upload_publish(input)
{
	var f=input.files[0]
	/*if(f.size>3007200)
	{
		alert("Supported formats : doc, docx, pdf, powerpoint Max File Size : 300Kb ");
		document.getElementById('document').value='';
		return;
	}*/
	i=document.getElementById('document').value;
	i = i.substr(i.length - 4, i.length).toLowerCase();
	i = i.replace('.','');
	switch(i)
	{
		//doc, docx, pdf, powerpoint
		case 'doc':
		case 'docx':
		case 'pdf':
		case 'pptx':
		case 'xlsx':
		// do OK stuff
		break;
		default:
			alert("Supported formats : doc, docx, pdf, powerpoint Max File Size : 300Kb ");
			document.getElementById('document').value='';
			return;
		break;
	}
}