<?php
/* use Storage; */

/**
 * @param $status = int
 * return status name
 */

function Status_type($status) {
    $status_name = "";
    switch ($status) {
        case 0:
            $status_name = "Active";
            break;
        case 1:
            $status_name = "Pending";
            break;
        case 2:
            $status_name = "Declined";
            break;
        case 3:
            $status_name = "Suspended";
            break;
        case 4:
            $status_name = "Disabled";
            break;
        case 5:
            $status_name = "Declined - Re Submit";
            break;
        default:
            # code...
            break;
    }
    return $status_name;
}

/**
 * @param $status = int
 * return status name
 */
function Reseller_type($reseller_type) {
    $reseller_type_name = "";

    switch ($reseller_type) {
        case 0:
            $reseller_type_name = "Individual";
            break;

        case 1:
            $reseller_type_name = "Business";
            break;
        
        default:
            # code...
            break;
    }

    return $reseller_type_name;
}

/**
 * @param $status = int
 * return status name
 */
function getProfileEditRequestStatusName($status) {
    $status_name = "";
    switch ($status) {
        case 0:
            $status_name = "Approved";
            break;
        case 1:
            $status_name = "Pending";
            break;
        case 2:
            $status_name = "Declined";
            break;
        
        default:
            # code...
            break;
    }

    return $status_name;
}

function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}


function Country_list() {
    return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
}

/**
 * @param $status = int
 * return status name
 */
function Status_type_category($status) {
    $status_name = "";
    switch ($status) {
        case 0:
            $status_name = "Active";
            break;
        case 1:
            $status_name = "Deactivated";
            break;
        default:
            # code...
            break;
    }
    return $status_name;
}

/**
 * @param $json = json
 * return clean json
 */
function cleanJson($json) {
    $json = str_replace('"[', '[', $json);
    $json = str_replace(']"', ']', $json);
    $json = html_entity_decode($json);
    $json = stripslashes($json);
    return $json;
}

function getProfilePhoto($username_id){
    $profile_path = \Storage::files("/public/avatars/".$username_id);
    // $profile_img = '/storage/app/public/avatars/default.png';
    $profile_img = '/storage/avatars/default.png';
    if ($profile_path) {
        $filename = str_replace("public/avatars/".$username_id."/", "", $profile_path[0]);
        // $profile_img = url("/storage/app/public/avatars/".$username_id."/".$filename);
        $profile_img = url("/storage/avatars/".$username_id."/".$filename);
    }

    return $profile_img;
}

function ph_regions() {
    return [
    'Region I',
    'Region II',
    'Region III',
    'Region IV‑A',
    'Region IV-B',
    'Region V',
    'Region VI',
    'Region VII',
    'Region VIII',
    'Region IX',
    'Region X',
    'Region XI',
    'Region XII',
    'Region XIII',
    'NCR',
    'CAR',
    'BARMM'];
}