$('#message_par').hide();

$(document).ready(function()
{
	console.log('ready');

	$("#postArticle").on('click',function()
		{
			event.preventDefault();

			$('#message_par').hide();
	        $('#message_par').removeClass('text-danger');
	        $('#message_par').removeClass('text-success');
	        $('#message_par').html();

			let title=$('#postTitle').val();
			let text=$('#postText').val();

			if(title=='' || text=='')
			{
				$('#message_par').show();
	            $('#message_par').addClass('text-danger');
	            $('#message_par').html('Missing title or text.');
	            return false;
			}

			$.ajax(
	    	{
	    		url: '../addPost',
	    		type:'POST',
	    		data:{title:title, text:text},
	    		success: function(data)
	    		{
	    			if(data.status)
	                {
	                    //received true, clear form, present success message
	                    $('#postTitle').val("");
	                    $('#postText').val("");
	                    $('#message_par').show();
	                    $('#message_par').addClass('text-success');
	                    $('#message_par').html('Post added');
	                    text=undefined;
	                    title=undefined;
	                }
	                else
	                {
	                    //received false, error/exception occured
	                    $('#message_par').show();
	                    $('#message_par').addClass('text-danger');
	                    $('#message_par').html('Error ocurred.');
	                }	    		
	            },
	    		error: function()
	    		{
	    			console.log('ajax error');
	    		}
	    	});
		});
});