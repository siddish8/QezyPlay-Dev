var imported = document.createElement('script');
imported.src = '../qp/js/browser_validations_ext.js';
document.head.appendChild(imported);


function validate(id) {

  var id = id;

  /* registraion start */

  if (id == "qz-register-btn") {

    //username
    var val1 = document.getElementById("reg_user_login");
    var check = validateUsername(val1.value);   
    if (val1.value == "") {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";

      //document.getElementById("reg_user_login_err_div").style.display = "block";
       document.getElementById("reg_user_login_err").innerHTML="Please enter username";
        val1.setCustomValidity("Please enter username");
      document.getElementById("register-form").reportValidity();
   //  alert(val1.setCustomValidity());
      return false;
    }else if (!check && (val1.value != "")) {
      val1.style.border = "1px solid rgb(210, 8, 8)";
      val1.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val1.style.outline = "medium none";
     // jQuery("#reg_user_login-help").css("color", "red !important");
      //document.getElementById("reg_user_login_err_div").style.display="block";
      document.getElementById("reg_user_login_err").innerHTML="User name must be between 4 - 15 alphanumeric characters(Aa-Zz 0..9) and should not start with underscore"; 
      val1.setCustomValidity("User name must be between 4 - 15 alphanumeric characters(Aa-Zz 0..9) and should not start with underscore");
      document.getElementById("register-form").reportValidity();
      //alert(val1.setCustomValidity());
      return false;
    }else{

      var uexist = ajaxuser(val1.value);
      if (document.getElementById("reg_user_login_err").innerHTML != "") {
        return false;
       }
      else {
        document.getElementById("reg_user_login_err").innerHTML="";
       val1.setCustomValidity("");
      }

    }

   //email
    var val2 = document.getElementById("reg_user_email");
    var check = validateEmail(val2.value);
    if (val2.value == "") {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      val2.setCustomValidity("Please enter email");
      document.getElementById("register-form").reportValidity();
     // alert(val2.setCustomValidity());
      //document.getElementById("reg_user_email_err_div").style.display = "block";
      document.getElementById("reg_user_email_err").innerHTML = "Please enter email";
      return false;
    }else if (!check && (val2.value != "")) {
      val2.style.border = "1px solid rgb(210, 8, 8)";
      val2.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val2.style.outline = "medium none";
      val2.setCustomValidity("Please enter a valid email");
      document.getElementById("register-form").reportValidity();
     // alert(val2.setCustomValidity());
      //document.getElementById("reg_user_email-help").style.color = "red !important";
      //document.getElementById("reg_user_email_err_div").style.display="block";
      document.getElementById("reg_user_email_err").innerHTML="Enter a valid email"; 
      return false;
    }else{

      var emexist = ajaxemail(val2.value);
      if (document.getElementById("reg_user_email_err").innerHTML != "") {
        return false;
      }else {
         document.getElementById("reg_user_email_err").innerHTML=""; 
        val2.setCustomValidity("");
      }

    }

    //pwd
    var val5 = document.getElementById("reg_user_pass");
    var check = validatePass(val5.value);
    if (val5.value == "") {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      //document.getElementById("reg_user_pass_err_div").style.display = "block";
      val5.setCustomValidity("Please enter Password");
      document.getElementById("register-form").reportValidity();
      document.getElementById("reg_user_pass_err").innerHTML = "Please enter Password";
      return false;
    }else if (!check && (val5.value != "")) {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
      //document.getElementById("reg_user_pass-help").style.color = "red !important";
      //document.getElementById("reg_user_pass_err_div").style.display="block";
       val5.setCustomValidity("Password must be minimum of 8-16 characters");
      document.getElementById("register-form").reportValidity();
      document.getElementById("reg_user_pass_err").innerHTML="Password must be minimum of 8-16 characters"; 
      return false;
    } else {
      val5.setCustomValidity("");
      document.getElementById("reg_user_pass_err").innerHTML=""; 
    }


    //conf-pwd
    var val6 = document.getElementById("reg_user_pass_confirm");
    if (val6.value == "") {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
      //document.getElementById("reg_user_pass_confirm_err_div").style.display = "block";
       val6.setCustomValidity("Please re-enter Password");
         document.getElementById("register-form").reportValidity();
      document.getElementById("reg_user_pass_confirm_err").innerHTML = "Please re-enter Password";
      return false;
    }else if (val6.value != val5.value) {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
     // jQuery("#reg_user_pass_confirm-help").css("color", "red");
      //document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
       val6.setCustomValidity("Passwords does not match");
         document.getElementById("register-form").reportValidity();
      document.getElementById("reg_user_pass_confirm_err").innerHTML="Passwords does not match"; 
      return false;
    } else {

        val6.setCustomValidity("");
        document.getElementById("reg_user_pass_confirm_err").innerHTML="";

    }



     var val0 = document.getElementById("countryCode");
      if(val0.value==""){

      jQuery("#phone").css("background-position","right 10px center");
      val0.setCustomValidity("Please select a country");
       document.getElementById("register-form").reportValidity();
       
       return false;
    }
    else{
      jQuery("#phone").css("background-position","right -10px center");
      val0.setCustomValidity("");
    }

    //phone
    var val3 = document.getElementById("phone");
    var check = validatePhone(val3.value);
    if (val3.value == "") {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
    //  document.getElementById("phone_err_div").style.display = "block";
    val3.setCustomValidity("Please enter Phone number");
         document.getElementById("register-form").reportValidity();
      document.getElementById("phone_err").innerHTML = "Please enter phone number";
      return false;
    }else if (!check && (val3.value != "")) {
      val3.style.border = "1px solid rgb(210, 8, 8)";
      val3.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val3.style.outline = "medium none";
     // document.getElementById("phone-help").style.color = "red !important";
      //document.getElementById("phone_err_div").style.display="block";
        val3.setCustomValidity("Please enter a valid Phone number");
         document.getElementById("register-form").reportValidity();
      document.getElementById("phone_err").innerHTML="Please enter a valid Phone number"; 
      return false;
    } else {

          val3.setCustomValidity("");
         document.getElementById("phone_err").innerHTML=""; 

    }

	//alert('ok. go for cc');
	//coupon
   var val7 = document.getElementById("cc");
   var val71=document.getElementById("cc_err");
   var check = ajaxCC(val7.value);
   var c=val71.innerHTML;

	 if(val7.value!="")
	 {   
        if(c!="")
        {
      
              return false;
        }
        else
        {
          	val7.setCustomValidity("");
        }

	}
  else { 
      	val7.setCustomValidity("");
      	}
       	
  //alert('ok. go for tc');
  //t&c
    var val4 = document.getElementById("uultra-terms-and-conditions-confirmation1");
    if (val4.checked == false) {
      val4.style.border = "1px solid rgb(210, 8, 8)";
      val4.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val4.style.outline = "medium none";
     // document.getElementById("uultra-terms-and-conditions-confirmation1_err_div").style.display = "block";
     val4.setCustomValidity("Please check the Terms of Service checkbox");
         document.getElementById("register-form").reportValidity();
      document.getElementById("uultra-terms-and-conditions-confirmation1_err").innerHTML = "Please check the Terms of Service checkbox";
       
      return false;
    } else {

      val4.setCustomValidity("");
      document.getElementById("uultra-terms-and-conditions-confirmation1_err").innerHTML = "";

    }
// alert('ok. go for swal');
    swal({
      title: " ",
      text: "<p class='saving'>Processing  <span class='dt'>.</span><span class='dt'>.</span><span class='dt'>.</span></p>",
      type: "warning",
      html: "true",
      showCancelButton: false,
      showConfirmButton: false,
      confirmButtonColor: "#F9F4F3",
      cancelButtonColor: "#607D8B !important",
      confirmButtonText: "",
    });

    var val99=document.getElementById("ip");
    var val98=document.getElementById("countryCode");

    var ph="+"+val98.value+val3.value;

    var values={"username":val1.value,"email":val2.value,"password":val5.value,"phone":ph,"cc":val7.value,"ip":val99.value}
   jQuery.ajax({
                      url: "http://ideabytestraining.com/newqezyplay/webservices/api.php?request=register",
                      type: "POST",
                      data: values,
                      success: function(data, textStatus, request){
                            if(data.status==1)
                            {
                            swal({   title: "Registration Successful",   
                                    text: "Please check your mail to activate your account. And Enjoy Live Channels",   
                                    html: true,
                                    showCancelButton: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false },
                                    function() {
                                        window.location.href="http://ideabytestraining.com/newqezyplay/qp1/sign-up";
                            });
                                                  
                             } 
                             else
                              swal(' ' + request.getResponseHeader('error'));
                      },
                      error: function (request, textStatus, errorThrown) {
                            swal(' ' + request.getResponseHeader('error'));
                      }
                      
                    });

    return false;

  }
  //reg btn

  if (id == "reg_user_login") {
    var val = document.getElementById("reg_user_login");
    var check = validateUsername(val.value);

    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_login").css("background-position","right 10px center");
     // val.setCustomValidity("Please enter Username");
      // document.getElementById("register-form").reportValidity();
     // alert(val.setCustomValidity());
     // jQuery("#reg_user_login-help").css("color", "black");
      // document.getElementById(id+"_err_div").style.display="block";
       //document.getElementById(id+"_status").innerHTML="<i class='fa fa-times' aria-hidden='true'></i>";
      document.getElementById(id+"_err").innerHTML="Please enter username"; 
      return false;
    }else if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_login").css("background-position","right 10px center");
     // val.setCustomValidity("User name must be between 4 - 15 alphanumeric characters(Aa-Zz 0..9) and should not start with underscore");
     //  document.getElementById("register-form").reportValidity();
     // alert(val.setCustomValidity());
     // jQuery("#reg_user_login-help").css("color", "red");
      // document.getElementById(id+"_err_div").style.display="block";
     //  document.getElementById(id+"_status").innerHTML="<i class='fa fa-times' aria-hidden='true'></i>";
      document.getElementById(id+"_err").innerHTML="User name must be between 4 - 15 alphanumeric characters(Aa-Zz 0..9) and should not start with underscore"; 
      return false;
    }else{

      var uexist = ajaxuser(val.value);

      if (document.getElementById("reg_user_login_err").innerHTML != "") {

        return false;
      }
    else {
         // val.setCustomValidity("")
        //jQuery("#reg_user_login-help").css("color", "black");
        // document.getElementById(id+"_status").innerHTML="<i class='fa fa-check' aria-hidden='true'></i>";
        jQuery("#reg_user_login").css("background-position","right -10px center");
        document.getElementById(id+"_err").innerHTML="";
      return true;
      }


    }

    

  }

  if (id == "reg_user_email") {
    var val = document.getElementById(id);
    var check = validateEmail(val.value);


    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#reg_user_email").css("background-position","right 10px center");
     // val.setCustomValidity("Please enter Email");
      // document.getElementById("register-form").reportValidity();
      //jQuery("#reg_user_email-help").css("color", "black");
      // document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter email";
      return false;
    }else if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
       jQuery("#reg_user_email").css("background-position","right 10px center");
      //val.setCustomValidity("Please enter valid Email");
      // document.getElementById("register-form").reportValidity();
      //jQuery("#reg_user_email-help").css("color", "red");
      // document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Enter a valid email"; 
      return false;
    }else{

          var emexist = ajaxemail(val.value);

          if (document.getElementById("reg_user_email_err").innerHTML != "") {
            return false;
          }else {
             jQuery("#reg_user_email").css("background-position","right -10px center");
          // val.setCustomValidity("");
        // jQuery("#reg_user_email-help").css("color", "black");
          return true;
        }
    }
   
     
  }

  
  if (id == "phone" ) {

    var val0 = document.getElementById("countryCode");
    if(val0.value==""){

      jQuery("#phone").css("background-position","right 10px center");
     // val0.setCustomValidity("Please select a country");
       //document.getElementById("register-form").reportValidity();
       
       return false;
    }
    else{
      jQuery("#phone").css("background-position","right -10px center");
     // val0.setCustomValidity("");
    }

    var val = document.getElementById(id);
    var check = validatePhone(val.value);
    if (val.value == "") {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#phone").css("background-position","right 10px center");
      // val.setCustomValidity("Please enter contact no.");
       //document.getElementById("register-form").reportValidity();
     // jQuery("#phone-help").css("color", "black");
   //   document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML="Please enter contact no."; 
      return false;
    }else if (!check && (val.value != "")) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
     // jQuery("#phone-help").css("color", "red");
      //val.setCustomValidity("Enter a valid no.");
       //document.getElementById("register-form").reportValidity();
      //document.getElementById(id+"_err_div").style.display="block";
      jQuery("#phone").css("background-position","right 10px center");
      document.getElementById(id+"_err").innerHTML="Enter a valid no."; 
      return false;
    } else {
      //val.setCustomValidity("");
     // jQuery("#phone-help").css("color", "black");
     jQuery("#phone").css("background-position","right -10px center");
      return true;
    }
  }


  if (id == "uultra-terms-and-conditions-confirmation1") {
    var val = document.getElementById("uultra-terms-and-conditions-confirmation1");
    if (val.checked == false) {
      val.style.border = "1px solid rgb(210, 8, 8)";
      val.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val.style.outline = "medium none";
      jQuery("#uultra-terms-and-conditions-confirmation1").css("background-position","right 10px center");
       //val.setCustomValidity("Please check Terms-and-Services checkbox");
       //document.getElementById("register-form").reportValidity();
      //document.getElementById(id+"_err_div").style.display="block";
      document.getElementById(id+"_err").innerHTML=""; 
      return false;
    } else {  
      //val.setCustomValidity("");
      // document.getElementById("register-form").reportValidity(); 
       jQuery("#uultra-terms-and-conditions-confirmation1").css("background-position","right -10px center");
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
       jQuery("#reg_user_pass").css("background-position","right 10px center");
     // val5.setCustomValidity("Please enter Password");
      // document.getElementById("register-form").reportValidity();
     // jQuery("#reg_user_pass-help").css("color", "black");
      //document.getElementById("reg_user_pass_err_div").style.display="block";
      document.getElementById("reg_user_pass_err").innerHTML="Please enter Password"; 
      return false;
    }else if (!check && (val5.value != "")) {
      val5.style.border = "1px solid rgb(210, 8, 8)";
      val5.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val5.style.outline = "medium none";
     // val5.setCustomValidity("Must contain minm of 8 characters");
       //document.getElementById("register-form").reportValidity();
     // jQuery("#reg_user_pass-help").css("color", "red");
      // document.getElementById("reg_user_pass_err_div").style.display="block";
      jQuery("#reg_user_pass").css("background-position","right 10px center");
      document.getElementById("reg_user_pass_err").innerHTML="Must contain minm of 8 characters"; 
      return false;
    } else {
     // jQuery("#reg_user_pass-help").css("color", "black");
     jQuery("#reg_user_pass").css("background-position","right -10px center");
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
      //val6.setCustomValidity("Please re-enter Password");
     //  document.getElementById("register-form").reportValidity();
    //  jQuery("#reg_user_pass_confirm-help").css("color", "black");
      // document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
      jQuery("#reg_user_pass_confirm").css("background-position","right 10px center");
      document.getElementById("reg_user_pass_confirm_err").innerHTML="Please enter Password"; 
      return false;
    }else if (val6.value != val5.value) {
      val6.style.border = "1px solid rgb(210, 8, 8)";
      val6.style.boxShadow = "0px 0px 10px rgb(210, 8, 8)";
      val6.style.outline = "medium none";
     // val6.setCustomValidity("Passwords does not match");
       //document.getElementById("register-form").reportValidity();
     // jQuery("#reg_user_pass_confirm-help").css("color", "red");
      jQuery("#reg_user_pass_confirm").css("background-position","right 10px center");
      // document.getElementById("reg_user_pass_confirm_err_div").style.display="block";
      document.getElementById("reg_user_pass_confirm_err").innerHTML="Passwords does not match"; 
      return false;
    } else {
     // jQuery("#reg_user_pass_confirm-help").css("color", "black");
      jQuery("#reg_user_pass_confirm").css("background-position","right -10px center");
      return true;
    }
  }


  if (id == "cc") {

      var val7 = document.getElementById("cc");
      var val71=document.getElementById("cc_err");
	    var check = ajaxCC(val7.value);
	  	var c=val71.innerHTML;

        if(val7.value!="")
        {       
              if(c!="")
              {          
                     jQuery("#cc").css("background-position","right 10px center");   
                    return false;
              }
              else
              {
                 jQuery("#cc").css("background-position","right -10px center"); 
              }
        }
        else { 
                   jQuery("#cc").css("background-position","right -10px center"); 
              }
   
  }

  /*registration end */
  
} //end of fn

