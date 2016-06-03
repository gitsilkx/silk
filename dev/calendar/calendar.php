<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<!-- 
	OR if you want to use the calendar in a right-to-left website
	just use the other CSS file instead and don't forget to switch g_jsDatePickDirectionality variable to "rtl"!
	
	<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.css" />
-->

<script type="text/javascript" src="jquery.1.4.2.js"></script>
<script type="text/javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){		
		
		
		
		g_globalObject2 = new JsDatePick({
			useMode:1,
			isStripped:false,
			target:"div4_example",
			cellColorScheme:"beige"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
		
		g_globalObject2.setOnSelectedDelegate(function(){
			var obj = g_globalObject2.getSelectedDay();
			//alert("a date was just selected and the date is : " + obj.day + "/" + obj.month + "/" + obj.year);
			document.getElementById("div3_example_result").innerHTML = obj.day + "/" + obj.month + "/" + obj.year;
		});		
		
	};
</script>
    
     <div id="div3_example_result" style="height:46px; background-color: #FFFFFF; 
    border: 1px solid #E5E5E5; padding-left:8px; line-height:46px; margin:10px 0 0 0; ">
    	<div style=" width:17px; height:19px;margin-top: 12px; float:left;background:url(../static/images/layout/product-cal.png) no-repeat;"></div>
    </div>
    <div id="div4_example" style="margin:10px 0 30px 0; left:22px; position:relative;  width:260px; height:auto; padding-bottom:12px; margin:12px auto;">
    	
    </div>
    
    
   
        
