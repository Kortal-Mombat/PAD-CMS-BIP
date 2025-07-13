var VERYSTRONG = "bardzo&nbsp;mocne";
var STRONG = "mocne";
var MEDIUM = "średnie";
var WEAK = "słabe";
var VERYWEAK = "bardzo&nbsp;słabe";
    
	
	function testPassword(passwd, divName, spanName)
     {
        var intScore   = 0
        var strVerdict = "weak"
        var strLog     = ""
        var color="";
        
        // PASSWORD LENGTH
        if (passwd.length == 0)                         // length 0
        {
            intScore = 0
        }
        else if (passwd.length < 5)                         // length 4 or less
        {
            intScore = (intScore+3)
        }
        else if (passwd.length < 4  &&  passwd.length  <  8)       // length between 5 and 7
        {
            intScore = (intScore+6)
        }
         else if (passwd.length > 7  &&  passwd.length < 16)// length between 8 and 15
        {
            intScore = (intScore+12)
        }
        else if (passwd.length >  15)                    // length 16 or more
        {
            intScore = (intScore+18)
        }
        
        
        // LETTERS (Not exactly implemented as dictacted above because of my limited understanding of Regex)
        if (passwd.match(/[a-z]/))                              // [verified] at least one lower case letter
        {
            intScore = (intScore+1)
        }
        
        if (passwd.match(/[A-Z]/))                              // [verified] at least one upper case letter
        {
            intScore = (intScore+5)
        }
        
        // NUMBERS
        if (passwd.match(/\d+/))                                 // [verified] at least one number
        {
            intScore = (intScore+5)
        }
        
        if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/))             // [verified] at least three numbers
        {
            intScore = (intScore+5)
        }
        
        
        // SPECIAL CHAR
        if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/))            // [verified] at least one special character
        {
            intScore = (intScore+5)
        }
                                             // [verified] at least two special characters
        if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^, & ,*,?,_,~])/))
        {
            intScore = (intScore+5)
        }
    
        
        // COMBOS
        if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))        // [verified] both upper and lower case
        {
            intScore = (intScore+2)
        }

        if (passwd.match(/([a-zA-Z])/)  &&  passwd.match(/([0-9])/)) // [verified] both letters and numbers
        {
            intScore = (intScore+2)
        }
 
        // [verified] letters, numbers, and special characters
        if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^, & ,*,?,_,~].*[a-zA-Z0-9])/))
        {
            intScore = (intScore+2)
        }

   
    	document.getElementById(spanName).style.color = "white"; 
    
        if(intScore  ==  0)
        {
           strVerdict = "";
        }
        else if(intScore  <  16)
        {
           strVerdict = VERYWEAK;
           color = "#EE4000";
        }
        else if (intScore  >  15  &&  intScore  <  25)
        {
           strVerdict = WEAK;
           color = "#FFA500"
        }
        else if (intScore  >  24  &&  intScore  <  35)
        {
           strVerdict = MEDIUM;
           document.getElementById(spanName).style.color = "red";
           color = "#FFFF00" 
        }
        else if (intScore  >  34  &&  intScore  <  45)
        {
           strVerdict = STRONG;
           color = "#008B45" 
        }
        else
        {
           strVerdict = VERYSTRONG;
           color = "#36648B"          
        }       

        document.getElementById(spanName).innerHTML = strVerdict;
        document.getElementById(divName).style.width = 5*intScore+"px";
        document.getElementById(divName).style.background = color;
        
       }