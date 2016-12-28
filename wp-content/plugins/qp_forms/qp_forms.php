<?php 

    /*

    Plugin Name: Forms- contact&Feedback

    Plugin URI: contactform

    Description: Contact form and Feedback form

    Author: IB

    Version: 1.0

    Author URI: ib

    */







add_shortcode( 'qp_contact_form', 'contact_form_fn');

add_shortcode( 'qp_feedback_form', 'feedback_form_fn');



function contact_form_fn()

{





if(isset($_POST['contact_submit']))

{



$firstname=$_POST['first_name'];

$lastname=$_POST['last_name'];

$email=$_POST['email'];

$phone=$_POST['phone'];

$address=$_POST['address'];

$message=$_POST['cf_comment'];







$headers = "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= 'From: Admin - QezyPlay <admin@qezyplay.com>' . "\r\n";



$subjectUser="Contact Received- Qezyplay";

$subjectAdmin="User Contacted at Qezyplay";



$bodyUser="
<p>Hi,  $firstname $lastname</p>

<p>Your contact details are received. <br />
We will be contacting you soon. Thank You.</p>

<p>Regards,<br />
QezyPlay</p>

";



$bodyAdmin="

<p>Hi,  Admin</p>

<br />

<p>User contacted at our site. </p>

<p>Below are the user details:</p>

<br />

<p>First Name: $firstname</p>

<p>Last Name: $lastname</p>

<p>Email: $email</p>

<p>Phone: $phone</p>

<p>Address: $address</p>

<p>Message: $message</p>



<br />

<p>Regards,</p>

<p>QezyPlay</p>



";



$user_mail=$email;

$admin_mail=get_option("admin_email");





//store tp db

//create table user_contact

 

global $wpdb;

$wpdb->insert("user_contact", array(

						"first_name" => $firstname,

						"last_name" => $lastname,

						"email" => $email,

						"phone" => $phone,

						"address" => $address,

						"message" => $message)

					);

					

//mail User						

mail($user_mail,$subjectUser,print_r($bodyUser,true),$headers);						

//mail Admin

mail($admin_mail,$subjectAdmin,print_r($bodyAdmin,true),$headers);

//$msg="Successfully Submitted..";

echo '<script>

swal("Contact Details Sent");

document.getElementById("success_message").innerHTML = "<i class=\"glyphicon glyphicon-thumbs-up\"></i> Thanks for contacting us, we will get back to you shortly.";

</script>';

}





echo '

<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->

<style>



</style>







<div class="container">



    

<form class="well form-horizontal" method="post"  id="contact_form">



<fieldset>



<!-- Form Name -->



<!-- <legend>Contact Us </legend> -->



<!-- Text input-->

<div class="form-group" id="fname_contactform">

 

 <label class="col-md-4 control-label">First Name</label>  

 

 <div class="col-md-4 inputGroupContainer">

  

<div class="input-group">

 

 <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

  

<input id="first_name" name="first_name" placeholder="First Name" class="form-control"  type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

   

 </div><div id="first_name_err_div"><div id="first_name_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

<div class="form-group" id="lname_contactform">

  <label class="col-md-4 control-label" >Last Name</label> 

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

  <input id="last_name" name="last_name" placeholder="Last Name" class="form-control"  type="text" onclick="styleback(this.id)" required>

    </div><div id="last_name_err_div"><div id="last_name_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

       <div class="form-group" id="email_contactform">

  <label class="col-md-4 control-label">E-Mail</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>

  <input id="email_cf" name="email" placeholder="E-Mail Address" class="form-control"  type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="email_cf_err_div"><div id="email_cf_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

       

<div class="form-group" id="pnum_contactform">

  <label class="col-md-4 control-label">Phone #</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>

  <input id="phone_cf" name="phone" placeholder="+919874563210 incl.CountryCode" class="form-control" type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="phone_cf_err_div"><div id="phone_cf_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

      

<div class="form-group" id="add_contactform">

  <label class="col-md-4 control-label">Address</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>

  <input id="address" name="address" placeholder="Address" class="form-control" type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="address_err_div"><div id="address_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text area -->

  

<div class="form-group" id="mesg_contactform">

  <label class="col-md-4 control-label">Message</label>

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>

        	<textarea class="form-control" id="cf_comment" name="cf_comment" placeholder="Type your message here.." onblur="validate(this.id)" onclick="styleback(this.id)" required></textarea>

  </div><div id="cf_comment_err_div"><div id="cf_comment_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Success message -->

<div class="alert alert-success" role="alert" id="success_message"></div>



<!-- Button -->

<div class="form-group" id="submit_contactform">

  <label class="col-md-4 control-label"></label>

  

<div class="col-md-4">

    <button type="submit" id="contact_submit" name="contact_submit" class="btn btn-warning" onclick="return validate(this.id)">Send <span class="glyphicon glyphicon-send"></span></button>

  </div>

</div>



</fieldset>



</form>

<div class="hugeit-field-block custom-text-block" id="ofcad_contactform" rel="huge-contact-field-38" align="center" style="width:100%;height: 30%;">

													



<div id="cofcad_contactform" style="font-size: large;float:left; width:50%;">

<b>Ideabytes Inc.,</b><br/>

142 Golflinks Drive,<br/>

Ottawa K2J 5N5,Canada<br/>

Email: contact_canada@qezymedia.com<br/>

Phone: +1 613 800 736</div>

<div id="hofcad_contactform" style="font-size: large; float:right; width:50%;">

<b>Ideabytes Software India Pvt. Ltd.</b><br/>#50,

Jayabheri Enclave,<br/> Gachibowli,<br/>

Hyderabad - 500 032, India.<br/>

Email: contact_india@qezymedia.com

<br/>

Phone: +91-40-6453 5959

</div>												



</div>



</div>

    </div><!-- /.container -->

<script>

if($.trim($("#first_name_err").html())==""){

$("#first_name_err_div").hide();}

if($.trim($("#last_name_err").html())==""){

$("#last_name_err_div").hide();}

if($.trim($("#email_cf_err").html())==""){

$("#email_cf_err_div").hide();}

if($.trim($("#phone_cf_err").html())==""){

$("#phone_cf_err_div").hide();}

if($.trim($("#address_err").html())==""){

$("#address_err_div").hide();}

if($.trim($("#cf_comment_err").html())==""){

$("#cf_comment_err_div").hide();}

if($.trim($("#success_message").html())==""){

$("#success_message").hide();}





</script>



';

} 



