<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/9
 * Time: 17:24
 * 通用类库帮助类
 */
class Help
{
    //获取本周的开始与结束日期
    public static function GetWeeks()
    {
        $nowDate   = getdate();
        $dq_date   = mktime(0,0,0,date('m'),date('d'),date('Y'));//得到今天第一秒
        $nowWeek   = $nowDate['wday']; //今天周几

        $bz_time_a   = $dq_date - ( ($nowWeek-1) * 86400 );  //本周第一秒
        $bz_time_b   = $bz_time_a + 86400 * 7 - 1;
        return  date("Y-m-d",$bz_time_a)."--".date("Y-m-d",$bz_time_b);
    }

    //获取当前月的上一个月或者下一个月
    public static function GetMonth($sign)
    {
        //得到系统的年月
        $tmp_date=date("Ym");
        //切割出年份
        $tmp_year=substr($tmp_date,0,4);
        //切割出月份
        $tmp_mon =substr($tmp_date,4,2);
        //$tmp_nextmonth=mktime(0,0,0,$tmp_mon+1,1,$tmp_year);
        $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-$sign,1,$tmp_year);

        //得到当前月的上一个月
        return $fm_forward_month=date("Ym",$tmp_forwardmonth);
    }

    /*
     * 过滤
     */
    public static function removeXSS($str)
    {
        $str = str_replace('<!--  -->', '', $str);
        $str = preg_replace('~/\*[ ]+\*/~i', '', $str);
        $str = preg_replace('/\\\0{0,4}4[0-9a-f]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}5[0-9a]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}6[0-9a-f]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}7[0-9a]/is', '', $str);
        $str = preg_replace('/&#x0{0,8}[0-9a-f]{2};/is', '', $str);
        $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);
        $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);

        $str = htmlspecialchars($str);
        //$str = preg_replace('/&lt;/i', '<', $str);
        //$str = preg_replace('/&gt;/i', '>', $str);


        // 非成对标签
        $lone_tags = array("img", "param","br","hr");
        foreach($lone_tags as $key=>$val)
        {
            $val = preg_quote($val);
            $str = preg_replace('/&lt;'.$val.'(.*)(\/?)&gt;/isU','<'.$val."\\1\\2>", $str);
            $str = transCase($str);
            $str =  preg_replace_callback(
                '/<'.$val.'(.+?)>/i',
                create_function('$temp','return str_replace("&quot;","\"",$temp[0]);'),
                $str
            );
        }
        $str = preg_replace('/&amp;/i', '&', $str);

        // 成对标签
        $double_tags = array("table", "tr", "td", "font", "a", "object", "embed", "p", "strong", "em", "u", "ol", "ul", "li", "div","tbody","span","blockquote","pre","b","font");
        foreach($double_tags as $key=>$val)
        {
            $val = preg_quote($val);
            $str = preg_replace('/&lt;'.$val.'(.*)&gt;/isU','<'.$val."\\1>", $str);
            $str = transCase($str);
            $str =  preg_replace_callback(
                '/<'.$val.'(.+?)>/i',
                create_function('$temp','return str_replace("&quot;","\"",$temp[0]);'),
                $str
            );
            $str = preg_replace('/&lt;\/'.$val.'&gt;/is','</'.$val.">", $str);
        }
        // 清理js
        $tags = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'behaviour', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base','font');

        foreach($tags as $tag)
        {
            $tag = preg_quote($tag);
            $str = preg_replace('/'.$tag.'\(.*\)/isU', '\\1', $str);
            $str = preg_replace('/'.$tag.'\s*:/isU', $tag.'\:', $str);
        }

        $str = preg_replace('/[\s]+on[\w]+[\s]*=/is', '', $str);

        Return $str;
    }
    /**
     * 一天中12小时时间数组
     */
    public static function DayHour()
    {
        return array(
            ' 00:00:01',
            ' 01:00:01',
            ' 02:00:01',
            ' 03:00:01',
            ' 04:00:01',
            ' 05:00:01',
            ' 06:00:01',
            ' 07:00:01',
            ' 08:00:01',
            ' 09:00:01',
            ' 10:00:01',
            ' 11:00:01',
            ' 12:00:01',
            ' 13:00:01',
            ' 14:00:01',
            ' 15:00:01',
            ' 16:00:01',
            ' 17:00:01',
            ' 18:00:01',
            ' 19:00:01',
            ' 20:00:01',
            ' 21:00:01',
            ' 22:00:01',
            ' 23:00:01'
        );
    }
    /**
     * [DayHourPart description]
     */
    public static function DayHourPart()
    {
        return array(
            array('00:00:01','01:00:00'),
            array('01:00:01','02:00:00'),
            array('02:00:01','03:00:00'),
            array('03:00:01','04:00:00'),
            array('04:00:01','05:00:00'),
            array('05:00:01','06:00:00'),
            array('06:00:01','07:00:00'),
            array('07:00:01','08:00:00'),
            array('08:00:01','09:00:00'),
            array('09:00:01','10:00:00'),
            array('10:00:01','11:00:00'),
            array('11:00:01','12:00:00'),
            array('12:00:01','13:00:00'),
            array('13:00:01','14:00:00'),
            array('14:00:01','15:00:00'),
            array('15:00:01','16:00:00'),
            array('16:00:01','17:00:00'),
            array('17:00:01','18:00:00'),
            array('18:00:01','19:00:00'),
            array('19:00:01','20:00:00'),
            array('20:00:01','21:00:00'),
            array('21:00:01','22:00:00'),
            array('22:00:01','23:00:00'),
            array('23:00:01','23:59:59')
        );
    }
    /**
     * 数字添加前导零
     * @param [type] $num [description]
     */
    public static function NumTransform($num){
        if(strlen($num) == 2 && $num < 10){
            $num = substr($num,1);
        }
        return $num;
    }
    /**
     * [cutstr 汉字切割]
     * @param  [string] $string [需要切割的字符串]
     * @param  [string] $length [显示的长度]
     * @param  string $dot    [切割后面显示的字符]
     * @return [string]         [切割后的字符串]
     */
    public static function cutstr($string, $length, $dot = '...') {
        if(strlen($string) <= $length) {
            return $string;
        }
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
        $strcut = '';
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t < 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        return $strcut.$dot;
    }

    /**
     * [getPassedHours 某时间戳到现在所经过的时间]
     * @param  [int] $distence [时间戳]
     * @return [string]           [秒/分钟/小时]
     */
    public static function getPassedHours($distence){
        $passed="";
        switch($distence){
            case ($distence < 60 ):{
                $passed=$distence."秒";
                break;
            }
            case ($distence > 60  && $distence < 60 * 60):{
                $passed=intval($distence/60)."分钟";
                break;
            }
            case ($distence > 60 * 60):{
                $passed= sprintf("%.1f", $distence/(60*60)) ."小时";
                break;
            }
        }

        return $passed;
    }

    /**
     * loadClass 类对象生成器，自动载入类定义文件，实例化并返回对象句柄
     * @param <type> $sClass 类名称
     * @param <type> $aParam 类初始化时使用的参数，数组形式
     * @param <type> $bForceInst 是否强制重新实例化对象
     * @return sClass
     */
    public static function loadClass ($sClass, $aParam = "", $bForceInst = FALSE) {
        if (empty ($aParam)) {
            $object = new $sClass();
        } else {
            $object = new $sClass($aParam);
        }
        return $object;
    }

    /**
     * 清除危险信息
     *
     * @param mixed $info
     * @return mixed
     */
    public static function escapeInfo($info) {
        if (is_array($info)) {
            foreach ($info as $key => $value) {
                $info[$key] = escapeInfo($value);
            }
        } else {
            return htmlspecialcharsUni($info);
        }
        return $info;
    }

    /**
     * 针对Unicode不安全改进的安全版htmlspecialchars()
     *
     * @param	string	Text to be made html-safe
     *
     * @return	string
     */
    public static function htmlspecialcharsUni($text, $entities = true)
    {
        return str_replace(
        // replace special html characters
            array('<', '>', '"','\''),
            array('&lt;', '&gt;', '&quot;','&apos;'),
            preg_replace(
            // translates all non-unicode entities
                '/&(?!' . ($entities ? '#[0-9]+|shy' : '(#[0-9]+|[a-z]+)') . ';)/si',
                '&amp;',
                $text
            )
        );
    }

    /**
     * 高级搜索代码
     *
     * @param array $keyword 关键字数组
     * @param string $con 关系，and 或 or
     * @param string $method 模糊或者精确搜索
     * @param array $field 要搜索的字段数组
     * @return string
     */
    public static function searchString($keyword, $con, $method, $field) {
        $tmp = null;
        $method = strtoupper($method);

        // 搜索中对 "_" 的过滤
        $keyword = str_replace("_", "\\_", trim($keyword));
        $keyword = split("[ \t\r\n,]+", $keyword);

        /*
        foreach ($field as $k => $v) {

        }
        */

        $num = count($field);
        if ($con == "OR") {
            $con = "OR";
        } else {
            $con = "AND";
        }

        // 模糊查找
        if ($method == "LIKE") {
            for($i = 0; $i < $num; $i++) {
                $i < $num - 1 ? $condition = "OR" : $condition = null;
                $tmp .= " {$field[$i]} $method '%" . join("%' $con {$field[$i]} $method '%", $keyword) . "%' $condition";
            }
        } else { // 精确查找
            for($i = 0; $i < $num; $i++) {
                $i < $num - 1 ? $condition = $con : $condition = null;
                $tmp .= " INSTR({$field[$i]}, \"" . join("\") != 0 $con INSTR({$field[$i]}, \"", $keyword) . "\") != 0 $condition";
            }
        }
        return "(".$tmp.")";
    }

    /**
     * 增加了全角转半角的trim
     *
     * @param	string  $str    原字符串
     * @return  string  $str    转换后的字符串
     */
    public static function wtrim($str) {
        return trim ( sbc2abc ( $str ) );
    }

    /**
     * 全角转半角
     *
     * @param	string  $str    原字符串
     * @return  string  $str    转换后的字符串
     */
    public static function sbc2abc($str) {
        $f = array ('　', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', '．', '－', '＿', '＠' );
        $t = array (' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '.', '-', '_', '@' );
        $str = str_replace ( $f, $t, $str );
        return $str;
    }

}