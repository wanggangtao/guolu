           function change1(){
	     		document.getElementById("index1_unchecked").style.visibility="hidden";
	     		document.getElementById("index1_checked").style.visibility="visible";
            }
	     	function change2(){
	     		document.getElementById("index1_unchecked").style.visibility="visible";
	     		document.getElementById("index1_checked").style.visibility="hidden";
	     	    
	     	}
	     	 function change3(){
	     		document.getElementById("index2_unchecked").style.visibility="hidden";
	     		document.getElementById("index2_checked").style.visibility="visible";
	     	}
	     	function change4(){
	     		document.getElementById("index2_unchecked").style.visibility="visible";
	     		document.getElementById("index2_checked").style.visibility="hidden";
	     	}
	     	 function change5(){
	     		document.getElementById("index3_unchecked").style.visibility="hidden";
	     		document.getElementById("index3_checked").style.visibility="visible";
	     	}
	     	function change6(){
	     		document.getElementById("index3_unchecked").style.visibility="visible";
	     		document.getElementById("index3_checked").style.visibility="hidden";
	     	}

           /*function only1(){
               document.getElementById('radio1').checked=false;
               document.getElementById('radio2').checked=false;
               document.getElementById('radio3').checked=false;
               document.getElementById('radio4').checked=false;
           }*/
		   function only1(){
			   $("input[name=price]").attr("checked",false);
		   }

		    function only2(){
			     		document.getElementById('radio5').checked=false;  
			     		document.getElementById('radio6').checked=false; 
			     		document.getElementById('radio7').checked=false; 
			     		document.getElementById('radio8').checked=false; 
			}
		    function only3(){
			     		document.getElementById('radio9').checked=false;  
			     		document.getElementById('radio10').checked=false; 
			     		document.getElementById('radio11').checked=false; 
			     		document.getElementById('radio12').checked=false; 
			     	}
		    function only4(){
			     		document.getElementById('radio13').checked=false;  
			     		document.getElementById('radio14').checked=false; 
			     		document.getElementById('radio15').checked=false; 
			     		document.getElementById('radio16').checked=false; 
			     	}
		    function only5(){
			     		document.getElementById('radio17').checked=false;  
			     		document.getElementById('radio18').checked=false; 
			     		document.getElementById('radio19').checked=false; 
			     		document.getElementById('radio20').checked=false; 
			     	}
            function only6(){
               document.getElementById('radio21').checked=false;
               document.getElementById('radio22').checked=false;
               document.getElementById('radio23').checked=false;
               document.getElementById('radio24').checked=false;
            }
		   function only7(){
			   document.getElementById('radio53').checked=false;
			   document.getElementById('radio54').checked=false;
			   document.getElementById('radio55').checked=false;
			   document.getElementById('radio56').checked=false;
		   }
		    function allchecked(){
		    	document.getElementById("checkbox1").checked=true;
		    	document.getElementById("checkbox2").checked=true;
		    	document.getElementById("checkbox3").checked=true;
		    	document.getElementById("checkbox4").checked=true;
		    	document.getElementById("checkbox5").checked=true;
		    	document.getElementById("checkbox6").checked=true;
		    }

         function colorChange1(){
         	document.getElementById("confirm1").style.backgroundColor="#04A6FE";
         	document.getElementById("confirm1").style.color="white";
         	document.getElementById("cancel1").style.backgroundColor="white";
         	document.getElementById("cancel1").style.color="#04A6FE";
         }
          function colorChange2(){
         	document.getElementById("confirm1").style.backgroundColor="white";
         	document.getElementById("confirm1").style.color="#04A6FE";
         	document.getElementById("cancel1").style.backgroundColor="#04A6FE";
         	document.getElementById("cancel1").style.color="white";
         }
         function colorChange3(){
         	document.getElementById("confirm2").style.backgroundColor="#04A6FE";
         	document.getElementById("confirm2").style.color="white";
         	document.getElementById("cancel2").style.backgroundColor="white";
         	document.getElementById("cancel2").style.color="#04A6FE";
         }
          function colorChange4(){
         	document.getElementById("confirm2").style.backgroundColor="white";
         	document.getElementById("confirm2").style.color="#04A6FE";
         	document.getElementById("cancel2").style.backgroundColor="#04A6FE";
         	document.getElementById("cancel2").style.color="white";
         }
