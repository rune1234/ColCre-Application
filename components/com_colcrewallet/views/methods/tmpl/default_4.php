<script>
    function handleCurrencyChange() {
    var country = null;
    var currency = null;
    if ($('beneficiaryBankCountry')) country = $('beneficiaryBankCountry').value;
    if ($('destinationCurrency')) currency = $('destinationCurrency').value;
function handleFeeNoteDisplaying(country, currency) {
    var notes = $$('#destinationCurrencyFieldErr  div.regformsubrow > span.rightlabel');
    if (!notes.length) return;

    if (country && currency && country != 'US' && currency == 'USD') {
        notes.removeClass('displayNone');
    } else {
        notes.addClass('displayNone');
    }
}
    handleFeeNoteDisplaying(country, currency);
}

    function handleCountryChange(page, changeCurrency) {
	var partialId = 'AccountHolder';
    if (page == 'intermediaryBank') {
        partialId = 'Intermediary';
    } else if (page == 'beneficiaryBank') {
        partialId = 'Beneficiary';
		if ($(page +'Country').value == 'US') {
			$('intermediaryButtons').addClass('displayNone');
			$('intermediaryNote').addClass('displayNone');
		} else {
            $('intermediaryButtons').removeClass('displayNone');
			$('intermediaryNote').removeClass('displayNone');
		}

        //set default value of changeCurrency for other function usages.
        changeCurrency =  typeof(changeCurrency) != 'undefined' ? changeCurrency : true;
        if ( changeCurrency ) {
            $('destinationCurrency').value = currencyMap[$(page +'Country').value];
            if ( $('destinationCurrency').value != currencyMap[$(page +'Country').value] ) {
                $('destinationCurrency').value = currencyMap['US'];
            }
        }
        handleFeeNoteDisplaying($(page +'Country').value, $('destinationCurrency').value);
    }

	if ($(page +'Country').value == 'US') {
		$(page +'StateFieldErr').removeClass('displayNone');
		$(page +'ZipCodeFieldErr').removeClass('displayNone');
		$(page +'ProvinceFieldErr').addClass('displayNone');
        $(page +'PostalCodeFieldErr').addClass('displayNone');
		$('review'+partialId+'Province').addClass('displayNone');
    	$('review'+partialId+'PostalCode').addClass('displayNone');
	    $('review'+partialId+'State').removeClass('displayNone');
    	$('review'+partialId+'ZipCode').removeClass('displayNone');
	} else {
		$(page +'StateFieldErr').addClass('displayNone');
        $(page +'ZipCodeFieldErr').addClass('displayNone');
		$(page +'ProvinceFieldErr').removeClass('displayNone');
		$(page +'PostalCodeFieldErr').removeClass('displayNone');
		$('review'+partialId+'Province').removeClass('displayNone');
    	$('review'+partialId+'PostalCode').removeClass('displayNone');
	    $('review'+partialId+'State').addClass('displayNone');
    	$('review'+partialId+'ZipCode').addClass('displayNone');
	}
	if (page == 'beneficiaryBank') {
        displayRelevantBankParams(page);
    } else if (page == 'imtermediaryBank') {
        displayRelevantBankIdTypes(page);
    }

}
   
    </script>
        <?php
if (1 == 2 ) {
?>
<span>1. Enter Your Bank Information</span>

<div id="newtrain-step2" class="newtraincar newtrain-inactive" style="z-index:8">
<div class="spr-regflow spr-postflow newtrain-left"> </div>
<span>2. Enter Account Holder Address</span>
</div>
<div id="newtrain-step3" class="newtraincar newtrain-inactive" style="z-index:7">
<div class="spr-regflow spr-postflow newtrain-left"> </div>
<span>3. Review Bank Account Details</span>
</div>
 
<?php
}
?>

<form method="post">
    <?php
