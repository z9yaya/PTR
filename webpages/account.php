<?php 
include '../functions/functions.php';
if (session_id() == '')
    {
        session_start();
    }
if(empty($_SESSION['EMPID']))
    header("Location: start.php");
else if(isset($_SESSION['INITIALSETUP']))
{
    if ($_SESSION['INITIALSETUP'] == 1)
    {
       checkShift();
       header("Location: ../index.php#/dashboard");
    } elseif ($_SESSION['INITIALSETUP'] == 3) {
        header("Location: start.php");
        return false;
    }
    
}
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../stylesheets/normalize.css">
<link rel="stylesheet" href="account.css">
<script src="../javascript/jquery-3.2.0.min.js"></script>
<script>$.getScript("../javascript/account.js");
</script>
</head>
<div class="accountContent">
    <div class="accountInfo boxContainer">
        <svg class="title_icon" xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 48 48" x="0px" y="0px" width="110" height="110" version="1.1" xmlns:xml="http://www.w3.org/XML/1998/namespace" xml:space="preserve"><defs id="defs39"></defs><g id="g4" style="stroke: #d4d4d4; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" transform="matrix(0.0490052 0 0 0.0490052 13.4889 13.489)"><path id="path2" style="fill: none; stroke: #d4d4d4; stroke-dasharray: none; stroke-miterlimit: 4; stroke-opacity: 1; stroke-width: 22.4466;" d="m 414.101 373.866 l -106.246 -56.188 l -4.907 -15.332 c -1.469 -5.137 -3.794 -10.273 -8.576 -11.623 c -1.519 -0.428 -3.441 -3.201 -3.689 -5.137 l -2.836 -29.813 c -0.156 -2.553 0.868 -4.844 2.216 -6.453 c 8.14 -9.754 12.577 -21.051 14.454 -33.967 c 0.944 -6.494 4.323 -12.483 6.059 -18.879 l 6.812 -35.649 c 0.711 -4.681 0.573 -8.289 -4.659 -10.103 c -1.443 -0.503 -2.699 -2.894 -2.699 -6.479 l 0.069 -67.264 C 308.988 60.699 300.368 48.11 288.142 39.348 C 264.788 22.609 222.967 30.371 236.616 3.067 C 237.422 1.46 237.165 -1.332 231.554 0.732 C 210.618 8.435 154.853 28.789 140.844 39.348 C 128.306 48.797 120 60.699 118.887 76.979 l 0.069 67.264 c 0 2.96 -1.255 5.976 -2.7 6.479 c -5.233 1.813 -5.37 5.422 -4.659 10.103 l 6.814 35.649 c 1.732 6.396 5.113 12.386 6.058 18.879 c 1.875 12.916 6.315 24.213 14.453 33.967 c 1.347 1.609 2.372 3.9 2.216 6.453 l -2.836 29.813 c -0.249 1.936 -2.174 4.709 -3.69 5.137 c -4.783 1.35 -7.109 6.486 -8.577 11.623 l -4.909 15.332 l -106.25 56.188 c -2.742 1.449 -4.457 4.297 -4.457 7.397 v 39.343 c 0 4.621 3.748 8.368 8.37 8.368 h 391.4 c 4.622 0 8.37 -3.747 8.37 -8.368 v -39.343 c -0.002 -3.1 -1.717 -5.948 -4.458 -7.397 Z" /></g><g id="g6" transform="translate(0 -388.759)" /><g id="g8" transform="translate(0 -388.759)" /><g id="g10" transform="translate(0 -388.759)" /><g id="g12" transform="translate(0 -388.759)" /><g id="g14" transform="translate(0 -388.759)" /><g id="g16" transform="translate(0 -388.759)" /><g id="g18" transform="translate(0 -388.759)" /><g id="g20" transform="translate(0 -388.759)" /><g id="g22" transform="translate(0 -388.759)" /><g id="g24" transform="translate(0 -388.759)" /><g id="g26" transform="translate(0 -388.759)" /><g id="g28" transform="translate(0 -388.759)" /><g id="g30" transform="translate(0 -388.759)" /><g id="g32" transform="translate(0 -388.759)" /><g id="g34" transform="translate(0 -388.759)" /></svg>
            <div class="containers">
                    <input id="email" type="text" name="ptr:employees:email" class="text input" disabled>
                    <label for="email"  class="label notEmpty">Email address</label>
            </div>
            <div class="containers">
                    <input id="empID" type="text" name="ptr:employees:empID" class="text input" disabled>
                    <label for="empID"  class="label notEmpty">Employee ID</label>
            </div>
    </div>
    <form class="form accountPages preload" method="POST" action="#">
        <div class="employeeDetails boxContainer">
            <div class="containers">
                    <input id="f_name" type="text" name="string:employees:f_name" class="text input" required>
                    <label for="f_name"  class="label">First name</label>
                    <div class="errorContainer"><span class="error f_name"></span></div>
            </div>
            <div class="containers">
                    <input id="m_name" type="text" name="string:employees:m_name" class="text input" value="">
                    <label for="m_name" class="label">Middle name</label>
                    <div class="errorContainer"><span class="error m_name"></span></div>
            </div>
            <div class="containers">
                    <input id="l_name" type="text" name="string:employees:l_name" class="text input" required>
                    <label for="l_name"  class="label">Last name</label>
                    <div class="errorContainer"><span class="error l_name"></span></div>
            </div>
            <div class="containers">
                <label for="dob"  class="label notEmpty dob">Date of birth</label>
                <div class="containers nomarg inline" id="dob">
                    <div class="containers inline nomarg">
                        <select name="dob:employees:day" id="day" class="input drop nomarg" required>
                            <option value=''>Day</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            <option value='6'>6</option>
                            <option value='7'>7</option>
                            <option value='8'>8</option>
                            <option value='9'>9</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                            <option value='13'>13</option>
                            <option value='14'>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            <option value='20'>20</option>
                            <option value='21'>21</option>
                            <option value='22'>22</option>
                            <option value='23'>23</option>
                            <option value='24'>24</option>
                            <option value='25'>25</option>
                            <option value='26'>26</option>
                            <option value='27'>27</option>
                            <option value='28'>28</option>
                            <option value='29'>29</option>
                            <option value='30'>30</option>
                            <option value='31'>31</option>
                    </select>
                </div>
                <div class="containers inline nomarg">
                    <select name="dob:employees:month" id="month" class="input drop nomarg" required>
                            <option value=''>Month</option>
                            <option value='1'>January</option>
                            <option value='2'>February</option>
                            <option value='3'>March</option>
                            <option value='4'>April</option>
                            <option value='5'>May</option>
                            <option value='6'>June</option>
                            <option value='7'>July</option>
                            <option value='8'>August</option>
                            <option value='9'>September</option>
                            <option value='10'>October</option>
                            <option value='11'>November</option>
                            <option value='12'>December</option>
                    </select>
                </div>
                <div class="containers inline nomarg">
                    <select name="dob:employees:year" id="year" class="input drop" required>
                            <option value=''>Year</option>
                                        <?php for($i = date("Y")-14; $i > 1949; $i--)
                        {
                            echo "<option value='".$i."'>".$i."</option>";
                        }
                    ?>
                    </select>
                    </div>
                    <div class="errorContainer"><span class="error dob"></span></div>
                </div>
                
            </div>
            <div class="containers negativeMarg">
                    <input id="phone" type="text" name="number:employees:phone" class="text input" required maxlength="10">
                    <label for="phone"  class="label">Contact number</label>
                    <div class="errorContainer"><span class="error phone"></span></div>
            </div>
        </div>
        <div class="addressDetails boxContainer">
            <div class="containers">
                    <input id="street1" type="text" name="string:employees:street1" class="text input" required>
                    <label for="street1"  class="label">Address line 1</label>
                    <div class="errorContainer"><span class="error street1"></span></div>
            </div>
            <div class="containers">
                    <input id="street2" type="text" name="string:employees:street2" class="text input">
                    <label for="street2"  class="label">Address line 2</label>
                    <div class="errorContainer"><span class="error street2"></span></div>
            </div>
            <div class="containers inline">
                    <input id="suburb" type="text" name="string:employees:suburb" class="input text nomarg" required>
                    <label for="suburb"  class="label">Suburb</label>
                    <div class="errorContainer"><span class="error suburb"></span></div>
            </div>
            <div class="containers inline">
                    <input id="postcode" type="text" name="number:employees:postcode" class="input text nomarg" required size="8" maxlength="4">
                    <label for="postcode"  class="label">Postcode</label>
                    <div class="errorContainer"><span class="error postcode"></span></div>
            </div>
            <div class="containers inline">
                    <select id="state" name="string:employees:state" class="input text nomarg drop" required="required">
                        <option value="" class="default">State/Territory</option>
                        <option value="ACT">Australian Capital Territory</option>
                        <option value="NSW">New South Wales</option>
                        <option value="NT">Northern Territory</option>
                        <option value="QLD">Queensland</option>
                        <option value="SA">South Australia</option>
                        <option value="TAS">Tasmania</option>
                        <option value="VIC">Victoria</option>
                        <option value="WA">Western Australia</option>
                    </select>
                    <div class="errorContainer"><span class="error state"></span></div>
            </div>
        </div>
        <div class="bankingDetails boxContainer">
            <div class="containers inline">
                    <input id="tfn" type="text" name="number:banking:tfn" class="text input nomarg" required maxlength="10" size="20">
                    <label for="tfn"  class="label">Tax File Number (TFN)</label>
                    <div class="errorContainer"><span class="error tfn"></span></div>
            </div>
            <div class="containers inline">
                    <input id="bankname" type="text" name="string:banking:bankname" class="text input nomarg" required>
                    <label for="bankname"  class="label">Bank name</label>
                    <div class="errorContainer"><span class="error bankname"></span></div>
            </div><br/>
            <div class="containers inline">
                    <input id="bsb" type="text" name="number:banking:bsb" class="text input bsb nomarg" required minlenght="6" size="6" maxlength="6">
                    <label for="bsb"  class="label">BSB</label>
                    <div class="errorContainer"><span class="error bsb"></span></div>
            </div>
            <div class="containers inline">
                    <input id="accnumber" type="text" name="number:banking:accnumber" class="text input nomarg" required>
                    <label for="accnumber"  class="label">Account number</label>
                    <div class="errorContainer"><span class="error accnumber"></span></div>
            </div>
        </div>
        <input type="hidden" name="number:employees:initialsetup" value="1">
        <input type="submit" class="submit button">
    </form>
</div>
