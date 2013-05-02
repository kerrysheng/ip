<?php
function quan_convert($str){
$quan_map = array('ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ',
		        'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ',
		        'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
		        'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ',
		        '１', '２', '３', '４', '５', '６', '７', '８', '９', '０', '，', '．', '；', '：', '～', '！', '（', '）', '｛', '｝', '［', '］', '＜', '＞',
		        '？', '＄', '＃', '％', '＠', '＆', '＊', //以下是中英文不同的标点
		        '。', '、', '￥',//以下替换内部空格
				' ');
$normal_map = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
		        'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
		        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
		        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ',', '.', ';', ':', '~', '!', '(', ')', '{', '}', '[', ']', '<', '>',
		        '?', '$', '#', '%', '@', '&', '*',
				  '.', '/', '$',
				'');
//$str_map=str_split($str);
//foreach($str_map as $a =>&$b){
//	foreach ($quan_map as $k=>$v){
//		if($b==$v){$b=$nomal_map[$k];
//		break;}
//	}
//}
$str_map=str_replace($quan_map,$normal_map,$str);
return $str_map;
}
function gpcr($k, $t = 'GP', $dv = '') {
    $t = strtoupper($t);
    switch ($t) {
        case 'G':
            $var = &$_GET;
            break;
        case 'P':
            $var = &$_POST;
            break;
        case 'C':
            $var = &$_COOKIE;
            break;
        case 'R':
            $var = &$_REQUEST;
            break;
        case 'GP':
        case 'PG':
        default:
            isset($_POST[$k]) ? $var = &$_POST : $var = &$_GET;
            break;
    }
    return isset($var[$k]) ? empty($var[$k]) ? $dv : $var[$k] : '';
}
function detect_browser_version($title) {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $start = $title;
    if ((strtolower($title) == strtolower("Opera") || strtolower($title) == strtolower("Opera Next") || strtolower($title) == strtolower("Opera Labs")) && preg_match('/Version/i', $ua)) {
        $start = "Version";
    } elseif (strtolower($title) == strtolower("Opera Mobi") && preg_match('/Version/i', $ua)) {
        $start = "Version";
    } elseif (strtolower($title) == strtolower("Safari") && preg_match('/Version/i', $ua)) {
        $start = "Version";
    } elseif (strtolower($title) == strtolower("Pre") && preg_match('/Version/i', $ua)) {
        $start = "Version";
    } elseif (strtolower($title) == strtolower("Android Webkit")) {
        $start = "Version";
    } elseif (strtolower($title) == strtolower("Links")) {
        $start = "Links (";
    } elseif (strtolower($title) == strtolower("UC Browser")) {
        $start = "UC Browse";
    } elseif (strtolower($title) == strtolower("TenFourFox")) {
        $start = " rv";
    } elseif (strtolower($title) == strtolower("Classilla")) {
        $start = " rv";
    } elseif (strtolower($title) == strtolower("SmartTV")) {
        $start = "WebBrowser";
    }
    // Grab the browser version if its present
    preg_match('/' . $start . '[\ |\/]?([.0-9a-zA-Z]+)/i', $ua, $regmatch);
    $version = $regmatch[1];
    // Return browser Title and Version, but first..some titles need to be changed
    if (strtolower($title) == "msie" && strtolower($version) == "7.0" && preg_match('/Trident\/4.0/i', $ua)) {
        return " 8.0 (Compatibility Mode)"; // Fix for IE8 quirky UA string with Compatibility Mode enabled
    } elseif (strtolower($title) == "msie") {
        return " " . $version;
    } elseif (strtolower($title) == "multi-browser") {
        return "Multi-Browser XP " . $version;
    } elseif (strtolower($title) == "nf-browser") {
        return "NetFront " . $version;
    } elseif (strtolower($title) == "semc-browser") {
        return "SEMC Browser " . $version;
    } elseif (strtolower($title) == "ucweb") {
        return "UC Browser " . $version;
    } elseif (strtolower($title) == "up.browser" || strtolower($title) == "up.link") {
        return "Openwave Mobile Browser " . $version;
    } elseif (strtolower($title) == "chromeframe") {
        return "Google Chrome Frame " . $version;
    } elseif (strtolower($title) == "mozilladeveloperpreview") {
        return "Mozilla Developer Preview " . $version;
    } elseif (strtolower($title) == "multi-browser") {
        return "Multi-Browser XP " . $version;
    } elseif (strtolower($title) == "opera mobi") {
        return "Opera Mobile " . $version;
    } elseif (strtolower($title) == "osb-browser") {
        return "Gtk+ WebCore " . $version;
    } elseif (strtolower($title) == "tablet browser") {
        return "MicroB " . $version;
    } elseif (strtolower($title) == "tencenttraveler") {
        return "TT Explorer " . $version;
    } elseif (strtolower($title) == "crmo") {
        return "Chrome Mobile " . $version;
    } elseif (strtolower($title) == "smarttv") {
        return "Maple Browser " . $version;
    } elseif (strtolower($title) == "wp-android" || strtolower($title) == "wp-iphone") {
        //TODO check into Android version being returned
        return "Wordpress App " . $version;
    } elseif (strtolower($title) == "atomicbrowser") {
        return "Atomic Web Browser " . $version;
    } elseif (strtolower($title) == "barcapro") {
        return "Barca Pro " . $version;
    } elseif (strtolower($title) == "dplus") {
        return "D+ " . $version;
    } elseif (strtolower($title) == "opera labs") {
        preg_match('/Edition\ Labs([\ ._0-9a-zA-Z]+);/i', $ua, $regmatch);
        return $title . $regmatch[1] . " " . $version;
    } else {
        return $title . " " . $version;
    }
}
function getbrowser() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/360se/i', $ua)) {
        $title = "360Safe Explorer";
        $code = "360se";
    } elseif (preg_match('/Abolimba/i', $ua)) {
        $title = "Abolimba";
        $code = "abolimba";
    } elseif (preg_match('/Acoo\ Browser/i', $ua)) {
        $title = "Acoo " . detect_browser_version("Browser");
        $code = "acoobrowser";
    } elseif (preg_match('/Alienforce/i', $ua)) {
        $title = detect_browser_version("Alienforce");
        $code = "alienforce";
    } elseif (preg_match('/Amaya/i', $ua)) {
        $title = detect_browser_version("Amaya");
        $code = "amaya";
    } elseif (preg_match('/Amiga-AWeb/i', $ua)) {
        $title = "Amiga " . detect_browser_version("AWeb");
        $code = "amiga-aweb";
    } elseif (preg_match('/America\ Online\ Browser/i', $ua)) {
        $title = "America Online " . detect_browser_version("Browser");
        $code = "aol";
    } elseif (preg_match('/AmigaVoyager/i', $ua)) {
        $title = "Amiga " . detect_browser_version("Voyager");
        $code = "amigavoyager";
    } elseif (preg_match('/AOL/i', $ua)) {
        $title = detect_browser_version("AOL");
        $code = "aol";
    } elseif (preg_match('/Arora/i', $ua)) {
        $title = detect_browser_version("Arora");
        $code = "arora";
    } elseif (preg_match('/AtomicBrowser/i', $ua)) {
        $title = detect_browser_version("AtomicBrowser");
        $code = "atomicwebbrowser";
    } elseif (preg_match('/Avant\ Browser/i', $ua)) {
        $title = "Avant " . detect_browser_version("Browser");
        $code = "avantbrowser";
    } elseif (preg_match('/baidubrowser/i', $ua)) {
        $title = detect_browser_version("Browser");
        $code = "baidubrowser";
    } elseif (preg_match('/BarcaPro/i', $ua)) {
        $title = detect_browser_version("BarcaPro");
        $code = "barca";
    } elseif (preg_match('/Barca/i', $ua)) {
        $title = detect_browser_version("Barca");
        $code = "barca";
    } elseif (preg_match('/Beamrise/i', $ua)) {
        $title = detect_browser_version("Beamrise");
        $code = "beamrise";
    } elseif (preg_match('/Beonex/i', $ua)) {
        $title = detect_browser_version("Beonex");
        $code = "beonex";
    } elseif (preg_match('/BlackBerry/i', $ua)) {
        $title = detect_browser_version("BlackBerry");
        $code = "blackberry";
    } elseif (preg_match('/Blackbird/i', $ua)) {
        $title = detect_browser_version("Blackbird");
        $code = "blackbird";
    } elseif (preg_match('/BlackHawk/i', $ua)) {
        $title = detect_browser_version("BlackHawk");
        $code = "blackhawk";
    } elseif (preg_match('/Blazer/i', $ua)) {
        $title = detect_browser_version("Blazer");
        $code = "blazer";
    } elseif (preg_match('/Bolt/i', $ua)) {
        $title = detect_browser_version("Bolt");
        $code = "bolt";
    } elseif (preg_match('/BonEcho/i', $ua)) {
        $title = detect_browser_version("BonEcho");
        $code = "firefoxdevpre";
    } elseif (preg_match('/BrowseX/i', $ua)) {
        $title = "BrowseX";
        $code = "browsex";
    } elseif (preg_match('/Browzar/i', $ua)) {
        $title = detect_browser_version("Browzar");
        $code = "browzar";
    } elseif (preg_match('/Bunjalloo/i', $ua)) {
        $title = detect_browser_version("Bunjalloo");
        $code = "bunjalloo";
    } elseif (preg_match('/Camino/i', $ua)) {
        $title = detect_browser_version("Camino");
        $code = "camino";
    } elseif (preg_match('/Cayman\ Browser/i', $ua)) {
        $title = "Cayman " . detect_browser_version("Browser");
        $code = "caymanbrowser";
    } elseif (preg_match('/Charon/i', $ua)) {
        $title = detect_browser_version("Charon");
        $code = "null";
    } elseif (preg_match('/Cheshire/i', $ua)) {
        $title = detect_browser_version("Cheshire");
        $code = "aol";
    } elseif (preg_match('/Chimera/i', $ua)) {
        $title = detect_browser_version("Chimera");
        $code = "null";
    } elseif (preg_match('/chromeframe/i', $ua)) {
        $title = detect_browser_version("chromeframe");
        $code = "google";
    } elseif (preg_match('/ChromePlus/i', $ua)) {
        $title = detect_browser_version("ChromePlus");
        $code = "chromeplus";
    } elseif (preg_match('/Iron/i', $ua)) {
        $title = "SRWare " . detect_browser_version("Iron");
        $code = "srwareiron";
    } elseif (preg_match('/Chromium/i', $ua)) {
        $title = detect_browser_version("Chromium");
        $code = "chromium";
    } elseif (preg_match('/Classilla/i', $ua)) {
        $title = detect_browser_version("Classilla");
        $code = "classilla";
    } elseif (preg_match('/Columbus/i', $ua)) {
        $title = detect_browser_version("Columbus");
        $code = "columbus";
    } elseif (preg_match('/CometBird/i', $ua)) {
        $title = detect_browser_version("CometBird");
        $code = "cometbird";
    } elseif (preg_match('/Comodo_Dragon/i', $ua)) {
        $title = "Comodo " . detect_browser_version("Dragon");
        $code = "comodo-dragon";
    } elseif (preg_match('/Conkeror/i', $ua)) {
        $title = detect_browser_version("Conkeror");
        $code = "conkeror";
    } elseif (preg_match('/CoolNovo/i', $ua)) {
        $title = detect_browser_version("CoolNovo");
        $code = "coolnovo";
    } elseif (preg_match('/Crazy\ Browser/i', $ua)) {
        $title = "Crazy " . detect_browser_version("Browser");
        $code = "crazybrowser";
    } elseif (preg_match('/CrMo/i', $ua)) {
        $title = detect_browser_version("CrMo");
        $code = "chrome";
    } elseif (preg_match('/Cruz/i', $ua)) {
        $title = detect_browser_version("Cruz");
        $code = "cruz";
    } elseif (preg_match('/Cyberdog/i', $ua)) {
        $title = detect_browser_version("Cyberdog");
        $code = "cyberdog";
    } elseif (preg_match('/DPlus/i', $ua)) {
        $title = detect_browser_version("DPlus");
        $code = "dillo";
    } elseif (preg_match('/Deepnet\ Explorer/i', $ua)) {
        $title = detect_browser_version("Deepnet Explorer");
        $code = "deepnetexplorer";
    } elseif (preg_match('/Demeter/i', $ua)) {
        $title = detect_browser_version("Demeter");
        $code = "demeter";
    } elseif (preg_match('/DeskBrowse/i', $ua)) {
        $title = detect_browser_version("DeskBrowse");
        $code = "deskbrowse";
    } elseif (preg_match('/Dillo/i', $ua)) {
        $title = detect_browser_version("Dillo");
        $code = "dillo";
    } elseif (preg_match('/DoCoMo/i', $ua)) {
        $title = detect_browser_version("DoCoMo");
        $code = "null";
    } elseif (preg_match('/DocZilla/i', $ua)) {
        $title = detect_browser_version("DocZilla");
        $code = "doczilla";
    } elseif (preg_match('/Dolfin/i', $ua)) {
        $title = detect_browser_version("Dolfin");
        $code = "samsung";
    } elseif (preg_match('/Dooble/i', $ua)) {
        $title = detect_browser_version("Dooble");
        $code = "dooble";
    } elseif (preg_match('/Doris/i', $ua)) {
        $title = detect_browser_version("Doris");
        $code = "doris";
    } elseif (preg_match('/Dorothy/i', $ua)) {
        $title = detect_browser_version("Dorothy");
        $code = "dorothybrowser";
    } elseif (preg_match('/Edbrowse/i', $ua)) {
        $title = detect_browser_version("Edbrowse");
        $code = "edbrowse";
    } elseif (preg_match('/Elinks/i', $ua)) {
        $title = detect_browser_version("Elinks");
        $code = "elinks";
    } elseif (preg_match('/Element\ Browser/i', $ua)) {
        $title = "Element " . detect_browser_version("Browser");
        $code = "elementbrowser";
    } elseif (preg_match('/Enigma\ Browser/i', $ua)) {
        $title = "Enigma " . detect_browser_version("Browser");
        $code = "enigmabrowser";
    } elseif (preg_match('/EnigmaFox/i', $ua)) {
        $title = detect_browser_version("EnigmaFox");
        $code = "null";
    } elseif (preg_match('/Epic/i', $ua)) {
        $title = detect_browser_version("Epic");
        $code = "epicbrowser";
    } elseif (preg_match('/Epiphany/i', $ua)) {
        $title = detect_browser_version("Epiphany");
        $code = "epiphany";
    } elseif (preg_match('/Escape/i', $ua)) {
        $title = "Espial TV Browser - " . detect_browser_version("Escape");
        $code = "espialtvbrowser";
    } elseif (preg_match('/Fennec/i', $ua)) {
        $title = detect_browser_version("Fennec");
        $code = "fennec";
    } elseif (preg_match('/Firebird/i', $ua)) {
        $title = detect_browser_version("Firebird");
        $code = "firebird";
    } elseif (preg_match('/Fireweb\ Navigator/i', $ua)) {
        $title = detect_browser_version("Fireweb Navigator");
        $code = "firewebnavigator";
    } elseif (preg_match('/Flock/i', $ua)) {
        $title = detect_browser_version("Flock");
        $code = "flock";
    } elseif (preg_match('/Fluid/i', $ua)) {
        $title = detect_browser_version("Fluid");
        $code = "fluid";
    } elseif (preg_match('/Galaxy/i', $ua) && !preg_match('/Chrome/i', $ua)) {
        $title = detect_browser_version("Galaxy");
        $code = "galaxy";
    } elseif (preg_match('/Galeon/i', $ua)) {
        $title = detect_browser_version("Galeon");
        $code = "galeon";
    } elseif (preg_match('/GlobalMojo/i', $ua)) {
        $title = detect_browser_version("GlobalMojo");
        $code = "globalmojo";
    } elseif (preg_match('/GoBrowser/i', $ua)) {
        $title = "GO " . detect_browser_version("Browser");
        $code = "gobrowser";
    } elseif (preg_match('/Google\ Wireless\ Transcoder/i', $ua)) {
        $title = "Google Wireless Transcoder";
        $code = "google";
    } elseif (preg_match('/GoSurf/i', $ua)) {
        $title = detect_browser_version("GoSurf");
        $code = "gosurf";
    } elseif (preg_match('/GranParadiso/i', $ua)) {
        $title = detect_browser_version("GranParadiso");
        $code = "firefoxdevpre";
    } elseif (preg_match('/GreenBrowser/i', $ua)) {
        $title = detect_browser_version("GreenBrowser");
        $code = "greenbrowser";
    } elseif (preg_match('/Hana/i', $ua)) {
        $title = detect_browser_version("Hana");
        $code = "hana";
    } elseif (preg_match('/HotJava/i', $ua)) {
        $title = detect_browser_version("HotJava");
        $code = "hotjava";
    } elseif (preg_match('/Hv3/i', $ua)) {
        $title = detect_browser_version("Hv3");
        $code = "hv3";
    } elseif (preg_match('/Hydra\ Browser/i', $ua)) {
        $title = "Hydra Browser";
        $code = "hydrabrowser";
    } elseif (preg_match('/Iris/i', $ua)) {
        $title = detect_browser_version("Iris");
        $code = "iris";
    } elseif (preg_match('/IBM\ WebExplorer/i', $ua)) {
        $title = "IBM " . detect_browser_version("WebExplorer");
        $code = "ibmwebexplorer";
    } elseif (preg_match('/IBrowse/i', $ua)) {
        $title = detect_browser_version("IBrowse");
        $code = "ibrowse";
    } elseif (preg_match('/iCab/i', $ua)) {
        $title = detect_browser_version("iCab");
        $code = "icab";
    } elseif (preg_match('/Ice Browser/i', $ua)) {
        $title = detect_browser_version("Ice Browser");
        $code = "icebrowser";
    } elseif (preg_match('/Iceape/i', $ua)) {
        $title = detect_browser_version("Iceape");
        $code = "iceape";
    } elseif (preg_match('/IceCat/i', $ua)) {
        $title = "GNU " . detect_browser_version("IceCat");
        $code = "icecat";
    } elseif (preg_match('/IceWeasel/i', $ua)) {
        $title = detect_browser_version("IceWeasel");
        $code = "iceweasel";
    } elseif (preg_match('/IEMobile/i', $ua)) {
        $title = detect_browser_version("IEMobile");
        $code = "msie-mobile";
    } elseif (preg_match('/iNet\ Browser/i', $ua)) {
        $title = "iNet " . detect_browser_version("Browser");
        $code = "null";
    } elseif (preg_match('/iRider/i', $ua)) {
        $title = detect_browser_version("iRider");
        $code = "irider";
    } elseif (preg_match('/Iron/i', $ua)) {
        $title = detect_browser_version("Iron");
        $code = "iron";
    } elseif (preg_match('/InternetSurfboard/i', $ua)) {
        $title = detect_browser_version("InternetSurfboard");
        $code = "internetsurfboard";
    } elseif (preg_match('/Jasmine/i', $ua)) {
        $title = detect_browser_version("Jasmine");
        $code = "samsung";
    } elseif (preg_match('/K-Meleon/i', $ua)) {
        $title = detect_browser_version("K-Meleon");
        $code = "kmeleon";
    } elseif (preg_match('/K-Ninja/i', $ua)) {
        $title = detect_browser_version("K-Ninja");
        $code = "kninja";
    } elseif (preg_match('/Kapiko/i', $ua)) {
        $title = detect_browser_version("Kapiko");
        $code = "kapiko";
    } elseif (preg_match('/Kazehakase/i', $ua)) {
        $title = detect_browser_version("Kazehakase");
        $code = "kazehakase";
    } elseif (preg_match('/Strata/i', $ua)) {
        $title = "Kirix " . detect_browser_version("Strata");
        $code = "kirix-strata";
    } elseif (preg_match('/KKman/i', $ua)) {
        $title = detect_browser_version("KKman");
        $code = "kkman";
    } elseif (preg_match('/KMail/i', $ua)) {
        $title = detect_browser_version("KMail");
        $code = "kmail";
    } elseif (preg_match('/KMLite/i', $ua)) {
        $title = detect_browser_version("KMLite");
        $code = "kmeleon";
    } elseif (preg_match('/Konqueror/i', $ua)) {
        $title = detect_browser_version("Konqueror");
        $code = "konqueror";
    } elseif (preg_match('/Kylo/i', $ua)) {
        $title = detect_browser_version("Kylo");
        $code = "kylo";
    } elseif (preg_match('/LBrowser/i', $ua)) {
        $title = detect_browser_version("LBrowser");
        $code = "lbrowser";
    } elseif (preg_match('/LeechCraft/i', $ua)) {
        $title = "LeechCraft";
        $code = "null";
    } elseif (preg_match('/Links/i', $ua) && !preg_match('/online\ link\ validator/i', $ua)) {
        $title = detect_browser_version("Links");
        $code = "links";
    } elseif (preg_match('/Lobo/i', $ua)) {
        $title = detect_browser_version("Lobo");
        $code = "lobo";
    } elseif (preg_match('/lolifox/i', $ua)) {
        $title = detect_browser_version("lolifox");
        $code = "lolifox";
    } elseif (preg_match('/Lorentz/i', $ua)) {
        $title = detect_browser_version("Lorentz");
        $code = "firefoxdevpre";
    } elseif (preg_match('/Lunascape/i', $ua)) {
        $title = detect_browser_version("Lunascape");
        $code = "lunascape";
    } elseif (preg_match('/Lynx/i', $ua)) {
        $title = detect_browser_version("Lynx");
        $code = "lynx";
    } elseif (preg_match('/Madfox/i', $ua)) {
        $title = detect_browser_version("Madfox");
        $code = "madfox";
    } elseif (preg_match('/Maemo\ Browser/i', $ua)) {
        $title = detect_browser_version("Maemo Browser");
        $code = "maemo";
    } elseif (preg_match('/Maxthon/i', $ua)) {
        $title = detect_browser_version("Maxthon");
        $code = "maxthon";
    } elseif (preg_match('/\ MIB\//i', $ua)) {
        $title = detect_browser_version("MIB");
        $code = "mib";
    } elseif (preg_match('/Tablet\ browser/i', $ua)) {
        $title = detect_browser_version("Tablet browser");
        $code = "microb";
    } elseif (preg_match('/Midori/i', $ua)) {
        $title = detect_browser_version("Midori");
        $code = "midori";
    } elseif (preg_match('/Minefield/i', $ua)) {
        $title = detect_browser_version("Minefield");
        $code = "minefield";
    } elseif (preg_match('/MiniBrowser/i', $ua)) {
        $title = detect_browser_version("MiniBrowser");
        $code = "minibrowser";
    } elseif (preg_match('/Minimo/i', $ua)) {
        $title = detect_browser_version("Minimo");
        $code = "minimo";
    } elseif (preg_match('/Mosaic/i', $ua)) {
        $title = detect_browser_version("Mosaic");
        $code = "mosaic";
    } elseif (preg_match('/MozillaDeveloperPreview/i', $ua)) {
        $title = detect_browser_version("MozillaDeveloperPreview");
        $code = "firefoxdevpre";
    } elseif (preg_match('/MQQBrowser/i', $ua)) {
        $title = "QQbrowser";
        $code = "qqbrowser";
    } elseif (preg_match('/Multi-Browser/i', $ua)) {
        $title = detect_browser_version("Multi-Browser");
        $code = "multi-browserxp";
    } elseif (preg_match('/MultiZilla/i', $ua)) {
        $title = detect_browser_version("MultiZilla");
        $code = "mozilla";
    } elseif (preg_match('/myibrow/i', $ua) && preg_match('/My\ Internet\ Browser/i', $ua)) {
        $title = detect_browser_version("myibrow");
        $code = "my-internet-browser";
    } elseif (preg_match('/MyIE2/i', $ua)) {
        $title = detect_browser_version("MyIE2");
        $code = "myie2";
    } elseif (preg_match('/Namoroka/i', $ua)) {
        $title = detect_browser_version("Namoroka");
        $code = "firefoxdevpre";
    } elseif (preg_match('/Navigator/i', $ua)) {
        $title = "Netscape " . detect_browser_version("Navigator");
        $code = "netscape";
    } elseif (preg_match('/NetBox/i', $ua)) {
        $title = detect_browser_version("NetBox");
        $code = "netbox";
    } elseif (preg_match('/NetCaptor/i', $ua)) {
        $title = detect_browser_version("NetCaptor");
        $code = "netcaptor";
    } elseif (preg_match('/NetFront/i', $ua)) {
        $title = detect_browser_version("NetFront");
        $code = "netfront";
    } elseif (preg_match('/NetNewsWire/i', $ua)) {
        $title = detect_browser_version("NetNewsWire");
        $code = "netnewswire";
    } elseif (preg_match('/NetPositive/i', $ua)) {
        $title = detect_browser_version("NetPositive");
        $code = "netpositive";
    } elseif (preg_match('/Netscape/i', $ua)) {
        $title = detect_browser_version("Netscape");
        $code = "netscape";
    } elseif (preg_match('/NetSurf/i', $ua)) {
        $title = detect_browser_version("NetSurf");
        $code = "netsurf";
    } elseif (preg_match('/NF-Browser/i', $ua)) {
        $title = detect_browser_version("NF-Browser");
        $code = "netfront";
    } elseif (preg_match('/NokiaBrowser/i', $ua)) {
        $title = "Nokia " . detect_browser_version("Browser");
        $code = "nokia";
    } elseif (preg_match('/Novarra-Vision/i', $ua)) {
        $title = "Novarra " . detect_browser_version("Vision");
        $code = "novarra";
    } elseif (preg_match('/Obigo/i', $ua)) {
        $title = detect_browser_version("Obigo");
        $code = "obigo";
    } elseif (preg_match('/OffByOne/i', $ua)) {
        $title = "Off By One";
        $code = "offbyone";
    } elseif (preg_match('/OmniWeb/i', $ua)) {
        $title = detect_browser_version("OmniWeb");
        $code = "omniweb";
    } elseif (preg_match('/Opera Mini/i', $ua)) {
        $title = detect_browser_version("Opera Mini");
        $code = "opera-2";
    } elseif (preg_match('/Opera Mobi/i', $ua)) {
        $title = detect_browser_version("Opera Mobi");
        $code = "opera-2";
    } elseif (preg_match('/Opera Labs/i', $ua) || (preg_match('/Opera/i', $ua) && preg_match('/Edition Labs/i', $ua))) {
        $title = detect_browser_version("Opera Labs");
        $code = "opera-next";
    } elseif (preg_match('/Opera Next/i', $ua) || (preg_match('/Opera/i', $ua) && preg_match('/Edition Next/i', $ua))) {
        $title = detect_browser_version("Opera Next");
        $code = "opera-next";
    } elseif (preg_match('/Opera/i', $ua)) {
        $title = detect_browser_version("Opera");
        $code = "opera-1";
        if (preg_match('/Version/i', $ua))
            $code = "opera-2";
    } elseif (preg_match('/Orca/i', $ua)) {
        $title = detect_browser_version("Orca");
        $code = "orca";
    } elseif (preg_match('/Oregano/i', $ua)) {
        $title = detect_browser_version("Oregano");
        $code = "oregano";
    } elseif (preg_match('/Origyn\ Web\ Browser/i', $ua)) {
        $title = "Oregano Web Browser";
        $code = "owb";
    } elseif (preg_match('/osb-browser/i', $ua)) {
        $title = detect_browser_version("osb-browser");
        $code = "null";
    } elseif (preg_match('/\ Pre\//i', $ua)) {
        $title = "Palm " . detect_browser_version("Pre");
        $code = "palmpre";
    } elseif (preg_match('/Palemoon/i', $ua)) {
        $title = "Pale " . detect_browser_version("Moon");
        $code = "palemoon";
    } elseif (preg_match('/Patriott\:\:Browser/i', $ua)) {
        $title = "Patriott " . detect_browser_version("Browser");
        $code = "patriott";
    } elseif (preg_match('/Phaseout/i', $ua)) {
        $title = "Phaseout";
        $code = "phaseout";
    } elseif (preg_match('/Phoenix/i', $ua)) {
        $title = detect_browser_version("Phoenix");
        $code = "phoenix";
    } elseif (preg_match('/Podkicker/i', $ua)) {
        $title = detect_browser_version("Podkicker");
        $code = "podkicker";
    } elseif (preg_match('/Podkicker\ Pro/i', $ua)) {
        $title = detect_browser_version("Podkicker Pro");
        $code = "podkicker";
    } elseif (preg_match('/Pogo/i', $ua)) {
        $title = detect_browser_version("Pogo");
        $code = "pogo";
    } elseif (preg_match('/Polaris/i', $ua)) {
        $title = detect_browser_version("Polaris");
        $code = "polaris";
    } elseif (preg_match('/Prism/i', $ua)) {
        $title = detect_browser_version("Prism");
        $code = "prism";
    } elseif (preg_match('/QtWeb\ Internet\ Browser/i', $ua)) {
        $title = "QtWeb Internet " . detect_browser_version("Browser");
        $code = "qtwebinternetbrowser";
    } elseif (preg_match('/QupZilla/i', $ua)) {
        $title = detect_browser_version("QupZilla");
        $code = "qupzilla";
    } elseif (preg_match('/rekonq/i', $ua)) {
        $title = "rekonq";
        $code = "rekonq";
    } elseif (preg_match('/retawq/i', $ua)) {
        $title = detect_browser_version("retawq");
        $code = "terminal";
    } elseif (preg_match('/RockMelt/i', $ua)) {
        $title = detect_browser_version("RockMelt");
        $code = "rockmelt";
    } elseif (preg_match('/Ryouko/i', $ua)) {
        $title = detect_browser_version("Ryouko");
        $code = "ryouko";
    } elseif (preg_match('/SaaYaa/i', $ua)) {
        $title = "SaaYaa Explorer";
        $code = "saayaa";
    } elseif (preg_match('/SeaMonkey/i', $ua)) {
        $title = detect_browser_version("SeaMonkey");
        $code = "seamonkey";
    } elseif (preg_match('/SEMC-Browser/i', $ua)) {
        $title = detect_browser_version("SEMC-Browser");
        $code = "semcbrowser";
    } elseif (preg_match('/SEMC-java/i', $ua)) {
        $title = detect_browser_version("SEMC-java");
        $code = "semcbrowser";
    } elseif (preg_match('/Series60/i', $ua) && !preg_match('/Symbian/i', $ua)) {
        $title = "Nokia " . detect_browser_version("Series60");
        $code = "s60";
    } elseif (preg_match('/S60/i', $ua) && !preg_match('/Symbian/i', $ua)) {
        $title = "Nokia " . detect_browser_version("S60");
        $code = "s60";
    } elseif (preg_match('/SE\ /i', $ua) && preg_match('/MetaSr/i', $ua)) {
        $title = "Sogou Explorer";
        $code = "sogou";
    } elseif (preg_match('/Shiira/i', $ua)) {
        $title = detect_browser_version("Shiira");
        $code = "shiira";
    } elseif (preg_match('/Shiretoko/i', $ua)) {
        $title = detect_browser_version("Shiretoko");
        $code = "firefoxdevpre";
    } elseif (preg_match('/Silk/i', $ua) && !preg_match('/PlayStation/i', $ua)) {
        $title = "Amazon " . detect_browser_version("Silk");
        $code = "silk";
    } elseif (preg_match('/SiteKiosk/i', $ua)) {
        $title = detect_browser_version("SiteKiosk");
        $code = "sitekiosk";
    } elseif (preg_match('/SkipStone/i', $ua)) {
        $title = detect_browser_version("SkipStone");
        $code = "skipstone";
    } elseif (preg_match('/Skyfire/i', $ua)) {
        $title = detect_browser_version("Skyfire");
        $code = "skyfire";
    } elseif (preg_match('/Sleipnir/i', $ua)) {
        $title = detect_browser_version("Sleipnir");
        $code = "sleipnir";
    } elseif (preg_match('/SlimBoat/i', $ua)) {
        $title = detect_browser_version("SlimBoat");
        $code = "slimboat";
    } elseif (preg_match('/SlimBrowser/i', $ua)) {
        $title = detect_browser_version("SlimBrowser");
        $code = "slimbrowser";
    } elseif (preg_match('/SmartTV/i', $ua)) {
        $title = detect_browser_version("SmartTV");
        $code = "maplebrowser";
    } elseif (preg_match('/Songbird/i', $ua)) {
        $title = detect_browser_version("Songbird");
        $code = "songbird";
    } elseif (preg_match('/Stainless/i', $ua)) {
        $title = detect_browser_version("Stainless");
        $code = "stainless";
    } elseif (preg_match('/SubStream/i', $ua)) {
        $title = detect_browser_version("SubStream");
        $code = "substream";
    } elseif (preg_match('/Sulfur/i', $ua)) {
        $title = "Flock " . detect_browser_version("Sulfur");
        $code = "flock";
    } elseif (preg_match('/Sundance/i', $ua)) {
        $title = detect_browser_version("Sundance");
        $code = "sundance";
    } elseif (preg_match('/Sunrise/i', $ua)) {
        $title = detect_browser_version("Sundial");
        $code = "sundial";
    } elseif (preg_match('/Sunrise/i', $ua)) {
        $title = detect_browser_version("Sunrise");
        $code = "sunrise";
    } elseif (preg_match('/Surf/i', $ua)) {
        $title = detect_browser_version("Surf");
        $code = "surf";
    } elseif (preg_match('/Swiftfox/i', $ua)) {
        $title = detect_browser_version("Swiftfox");
        $code = "swiftfox";
    } elseif (preg_match('/Swiftweasel/i', $ua)) {
        $title = detect_browser_version("Swiftweasel");
        $code = "swiftweasel";
    } elseif (preg_match('/Sylera/i', $ua)) {
        $title = detect_browser_version("Sylera");
        $code = "null";
    } elseif (preg_match('/tear/i', $ua)) {
        $title = "Tear";
        $code = "tear";
    } elseif (preg_match('/TeaShark/i', $ua)) {
        $title = detect_browser_version("TeaShark");
        $code = "teashark";
    } elseif (preg_match('/Teleca/i', $ua)) {
        $title = detect_browser_version(" Teleca");
        $code = "obigo";
    } elseif (preg_match('/TencentTraveler/i', $ua)) {
        $title = "Tencent " . detect_browser_version("Traveler");
        $code = "tencenttraveler";
    } elseif (preg_match('/TenFourFox/i', $ua)) {
        $title = detect_browser_version("TenFourFox");
        $code = "tenfourfox";
    } elseif (preg_match('/TheWorld/i', $ua)) {
        $title = "TheWorld Browser";
        $code = "theworld";
    } elseif (preg_match('/Thunderbird/i', $ua)) {
        $title = detect_browser_version("Thunderbird");
        $code = "thunderbird";
    } elseif (preg_match('/Tizen/i', $ua)) {
        $title = detect_browser_version("Tizen");
        $code = "tizen";
    } elseif (preg_match('/Tjusig/i', $ua)) {
        $title = detect_browser_version("Tjusig");
        $code = "tjusig";
    } elseif (preg_match('/TencentTraveler/i', $ua)) {
        $title = detect_browser_version("TencentTraveler");
        $code = "tt-explorer";
    } elseif (preg_match('/uBrowser/i', $ua)) {
        $title = detect_browser_version("uBrowser");
        $code = "ubrowser";
    } elseif (preg_match('/UC\ Browser/i', $ua)) {
        $title = detect_browser_version("UC Browser");
        $code = "ucbrowser";
    } elseif (preg_match('/UCWEB/i', $ua)) {
        $title = detect_browser_version("UCWEB");
        $code = "ucweb";
    } elseif (preg_match('/UltraBrowser/i', $ua)) {
        $title = detect_browser_version("UltraBrowser");
        $code = "ultrabrowser";
    } elseif (preg_match('/UP.Browser/i', $ua)) {
        $title = detect_browser_version("UP.Browser");
        $code = "openwave";
    } elseif (preg_match('/UP.Link/i', $ua)) {
        $title = detect_browser_version("UP.Link");
        $code = "openwave";
    } elseif (preg_match('/Usejump/i', $ua)) {
        $title = detect_browser_version("Usejump");
        $code = "usejump";
    } elseif (preg_match('/uZardWeb/i', $ua)) {
        $title = detect_browser_version("uZardWeb");
        $code = "uzardweb";
    } elseif (preg_match('/uZard/i', $ua)) {
        $title = detect_browser_version("uZard");
        $code = "uzardweb";
    } elseif (preg_match('/uzbl/i', $ua)) {
        $title = "uzbl";
        $code = "uzbl";
    } elseif (preg_match('/Vimprobable/i', $ua)) {
        $title = detect_browser_version("Vimprobable");
        $code = "null";
    } elseif (preg_match('/Vonkeror/i', $ua)) {
        $title = detect_browser_version("Vonkeror");
        $code = "null";
    } elseif (preg_match('/w3m/i', $ua)) {
        $title = detect_browser_version("W3M");
        $code = "w3m";
    } elseif (preg_match('/AppleWebkit/i', $ua) && preg_match('/Android/i', $ua) && !preg_match('/Chrome/i', $ua)) {
        $title = detect_browser_version("Android Webkit");
        $code = "android-webkit";
    } elseif (preg_match('/WebianShell/i', $ua)) {
        $title = "Webian " . detect_browser_version("Shell");
        $code = "webianshell";
    } elseif (preg_match('/Webrender/i', $ua)) {
        $title = "Webrender";
        $code = "webrender";
    } elseif (preg_match('/WeltweitimnetzBrowser/i', $ua)) {
        $title = "Weltweitimnetz " . detect_browser_version("Browser");
        $code = "weltweitimnetzbrowser";
    } elseif (preg_match('/wKiosk/i', $ua)) {
        $title = "wKiosk";
        $code = "wkiosk";
    } elseif (preg_match('/WorldWideWeb/i', $ua)) {
        $title = detect_browser_version("WorldWideWeb");
        $code = "worldwideweb";
    } elseif (preg_match('/wp-android/i', $ua)) {
        $title = detect_browser_version("wp-android");
        $code = "wordpress";
    } elseif (preg_match('/wp-blackberry/i', $ua)) {
        $title = detect_browser_version("wp-blackberry");
        $code = "wordpress";
    } elseif (preg_match('/wp-iphone/i', $ua)) {
        $title = detect_browser_version("wp-iphone");
        $code = "wordpress";
    } elseif (preg_match('/wp-nokia/i', $ua)) {
        $title = detect_browser_version("wp-nokia");
        $code = "wordpress";
    } elseif (preg_match('/wp-webos/i', $ua)) {
        $title = detect_browser_version("wp-webos");
        $code = "wordpress";
    } elseif (preg_match('/wp-windowsphone/i', $ua)) {
        $title = detect_browser_version("wp-windowsphone");
        $code = "wordpress";
    } elseif (preg_match('/Wyzo/i', $ua)) {
        $title = detect_browser_version("Wyzo");
        $code = "Wyzo";
    } elseif (preg_match('/X-Smiles/i', $ua)) {
        $title = detect_browser_version("X-Smiles");
        $code = "x-smiles";
    } elseif (preg_match('/Xiino/i', $ua)) {
        $title = detect_browser_version("Xiino");
        $code = "null";
    } elseif (preg_match('/YaBrowser/i', $ua)) {
        $title = "Yandex." . detect_browser_version("Browser");
        $code = "yandex";
    } elseif (preg_match('/zBrowser/i', $ua)) {
        $title = detect_browser_version("zBrowser");
        $code = "zbrowser";
    } elseif (preg_match('/ZipZap/i', $ua)) {
        $title = detect_browser_version("ZipZap");
        $code = "zipzap";
    }
    // Pulled out of order to help ensure better detection for above browsers
    elseif (preg_match('/ABrowse/i', $ua)) {
        $title = detect_browser_version("ABrowse");
        $code = "abrowse";
    } elseif (preg_match('/Chrome/i', $ua)) {
        $title = "Google " . detect_browser_version("Chrome");
        $code = "chrome";
    } elseif (preg_match('/Safari/i', $ua) && !preg_match('/Nokia/i', $ua)) {
        $title = "Safari";
        if (preg_match('/Version/i', $ua)) {
            $title = detect_browser_version("Safari");
        }
        if (preg_match('/Mobile Safari/i', $ua)) {
            $title = "Mobile " . $title;
        }
        $code = "safari";
    } elseif (preg_match('/Nokia/i', $ua)) {
        $title = "Nokia Web Browser";
        $code = "maemo";
    } elseif (preg_match('/Firefox/i', $ua)) {
        $title = detect_browser_version("Firefox");
        $code = "firefox";
    } elseif (preg_match('/MSIE/i', $ua)) {
        $title = "Internet Explorer" . detect_browser_version("MSIE");
        preg_match('/MSIE[\ |\/]?([.0-9a-zA-Z]+)/i', $ua, $regmatch);
        if ($regmatch[1] >= 9) {
            $code = "msie9";
        } elseif ($regmatch[1] >= 7) {
            // also ie8
            $code = "msie7";
        } elseif ($regmatch[1] >= 6) {
            $code = "msie6";
        } elseif ($regmatch[1] >= 4) {
            // also ie5
            $code = "msie4";
        } elseif ($regmatch[1] >= 3) {
            $code = "msie3";
        } elseif ($regmatch[1] >= 2) {
            $code = "msie2";
        } elseif ($regmatch[1] >= 1) {
            $code = "msie1";
        } else {
            $code = "msie";
        }
    } elseif (preg_match('/Mozilla/i', $ua)) {
        $title = "Mozilla Compatible";
        if (preg_match('/rv:([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title = "Mozilla " . $regmatch[1];
        }
        $code = "mozilla";
    } else {
        $title = "Unknown";
        $code = null;
    }
	return array('img'=>$code == null ?'':$code.'.png','title'=>$title);
}

