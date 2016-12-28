var imported = document.createElement('script');
imported.src = '../qp/js/browser_validations_ext.js';
document.head.appendChild(imported);

function validate(id) {

  var id = id;

  /* forgot paswd start */
  if (id == "xoouserultra-forgot-pass-btn-confirm") {

    var valf = document.getElementById("user_name_email");
    if (valf.value == "") {
      valf.style.border = "1px solid rgb(210, 8, 8) !important";
      valf.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      valf.style.outline = "medium none";
      document.getElementById("user_name_email_err_div").style.display = "block";
      document.getElementById("user_name_email_err").innerHTML = 'Please enter E-mail or username';
      return false;
    } else {
      return true;
    }
  }


  if (id == "user_name_email") {

    var valf = document.getElementById("user_name_email");
    if (valf.value == "") {
      valf.style.border = "1px solid rgb(210, 8, 8) !important";
      valf.style.boxShadow = "0px 0px 10px rgb(210, 8, 8) !important";
      valf.style.outline = "medium none";
      return false;
    } else {
      return true;
    }
  }

  /* forgot paswd end */

  /* login start */
  if (id == "Login") {
    var val1 = document.getElementById("user_login");
    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("user_login").setCustomValidity('');
      document.getElementById("user_login_err_div").style.display = "block";
      document.getElementById("user_login_err").innerHTML = 'Please enter Username or E-mail';
      return false;
    } else {}

    var val2 = document.getElementById("login_user_pass");
    //var check=validatePass(val2.value); 
    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("login_user_pass").setCustomValidity('');
      document.getElementById("login_user_pass_err_div").style.display = "block";
      document.getElementById("login_user_pass_err").innerHTML = 'Please enter Password';
      return false;
    }
    /* if(!check && (val2.value != "")){
    	val2.style.border="1px solid rgb(210, 8, 8)";
    	val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
    	val2.style.outline="medium none";
    	document.getElementById("login_user_pass_err_div").style.display="block";
    	document.getElementById("login_user_pass_err").innerHTML="Must contain minm of 8 characters with atleast an uppercase alphabet, a number and a special character"; 
    	return false;} */
    else {
      return true;
    }
  }


  if (id == "user_login") {
    var val1 = document.getElementById("user_login");
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      /*document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter Username or E-mail"; */
      return false;
    } else {
      return true;
    }
  }

  if (id == "login_user_pass") {
    var val2 = document.getElementById("login_user_pass");
    //var check=validatePass(val2.value); 
    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      /*document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter Password"; */
      return false;
    }
    /* if(!check && (val2.value!="")){
    	val2.style.border="1px solid rgb(210, 8, 8)";
    	val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
    	val2.style.outline="medium none";
    	document.getElementById("login_user_pass_err_div").style.display="block";
    	document.getElementById("login_user_pass_err").innerHTML="Must contain minm of 8 characters with atleast an uppercase alphabet, a number and a special character"; 
    	return false;} */
    else {
      return true;
    }
  }
  /* login end */

  /* registraion start */

  if (id == "xoouserultra-register-btn") {

    var val1 = document.getElementById("reg_user_login");

    var check = validateUsername(val1.value);

    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";

      document.getElementById("reg_user_login_err_div").style.display = "block";
      document.getElementById("reg_user_login_err").innerHTML = "Please enter username";

      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      jQuery("#reg_user_login-help").css("color", "red !important");
      //document.getElementById("reg_user_login_err_div").style.display="block";
      //document.getElementById("reg_user_login_err").innerHTML="UserName should not contain special char and shouldn\'t start with underscore"; 

      return false;
    }

    var uexist = ajaxuser(val1.value);

    if (document.getElementById("reg_user_login_err").innerHTML != "") {
      return false;
    }

    /*if((uexist==0)){
    		val1.style.border="1px solid rgb(210, 8, 8)";
    		val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
    		val1.style.outline="medium none";
    		document.getElementById("reg_user_login_err_div").style.display="block";
    		document.getElementById("reg_user_login_err").innerHTML="User Name already exist. Please choose another"; 
    	
    		return false;}*/
    else {}

    var val2 = document.getElementById("reg_user_email");
    var check = validateEmail(val2.value);


    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("reg_user_email_err_div").style.display = "block";
      document.getElementById("reg_user_email_err").innerHTML = "Please enter email";
      return false;
    }
    if (!check && (val2.value != "")) {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("reg_user_email-help").style.color = "red !important";
      //document.getElementById("reg_user_email_err_div").style.display="block";
      //document.getElementById("reg_user_email_err").innerHTML="Enter a valid email"; 
      return false;
    }


    var emexist = ajaxemail(val2.value);

    if (document.getElementById("reg_user_email_err").innerHTML != "") {
      return false;
    }


    /*if((emexist==0) && check && (val2.value!="")){
     val.style.border="1px solid rgb(210, 8, 8)";
    val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
    val.style.outline="medium none";
     document.getElementById(id+"_err_div").style.display="block";
    document.getElementById(id+"_err").innerHTML="Email already taken. choose another"; 
    return false;}*/
    else {}



    var val5 = document.getElementById("reg_user_pass");
    var check = validatePass(val5.value);
    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      document.getElementById("reg_user_pass_err_div").style.display = "block";
      document.getElementById("reg_user_pass_err").innerHTML = "Please enter Password";
      return false;
    }
    if (!check && (val5.value != "")) {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      document.getElementById("reg_user_pass-help").style.color = "red !important";
      //document.getElementById("reg_user_pass_err_div").style.display="block";
      //document.getElementById("reg_user_pass_err").innerHTML="Must contain minm of 8 characters"; 
      return false;
    } else {}


    var val6 = document.getElementById("reg_user_pass_confirm");

    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      document.getElementById("reg_user_pass_confirm_err_div").style.display = "block";
      document.getElementById("reg_user_pass_confirm_err").innerHTML = "Please enter Password";
      return false;
    }
    if (val6.value != val5.value) {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      jQuery("#reg_user_pass_confirm-help").css("color", "red");
      //document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
      //document.getElementById("reg_user_pass_confirm_err").innerHTML="Passwords did not match"; 
      return false;
    } else {}


    var val3 = document.getElementById("phone");
    var check = validatePhone(val3.value);
    if (val3.value == "") {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("phone_err_div").style.display = "block";
      document.getElementById("phone_err").innerHTML = "Please enter contact no.";
      return false;
    }
    if (!check && (val3.value != "")) {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("phone-help").style.color = "red !important";
      //document.getElementById("phone_err_div").style.display="block";
      //document.getElementById("phone_err").innerHTML="Enter a valid Phone no."; 
      return false;
    } else {}


	//alert('ok. go for cc');
	
	
    var val7 = document.getElementById("cc");
   
	var val71=document.getElementById("cc_err");
	
	 var check = ajaxCC(val7.value);
	
	//alert(val71.innerHTML);
	//alert(val7.value);

	var c=val71.innerHTML;

	 if(val7.value!="")
	{

	//alert("c:"+c);
	if(c!="")
	{
	//alert("cc not null but err");
      	return false;
	}
	else
	{
	//alert("cc not null but valid");
      	//return true;

	}

	}
      else { 
      	
	//alert("cc null");
		//return true;
      	}

         	

    //if (document.getElementById("cc_div_show").style.display == "block") {
      //var val7 = document.getElementById("cc");
      //var check=ajaxCC(val7.value);
      //if(val7.style.display=="block")
      //{

      /* if (val7.value == "") {
        val7.style.border = "1px solid rgb(210, 8, 8)";
        val7.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
        val7.style.outline = "medium none";
        document.getElementById("cc_err_div").style.display = "block";
        document.getElementById("cc_err").innerHTML = "Please enter coupon";
        return false;
      }*/

     

      /*if((check==1 || check==2) && (val7.value != "")){
      	val7.style.border="1px solid rgb(210, 8, 8)";
      	val7.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
      	val7.style.outline="medium none";
      	document.getElementById("cc_err_div").style.display="block";
      	document.getElementById("cc_err").innerHTML="Enter a valid Coupon"; 
      	return false;}
      else { 
      	
      	}*/

      //}
      //else
      //{
      //	return true;
      //}

   // }