function styleback(id) {
  var id = id;
  if (id == "reg_user_login" || id == "reg_user_email" || id == "phone" || id == "uultra-terms-and-conditions-confirmation1" || id == "reg_user_pass" || id == "reg_user_pass_confirm" || id == "cc") {
    //document.getElementById("reg_user_login_err_div").style.display = "none";
    document.getElementById("reg_user_login").style.border = "1px solid black";
    document.getElementById("reg_user_login").style.boxShadow = "0px 0px 0px black";
    //jQuery("#reg_user_login-help").css("color","black");
  document.getElementById("reg_user_login").setCustomValidity("");
  jQuery("#reg_user_login").css("background-position","right -10px center");

    //document.getElementById("reg_user_email_err_div").style.display = "none";
    document.getElementById("reg_user_email").style.border = "1px solid black";
    document.getElementById("reg_user_email").style.boxShadow = "0px 0px 0px black";
    document.getElementById("reg_user_email").setCustomValidity("");
    jQuery("#reg_user_email").css("background-position","right -10px center"); 
    //jQuery("#reg_user_email-help").css("color","black");

   // document.getElementById("reg_user_pass_err_div").style.display = "none";
    document.getElementById("reg_user_pass").style.border = "1px solid black";
    document.getElementById("reg_user_pass").style.boxShadow = "0px 0px 0px black";
    document.getElementById("reg_user_pass").setCustomValidity("");
    jQuery("#reg_user_pass").css("background-position","right -10px center"); 
    //jQuery("#reg_user_pass-help").css("color","black");

  //  document.getElementById("reg_user_pass_confirm_err_div").style.display = "none";
    document.getElementById("reg_user_pass_confirm").style.border = "1px solid black";
    document.getElementById("reg_user_pass_confirm").style.boxShadow = "0px 0px 0px black";
    document.getElementById("reg_user_pass_confirm").setCustomValidity("");
    jQuery("#reg_user_pass_confirm").css("background-position","right -10px center"); 
    //jQuery("#reg_user_pass_confirm-help").css("color","black");

   // document.getElementById("phone_err_div").style.display = "none";
    document.getElementById("phone").style.border = "1px solid black";
    document.getElementById("phone").style.boxShadow = "0px 0px 0px black";
    document.getElementById("countryCode").setCustomValidity("");
    document.getElementById("phone").setCustomValidity("");
    jQuery("#phone").css("background-position","right -10px center"); 
    //jQuery("#phone-help").css("color","black");

     document.getElementById("cc").setCustomValidity("");
     jQuery("#cc").css("background-position","right -10px center"); 

   // if (document.getElementById("cc_div_show").style.display == "block") {
     // document.getElementById("cc_err_div").style.display = "none";
     // document.getElementById("cc").style.border = "1px solid black";
    //  document.getElementById("cc").style.boxShadow = "0px 0px 0px black";
      //jQuery("#reg_user_pass-help").css("color","black");
  //  }

   // document.getElementById("uultra-terms-and-conditions-confirmation1_err_div").style.display = "none";
   // document.getElementById("uultra-terms-and-conditions-confirmation1").style.border = "1px solid black";
   // document.getElementById("uultra-terms-and-conditions-confirmation1").style.boxShadow = "0px 0px 0px black";
  }

}