function getos() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/iPad/i', $ua)) {
        $title = "iPad";
        if (preg_match('/CPU\ OS\ ([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " iOS " . str_replace("_", ".", $regmatch[1]);
        }
        $code = "ipad";
    } elseif (preg_match('/iPod/i', $ua)) {
        $title = "iPod";
        if (preg_match('/iPhone\ OS\ ([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " iOS " . str_replace("_", ".", $regmatch[1]);
        }
        $code = "iphone";
    } elseif (preg_match('/iPhone/i', $ua)) {
        $title = "iPhone";
        if (preg_match('/iPhone\ OS\ ([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " iOS " . str_replace("_", ".", $regmatch[1]);
        }
        $code = "iphone";
    }
    // BenQ-Siemens (Openwave)
    elseif (preg_match('/[^M]SIE/i', $ua)) {
        $title = "BenQ-Siemens";
        if (preg_match('/[^M]SIE-([.0-9a-zA-Z]+)\//i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "benq-siemens";
    }
    // BlackBerry
    elseif (preg_match('/BlackBerry/i', $ua)) {
        $title = "BlackBerry";
        if (preg_match('/blackberry([.0-9a-zA-Z]+)\//i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "blackberry";
    }
    // Dell
    elseif (preg_match('/Dell Mini 5/i', $ua)) {
        $title = "Dell Mini 5";
        $code = "dell";
    } elseif (preg_match('/Dell Streak/i', $ua)) {
        $title = "Dell Streak";
        $code = "dell";
    } elseif (preg_match('/Dell/i', $ua)) {
        $title = "Dell";
        $code = "dell";
    }
    // Google
    elseif (preg_match('/Nexus One/i', $ua)) {
        $title = "Nexus One";
        $code = "google-nexusone";
    }
    // HTC
    elseif (preg_match('/Desire/i', $ua)) {
        $title = "HTC Desire";
        $code = "htc";
    } elseif (preg_match('/Rhodium/i', $ua) || preg_match('/HTC[_|\ ]Touch[_|\ ]Pro2/i', $ua) || preg_match('/WMD-50433/i', $ua)) {
        $title = "HTC Touch Pro2";
        $code = "htc";
    } elseif (preg_match('/HTC[_|\ ]Touch[_|\ ]Pro/i', $ua)) {
        $title = "HTC Touch Pro";
        $code = "htc";
    } elseif (preg_match('/HTC/i', $ua)) {
        $title = "HTC";
        if (preg_match('/HTC[\ |_|-]8500/i', $ua)) {
            $title .= " Startrek";
        } elseif (preg_match('/HTC[\ |_|-]Hero/i', $ua)) {
            $title .= " Hero";
        } elseif (preg_match('/HTC[\ |_|-]Legend/i', $ua)) {
            $title .= " Legend";
        } elseif (preg_match('/HTC[\ |_|-]Magic/i', $ua)) {
            $title .= " Magic";
        } elseif (preg_match('/HTC[\ |_|-]P3450/i', $ua)) {
            $title .= " Touch";
        } elseif (preg_match('/HTC[\ |_|-]P3650/i', $ua)) {
            $title .= " Polaris";
        } elseif (preg_match('/HTC[\ |_|-]S710/i', $ua)) {
            $title .= " S710";
        } elseif (preg_match('/HTC[\ |_|-]Tattoo/i', $ua)) {
            $title .= " Tattoo";
        } elseif (preg_match('/HTC[\ |_|-]?([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        } elseif (preg_match('/HTC([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= str_replace("_", " ", $regmatch[1]);
        }
        $code = "htc";
    }
    // Kindle
    elseif (preg_match('/Kindle/i', $ua)) {
        $title = "Kindle";
        if (preg_match('/Kindle\/([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "kindle";
    }
    // LG
    elseif (preg_match('/LG/i', $ua)) {
        $title = "LG";
        if (preg_match('/LG[E]?[\ |-|\/]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "lg";
    }
    // Microsoft
    elseif (preg_match('/Windows Phone OS 7.0/i', $ua) || preg_match('/ZuneWP7/i', $ua) || preg_match('/WP7/i', $ua)) {
        $title .= "Windows Phone 7";
        $code = "wp7";
    }
    // Motorola
    elseif (preg_match('/\ Droid/i', $ua)) {
        $title .= "Motorola Droid";
        $code = "motorola";
    } elseif (preg_match('/XT720/i', $ua)) {
        $title .= "Motorola Motoroi (XT720)";
        $code = "motorola";
    } elseif (preg_match('/MOT-/i', $ua) || preg_match('/MIB/i', $ua)) {
        $title = "Motorola";
        if (preg_match('/MOTO([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        if (preg_match('/MOT-([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "motorola";
    } elseif (preg_match('/XOOM/i', $ua)) {
        $title .= "Motorola Xoom";
        $code = "motorola";
    }
    // Nintendo
    elseif (preg_match('/Nintendo/i', $ua)) {
        $title = "Nintendo";
        if (preg_match('/Nintendo DSi/i', $ua)) {
            $title .= " DSi";
            $code = "nintendodsi";
        } elseif (preg_match('/Nintendo DS/i', $ua)) {
            $title .= " DS";
            $code = "nintendods";
        } elseif (preg_match('/Nintendo Wii/i', $ua)) {
            $title .= " Wii";
            $code = "nintendowii";
        } else {
            $code = "nintendo";
        }
    }
    // Nokia
    elseif (preg_match('/Nokia/i', $ua) && !preg_match('/S(eries)?60/i', $ua)) {
        $title = "Nokia";
        if (preg_match('/Nokia(E|N)?([0-9]+)/i', $ua, $regmatch))
            $title .= " " . $regmatch[1] . $regmatch[2];
        $code = "nokia";
    } elseif (preg_match('/S(eries)?60/i', $ua)) {
        $title = "Nokia Series60";
        $code = "nokia";
    }
    // OLPC (One Laptop Per Child)
    elseif (preg_match('/OLPC/i', $ua)) {
        $title = "OLPC (XO)";
        $code = "olpc";
    }
    // Palm
    elseif (preg_match('/\ Pixi\//i', $ua)) {
        $title = "Palm Pixi";
        $code = "palm";
    } elseif (preg_match('/\ Pre\//i', $ua)) {
        $title = "Palm Pre";
        $code = "palm";
    } elseif (preg_match('/Palm/i', $ua)) {
        $title = "Palm";
        $code = "palm";
    } elseif (preg_match('/wp-webos/i', $ua)) {
        $title = "Palm";
        $code = "palm";
    }
    // Playstation
    elseif (preg_match('/PlayStation/i', $ua)) {
        $title = "PlayStation";
        if (preg_match('/[PS|PlayStation\ ]3/i', $ua)) {
            $title .= " 3";
        } elseif (preg_match('/[PlayStation Portable|PSP]/i', $ua)) {
            $title .= " Portable";
        } elseif (preg_match('/[PlayStation Vita|PSVita]/i', $ua)) {
            $title .= " Vita";
        } else {
        }
        $code = "playstation";
    }
    // Samsung
    elseif (preg_match('/Galaxy Nexus/i', $ua)) {
        $title = "Galaxy Nexus";
        $code = "samsung";
    } elseif (preg_match('/SmartTV/i', $ua)) {
        $title = "Samsung Smart TV";
        $code = "samsung";
    } elseif (preg_match('/Samsung/i', $ua)) {
        $title = "Samsung";
        if (preg_match('/Samsung-([.\-0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "samsung";
    }
    // Sony Ericsson
    elseif (preg_match('/SonyEricsson/i', $ua)) {
        $title = "SonyEricsson";
        if (preg_match('/SonyEricsson([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            if (strtolower($regmatch[1]) == strtolower("U20i")) {
                $title .= " Xperia X10 Mini Pro";
            } else {
                $title .= " " . $regmatch[1];
            }
        }
        $code = "sonyericsson";
    }
    // Windows Phone
    elseif (preg_match('/wp-windowsphone/i', $ua)) {
        $title = "Windows Phone";
        $code = "windowsphone";
    } elseif (preg_match('/AmigaOS/i', $ua)) {
        $title = "AmigaOS";
        if (preg_match('/AmigaOS\ ([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "amigaos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Android/i', $ua)) {
        $title = "Android";
        $code = "android";
        if (preg_match('/Android[\ |\/]?([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version = $regmatch[1];
            $title .= " " . $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/[^A-Za-z]Arch/i', $ua)) {
        $title = "Arch Linux";
        $code = "archlinux";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/BeOS/i', $ua)) {
        $title = "BeOS";
        $code = "beos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/CentOS/i', $ua)) {
        $title = "CentOS";
        if (preg_match('/.el([.0-9a-zA-Z]+).centos/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "centos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Chakra/i', $ua)) {
        $title = "Chakra Linux";
        $code = "chakra";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/CrOS/i', $ua)) {
        $title = "Google Chrome OS";
        $code = "chromeos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Crunchbang/i', $ua)) {
        $title = "Crunchbang";
        $code = "crunchbang";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Debian/i', $ua)) {
        $title = "Debian GNU/Linux";
        $code = "debian";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/DragonFly/i', $ua)) {
        $title = "DragonFly BSD";
        $code = "dragonflybsd";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Edubuntu/i', $ua)) {
        $title = "Edubuntu";
        if (preg_match('/Edubuntu[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if ($regmatch[1] < 10) {
            $code = "edubuntu-1";
        } else {
            $code = "edubuntu-2";
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Fedora/i', $ua)) {
        $title = "Fedora";
        if (preg_match('/.fc([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "fedora";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Foresight\ Linux/i', $ua)) {
        $title = "Foresight Linux";
        if (preg_match('/Foresight\ Linux\/([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "foresight";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/FreeBSD/i', $ua)) {
        $title = "FreeBSD";
        $code = "freebsd";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Gentoo/i', $ua)) {
        $title = "Gentoo";
        $code = "gentoo";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Inferno/i', $ua)) {
        $title = "Inferno";
        $code = "inferno";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/IRIX/i', $ua)) {
        $title = "IRIX Linux";
        if (preg_match('/IRIX(64)?\ ([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            if ($regmatch[1]) {
                $title .= " x" . $regmatch[1];
            }
            if ($regmatch[2]) {
                $title .= " " . $regmatch[2];
            }
        }
        $code = "irix";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Kanotix/i', $ua)) {
        $title = "Kanotix";
        $code = "kanotix";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Knoppix/i', $ua)) {
        $title = "Knoppix";
        $code = "knoppix";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Kubuntu/i', $ua)) {
        $title = "Kubuntu";
        if (preg_match('/Kubuntu[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if ($regmatch[1] < 10) {
            $code = "kubuntu-1";
        } else {
            $code = "kubuntu-2";
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/LindowsOS/i', $ua)) {
        $title = "LindowsOS";
        $code = "lindowsos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Linspire/i', $ua)) {
        $title = "Linspire";
        $code = "lindowsos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Linux\ Mint/i', $ua)) {
        $title = "Linux Mint";
        if (preg_match('/Linux\ Mint\/([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "linuxmint";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Lubuntu/i', $ua)) {
        $title = "Lubuntu";
        if (preg_match('/Lubuntu[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if ($regmatch[1] < 10) {
            $code = "lubuntu-1";
        } else {
            $code = "lubuntu-2";
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Mac/i', $ua) || preg_match('/Darwin/i', $ua)) {
        if (preg_match('/Mac OS X/i', $ua)) {
            $title = substr($ua, strpos(strtolower($ua), strtolower("Mac OS X")));
            $title = substr($title, 0, strpos($title, ")"));
            if (strpos($title, ";")) {
                $title = substr($title, 0, strpos($title, ";"));
            }
            $title = str_replace("_", ".", $title);
            $code = "mac-3";
        } elseif (preg_match('/Mac OSX/i', $ua)) {
            $title = substr($ua, strpos(strtolower($ua), strtolower("Mac OS X")));
            $title = substr($title, 0, strpos($title, ")"));
            if (strpos($title, ";")) {
                $title = substr($title, 0, strpos($title, ";"));
            }
            $title = str_replace("_", ".", $title);
            $code = "mac-2";
        } elseif (preg_match('/Darwin/i', $ua)) {
            $title = "Mac OS Darwin";
            $code = "mac-1";
        } else {
            $title = "Macintosh";
            $code = "mac-1";
        }
    } elseif (preg_match('/Mageia/i', $ua)) {
        $title = "Mageia";
        $code = "mageia";
    } elseif (preg_match('/Mandriva/i', $ua)) {
        $title = "Mandriva";
        if (preg_match('/mdv([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "mandriva";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/moonOS/i', $ua)) {
        $title = "moonOS";
        if (preg_match('/moonOS\/([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "moonos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/MorphOS/i', $ua)) {
        $title = "MorphOS";
        $code = "morphos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/NetBSD/i', $ua)) {
        $title = "NetBSD";
        $code = "netbsd";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Nova/i', $ua)) {
        $title = "Nova";
        if (preg_match('/Nova[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        $code = "nova";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/OpenBSD/i', $ua)) {
        $title = "OpenBSD";
        $code = "openbsd";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Oracle/i', $ua)) {
        $title = "Oracle";
        if (preg_match('/.el([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " Enterprise Linux " . str_replace("_", ".", $regmatch[1]);
        } else {
            $title .= " Linux";
        }
        $code = "oracle";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Pardus/i', $ua)) {
        $title = "Pardus";
        $code = "pardus";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/PCLinuxOS/i', $ua)) {
        $title = "PCLinuxOS";
        if (preg_match('/PCLinuxOS\/[.\-0-9a-zA-Z]+pclos([.\-0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . str_replace("_", ".", $regmatch[1]);
        }
        $code = "pclinuxos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Red\ Hat/i', $ua) || preg_match('/RedHat/i', $ua)) {
        $title = "Red Hat";
        if (preg_match('/.el([._0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " Enterprise Linux " . str_replace("_", ".", $regmatch[1]);
        }
        $code = "red-hat";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Rosa/i', $ua)) {
        $title = "Rosa Linux";
        $code = "rosa";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Sabayon/i', $ua)) {
        $title = "Sabayon Linux";
        $code = "sabayon";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Slackware/i', $ua)) {
        $title = "Slackware";
        $code = "slackware";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Solaris/i', $ua)) {
        $title = "Solaris";
        $code = "solaris";
    } elseif (preg_match('/SunOS/i', $ua)) {
        $title = "Solaris";
        $code = "solaris";
    } elseif (preg_match('/Suse/i', $ua)) {
        $title = "openSUSE";
        $code = "suse";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Symb[ian]?[OS]?/i', $ua)) {
        $title = "SymbianOS";
        if (preg_match('/Symb[ian]?[OS]?\/([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $title .= " " . $regmatch[1];
        }
        $code = "symbianos";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Unix/i', $ua)) {
        $title = "Unix";
        $code = "unix";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/VectorLinux/i', $ua)) {
        $title = "VectorLinux";
        $code = "vectorlinux";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Venenux/i', $ua)) {
        $title = "Venenux GNU Linux";
        $code = "venenux";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/webOS/i', $ua)) {
        $title = "Palm webOS";
        $code = "palm";
    } elseif (preg_match('/Windows/i', $ua) || preg_match('/WinNT/i', $ua) || preg_match('/Win32/i', $ua)) {
        if (preg_match('/Windows NT 6.2; Win64; x64;/i', $ua) || preg_match('/Windows NT 6.2; WOW64/i', $ua)) {
            $title = "Windows 8 x64";
            $code = "win-5";
        } elseif (preg_match('/Windows NT 6.2/i', $ua)) {
            $title = "Windows 8";
            $code = "win-5";
        } elseif (preg_match('/Windows NT 6.1; Win64; x64;/i', $ua) || preg_match('/Windows NT 6.1; WOW64/i', $ua)) {
            $title = "Windows 7 x64";
            $code = "win-4";
        } elseif (preg_match('/Windows NT 6.1/i', $ua)) {
            $title = "Windows 7";
            $code = "win-4";
        } elseif (preg_match('/Windows NT 6.0/i', $ua)) {
            $title = "Windows Vista";
            $code = "win-3";
        } elseif (preg_match('/Windows NT 5.2 x64/i', $ua)) {
            $title = "Windows XP x64";
            $code = "win-2";
        } elseif (preg_match('/Windows NT 5.2; Win64; x64;/i', $ua)) {
            $title = "Windows Server 2003 x64";
            $code = "win-2";
        } elseif (preg_match('/Windows NT 5.2/i', $ua)) {
            $title = "Windows Server 2003";
            $code = "win-2";
        } elseif (preg_match('/Windows NT 5.1/i', $ua) || preg_match('/Windows XP/i', $ua)) {
            $title = "Windows XP";
            $code = "win-2";
        } elseif (preg_match('/Windows NT 5.01/i', $ua)) {
            $title = "Windows 2000 SP1";
            $code = "win-1";
        } elseif (preg_match('/Windows NT 5.0/i', $ua) || preg_match('/Windows 2000/i', $ua)) {
            $title = "Windows 2000";
            $code = "win-1";
        } elseif (preg_match('/Windows NT 4.0/i', $ua) || preg_match('/WinNT4.0/i', $ua)) {
            $title = "Microsoft Windows NT 4.0";
            $code = "win-1";
        } elseif (preg_match('/Windows NT 3.51/i', $ua) || preg_match('/WinNT3.51/i', $ua)) {
            $title = "Microsoft Windows NT 3.11";
            $code = "win-1";
        } elseif (preg_match('/Windows 3.11/i', $ua) || preg_match('/Win3.11/i', $ua) || preg_match('/Win16/i', $ua)) {
            $title = "Microsoft Windows 3.11";
            $code = "win-1";
        } elseif (preg_match('/Windows 3.1/i', $ua)) {
            $title = "Microsoft Windows 3.1";
            $code = "win-1";
        } elseif (preg_match('/Windows 98; Win 9x 4.90/i', $ua) || preg_match('/Win 9x 4.90/i', $ua) || preg_match('/Windows ME/i', $ua)) {
            $title = "Windows Me";
            $code = "win-1";
        } elseif (preg_match('/Win98/i', $ua)) {
            $title = "Windows 98 SE";
            $code = "win-1";
        } elseif (preg_match('/Windows 98/i', $ua) || preg_match('/Windows\ 4.10/i', $ua)) {
            $title = "Windows 98";
            $code = "win-1";
        } elseif (preg_match('/Windows 95/i', $ua) || preg_match('/Win95/i', $ua)) {
            $title = "Windows 95";
            $code = "win-1";
        } elseif (preg_match('/Windows CE/i', $ua)) {
            $title = "Windows CE";
            $code = "win-2";
        } elseif (preg_match('/WM5/i', $ua)) {
            $title = "Windows Mobile 5";
            $code = "win-phone";
        } elseif (preg_match('/WindowsMobile/i', $ua)) {
            $title = "Windows Mobile";
            $code = "win-phone";
        } else {
            $title = "Windows";
            $code = "win-2";
        }
    } elseif (preg_match('/Xandros/i', $ua)) {
        $title = "Xandros";
        $code = "xandros";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Xubuntu/i', $ua)) {
        $title = "Xubuntu";
        if (preg_match('/Xubuntu[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if ($regmatch[1] < 10) {
            $code = "xubuntu-1";
        } else {
            $code = "xubuntu-2";
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Zenwalk/i', $ua)) {
        $title = "Zenwalk GNU Linux";
        $code = "zenwalk";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    }
    // Pulled out of order to help ensure better detection for above platforms
    elseif (preg_match('/Ubuntu/i', $ua)) {
        $title = "Ubuntu";
        if (preg_match('/Ubuntu[\/|\ ]([.0-9a-zA-Z]+)/i', $ua, $regmatch)) {
            $version .= " " . $regmatch[1];
        }
        if ($regmatch[1] < 10) {
            $code = "ubuntu-1";
        } else {
            $code = "ubuntu-2";
        }
        if (strlen($version) > 1) {
            $title .= $version;
        }
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/Linux/i', $ua)) {
        $title = "GNU/Linux";
        $code = "linux";
        if (preg_match('/x86_64/i', $ua)) {
            $title .= " x64";
        }
    } elseif (preg_match('/J2ME\/MIDP/i', $ua)) {
        $title = "J2ME/MIDP Device";
        $code = "java";
    } else {
        $code = null;
        $title = "Unknown";
    }
   return array('img'=>$code == null ?'':$code.'.png','title'=>$title);
}

?>