//alert('ok. go for t&c');


    var val4 = document.getElementById("uultra-terms-and-conditions-confirmation1");
    if (val4.checked == false) {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
      document.getElementById("uultra-terms-and-conditions-confirmation1_err_div").style.display = "block";
      document.getElementById("uultra-terms-and-conditions-confirmation1_err").innerHTML = "Please check the Terms-and-service checkbox";
      return false;
    } else {

    }

//alert('ok. swal');

    //swal('Everything OK. Processing . . .');
    swal({
      title: "",
      text: "<p class='saving'>Processing  <span class='dt'>.</span><span class='dt'>.</span><span class='dt'>.</span></p>",
      type: "warning",
      html: "true",
      showCancelButton: false,
      showConfirmButton: false,
      confirmButtonColor: "#F9F4F3",
      cancelButtonColor: "#607D8B !important",
      confirmButtonText: "",
    });

    //return false;
    return true;

  }
  //reg btn

  if (id == "reg_user_login") {
    var val = document.getElementById("reg_user_login");
    var check = validateUsername(val.value);

    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_login-help").css("color", "black");
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter username"; */
      return false;
    }


    if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_login-help").css("color", "red");
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="UserName should not contain special char and shouldn\'t start with underscore"; */
      return false;
    }

    var uexist = ajaxuser(val.value);

    if (document.getElementById("reg_user_login_err").innerHTML != "") {
      return false;
    }


    /*if((uexist==0) && (val.value!="")){
					val1.style.border="1px solid rgb(210, 8, 8)";
					val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val1.style.outline="medium none";
					document.getElementById("reg_user_login_err_div").style.display="block";
					document.getElementById("reg_user_login_err").innerHTML="User Name already exist. Please choose another"; 
				
					return false;}*/
    else {
      jQuery("#reg_user_login-help").css("color", "black");
      return true;
    }
  }

  if (id == "reg_user_email" || id == "email" || id == "email_fb" || id == "email_cf") {
    var val = document.getElementById(id);
    var check = validateEmail(val.value);


    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_email-help").css("color", "black");
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter email"; */
      return false;
    }
    if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_email-help").css("color", "red");
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Enter a valid email"; */
      return false;
    }


    if (id == "reg_user_email") {
      var emexist = ajaxemail(val.value);

      if (document.getElementById("reg_user_email_err").innerHTML != "") {
        return false;
      }

    }

    /*if(id=="reg_user_email")
    {
    	var emexist=ajaxemail(val.value);
    	if((emexist==0) && check && (val.value!="")){
    	 val.style.border="1px solid rgb(210, 8, 8)";
    	val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
    	val.style.outline="medium none";
    	 document.getElementById(id+"_err_div").style.display="block";
    	document.getElementById(id+"_err").innerHTML="Email already taken. choose another"; 
    	return false;}
    }*/
    else {
      jQuery("#reg_user_email-help").css("color", "black");
      return true;
    }
  }

  if (id == "phone" || id == "phone_fb" || id == "phone_cf") {
    var val = document.getElementById(id);
    var check = validatePhone(val.value);
    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#phone-help").css("color", "black");
      /*document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter contact no."; */
      return false;
    }

    if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#phone-help").css("color", "red");
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Enter a valid no."; */
      return false;
    } else {
      jQuery("#phone-help").css("color", "black");
      return true;
    }
  }


  if (id == "uultra-terms-and-conditions-confirmation1") {
    var val = document.getElementById("uultra-terms-and-conditions-confirmation1");
    if (val.checked == false) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      /* document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please check this in agreement"; */
      return false;
    } else { /*document.getElementById("uultra-terms-and-conditions-confirmation1").setCustomValidity(""); */
      return true;
    }
  }

  if (id == "reg_user_pass") {
    var val5 = document.getElementById("reg_user_pass");
    var check = validatePass(val5.value);
    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      jQuery("#reg_user_pass-help").css("color", "black");
      /*document.getElementById("reg_user_pass_err_div").style.display="block";
      document.getElementById("reg_user_pass_err").innerHTML="Please enter Password"; */
      return false;
    }
    if (!check && (val5.value != "")) {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      jQuery("#reg_user_pass-help").css("color", "red");
      /* document.getElementById("reg_user_pass_err_div").style.display="block";
      document.getElementById("reg_user_pass_err").innerHTML="Must contain minm of 8 characters"; */
      return false;
    } else {
      jQuery("#reg_user_pass-help").css("color", "black");
      return true;
    }
  }

  if (id == "reg_user_pass_confirm") {
    var val6 = document.getElementById("reg_user_pass_confirm");
    var val5 = document.getElementById("reg_user_pass");
    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      jQuery("#reg_user_pass_confirm-help").css("color", "black");
      /* document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
      document.getElementById("reg_user_pass_confirm_err").innerHTML="Please enter Password"; */
      return false;
    }
    if (val6.value != val5.value) {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      jQuery("#reg_user_pass_confirm-help").css("color", "red");
      /* document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
      document.getElementById("reg_user_pass_confirm_err").innerHTML="Passwords did not match"; */
      return false;
    } else {
      jQuery("#reg_user_pass_confirm-help").css("color", "black");
      return true;
    }
  }


  if (id == "cc") {
    var val7 = document.getElementById("cc");
    //var check=ajaxCC(val7.value);
    //if(val7.style.display=="block")
    //{

   /* if (val7.value == "") {
      val7.style.border = "1px solid rgb(210, 8, 8)";
      val7.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val7.style.outline = "medium none";
      document.getElementById("cc_err_div").style.display = "block";
      document.getElementById("cc_err").innerHTML = "Please enter coupon";
      return false;
    }*/


  // var val7 = document.getElementById("cc");
   
	var val71=document.getElementById("cc_err");
	
	 var check = ajaxCC(val7.value);
	
	//alert(val71.innerHTML);
	//alert(val7.value);

	var c=val71.innerHTML;

	 if(val7.value!="")
	{

	//alert("c:"+c);
	if(c!="")
	{
	//alert("cc not null but err");
      	return false;
	}
	else
	{
	//alert("cc not null but valid");
      	//return true;

	}

	}
      else { 
      	
	//alert("cc null");
		//return true;
      	}
    //return false;
   /* if ((check == 1 || check == 2) && (val7.value != "")) {
      val7.style.border = "1px solid rgb(210, 8, 8)";
      val7.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val7.style.outline = "medium none";
      document.getElementById("cc_err_div").style.display = "block";
      document.getElementById("cc_err").innerHTML = "Enter a valid Coupon";
      return false;
    } else {
      return true;
    }*/




    //}
    //else
    //{
    //	return true;
    //}

  }

  /*registration end */


  /* Update Profile start*/
  if (id == "xoouserultra-update") {
    var val3 = document.getElementById("phone");
    var check = validatePhone(val3.value);
    if (val3.value == "") {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("phone_err_div").style.display = "block";
      document.getElementById("phone_err").innerHTML = "Please enter contact no.";
      return false;
    }
    if (!check && (val3.value != "")) {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("phone_err_div").style.display = "block";
      document.getElementById("phone_err").innerHTML = "Enter a valid contact no.";
      return false;
    } else {}
  }

  /* Update prof end */

  /* Contact form start*/

  if (id == "contact_submit") {

    var val1 = document.getElementById("first_name");

    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("first_name_err_div").style.display = "block";
      document.getElementById("first_name_err").innerHTML = "Please enter First Name";

      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("first_name_err_div").style.display = "block";
      document.getElementById("first_name_err").innerHTML = "First Name should not contain special char and shouldn\'t start with underscore";

      return false;
    } else {}

    var val2 = document.getElementById("last_name");
    var check = validateUsername(val2.value);

    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("last_name_err_div").style.display = "block";
      document.getElementById("last_name_err").innerHTML = "Please enter Last Name";
      return false;
    }
    if (!check && (val2.value != "")) {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("last_name_err_div").style.display = "block";
      document.getElementById("last_name_err").innerHTML = "Last Name should not contain special char and shouldn\'t start with underscore";
      return false;
    } else {}

    var val3 = document.getElementById("email_cf");
    var check = validateEmail(val3.value);

    if (val3.value == "") {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("email_cf_err_div").style.display = "block";
      document.getElementById("email_cf_err").innerHTML = "Please enter email";
      return false;
    }
    if (!check && (val3.value != "")) {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("email_cf_err_div").style.display = "block";
      document.getElementById("email_cf_err").innerHTML = "Enter a valid email";
      return false;
    } else {}


    var val4 = document.getElementById("phone_cf");
    var check = validatePhone(val4.value);
    if (val4.value == "") {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
      document.getElementById("phone_cf_err_div").style.display = "block";
      document.getElementById("phone_cf_err").innerHTML = "Please enter contact no.";
      return false;
    }
    if (!check && (val4.value != "")) {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
      document.getElementById("phone_cf_err_div").style.display = "block";
      document.getElementById("phone_cf_err").innerHTML = "Enter a valid Phone no.";
      return false;
    } else {}


    var val5 = document.getElementById("address");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      document.getElementById("address_err_div").style.display = "block";
      document.getElementById("address_err").innerHTML = "Please fill the address";
      return false;
    } else {}


    var val6 = document.getElementById("cf_comment");

    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      document.getElementById("cf_comment_err_div").style.display = "block";
      document.getElementById("cf_comment_err").innerHTML = "Please fill the message";
      return false;
    } else {
      return true;
    }

  } //end of submit


  if (id == "first_name") {
    var val1 = document.getElementById("first_name");

    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";


      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }


  if (id == "last_name") {
    var val1 = document.getElementById("last_name");

    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";


      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }


  if (id == "address") {
    var val5 = document.getElementById("address");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";

      return false;
    } else {
      return true;
    }

  }

  if (id == "cf_comment") {
    var val6 = document.getElementById("cf_comment");

    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }



  /*contact form end */



  /* Feedback form start*/

  if (id == "fb_submit") {

    var val1 = document.getElementById("name");

    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("name_err_div").style.display = "block";
      document.getElementById("name_err").innerHTML = "Please enter Name";

      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("name_err_div").style.display = "block";
      document.getElementById("name_err").innerHTML = "Name should not contain special char and shouldn\'t start with underscore";

      return false;
    } else {}


    var form = document.getElementById("feedback_form");
    if ((form.Gender[0].checked == false) && (form.Gender[1].checked == false)) {
      document.getElementById("Gender_err_div").style.display = "block";
      document.getElementById("Gender_err").innerHTML = "Please select Gender";
      return false;
    }

    var genM = document.getElementById("GenderM");
    var genF = document.getElementById("GenderF");
    if (genM.value == "" || genF.value == "") {
      document.getElementById("Gender_err_div").style.display = "block";
      document.getElementById("Gender_err").innerHTML = "Please select Gender";
      return false;
    } else {}


    var val7 = document.getElementById("country");

    if (val7.value == "") {
      val7.style.border = "1px solid rgb(210, 8, 8)";
      val7.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val7.style.outline = "medium none";
      document.getElementById("country_err_div").style.display = "block";
      document.getElementById("country_err").innerHTML = "Please select your country";
      return false;
    } else {}


    var val3 = document.getElementById("email_fb");
    var check = validateEmail(val3.value);

    if (val3.value == "") {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("email_fb_err_div").style.display = "block";
      document.getElementById("email_fb_err").innerHTML = "Please enter email";
      return false;
    }
    if (!check && (val3.value != "")) {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
      document.getElementById("email_fb_err_div").style.display = "block";
      document.getElementById("email_fb_err").innerHTML = "Enter a valid email";
      return false;
    } else {}


    var val4 = document.getElementById("phone_fb");
    var check = validatePhone(val4.value);
    if (val4.value == "") {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
      document.getElementById("phone_fb_err_div").style.display = "block";
      document.getElementById("phone_fb_err").innerHTML = "Please enter contact no.";
      return false;
    }
    if (!check && (val4.value != "")) {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
      document.getElementById("phone_fb_err_div").style.display = "block";
      document.getElementById("phone_fb_err").innerHTML = "Enter a valid Phone no.";
      return false;
    } else {}

    var val8 = document.getElementById("qpPlatform");

    if (val8.value == "") {
      val8.style.border = "1px solid rgb(210, 8, 8)";
      val8.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val8.style.outline = "medium none";
      document.getElementById("qpPlatform_err_div").style.display = "block";
      document.getElementById("qpPlatform_err").innerHTML = "Please select the QezyPlay Platform";
      return false;
    } else {}


    var val5 = document.getElementById("Channel");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      document.getElementById("Channel_err_div").style.display = "block";
      document.getElementById("Channel_err").innerHTML = "Please enter Channel Name";
      return false;
    } else {}


    var val6 = document.getElementById("fb_comment");

    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      document.getElementById("fb_comment_err_div").style.display = "block";
      document.getElementById("fb_comment_err").innerHTML = "Please fill the message";
      return false;
    } else {
      return true;
    }

  } //end of submit


  if (id == "name") {
    var val1 = document.getElementById("name");

    var check = validateUsername(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";


      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }


  if (id == "country") {
    var val5 = document.getElementById("country");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }

  if (id == "qpPlatform") {
    var val5 = document.getElementById("qpPlatform");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";

      return false;
    } else {
      return true;
    }

  }

  if (id == "Channel") {
    var val5 = document.getElementById("Channel");

    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";

      return false;
    } else {
      return true;
    }

  }

  if (id == "fb_comment") {
    var val6 = document.getElementById("fb_comment");

    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";

      return false;
    } else {
      return true;
    }
  }





  /* Feedback form end*/


  /* Reset Password (after fp from mail)*/

  if (id == "xoouserultra-reset-confirm-pass-btn")

  {

    var val1 = document.getElementById("preset_password");
    var check = validatePass(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("preset_password_err_div").style.display = "block";
      document.getElementById("preset_password_err").innerHTML = "Please enter Password";
      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("preset_password_err_div").style.display = "block";
      document.getElementById("preset_password_err").innerHTML = "Must contain minm of 8 characters";
      return false;
    } else {}


    var val2 = document.getElementById("preset_password_2");

    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("preset_password_2_err_div").style.display = "block";
      document.getElementById("preset_password_2_err").innerHTML = "Please enter Password";
      return false;
    }
    if (val2.value != val1.value) {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("preset_password_2_err_div").style.display = "block";
      document.getElementById("preset_password_2_err").innerHTML = "Passwords did not match";
      return false;
    } else {
      return true;
    }

  }

  if (id == "preset_password") {
    var val1 = document.getElementById("preset_password");
    var check = validatePass(val1.value);
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("preset_password_err_div").style.display = "block";
      document.getElementById("preset_password_err").innerHTML = "Please enter Password";
      return false;
    }
    if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
      document.getElementById("preset_password_err_div").style.display = "block";
      document.getElementById("preset_password_err").innerHTML = "Must contain minm of 8 characters ";
      return false;
    } else {
      return true;
    }

  }


  if (id == "preset_password_2") {

    var val2 = document.getElementById("preset_password_2");

    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("preset_password_2_err_div").style.display = "block";
      document.getElementById("preset_password_2_err").innerHTML = "Please enter Password";
      return false;
    }
    if (val2.value != val1.value) {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      document.getElementById("preset_password_2_err_div").style.display = "block";
      document.getElementById("preset_password_2_err").innerHTML = "Passwords did not match";
      return false;
    } else {
      return true;
    }

  }
  /* */

} //end of fn