function validateEmail(email) {
  /* var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; */
  /* var re=/^[a-zA-Z0-9.!#$%&Â’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9](?:\.[a-zA-Z0-9-]+)*$/; */
//  var re = /^[a-zA-Z0-9.!#$%&Â’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/;
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
  //var re = /^\+(?:[0-9] ?){5,12}[0-9]$/;
   var re = /^[0-9]{5,11}$/;

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
          //document.getElementById("cc_err_div").style.display = "block";
          if (response == 1){
            document.getElementById("cc_err").innerHTML = "Invalid code";
             document.getElementById("cc").setCustomValidity("Invalid code");
      document.getElementById("register-form").reportValidity();
          }
            
          if (response == 2){
             document.getElementById("cc_err").innerHTML = "Code Expired";
             document.getElementById("cc").setCustomValidity("Code Expired");
      document.getElementById("register-form").reportValidity();
          }
           
          //document.getElementById("invalid").value="false";
          //return false;
        } else {
          //document.getElementById("cc_err_div").style.display = "none";
          document.getElementById("cc_err").innerHTML = "";
          document.getElementById("cc").setCustomValidity("");
          document.getElementById("cc").style.border = "1px solid black !important";
          document.getElementById("cc").style.boxShadow = "0px 0px 0px black !important";
          //document.getElementById("invalid").value="true";
          //return true;
        }
      }
    });


  } else {

		//document.getElementById("cc_err_div").style.display = "none";
          document.getElementById("cc_err").innerHTML = "";
          document.getElementById("cc").setCustomValidity("");
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
          //document.getElementById("reg_user_login_err_div").style.display = "block";
	        document.getElementById("reg_user_login_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_login_err").innerHTML = '<script>  			</script>';


          swal({
              title: "Info: Account Pending",
              text: "<p>Please activate your account by clicking on activation link in the Verification Mail sent to you</p> <p>To change your Email, click on Edit-Email</p><p> If you did not receive the mail, click on Resend-Email</p><p> To close this box, click on Done</p><p>Thank You</p><a href=\'http://ideabytestraining.com/newqezyplay/qp1/sign-up\' style=\'padding: 10px 10%;   background-color: green !important;    border-radius: 5px !important;    text-align: center;    font-size: 14px;    margin-bottom: -12px;color: white;  text-decoration: none;\'>Done</a>",
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
                        swal({   title: " ",   text: data,   html: true });
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
          //document.getElementById("reg_user_login_err_div").style.display = "block";
	        document.getElementById("reg_user_login_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_login_err").innerHTML = "<script></script>";
		 SweetAlertMultiInputReset();
		swal('Error','Username already exists. Please select another');
        } else {
          val1 = document.getElementById("reg_user_login");
          val1.style.border = "1px solid black !important";
          val1.style.boxShadow = "0px 0px 0px black !important";
          //document.getElementById("reg_user_login_err_div").style.display = "none";
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
          //document.getElementById("reg_user_email_err_div").style.display = "block";
	document.getElementById("reg_user_email_err").style.backgroundColor="transparent !important";
          document.getElementById("reg_user_email_err").innerHTML = '<script>  			</script>';


           swal({
              title: "Account Pending",
              text: "<p>Please activate your account by clicking on activation link in the Verification Mail sent to you</p> <p>To change your Email, click on Edit-Email</p><p> If you did not receive the mail, click on Resend-Email</p><p> To close this box, click on Done</p><p>Thank You</p><a href=\'http://ideabytestraining.com/newqezyplay/qp1/sign-up\' style=\'padding: 10px 10%;   background-color: green !important;    border-radius: 5px !important;    text-align: center;    font-size: 14px;    margin-bottom: -12px;color: white;  text-decoration: none;\'>Done</a>",
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

                        swal({   title: " ",   text: data,   html: true });
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
          //document.getElementById("reg_user_email_err_div").style.display = "block";
	document.getElementById("reg_user_email_err").style.backgroundColor="transparent !important";
              document.getElementById("reg_user_email_err").innerHTML = "<script></script>";
		 SweetAlertMultiInputReset();
		swal('Error','Email already exists. Please select another');
        } else {
          val1 = document.getElementById("reg_user_email");
          val1.style.border = "1px solid black !important";
          val1.style.boxShadow = "0px 0px 0px black !important";
          //document.getElementById("reg_user_email_err_div").style.display = "none";
          document.getElementById("reg_user_email_err").innerHTML = "";
        }

      }
    });
  }
}




