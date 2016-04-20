<?php


function quan_convert($str)
{
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

    $str_map = str_replace($quan_map, $normal_map, $str);
    return $str_map;
}

function get_variable($key, $type = 'G', $default_value = '')
{
    $type = strtoupper($type);
    $count = strlen($type);
    $has_value = false;


    for ($i = 0, $j = $count; $i < $j; $i++) {

        $t = $type[$i];

        switch ($t) {
            case 'G':
            default:
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
            case 'S':
                $var = &$_SESSION;
                break;
            case 'F':
                $var = &$_FILES;
                break;
        }

        if (isset($var[$key])) {
            $has_value = true;
            break;
        }
    }
    if (!$has_value) return $default_value;
    return $var[$key];
}
