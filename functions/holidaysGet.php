<?php include "tools.php";
date_default_timezone_set('Australia/Brisbane');
//URL of targeted site  
$url = "http://data.gov.au/api/action/datastore_search_sql?sql=SELECT%20%22Date%22%20,%20%22ApplicableTo%22%20,%20%22HolidayName%22%20from%20%2231eec35e-1de6-4f04-9703-9be1d43d405b%22&format=json";  
$ch = curl_init();  
// set URL and other appropriate options  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_HEADER, 0);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
// grab URL and pass it to the browser  
$output = curl_exec($ch);  
// close curl resource, and free up system resources  
curl_close($ch); 
$holidays = json_decode($output, true);
$holidayArr = $holidays['result']['records'];
$insertValues = '';
for($i= 0; $i<count($holidayArr); $i++)
{
    if (strlen($holidayArr[$i]['ApplicableTo']) > 3)
    {
        $states[$i] = explode("|", $holidayArr[$i]['ApplicableTo']);
        for ($j=0;$j < count($states[$i]);$j++)
        {
            $insertValues .= 'INTO PUBLICHOLIDAY (HOLIDAYSTART, HOLIDAYEND, HOLIDAYNAME, STATE) VALUES (\''.strtotime($holidayArr[$i]['Date']).'\', \''.strtotime($holidayArr[$i]['Date'].' 11:59 PM').'\', Q\'['.$holidayArr[$i]['HolidayName'].']\', \''.$states[$i][$j].'\')';
        }
    }
    else
    {
        $insertValues .= 'INTO PUBLICHOLIDAY (HOLIDAYSTART, HOLIDAYEND, HOLIDAYNAME, STATE) VALUES (\''.strtotime($holidayArr[$i]['Date']).'\',  \''.strtotime($holidayArr[$i]['Date'].' 11:59 PM').'\', Q\'['.$holidayArr[$i]['HolidayName'].']\', \''.$holidayArr[$i]['ApplicableTo'].'\')';
    }
}
$s = $insertValues;
//echo $s;
InsertData("TRUNCATE TABLE PUBLICHOLIDAY");
$query = "INSERT ALL ".$s." SELECT * FROM DUAL";
print_r(InsertData($query));
?>  