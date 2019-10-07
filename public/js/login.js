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
    			if(data===true)
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

    $("#registerSub").on('click',function(event)
        {
            event.preventDefault();
            $("#message_place_r").html("<br>");
            let un=$('#username_r').val();
            let up=$('#userpass_r').val();
            let al=$('#alias_r').val();

            if(un=='' || up=='')
            {
                $('#message_place_r').html('Missing creditenals');
                return false;
            }
            if(al.length>0 && al.length<3)
            {
                //alias too short
                $('#message_place_r').html('Alias is too short, pleas use at least 3 characters');
                return false;
            }

            $.ajax(
            {
                url:'../registerCheck',
                type:'POST',
                data:{un:un, up:up, al:al},
                success: function(data)
                {
                    console.log(data);
                    if(data.message!='')
                    {
                        $("#message_place_r").html(data.message);
                    }
                },
                error: function()
                {
                    console.log('ajax error');
                }
            });
        });

});