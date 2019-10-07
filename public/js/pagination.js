
//request number of articles per page
//request articles count and articles for first page

//accept increment/decrement
//request new page depending on increment/decrement
//update page numbers in page navigation

var per_page;
var total_articles=0;
var firstId=0;
var lastId=0;
var direction=null;
var current_page;

$(document).ready(function()
{
	var sel_opt="<option value=2>2</option>";
	//var sel_opt="<option value=1>1</option><option value=2>2</option>";
	sel_opt+="<option value=5>5</option><option value=10>10</option>";
	sel_opt+="<option value=20>20</option><option value=50>50</option>";

	$("#per_page_s").html(sel_opt);
	per_page=$("#per_page_s").val();
	FirstLoad();

	$("#per_page_s").on('change',function()
	{
		per_page=$("#per_page_s").val();
		//reload pagination
		direction=null;
		firstId=0;
		lastId=0;
		FirstLoad();
	});
});

function FirstLoad()
{
	$.ajax(
	{
		url: '../CountArticles',
		type:'POST',
		success: function(data)
		{
			//console.log(data);
			//console.log(data.total);
			total_articles=data.total;
			DrawNav();
			current_page=1;
			RequestPage();
        },
		error: function()
		{

		}
	});
}

function DrawNav()
{
	//calculate number of needed pages
	//make menu
	menu_pages=Math.ceil(total_articles/per_page);
	//console.log('We need '+menu_pages+' pages.');
	$("#nav_num").html('');

	if(menu_pages==2)
	{
		//block 1 block 2 block next
		var table = "<tr><td><button style=\"background-color:green\">1</button></td>";
		table+="<td><button onclick=\"NextPage();\">2</button></td>";
	}
	else if(menu_pages>1)
	{
		//block 1 block 2 block next
		var table = "<tr><td><button style=\"background-color:green\">1</button></td>";
		table+="<td><button onclick=\"NextPage();\">2</button></td>";
		table+="<td><button onclick=\"NextPage();\">></button></td></tr>";
	}
	else if(menu_pages==1)
	{
		//just 1 button
		var table = "<tr><td><button>1</button></td></tr>";
	}
	else
	{
		var table = "<tr><td><button>Error</button></td></tr>";
	}
	$("#nav_num").html(table);
	//console.log('Drawn');
}

function RequestPage()
{
	//need last shown article id
	//need per_page
	var art_divs="";
	$.ajax(
	{
		url: '../loadPage',
		type:'POST',
		data:{fid:firstId, lid:lastId, perPage:per_page, dir:direction},
		success: function(data)
		{
			//console.log(data);
			//console.log(data.articles);
			firstId=data.articles[0].id;
			lastId=data.articles[data.articles.length-1].id;

			for(let i = 0; i < data.articles.length; i++)
			{
				//console.log(data.articles[i]);
				art_divs+="<a href='/post/"+ data.articles[i].id +"' style=\"text-decoration: none;color: inherit;\">";
				art_divs+="<div style=\"border:1px solid black;\">";
				art_divs+="<img class=\"nav-profile-img rounded-circle\" src=\"images/astronaut-profile.png\">";
				art_divs+="<p>"+ data.articles[i].name +"</p><p>"+ data.articles[i].text +"</p><p>"+data.articles[i].message+"</p></div></a>";
			}
			//console.log(art_divs);
			//$('#paged_articles').html('');
			$('#paged_articles').html(art_divs);
        },
		error: function()
		{}
	});
}


function NextPage()
{
	//obtain next articles
	//console.log('Next Page');
	direction='inc';
	current_page=current_page+1;
	RequestPage();

	//redraw navigatiom buttons
	var table = "<tr>";

	if(current_page>2)
	{
		//there is more than 1 previous page
		table+="<td><button onclick=\"PreviousPage();\"><</button></td>";
		table+="<td><button onclick=\"PreviousPage();\">"+(current_page-1)+"</button></td>";
	}
	if(current_page==2)
	{
		//you are on second page, there is only number button
		table+="<td><button onclick=\"PreviousPage();\">"+(current_page-1)+"</button></td>";
	}

	//current page
	table+="<td><button style=\"background-color:green\">"+current_page+"</button></td>";
	
	if(menu_pages-current_page==1)
	{
		//there is 1 next page
		table+="<td><button onclick=\"NextPage();\">"+(current_page+1)+"</button></td>";
	}
	if(menu_pages-current_page>1)
	{
		//there is more than 1 next page
		table+="<td><button onclick=\"NextPage();\">"+(current_page+1)+"</button></td>";
		table+="<td><button onclick=\"NextPage();\">></button></td>";
	}
	table+="</tr>";
	$("#nav_num").html(table);
}


function PreviousPage()
{
	//obtain next articles
	//console.log('Previous Page');
	direction='desc';
	current_page=current_page-1;
	RequestPage();

	//redraw navigatiom buttons
	var table = "<tr>";

	if(current_page>2)
	{
		//there is more than 1 previous page
		table+="<td><button onclick=\"PreviousPage();\"><</button></td>";
		table+="<td><button onclick=\"PreviousPage();\">"+(current_page-1)+"</button></td>";
	}
	if(current_page==2)
	{
		//you are on second page, there is only number button
		table+="<td><button onclick=\"PreviousPage();\">"+(current_page-1)+"</button></td>";
	}

	//current page
	table+="<td><button style=\"background-color:green\">"+current_page+"</button></td>";
	
	if(menu_pages-current_page==1)
	{
		//there is 1 next page
		table+="<td><button onclick=\"NextPage();\">"+(current_page+1)+"</button></td>";
	}
	if(menu_pages-current_page>1)
	{
		//there is more than 1 next page
		table+="<td><button onclick=\"NextPage();\">"+(current_page+1)+"</button></td>";
		table+="<td><button onclick=\"NextPage();\">></button></td>";
	}
	table+="</tr>";
	$("#nav_num").html(table);
}