$("#log_div").hide();
$("#reg_div").hide();

$("#log_b").on('click',function(event)
{
    $("#log_div").show();
    $("#reg_div").hide();
    console.log("login");
});

$("#reg_b").on('click',function(event)
{
    $("#reg_div").show();
    $("#log_div").hide();
    console.log('register');
});


$( document ).ready(function()
{
    console.log( "ready!" );

    $("#loginSub").on('click',function(event)
    {
    	event.preventDefault();
        $('#message_place').html("<br/>");
    	let un=$('#username').val();
    	let up=$('#userpass').val();

        if(un=='' || up=='')
        {
            $('#message_place').html('Missing creditenals');
            return false;
        }

    	$.ajax(
    	{
    		url: '../login_check',
    		type:'POST',
    		data:{un:un, up:up},
    		success: function(data)
    		{
    			if(data)
                {
                    //received true, return to welcome
                    //console.log(window.location.pathname);
                    window.location='/';
                }
                else
                {
                    $('#message_place').html('Invalid creditenals');
                    //received false, error
                }
    		},
    		error: function()
    		{
    			console.log('ajax error');
    		}
    	});

    });

});