function feedback_form_fn()

{



if(isset($_POST['fb-submit']))

{



//echo "<script>alert('hi')</script>";



$name=$_POST['u_name'];

$gender=$_POST['Gender'];

$country=$_POST['country'];

$email=$_POST['email'];

$phone=$_POST['phone'];

$platform=$_POST['qpPlatform'];

$channel=$_POST['Channel'];

$message=$_POST['fb_comment'];







$headers = "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= 'From: Admin - QezyPlay <admin@qezyplay.com>' . "\r\n";



$subjectUser="Feedback Received - Qezyplay";

$subjectAdmin="User Feedback at Qezyplay";



$bodyUser="
<p>Hi,</p>

<p>Your feedback is received. 
<br /> Thank You for your valuable suggestions. We will look into it.</p>

<p>Regards,<br />
QezyPlay</p>



";



$bodyAdmin="

<p>Hi,  Admin</p>

<br />

<p>User feedback at our site. </p>

<p>Below are the user details:</p>

<br />

<p>Name: $name</p>

<p>Gender: $gender</p>

<p>Country: $country</p>

<p>Email: $email</p>

<p>Phone: $phone</p>

<p>Platform: $platform</p>

<p>Channel: $channel</p>

<p>Message: $message</p>



<br />

<p>Regards,</p>

<p>QezyPlay</p>



";



$user_mail=$email;

$admin_mail=get_option("admin_email");





//store tp db

//create table user_feedback

 

global $wpdb;

$wpdb->insert("user_feedback", array(

						"name" => $name,

						"gender" => $gender,

						"country" => $country, 

						"email" => $email,

						"phone" => $phone,

						"qezy_platform" => $platform,

						"channel" => $channel,

						"message" => $message)

					);



					

//mail User						

mail($user_mail,$subjectUser,print_r($bodyUser,true),$headers);						

//mail Admin

mail($admin_mail,$subjectAdmin,print_r($bodyAdmin,true),$headers);



//$msg="Successfully Submitted..";

echo '<script>

swal("Feedback Sent");

document.getElementById("success_message").innerHTML = "<i class=\"glyphicon glyphicon-thumbs-up\"></i> Thanks for your Feedback. Keep Watching";

</script>';

}

else

{

//echo "<script>alert('N O')</script>";

}



echo '

<style>



</style>

</head>

<body>

<!--<script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>-->



<div class="container">



    <form class="well form-horizontal" method="post"  id="feedback_form">

<fieldset>



<!-- Form Name -->

<legend>FeedBack Form</legend>



<!-- Text input-->



<div class="form-group" id="name_feed">

  <label class="col-md-4 control-label">Name</label>  

  <div class="col-md-4 inputGroupContainer">

  <div class="input-group">

  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

  <input  id="name" name="u_name" placeholder="Name" class="form-control"  type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="name_err_div"><div id="name_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- radio checks -->

 <div class="form-group" id="gen_feed">

                        <label class="col-md-4 control-label">Gender</label>

                        <div class="col-md-4">

                            <div class="radio">

                                <label>

                                    <input id="GenderM" type="radio" name="Gender" value="Male" onblur="validate(this.id)" onchange="styleback(this.id)" required /> Male

                                </label>

					</div>

                           <div class="radio">



                                <label>

                                    <input id="GenderF" type="radio" name="Gender" value="Female" onblur="validate(this.id)" onchange="styleback(this.id)" /> Female

                                </label>

                            </div><div id="Gender_err_div"><div id="Gender_err" class="alert alert-danger xoouserultra-field-value"></div></div>

                        </div>

                    </div>

<!-- Select Basic -->

   

<div class="form-group" id="country_feed"> 

  <label class="col-md-4 control-label">Country</label>

    <div class="col-md-4 selectContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

    <select required="required" class="xoouserultra-input" name="country" id="country" title="Country" onblur="validate(this.id)" onclick="styleback(this.id)"><option value="" selected="selected">Select Country</option><option value="Afghanistan">Afghanistan</option><option value="Aland Islands">Aland Islands</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">British Indian Ocean Territory</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Congo Democratic">Congo Democratic</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Cote d\'Ivoire">Cote d\'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Territories</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guernsey">Guernsey</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option><option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Isle of Man">Isle of Man</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jersey">Jersey</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea Democratic">Korea Democratic</option><option value="Korea Republic">Korea Republic</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macao">Macao</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Palestine">Palestine</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="Saint Barthelemy">Saint Barthelemy</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Martin (French part)">Saint Martin (French part)</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia">Serbia</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Timor-Leste">Timor-Leste</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option><option value="Viet Nam">Viet Nam</option><option value="Virgin Islands, British">Virgin Islands, British</option><option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option><option value="Wallis and Futuna">Wallis and Futuna</option><option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>

  </div><div id="country_err_div"><div id="country_err" class="alert alert-danger xoouserultra-field-value"></div></div>

</div>

</div>

<!-- Text input-->

       <div class="form-group" id="email_feed">

  <label class="col-md-4 control-label">E-Mail</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>

  <input id="email_fb" name="email" placeholder="E-Mail Address" class="form-control"  type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="email_fb_err_div"><div id="email_fb_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>





<!-- Text input-->

       

<div class="form-group" id="num_feed">

  <label class="col-md-4 control-label">Contact Number #</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>

  <input id="phone_fb" name="phone" placeholder="+919874563210" class="form-control" type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div><div id="phone_fb_err_div"><div id="phone_fb_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

      

<div class="form-group" id="qpf_feed"> 

  <label class="col-md-4 control-label">QezyPlay Platform</label>

    <div class="col-md-4 selectContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

    <select id="qpPlatform" name="qpPlatform" class="form-control selectpicker" onblur="validate(this.id)" onclick="styleback(this.id)" required="required">

      <option value="">Please select Platform</option>

      <option value="QezyPlay Website">QezyPlay Website</option>

      <option value="QezyPlay Android App">QezyPlay Android App</option>

      <option value="Qezyplay iOS App">Qezyplay iOS App</option>

 </select>

  </div><div id="qpPlatform_err_div"><div id="qpPlatform_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Text input-->

 

 <div class="form-group" id="channel_feed">

  <label class="col-md-4 control-label">Channel</label>  

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicons glyphicons-tv"></i></span>

  <input id="Channel" name="Channel" placeholder="Channel" class="form-control"  type="text" onblur="validate(this.id)" onclick="styleback(this.id)" required>

    </div>

	<div id="Channel_err_div"><div id="Channel_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div> 



<!-- Text area -->

  

<div class="form-group" id="mesg_feed">

  <label class="col-md-4 control-label">Message</label>

    <div class="col-md-4 inputGroupContainer">

    <div class="input-group">

        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>

        	<textarea class="form-control" id="fb_comment" name="fb_comment" placeholder="We value your Suggestions.." onblur="validate(this.id)" onclick="styleback(this.id)" required></textarea>

  </div>

	<div id="fb_comment_err_div"><div id="fb_comment_err" class="alert alert-danger xoouserultra-field-value"></div></div>

  </div>

</div>



<!-- Success message -->

<div class="alert alert-success" role="alert" id="success_message"></div>



<!-- Button -->

<div class="form-group" id="submit_feed">

  <label class="col-md-4 control-label"></label>

  <div class="col-md-4">

    <button type="submit" id="fb_submit" name="fb-submit" class="btn btn-warning" onclick="return validate(this.id)">Send <span class="glyphicon glyphicon-send"></span></button>

  </div>

</div>



</fieldset>

</form>

</div>

    </div><!-- /.container -->

<script>

if($.trim($("#name_err").html())==""){

$("#name_err_div").hide();}

if($.trim($("#Gender_err").html())==""){

$("#Gender_err_div").hide();}

if($.trim($("#country_err").html())==""){

$("#country_err_div").hide();}

if($.trim($("#email_fb_err").html())==""){

$("#email_fb_err_div").hide();}

if($.trim($("#phone_fb_err").html())==""){

$("#phone_fb_err_div").hide();}

if($.trim($("#qPplatform_err").html())==""){

$("#qpPlatform_err_div").hide();}

if($.trim($("#Channel_err").html())==""){

$("#Channel_err_div").hide();}

if($.trim($("#fb_comment_err").html())==""){

$("#fb_comment_err_div").hide();}



if($.trim($("#success_message").html())==""){

$("#success_message").hide();}



</script>

';



}

?>

<?php