function styleback(id) {
  var id = id;
  if (id == "user_name_email") {
    document.getElementById("user_name_email_err_div").style.display = "none";
    document.getElementById("user_name_email").style.border = "1px solid black !important";
    document.getElementById("user_name_email").style.boxShadow = "0px 0px 0px black !important";
  } else if (id == "Login" || id == "user_login" || id == "login_user_pass") {
    document.getElementById("user_login_err_div").style.display = "none";
    document.getElementById("user_login").style.border = "1px solid black";
    document.getElementById("user_login").style.boxShadow = "0px 0px 0px black";
    document.getElementById("login_user_pass_err_div").style.display = "none";
    document.getElementById("login_user_pass").style.border = "1px solid black";
    document.getElementById("login_user_pass").style.boxShadow = "0px 0px 0px black";
  } else if (id == "xoouserultra-update") {
    document.getElementById("phone_err_div").style.display = "none";
    document.getElementById("phone").style.border = "1px solid black";
    document.getElementById("phone").style.boxShadow = "0px 0px 0px black";
  } else if (id == "reg_user_login" || id == "reg_user_email" || id == "phone" || id == "uultra-terms-and-conditions-confirmation1" || id == "reg_user_pass" || id == "reg_user_pass_confirm" || id == "cc") {
    document.getElementById("reg_user_login_err_div").style.display = "none";
    document.getElementById("reg_user_login").style.border = "1px solid black";
    document.getElementById("reg_user_login").style.boxShadow = "0px 0px 0px black";
    //jQuery("#reg_user_login-help").css("color","black");

    document.getElementById("reg_user_email_err_div").style.display = "none";
    document.getElementById("reg_user_email").style.border = "1px solid black";
    document.getElementById("reg_user_email").style.boxShadow = "0px 0px 0px black";
    //jQuery("#reg_user_email-help").css("color","black");

    document.getElementById("reg_user_pass_err_div").style.display = "none";
    document.getElementById("reg_user_pass").style.border = "1px solid black";
    document.getElementById("reg_user_pass").style.boxShadow = "0px 0px 0px black";
    //jQuery("#reg_user_pass-help").css("color","black");

    document.getElementById("reg_user_pass_confirm_err_div").style.display = "none";
    document.getElementById("reg_user_pass_confirm").style.border = "1px solid black";
    document.getElementById("reg_user_pass_confirm").style.boxShadow = "0px 0px 0px black";
    //jQuery("#reg_user_pass_confirm-help").css("color","black");

    document.getElementById("phone_err_div").style.display = "none";
    document.getElementById("phone").style.border = "1px solid black";
    document.getElementById("phone").style.boxShadow = "0px 0px 0px black";
    //jQuery("#phone-help").css("color","black");

    if (document.getElementById("cc_div_show").style.display == "block") {
      document.getElementById("cc_err_div").style.display = "none";
      document.getElementById("cc").style.border = "1px solid black";
      document.getElementById("cc").style.boxShadow = "0px 0px 0px black";
      //jQuery("#reg_user_pass-help").css("color","black");
    }

    document.getElementById("uultra-terms-and-conditions-confirmation1_err_div").style.display = "none";
    document.getElementById("uultra-terms-and-conditions-confirmation1").style.border = "1px solid black";
    document.getElementById("uultra-terms-and-conditions-confirmation1").style.boxShadow = "0px 0px 0px black";
  } else if (id == "first_name" || id == "last_name" || id == "phone_cf" || id == "email_cf" || id == "cf_comment" || id == "address") {

    document.getElementById("first_name_err_div").style.display = "none";
    document.getElementById("first_name").style.border = "1px solid black";
    document.getElementById("first_name").style.boxShadow = "0px 0px 0px black";


    document.getElementById("last_name_err_div").style.display = "none";
    document.getElementById("last_name").style.border = "1px solid black";
    document.getElementById("last_name").style.boxShadow = "0px 0px 0px black";


    document.getElementById("phone_cf_err_div").style.display = "none";
    document.getElementById("phone_cf").style.border = "1px solid black";
    document.getElementById("phone_cf").style.boxShadow = "0px 0px 0px black";

    document.getElementById("email_cf_err_div").style.display = "none";
    document.getElementById("email_cf").style.border = "1px solid black";
    document.getElementById("email_cf").style.boxShadow = "0px 0px 0px black";

    document.getElementById("address_err_div").style.display = "none";
    document.getElementById("address").style.border = "1px solid black";
    document.getElementById("address").style.boxShadow = "0px 0px 0px black";

    document.getElementById("cf_comment_err_div").style.display = "none";
    document.getElementById("cf_comment").style.border = "1px solid black";
    document.getElementById("cf_comment").style.boxShadow = "0px 0px 0px black";

  } else if (id == "name" || id == "country" || id == "GenderM" || id == "GenderF" || id == "phone_fb" || id == "email_fb" || id == "fb_comment" || id == "Channel" || id == "qpPlatform") {

    document.getElementById("name_err_div").style.display = "none";
    document.getElementById("name").style.border = "1px solid black";
    document.getElementById("name").style.boxShadow = "0px 0px 0px black";


    document.getElementById("country_err_div").style.display = "none";
    document.getElementById("country").style.border = "1px solid black";
    document.getElementById("country").style.boxShadow = "0px 0px 0px black";


    document.getElementById("phone_fb_err_div").style.display = "none";
    document.getElementById("phone_fb").style.border = "1px solid black";
    document.getElementById("phone_fb").style.boxShadow = "0px 0px 0px black";

    document.getElementById("email_fb_err_div").style.display = "none";
    document.getElementById("email_fb").style.border = "1px solid black";
    document.getElementById("email_fb").style.boxShadow = "0px 0px 0px black";

    document.getElementById("Channel_err_div").style.display = "none";
    document.getElementById("Channel").style.border = "1px solid black";
    document.getElementById("Channel").style.boxShadow = "0px 0px 0px black";

    document.getElementById("fb_comment_err_div").style.display = "none";
    document.getElementById("fb_comment").style.border = "1px solid black";
    document.getElementById("fb_comment").style.boxShadow = "0px 0px 0px black";

    document.getElementById("qpPlatform_err_div").style.display = "none";
    document.getElementById("qpPlatform").style.border = "1px solid black";
    document.getElementById("qpPlatform").style.boxShadow = "0px 0px 0px black";

    document.getElementById("Gender_err_div").style.display = "none";

  } else if (id == "preset_password" || id == "preset_password_2") {

    document.getElementById("preset_password_err_div").style.display = "none";
    document.getElementById("preset_password").style.border = "1px solid black";
    document.getElementById("preset_password").style.boxShadow = "0px 0px 0px black";


    document.getElementById("preset_password_2_err_div").style.display = "none";
    document.getElementById("preset_password_2").style.border = "1px solid black";
    document.getElementById("preset_password_2").style.boxShadow = "0px 0px 0px black";

  }

}