if (1 == 2 ) {
?>
<div><h1 class="conf"">Please Enter Your Bank Information</h1></div>
<div style="width:500px">To withdraw funds by ACH (U.S. banks) or Wire Transfer (banks outside of U.S.), please enter your bank account information below.</div>
<div class="warning" style="padding:20px 0px 0px 0px;">* Required Information</div>

		
<div id="beneficiaryBankError" class="formerrormsgsbin displayNone" type="error">
	<div class="formerrormsgstitle">Please correct the following:
		<div class="formerrormsgs" id="beneficiaryBankErrorList">
		</div>
	</div>
</div>

		<div style="padding:20px 0px 0px 0px;">
			

<div>Bank Information</div>



<div>




<div class="formmaincol" defaultclass="formmaincol"  style='width:535px;'>

<div class="regformsubrow">


<span>
	<select  name="beneficiaryBankAcctType" id="beneficiaryBankAcctType">
		  
<option value="CHECKING"  >CHECKING/CURRENT</option>
  
<option value="SAVINGS"  >SAVINGS</option>

	</select>
</span>


</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Name:
	</div>
</div>


<div>

<div class="regformsubrow">

 
<input type="text" value=""  name="beneficiaryBankName" id="beneficiaryBankName" maxlength="50" tabindex="6" class="inputheightsmall regBoxInput" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Country:
	</div>
</div>


<div>

<div class="regformsubrow">


<span class="formmaincol1">
	<select  name="beneficiaryBankCountry" id="beneficiaryBankCountry" onchange="handleCountryChange('beneficiaryBank')">
		  
<option value="US"  selected>United States</option>
  
<option value="AF"  >Afghanistan</option>
  
<option value="AL"  >Albania</option>
  
<option value="DZ"  >Algeria</option>
  
<option value="AS"  >American Samoa</option>
  
<option value="AD"  >Andorra</option>
  
<option value="AO"  >Angola</option>
  
<option value="AI"  >Anguilla</option>
  
<option value="AQ"  >Antarctica</option>
  
<option value="AG"  >Antigua And Barbuda</option>
  
<option value="AR"  >Argentina</option>
  
<option value="AM"  >Armenia</option>
  
<option value="AW"  >Aruba</option>
  
<option value="AU"  >Australia</option>
  
<option value="AT"  >Austria</option>
  
<option value="AZ"  >Azerbaijan</option>
  
<option value="BS"  >Bahamas</option>
  
<option value="BH"  >Bahrain</option>
  
<option value="BD"  >Bangladesh</option>
  
<option value="BB"  >Barbados</option>
  
<option value="BY"  >Belarus</option>
  
<option value="BE"  >Belgium</option>
  
<option value="BZ"  >Belize</option>
  
<option value="BJ"  >Benin</option>
  
<option value="BM"  >Bermuda</option>
  
<option value="BT"  >Bhutan</option>
  
<option value="BO"  >Bolivia</option>
  
<option value="BA"  >Bosnia and Herzegovina</option>
  
<option value="BW"  >Botswana</option>
  
<option value="BV"  >Bouvet Island</option>
  
<option value="BR"  >Brazil</option>
  
<option value="IO"  >British Indian Ocean Territory</option>
  
<option value="BN"  >Brunei Darussalam</option>
  
<option value="BG"  >Bulgaria</option>
  
<option value="BF"  >Burkina Faso</option>
  
<option value="BI"  >Burundi</option>
  
<option value="KH"  >Cambodia</option>
  
<option value="CM"  >Cameroon</option>
  
<option value="CA"  >Canada</option>
  
<option value="CV"  >Cape Verde</option>
  
<option value="KY"  >Cayman Islands</option>
  
<option value="CF"  >Central African Republic</option>
  
<option value="TD"  >Chad</option>
  
<option value="CL"  >Chile</option>
  
<option value="CN"  >China</option>
  
<option value="CX"  >Christmas Island</option>
  
<option value="CC"  >Cocos (Keeling) Islands</option>
  
<option value="CO"  >Colombia</option>
  
<option value="KM"  >Comoros</option>
  
<option value="CK"  >Cook Islands</option>
  
<option value="CR"  >Costa Rica</option>
  
<option value="HR"  >Croatia (Hrvatska)</option>
  
<option value="CW"  >Curacao</option>
  
<option value="CY"  >Cyprus</option>
  
<option value="CZ"  >Czech Republic</option>
  
<option value="DK"  >Denmark</option>
  
<option value="DJ"  >Djibouti</option>
  
<option value="DM"  >Dominica</option>
  
<option value="DO"  >Dominican Republic</option>
  
<option value="TP"  >East Timor</option>
  
<option value="EC"  >Ecuador</option>
  
<option value="EG"  >Egypt</option>
  
<option value="SV"  >El Salvador</option>
  
<option value="GQ"  >Equatorial Guinea</option>
  
<option value="ER"  >Eritrea</option>
  
<option value="EE"  >Estonia</option>
  
<option value="ET"  >Ethiopia</option>
  
<option value="FK"  >Falkland Islands (Malvinas)</option>
  
<option value="FO"  >Faroe Islands</option>
  
<option value="FJ"  >Fiji</option>
  
<option value="FI"  >Finland</option>
  
<option value="FR"  >France</option>
  
<option value="FX"  >France, Metropolitan</option>
  
<option value="GF"  >French Guiana</option>
  
<option value="PF"  >French Polynesia</option>
  
<option value="TF"  >French Southern Territories</option>
  
<option value="GA"  >Gabon</option>
  
<option value="GM"  >Gambia</option>
  
<option value="GE"  >Georgia</option>
  
<option value="DE"  >Germany</option>
  
<option value="GH"  >Ghana</option>
  
<option value="GI"  >Gibraltar</option>
  
<option value="GR"  >Greece</option>
  
<option value="GL"  >Greenland</option>
  
<option value="GD"  >Grenada</option>
  
<option value="GP"  >Guadeloupe</option>
  
<option value="GU"  >Guam</option>
  
<option value="GT"  >Guatemala</option>
  
<option value="GN"  >Guinea</option>
  
<option value="GW"  >Guinea-Bissau</option>
  
<option value="GY"  >Guyana</option>
  
<option value="HT"  >Haiti</option>
  
<option value="HM"  >Heard And Mc Donald Islands</option>
  
<option value="VA"  >Holy See (Vatican City State)</option>
  
<option value="HN"  >Honduras</option>
  
<option value="HK"  >Hong Kong SAR, PRC</option>
  
<option value="HU"  >Hungary</option>
  
<option value="IS"  >Iceland</option>
  
<option value="IN"  >India</option>
  
<option value="ID"  >Indonesia</option>
  
<option value="IE"  >Ireland</option>
  
<option value="IL"  >Israel</option>
  
<option value="IT"  >Italy</option>
  
<option value="JM"  >Jamaica</option>
  
<option value="JP"  >Japan</option>
  
<option value="JO"  >Jordan</option>
  
<option value="KZ"  >Kazakhstan</option>
  
<option value="KE"  >Kenya</option>
  
<option value="KI"  >Kiribati</option>
  
<option value="KR"  >Korea, Republic of</option>
  
<option value="KW"  >Kuwait</option>
  
<option value="KG"  >Kyrgyzstan</option>
  
<option value="LA"  >Lao, People's Dem. Rep.</option>
  
<option value="LV"  >Latvia</option>
  
<option value="LB"  >Lebanon</option>
  
<option value="LS"  >Lesotho</option>
  
<option value="LY"  >Libya</option>
  
<option value="LI"  >Liechtenstein</option>
  
<option value="LT"  >Lithuania</option>
  
<option value="LU"  >Luxembourg</option>
  
<option value="MO"  >Macau</option>
  
<option value="MK"  >Macedonia</option>
  
<option value="MG"  >Madagascar</option>
  
<option value="MW"  >Malawi</option>
  
<option value="MY"  >Malaysia</option>
  
<option value="MV"  >Maldives</option>
  
<option value="ML"  >Mali</option>
  
<option value="MT"  >Malta</option>
  
<option value="MH"  >Marshall Islands</option>
  
<option value="MQ"  >Martinique</option>
  
<option value="MR"  >Mauritania</option>
  
<option value="MU"  >Mauritius</option>
  
<option value="YT"  >Mayotte</option>
  
<option value="MX"  >Mexico</option>
  
<option value="FM"  >Micronesia, Federated States Of</option>
  
<option value="MD"  >Moldova, Republic Of</option>
  
<option value="MC"  >Monaco</option>
  
<option value="MN"  >Mongolia</option>
  
<option value="ME"  >Montenegro</option>
  
<option value="MS"  >Montserrat</option>
  
<option value="MA"  >Morocco</option>
  
<option value="MZ"  >Mozambique</option>
  
<option value="MM"  >Myanmar</option>
  
<option value="NA"  >Namibia</option>
  
<option value="NR"  >Nauru</option>
  
<option value="NP"  >Nepal</option>
  
<option value="NL"  >Netherlands</option>
  
<option value="AN"  >Netherlands Antilles</option>
  
<option value="NC"  >New Caledonia</option>
  
<option value="NZ"  >New Zealand</option>
  
<option value="NI"  >Nicaragua</option>
  
<option value="NE"  >Niger</option>
  
<option value="NG"  >Nigeria</option>
  
<option value="NU"  >Niue</option>
  
<option value="NF"  >Norfolk Island</option>
  
<option value="MP"  >Northern Mariana Islands</option>
  
<option value="NO"  >Norway</option>
  
<option value="OM"  >Oman</option>
  
<option value="OT"  >Others</option>
  
<option value="PK"  >Pakistan</option>
  
<option value="PW"  >Palau</option>
  
<option value="PS"  >Palestine</option>
  
<option value="PA"  >Panama</option>
  
<option value="PG"  >Papua New Guinea</option>
  
<option value="PY"  >Paraguay</option>
  
<option value="PE"  >Peru</option>
  
<option value="PH"  >Philippines</option>
  
<option value="PN"  >Pitcairn</option>
  
<option value="PL"  >Poland</option>
  
<option value="PT"  >Portugal</option>
  
<option value="PR"  >Puerto Rico</option>
  
<option value="QA"  >Qatar</option>
  
<option value="RE"  >Reunion</option>
  
<option value="RO"  >Romania</option>
  
<option value="RU"  >Russia</option>
  
<option value="RW"  >Rwanda</option>
  
<option value="GS"  >S. Georgia & S. Sandwich Isls.</option>
  
<option value="KN"  >Saint Kitts And Nevis</option>
  
<option value="LC"  >Saint Lucia</option>
  
<option value="VC"  >Saint Vincent And the Grenadines</option>
  
<option value="WS"  >Samoa</option>
  
<option value="SM"  >San Marino</option>
  
<option value="ST"  >Sao Tome And Principe</option>
  
<option value="SA"  >Saudi Arabia</option>
  
<option value="SN"  >Senegal</option>
  
<option value="RS"  >Serbia</option>
  
<option value="SC"  >Seychelles</option>
  
<option value="SL"  >Sierra Leone</option>
  
<option value="SG"  >Singapore</option>
  
<option value="SK"  >Slovak Republic</option>
  
<option value="SI"  >Slovenia</option>
  
<option value="SB"  >Solomon Islands</option>
  
<option value="SO"  >Somalia</option>
  
<option value="ZA"  >South Africa</option>
  
<option value="ES"  >Spain</option>
  
<option value="LK"  >Sri Lanka</option>
  
<option value="SH"  >St. Helena</option>
  
<option value="PM"  >St. Pierre And Miquelon</option>
  
<option value="SR"  >Suriname</option>
  
<option value="SJ"  >Svalbard And Jan Mayen Islands</option>
  
<option value="SZ"  >Swaziland</option>
  
<option value="SE"  >Sweden</option>
  
<option value="CH"  >Switzerland</option>
  
<option value="TW"  >Taiwan</option>
  
<option value="TJ"  >Tajikistan</option>
  
<option value="TZ"  >Tanzania, United Republic Of</option>
  
<option value="TH"  >Thailand</option>
  
<option value="TG"  >Togo</option>
  
<option value="TK"  >Tokelau</option>
  
<option value="TO"  >Tonga</option>
  
<option value="TT"  >Trinidad And Tobago</option>
  
<option value="TN"  >Tunisia</option>
  
<option value="TR"  >Turkey</option>
  
<option value="TM"  >Turkmenistan</option>
  
<option value="TC"  >Turks And Caicos Islands</option>
  
<option value="TV"  >Tuvalu</option>
  
<option value="UG"  >Uganda</option>
  
<option value="UA"  >Ukraine</option>
  
<option value="AE"  >United Arab Emirates</option>
  
<option value="GB"  >United Kingdom</option>
  
<option value="UM"  >United States Minor Outlying Islands</option>
  
<option value="UY"  >Uruguay</option>
  
<option value="UZ"  >Uzbekistan</option>
  
<option value="VU"  >Vanuatu</option>
  
<option value="VE"  >Venezuela</option>
  
<option value="VN"  >Vietnam</option>
  
<option value="VG"  >Virgin Islands (British)</option>
  
<option value="VI"  >Virgin Islands (US)</option>
  
<option value="WF"  >Wallis And Futuna Islands</option>
  
<option value="EH"  >Western Sahara</option>
  
<option value="YE"  >Yemen</option>
  
<option value="ZM"  >Zambia</option>

	</select>
</span>

	<span>



</span><div style="clear:both;"></div>
</div>
</div>
<div style="clear:both;"></div>
</div>


<!-- -->


<div id="beneficiaryBankIdentificationNumberFieldErr">
 


<div>

<div class="regformsubrow">



	<input type="text" value=""  name="beneficiaryBankIdentificationNumber" id="beneficiaryBankIdentificationNumber" maxlength="50" tabindex="8" class="inputheightsmall regBoxInput" style="width:300px" onblur="">
	
	


	
</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Secondary Bank ID:
	</div>
</div>


<div>

<div class="regformsubrow">


<span>
	<input type="text" value=""  name="beneficiaryBankSecondaryNumber" id="beneficiaryBankSecondaryNumber" maxlength="50" tabindex="9" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
	
	
</span>


</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Branch:
	</div>
</div>


<div>

<div class="regformsubrow">


<span class="formmaincol1">
	<input type="text" value=""  name="beneficiaryBankBranch" id="beneficiaryBankBranch" maxlength="50" tabindex="10" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
	
	
</span>


</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Address:
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value=""  name="beneficiaryBankAddress" id="beneficiaryBankAddress" maxlength="35" tabindex="11" class="inputheightsmall regBoxInput" style="width:300px" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		Bank Address 2:
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value=""  name="beneficiaryBankAddress2" id="beneficiaryBankAddress2" maxlength="35" tabindex="12" class="inputheightsmall regBoxInput" style="width:300px" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank City:
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value=""  name="beneficiaryBankCity" id="beneficiaryBankCity" maxlength="50" tabindex="13" class="inputheightsmall regBoxInput" style="width:300px" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Province:
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value=""  name="beneficiaryBankProvince" id="beneficiaryBankProvince" maxlength="50" tabindex="14" class="inputheightsmall regBoxInput" style="width:300px" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Postal Code:
	</div>
</div>


<div>

    <div>


<input type="text" value=""  name="beneficiaryBankPostalCode" id="beneficiaryBankPostalCode" maxlength="20" tabindex="15" class="inputheightsmall regBoxInput" style="width:300px" onblur="">




</div>
</div>
<div style="clear:both;"></div>
</div>




<div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank State:
	</div>
</div>


<div>

<div class="regformsubrow">


<span>
	<select  name="beneficiaryBankState" id="beneficiaryBankState" tabindex="16" class="regformselect" style="width:300px">
		  
<option value="AL"  >Alabama</option>
  
<option value="AK"  >Alaska</option>
  
<option value="AZ"  >Arizona</option>
  
<option value="AR"  >Arkansas</option>
  
<option value="CA"  >California</option>
  
<option value="CO"  >Colorado</option>
  
<option value="CT"  >Connecticut</option>
  
<option value="DE"  >Delaware</option>
  
<option value="DC"  >District Of Columbia</option>
  
<option value="FL"  >Florida</option>
  
<option value="GA"  >Georgia</option>
  
<option value="HI"  >Hawaii</option>
  
<option value="ID"  >Idaho</option>
  
<option value="IL"  >Illinois</option>
  
<option value="IN"  >Indiana</option>
  
<option value="IA"  >Iowa</option>
  
<option value="KS"  >Kansas</option>
  
<option value="KY"  >Kentucky</option>
  
<option value="LA"  >Louisiana</option>
  
<option value="ME"  >Maine</option>
  
<option value="MD"  >Maryland</option>
  
<option value="MA"  >Massachusetts</option>
  
<option value="MI"  >Michigan</option>
  
<option value="MN"  >Minnesota</option>
  
<option value="MS"  >Mississippi</option>
  
<option value="MO"  >Missouri</option>
  
<option value="MT"  >Montana</option>
  
<option value="NE"  >Nebraska</option>
  
<option value="NV"  >Nevada</option>
  
<option value="NH"  >New Hampshire</option>
  
<option value="NJ"  >New Jersey</option>
  
<option value="NM"  >New Mexico</option>
  
<option value="NY"  >New York</option>
  
<option value="NC"  >North Carolina</option>
  
<option value="ND"  >North Dakota</option>
  
<option value="OH"  >Ohio</option>
  
<option value="OK"  >Oklahoma</option>
  
<option value="OR"  >Oregon</option>
  
<option value="PA"  >Pennsylvania</option>
  
<option value="RI"  >Rhode Island</option>
  
<option value="SC"  >South Carolina</option>
  
<option value="SD"  >South Dakota</option>
  
<option value="TN"  >Tennessee</option>
  
<option value="TX"  >Texas</option>
  
<option value="UT"  >Utah</option>
  
<option value="VT"  >Vermont</option>
  
<option value="VA"  >Virginia</option>
  
<option value="WA"  >Washington</option>
  
<option value="WV"  >West Virginia</option>
  
<option value="WI"  >Wisconsin</option>
  
<option value="WY"  >Wyoming</option>
  
<option value="AA"  >Armed Forces Americas</option>
  
<option value="AE"  >Armed Forces Europe</option>
  
<option value="AP"  >Armed Forces Pacific</option>

	</select>
</span>


</div>
</div>
 
</div>




<div>



<div>

 
</div>
 
</div>


 
<script type="text/javascript">
var currencyMap = eval({"DZ":"DZD","AO":"AOA","AQ":"USD","AW":"AWG","AZ":"AZM","BS":"BSD","BE":"EUR","BT":"BTN","BO":"BOB","IO":"USD","BF":"XOF","CA":"CAD","CF":"XAF","CL":"CLP","CN":"USD","CC":"AUD","KM":"KMF","CD":"CDF","CR":"CRC","HR":"HKR","TP":"USD","EC":"ECS","EG":"EGP","ET":"ETB","FX":"EUR","TF":"EUR","GM":"GMD","GE":"GEL","GR":"EUR","GL":"DKK","GP":"XEU","GU":"USD","GN":"GNS","GY":"GYD","HT":"HTG","HK":"HKD","IN":"INR","IL":"ILS","JM":"JMD","JO":"JOD","KZ":"KZT","KI":"AUD","KR":"KRW","KW":"KWD","LV":"LVL","LS":"LSL","MG":"MGF","MY":"MYR","MV":"MVR","ML":"XOF","MQ":"XEU","MC":"XEU","MN":"MNT","MA":"MAD","MZ":"MZM","MM":"MMK","AN":"ANG","NC":"XPF","NE":"XOF","NG":"NGN","NU":"NZD","MP":"USD","OM":"OMR","PW":"USD","PA":"PAB","RE":"XEU","RO":"RON","RU":"RUB","LC":"XCD","WS":"WST","ST":"STD","SA":"SAR","SC":"SCR","SG":"SGD","SO":"SOS","LK":"LKR","SH":"SHP","PM":"FRE","SR":"SRG","SJ":"NOK","SE":"SEK","TW":"TWD","TG":"XOF","TK":"NZD","TR":"TRY","TV":"AUD","UG":"UGX","AE":"AED","GB":"GBP","US":"USD","UM":"USD","UZ":"UZS","VU":"VUV","EH":"ESP","LA":"LAK","AT":"EUR","BA":"BAM","GI":"GIP","VA":"ITL","SI":"EUR","AG":"XCD","BB":"BBD","KY":"KYD","CU":"CUP","GD":"XCD","GT":"GTQ","HN":"HNL","MX":"MXN","NI":"NIO","AU":"AUD","CK":"NZD","FJ":"FJD","PF":"XPF","MO":"MOP","NF":"AUD","PN":"NZD","SB":"SBD","AR":"ARS","BR":"BRL","CO":"COP","FK":"FKP","PE":"PEN","AI":"XCD","BZ":"BZD","MS":"XCD","VI":"USD","CZ":"CZK","HU":"HUF","PL":"PLN","SK":"EUR","LB":"LBP","YE":"YER","AM":"AMD","BY":"BYR","KG":"KGS","LT":"LTL","MD":"MDL","TJ":"RUB","UA":"UAH","BD":"BDT","KH":"KHR","ID":"IDR","PH":"PHP","TH":"THB","BJ":"XOF","BW":"BWP","BI":"BIF","CV":"CVE","TD":"XAF","CG":"XAF","CI":"XOF","DJ":"DJF","ER":"ERN","GH":"GHC","GW":"XAF","LR":"LRD","MW":"MWK","MR":"MRO","NA":"NAD","RW":"RWF","SN":"XOF","SL":"SLL","ZA":"ZAR","SD":"SDD","TZ":"TZS","ZW":"ZWD","DK":"DKK","FO":"DKK","FI":"EUR","DE":"EUR","IE":"EUR","LU":"EUR","NL":"EUR","PT":"EUR","CH":"CHF","GF":"XEU","AS":"USD","BH":"BHD","BN":"BND","CY":"USD","SV":"SVC","HM":"AUD","JP":"JPY","KE":"KES","MK":"MKD","MH":"USD","MU":"MUR","NR":"AUD","NZ":"NZD","NO":"NOK","QA":"QAR","KN":"XCD","VC":"XCD","ES":"EUR","SZ":"SZL","TM":"TMM","UY":"UYU","WF":"XPF","TC":"USD","VE":"VEB","ZM":"ZMK","LI":"CHF","DO":"DOP","AL":"ALL","CM":"XAF","FR":"EUR","IS":"ISK","LY":"LYD","NP":"NPR","PY":"PYG","GS":"GBP","VN":"VND","BM":"BMD","RS":"USD","ME":"EUR","PS":"ILS","BV":"NOK","GQ":"GQE","IT":"EUR","FM":"USD","SM":"USD","TO":"TOP","KP":"KPW","TT":"TTD","PK":"PKR","GA":"XAF","AD":"EUR","CX":"AUD","OT":null,"AF":"AFA","BG":"BGL","DM":"XCD","EE":"EUR","IR":"IRR","IQ":"IQD","MT":"EUR","YT":"FRF","PG":"PGK","PR":"USD","SY":"SYP","TN":"TND","VG":"GBP","CW":"ANG"});
var ruleMap = eval({"US":{"primary":"ABA Routing Number","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"IN":{"primary":"SWIFT Code","secondary":"IFSC Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"CA":{"primary":"SWIFT Code","secondary":"Transit Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"GB":{"primary":"SWIFT Code","secondary":"Sort Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"DE":{"primary":"SWIFT Code","secondary":"German Bankleitzahl Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"AU":{"primary":"SWIFT Code","secondary":"AU BSB Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"NZ":{"primary":"SWIFT Code","secondary":"NZ BSB Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"ZA":{"primary":"SWIFT Code","secondary":"South African Bank Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"PK":{"primary":"SWIFT Code","secondary":null,"branch":"Branch Name","localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"BD":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"RU":{"primary":"SWIFT Code","secondary":"BIC Code","branch":null,"localTaxId":"INN Code","account":"Account Number \/ IBAN","special":"VO Code"},"MX":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"CLABE Account Number","special":null},"OT":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"DZ":{"primary":"SWIFT Code","secondary":"Bank Branch Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"AR":{"primary":"SWIFT Code","secondary":"CBU","branch":null,"localTaxId":"CUIL \/ CUIT ","account":"Account Number \/ IBAN","special":null},"BR":{"primary":"SWIFT Code","secondary":"Bank Agency Code","branch":null,"localTaxId":"CNPJ \/ CPF","account":"Account Number \/ IBAN","special":"Purpose of Payment"},"KZ":{"primary":"SWIFT Code","secondary":"BKK\/MFO Code","branch":null,"localTaxId":"RNN","account":"Account Number \/ IBAN","special":"Purpose of Payment"},"KE":{"primary":"SWIFT Code","secondary":"Sort Code","branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"EG":{"primary":"SWIFT Code","secondary":null,"branch":"Branch Name","localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"ET":{"primary":"SWIFT Code","secondary":null,"branch":"Branch Name","localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"UG":{"primary":"SWIFT Code","secondary":null,"branch":"Branch Name","localTaxId":null,"account":"Account Number \/ IBAN","special":null},"BF":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"CL":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":"RUT","account":"Account Number \/ IBAN","special":"Purpose of Payment"},"CN":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":null},"GN":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"KR":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"ML":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"NE":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"SA":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"TG":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"CO":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":"NIT \/ Cedulla","account":"Account Number \/ IBAN","special":"Purpose of Payment"},"UA":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"ID":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"BJ":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"SN":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"NP":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":null,"account":"Account Number \/ IBAN","special":"Purpose of Payment"},"CR":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":"NIT \/ Cedulla","account":"Account Number \/ IBAN","special":null},"GT":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":"NIT \/ Cedulla","account":"Account Number \/ IBAN","special":null},"VE":{"primary":"SWIFT Code","secondary":null,"branch":null,"localTaxId":"RIF","account":"Account Number \/ IBAN","special":null}});
removeConfirmationText = "Are you sure you want to Remove Intermediary Bank Info?";
intermediaryAddBtnText = "Add Intermediary Bank";
intermediaryEditBtnText = "Edit Intermediary Bank";
</script>

			<input id="mode" type="hidden" name="mode" value="undefined"/>
		 
			

 
<?php 
}
?>

<div><br />
    <p style="border-bottom: 1px solid #000; font-size: 16px;"><b>Account Holder Information</b>
   </p>   
</div>
 
    <?php
if (1 == 2 ) {
?>
<div>
	<div  class="formlabel">
		<span class="warning">*</span> Destination Currency:
	</div>
</div>


 
<div>
 <span>
	<select  name="destinationCurrency" id="destinationCurrency" tabindex="1"  style="width:300px" onchange="handleCurrencyChange()">
		  
<option value="ARS"  >ARGENTINE PESO</option>
  
<option value="AMD"  >ARMENIAN DRAM</option>
  
<option value="AUD"  >AUSTRALIAN DOLLAR</option>
  
<option value="BDT"  >BANGLADESHI TAKKA</option>
  
<option value="BRL"  >BRAZILIAN REAL</option>
  
<option value="GBP"  >BRITISH POUND</option>
  
<option value="BGN"  >BULGARIAN LEV</option>
  
<option value="CAD"  >CANADIAN DOLLAR</option>
  
<option value="XPF"  >CFP FRANC</option>
  
<option value="CLP"  >CHILEAN PESO</option>
  
<option value="COP"  >COLOMBIAN PESO</option>
  
<option value="CRC"  >COSTA RICAN COLON</option>
  
<option value="HRK"  >CROATIAN KUNA</option>
  
<option value="CZK"  >CZECH KORUNA</option>
  
<option value="DKK"  >DANISH KRONE</option>
  
<option value="DOP"  >DOMINICAN PESO</option>
  
<option value="EGP"  >EGYPTIAN POUND</option>
  
<option value="EUR"  >EURO</option>
  
<option value="FJD"  >FIJI DOLLAR</option>
  
<option value="GHS"  >GHANA CEDI</option>
  
<option value="GTQ"  >GUATEMALAN QUETZAL</option>
  
<option value="HKD"  >HONG KONG DOLLAR</option>
  
<option value="HUF"  >HUNGARY FORINT</option>
  
<option value="INR"  >INDIAN RUPEE</option>
  
<option value="IDR"  >INDONESIA RUPIAH</option>
  
<option value="ILS"  >ISRAELI SHEKEL</option>
  
<option value="JMD"  >JAMAICAN DOLLAR</option>
  
<option value="JPY"  >JAPANESE YEN</option>
  
<option value="JOD"  >JORDANIAN DINAR</option>
  
<option value="KZT"  >KAZAKHSTANI TENGE</option>
  
<option value="KES"  >KENYAN SHILLING</option>
  
<option value="KWD"  >KUWAITI DINAR</option>
  
<option value="LTL"  >LITHUANIAN LITAS</option>
  
<option value="MKD"  >MACEDONIAN DENAR</option>
  
<option value="MYR"  >MALAYSIAN RINGGIT</option>
  
<option value="MXN"  >MEXICAN NUEVO PESO</option>
  
<option value="MDL"  >MOLDAVIAN LEU</option>
  
<option value="NPR"  >NEPALESE RUPEE</option>
  
<option value="PLN"  >NEW POLISH ZLOTY</option>
  
<option value="NZD"  >NEW ZEALAND DOLLAR</option>
  
<option value="NOK"  >NORWEGIAN KRONE</option>
  
<option value="PKR"  >PAKISTAN RUPEE</option>
  
<option value="PGK"  >PAPUA NEW GUINEA KINA</option>
  
<option value="PHP"  >PHILIPPINE PESO</option>
  
<option value="OMR"  >RIAL OMANI</option>
  
<option value="RON"  >ROMANIAN NEW LEU</option>
  
<option value="RUB"  >RUSSIAN RUBLE</option>
  
<option value="SAR"  >SAUDI RIYAL</option>
  
<option value="SGD"  >SINGAPORE DOLLAR</option>
  
<option value="ZAR"  >SOUTH AFRICA RAND</option>
  
<option value="KRW"  >SOUTH KOREAN WON</option>
  
<option value="LKR"  >SRI LANKAN RUPEE</option>
  
<option value="SEK"  >SWEDISH KRONA</option>
  
<option value="CHF"  >SWISS FRANC</option>
  
<option value="THB"  >THAILAND BAHT</option>
  
<option value="TRY"  >TURKISH LIRA</option>
  
<option value="AED"  >UAE DIRHAM</option>
  
<option value="UYU"  >URUGUAYAN PESO</option>
  
<option value="USD"  selected>US DOLLAR</option>
  
<option value="VEB"  >VENEZUELAN BOLIVAR</option>
  
<option value="VND"  >VIETNAMESE DONG</option>

	</select>
</span>
</div>
<?php

}
?>
   <div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Bank Account
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value="<?php echo isset($this->address->account) ? $this->address->account : ''; ?>" name="bankaccount" id="bankaccount" maxlength="20" tabindex="19" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
</div>




</div>
</div>
                           <div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> BIC / Switft
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value="<?php echo isset($this->address->bic) ? $this->address->bic : ''; ?>"  name="bicswift" id="bicswift" maxlength="20" tabindex="19" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
</div>




</div>
</div>
                        
                        
   <div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Account Holder Name
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value="<?php echo isset($this->address->holder_name) ? $this->address->holder_name : ''; ?>"  name="accountholdername" id="accountholdername" maxlength="20" tabindex="19" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
</div>




</div>
</div>
  
                          <div>

<div>
	<div  class="formlabel">
		<span class="warning">*</span> Account Number / IBAN
	</div>
</div>


<div>

<div class="regformsubrow">


<input type="text" value="<?php echo isset($this->address->iban) ? $this->address->iban : ''; ?>"   name="accountholdernumber" id="accountholdernumber" maxlength="20" tabindex="19" class="inputheightsmall regBoxInput" style="width:300px;" onblur="">
</div>




</div>
</div> 
                        

    <input type="hidden" name="payment_type" value="3" />
    <input type='hidden' name='task' value='method' />
     <input type='hidden' name='option' value='com_colcrewallet' />
     <input type='hidden' name='user_id' value='<?php echo $this->user_id;?>' />
     <?php $id = JRequest::getInt('id'); ?>
     <input type='hidden' name='id' value='<?php echo $id; ?>' />
       <?php echo JHtml::_( 'form.token' ); ?>
<input type="Submit" value="Submit" style="padding: 10px; background: #fff; width: 120px;"/>
</form></div>