function validateEmail(email) {
  /* var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; */
  /* var re=/^[a-zA-Z0-9.!#$%&Â’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9](?:\.[a-zA-Z0-9-]+)*$/; */
 // var re = /^[a-zA-Z0-9.!#$%&Â’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/;
  var re = /^[a-zA-Z0-9.!#$%&Â’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.][^..][a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/;
  return re.test(email);
}


function validateUsername(name) {
  /* var re=/^(?!_).*\\W+.*$/; */
  //var re= /^(((?!_)[A-Za-z0-9])+[A-Za-z0-9_]*){4,10}$/;
  var re = /^(?=.{4,15}$)(((?!_)[A-Za-z0-9])+[A-Za-z0-9_]*)$/;
  return re.test(name);
}


function validatePhone(phone) {
  var re = /^\+(?:[0-9] ?){6,14}[0-9]$/;

  return re.test(phone); /*(ITU-T E.164)*/

}

function validatePass(pass) {
  //var re=/^(?=.*\d)(?=.*[A-Z])(?=.*[!@#$%^&*()?])[0-9A-Za-z!@#$%^&*()?]{8,50}$/;
  var re = /^.{8,16}$/;
  return re.test(pass);
}

function ajaxCC(cc) {

  var values = {
    'action': 'couponCheck',
    'cc': cc
  };



  if (cc != "") {
    var chk;
    jQuery.ajax({
      url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
      type: 'post',
      data: values,
      success: function(response) {

        if (response == 1 || response == 2) {
          val1 = document.getElementById("cc");
          val1.style.border = "1px solid rgb(210, 8, 8)";
          val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
          val1.style.outline = "medium none";
          document.getElementById("cc_err_div").style.display = "block";
          if (response == 1)
            document.getElementById("cc_err").innerHTML = "Invalid Coupon code";
          if (response == 2)
            document.getElementById("cc_err").innerHTML = "Couponcode/Promocode expired";
          //document.getElementById("invalid").value="false";
          //return false;
        } else {
          document.getElementById("cc_err_div").style.display = "none";
          document.getElementById("cc_err").innerHTML = "";
          document.getElementById("cc").style.border = "1px solid black !important";
          document.getElementById("cc").style.boxShadow = "0px 0px 0px black !important";
          //document.getElementById("invalid").value="true";
          //return true;
        }
      }
    });


  } else {

		document.getElementById("cc_err_div").style.display = "none";
          document.getElementById("cc_err").innerHTML = "";
          document.getElementById("cc").style.border = "1px solid black !important";
          document.getElementById("cc").style.boxShadow = "0px 0px 0px black !important";
    //		document.getElementById("invalid").value="true";
  }

}

function ajaxuser(user) {

  var values = {
    'action': 'userExist',
    'u_name': user
  };
  if (user != "") {
    jQuery.ajax({
      url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
      type: 'post',
      data: values,
      success: function(response) {

        if (response == 2) {

          //var reg_email="siddishg@gmail.com";
          val1 = document.getElementById("reg_user_login");
          val1.style.border = "1px solid rgb(210, 8, 8)";
          val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
          val1.style.outline = "medium none";
          document.getElementById("reg_user_login_err_div").style.display = "block";
	document.getElementById("reg_user_login_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_login_err").innerHTML = '<script>  			</script>';


          swal({
              title: "Info: Account Pending",
              text: "<p>Please activate your account by clicking on activation link in the Verification Mail sent to you</p> <p>To change your Email, click on Edit-Email</p><p> If you did not receive the mail, click on Resend-Email</p><p> To close this box, click on Done</p><p>Thank You</p><a href=\'http://ideabytestraining.com/newqezyplay/register\' style=\'padding: 10px 10%;   background-color: green !important;    border-radius: 5px !important;    text-align: center;    font-size: 14px;    margin-bottom: -12px;color: white;  text-decoration: none;\'>Done</a>",
              type: "warning",
              html: "true",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              cancelButtonColor: "#29a1d8 !important",
              confirmButtonText: "Resend-Email",
              cancelButtonText: "Edit-Email",
              allowEscapeKey: false,
              closeOnConfirm: false,
              closeOnCancel: false,
              showLoaderOnConfirm: true,
              showLoaderOnCancel: true
            },
            function(isConfirm) {
              var valuesx = {
                "action": "getEmail",
                "user": user
              };
			
              var reg_email=
              jQuery.ajax({
                url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
                type: "post",
                data: valuesx,
		 global: false,
               async:false,
                success: function(data) {
                  reg_email = data; 
                }
		
              }).responseText;

		 SweetAlertMultiInputReset();
		  // make sure you call this
              if (isConfirm) {
		SweetAlertMultiInputReset();
                var values = {
                  "action": "resendEmail",
                  "user_email": reg_email
                };
                jQuery.ajax({
                  url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
                  type: "post",
                  data: values,
                  success: function(data) {
                    swal(data);
                    return true;
                  }
                });
                return false;
              } else {
                var values;
		SweetAlertMultiInputReset();
                swal({
                  title: "Update Email",
                  text: "Please enter new email and last password",
                  type: "input",
		allowEscapeKey: false,
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  closeOnConfirm: false,
                  animation: "slide-from-top"
                }, function(inputValue) {
                  var x = JSON.parse(inputValue);
                  var email = x[0];
                  var pwd = x[1];
                  if (email == "") {

                    swal.showInputError("Please Enter Email");
                    return false
                  }


                  var ch = validateEmail(email);
                  if (!ch) {
                    swal.showInputError("Please Enter Valid Email");
                    return false
                  }

                  if (pwd == "") {

                    swal.showInputError("Please Enter Password");
                    return false
                  }

                  SweetAlertMultiInputReset(); // make sure you call this


                  //  function(){SweetAlertMultiInputFix()}); // fix used if you want to display another box immediately

                  if (email != "" && ch && pwd != "") {
                    values = {
                      "action": "regEditEmailPass",
                      "user_email": email,
                      "old_user_email": reg_email,
                      "pwd": pwd
                    };
                    jQuery.ajax({
                      url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
                      type: "POST",
                      data: values,
                      success: function(data) {
                        swal({   title: "",   text: data,   html: true });
                        return true;
                      }

                    });

                    return false;
                  }

                });
                //set up the fields: labels
                var tooltipsArray = ["New Email", "Password"];
                //set up the fields: placeholder
                var defaultsArray = ["", ""];
                //set up the fields: input-type
                var typesArray = ["text", "password"];
		SweetAlertMultiInputReset();
                SweetAlertMultiInput(tooltipsArray, defaultsArray, typesArray);

                return false;

              }

            });

SweetAlertMultiInputReset();
          return;
        } else if (response == 1) {
          val1 = document.getElementById("reg_user_login");
          val1.style.border = "1px solid rgb(210, 8, 8)";
          val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
          val1.style.outline = "medium none";
          document.getElementById("reg_user_login_err_div").style.display = "block";
	document.getElementById("reg_user_login_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_login_err").innerHTML = "<script></script>";
		 SweetAlertMultiInputReset();
		swal('Error','Username already exists. Please select another');
        } else {
          val1 = document.getElementById("reg_user_login");
          val1.style.border = "1px solid black !important";
          val1.style.boxShadow = "0px 0px 0px black !important";
          document.getElementById("reg_user_login_err_div").style.display = "none";
          document.getElementById("reg_user_login_err").innerHTML = "";

        }

      }
    });
  }

}

function ajaxemail(email) {

  var values = {
    'action': 'emailExist',
    'u_email': email
  };
  if (email != "") {
    jQuery.ajax({
      url: 'http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php',
      type: 'post',
      data: values,
      success: function(response) {



        if (response == 2) {

         
          val1 = document.getElementById("reg_user_email");
          val1.style.border = "1px solid rgb(210, 8, 8)";
          val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
          val1.style.outline = "medium none";
          document.getElementById("reg_user_email_err_div").style.display = "block";
	document.getElementById("reg_user_email_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_email_err").innerHTML = '<script>  			</script>';


           swal({
              title: "Account Pending",
              text: "<p>Please activate your account by clicking on activation link in the Verification Mail sent to you</p> <p>To change your Email, click on Edit-Email</p><p> If you did not receive the mail, click on Resend-Email</p><p> To close this box, click on Done</p><p>Thank You</p><a href=\'http://ideabytestraining.com/newqezyplay/register\' style=\'padding: 10px 10%;   background-color: green !important;    border-radius: 5px !important;    text-align: center;    font-size: 14px;    margin-bottom: -12px;color: white;  text-decoration: none;\'>Done</a>",
              type: "warning",
              html: "true",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              cancelButtonColor: "#29a1d8 !important",
              confirmButtonText: "Resend-Email",
              cancelButtonText: "Edit-Email",
              allowEscapeKey: false,
              closeOnConfirm: false,
              closeOnCancel: false,
              showLoaderOnConfirm: true,
              showLoaderOnCancel: true
            },
            function(isConfirm) {
              var reg_email = val1.value;
		
             
              SweetAlertMultiInputReset();
		  // make sure you call this
              if (isConfirm) {
		SweetAlertMultiInputReset();
                var values = {
                  "action": "resendEmail",
                  "user_email": reg_email
                };
                jQuery.ajax({
                  url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
                  type: "post",
                  data: values,
                  success: function(data) {
                    swal(data);
                    return true;
                  }
                });
                return false;
              } else {
                var values;
		SweetAlertMultiInputReset();
                swal({
                  title: "Update Email",
                  text: "Please enter new email and last password",
                  type: "input",
		allowEscapeKey: false,
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  closeOnConfirm: false,
                  animation: "slide-from-top"
                }, function(inputValue) {
                  var x = JSON.parse(inputValue);
                  var email = x[0];
                  var pwd = x[1];
                  if (email == "") {

                    swal.showInputError("Please Enter Email");
                    return false
                  }


                  var ch = validateEmail(email);
                  if (!ch) {
                    swal.showInputError("Please Enter Valid Email");
                    return false
                  }

                  if (pwd == "") {

                    swal.showInputError("Please Enter Password");
                    return false
                  }

                  SweetAlertMultiInputReset(); // make sure you call this


                  //  function(){SweetAlertMultiInputFix()}); // fix used if you want to display another box immediately

                  if (email != "" && ch && pwd != "") {
                    values = {
                      "action": "regEditEmailPass",
                      "user_email": email,
                      "old_user_email": reg_email,
                      "pwd": pwd
                    };
                    jQuery.ajax({
                      url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
                      type: "POST",
                      data: values,
                      success: function(data) {
                        swal({   title: "",   text: data,   html: true });
                        return true;
                      }

                    });

                    return false;
                  }

                });
                //set up the fields: labels
                var tooltipsArray = ["New Email", "Password"];
                //set up the fields: placeholder
                var defaultsArray = ["", ""];
                //set up the fields: input-type
                var typesArray = ["text", "password"];
		SweetAlertMultiInputReset();
                SweetAlertMultiInput(tooltipsArray, defaultsArray, typesArray);

                return false;

              }

            });

SweetAlertMultiInputReset();
          return;
        } else if (response == 1) {
          val1 = document.getElementById("reg_user_email");
          val1.style.border = "1px solid rgb(210, 8, 8)";
          val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
          val1.style.outline = "medium none";
          document.getElementById("reg_user_email_err_div").style.display = "block";
	document.getElementById("reg_user_email_err").style.backgroundColor="transparent !important";
              document.getElementById("reg_user_email_err").innerHTML = "<script></script>";
		 SweetAlertMultiInputReset();
		swal('Error','Email already exists. Please select another');
        } else {
          val1 = document.getElementById("reg_user_email");
          val1.style.border = "1px solid black !important";
          val1.style.boxShadow = "0px 0px 0px black !important";
          document.getElementById("reg_user_login_err_div").style.display = "none";
          document.getElementById("reg_user_login_err").innerHTML = "";
        }

      }
    });
  }
}


function avoidspace(id) {
  var id = "phone";
  $('#' + id).keyup(function() {
    if (this.value == " ") {
      this.value = this.value.substring(0, this.value.length - 1);
    }
  });
}

