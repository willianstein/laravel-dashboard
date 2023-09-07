<?php

/**
 * ###################
 * ###   LARAVEL   ###
 * ###################
 */

if (!function_exists('user')) {
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user() {
        return \Illuminate\Support\Facades\Auth::user();
    }
}

if (!function_exists('nav_active')) {
    /**
     * @param string $href
     * @param string|null $class
     * @param string|null $param
     * @return string|null
     */
    function nav_active(string $href, ?string $class = 'active', ?string $param = null): ?string {
        return $class = (strpos(\Illuminate\Support\Facades\Route::currentRouteName(), $href) === 0 ? $class : $param);
    }
}

if (!function_exists('storage')){
    /**
     * BUSCA UM ARQUIVO DA FACADE STORAGE
     * @param string $file
     * @param string $disk
     * @return string
     */
    function storage(string $file, string $disk = 'local') {
        return Illuminate\Support\Facades\Storage::disk($disk)->url($file);
    }
}

/* ***************************
 *          SELECT           *
 * ************************ */

/**
 * @param string $name
 * @param array|null $data
 * @param string|null $selected
 * @param array|null $params
 * @param string|null $defaultOption
 * @return string
 */
function form_select(string $name, array $data=null, string $selected=null, array $params=null, string $defaultOption=null): string {
    //Params
    $strParams = null;
    if(!empty($params)){
        foreach ($params as $param => $value){
            $strParams .= "{$param}=\"$value\" ";
        }
    }
    //Options
    $strOptions = null;
    if(!empty($defaultOption)){
        $data = array_merge(['0'=>['id'=>'','field'=>$defaultOption]],($data??[]));
    }
    //Flat or Multidimensional
    if(!empty($data)){
        if (count($data) == count($data, COUNT_RECURSIVE)) {
            foreach ($data as $value => $label){
                $strSelected = ($value == $selected ? "selected" : "");
                $strOptions .= "    <option value=\"{$value}\" {$strSelected}>{$label}</option>\n";
            }
        } else {
            foreach ($data as $item){
                $strSelected = (reset($item) == $selected ? "selected" : "");
                $strOptions .= "    <option value=\"".reset($item)."\" {$strSelected}>".end($item)."</option>\n";
            }
        }
    }
    //OutPut
    $html  = "<select name=\"{$name}\" ".trim($strParams).">\n";
    $html .= $strOptions;
    $html .= "</select>";
    return $html;
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */
if (!function_exists('date_fmt')) {
    /**
     * @param string|null $date
     * @param string $format
     * @return string|null
     * @throws Exception
     */
    function date_fmt(?string $date, string $format = 'd/m/Y H:i:s'): ?string {
        if(empty($date)){
            return null;
        }
        return (new \DateTime($date))->format($format);
    }
}


/**
 * ##################
 * ###   STRING   ###
 * ##################
 */
if (!function_exists('str_limit_words')) {
    /**
     * @param string|null $string
     * @param int $limit
     * @param string $pointer
     * @return string|null
     */
    function str_limit_words(?string $string, int $limit, string $pointer = '...'): ?string {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
        $arrWords = explode(' ', $string);
        $numWords = count($arrWords);

        if ($numWords < $limit) {
            return $string;
        }

        $words = implode(' ', array_slice($arrWords, 0, $limit));
        return "{$words}{$pointer}";
    }
}

if (!function_exists('str_limit_chars')) {
    /**
     * @param string|null $string
     * @param int $limit
     * @param string $pointer
     * @return string
     */
    function str_limit_chars(?string $string, int $limit, string $pointer = '...'): string {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
        if (mb_strlen($string) <= $limit) {
            return $string;
        }

        $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), ' '));
        return "{$chars}{$pointer}";
    }
}

if (!function_exists('str_price')) {
    /**
     * @param string|null $price
     * @return string
     */
    function str_price(?string $price): string {
        return number_format((!empty($price) ? $price : 0), 2, ',', '.');
    }
}

if (!function_exists('money')){
    /**
     * TRABALHA COM MOEDA
     * @param string|null $value
     * @param string $format
     * @param int $decimals
     * @return string|null
     */
    function money(string $value = null, string $format="BRL", int $decimals=2):?string{

        /* check value */
        if(empty( $value)){ return $value;}

        /*BRL*/
        if($format == "BRL"){
            return number_format($value,$decimals,',','.');
        }

        /* DB */
        if($format == "DB"){
            $value = floatval(str_replace(',','.',str_replace(".", "", $value)));
            if(empty($value)){
                $value = (float) str_replace(['.',','], ['','.'], $value);
            }
            return number_format($value,$decimals,'.','');
        }

        return $value;
    }

}

if (!function_exists('toFloat')){
    /**
     * TRANSFORNA QUALQUER NUMERO EM FLOAT
     * @param string|null $value
     * @param int|null $decimals
     * @return float
     */
    function toFloat(string $value = null, int $decimals=null): float{
        /* Empty */
        if(empty($value)){ return 0.00; }

        /* Vars */
        $dotPos = strripos($value,'.');
        $commaPos = strripos($value,',');

        /* Origin BRL */
        if($dotPos && $commaPos && $dotPos < $commaPos){
            $value = str_replace(['.',','], ['','.'], $value);
            $value = ($decimals ? number_format($value,$decimals,'.',''):$value);
        }

        /* Origin EN */
        if($dotPos && $commaPos && $dotPos > $commaPos){
            $value = str_replace([','], [''], $value);
            $value = ($decimals ? number_format($value,$decimals,'.',''):$value);
        }

        /* Default */
        $value = str_replace([','], ['.'], $value);
        $value = ($decimals ? number_format($value,$decimals,'.',''):$value);

        return (is_numeric($value)?$value:0.00);

    }

}

if (!function_exists('toIntOrFloat')){
    /**
     * VERIFICA SE É UM INT SENÃO RETORNA FLOAT
     * @param string|null $value
     * @param int|null $decimals
     * @return float
     */
    function toIntOrFloat(string $value = null, int $decimals=null){
        /* Empty */
        if(empty($value)){ return 0; }

        /* Vars */
        $dotPos = strripos($value,'.');
        $commaPos = strripos($value,',');

        /* Is Numeric */
        if(!$dotPos && !$commaPos && is_numeric($value)){
            return (int) $value;
        }

        /* Is Float */
        return toFloat($value, $decimals);
    }

}

if (!function_exists('str_convert_to_double')) {
    /**
     * @param string|null $param
     * @return float|null
     */
    function str_convert_to_double(?string $param): ?float {
        if (empty($param)) {
            return null;
        }

        return (float)str_replace(',', '.', str_replace('.', null, $param));
    }
}

if (!function_exists('str_convert_to_phone')) {
    /**
     * Convert 1212345678 to 12 1234-5678 or 12912345678 to 12 9 1234-5678
     *
     * @param string|null $param
     * @return string|null
     */
    function str_convert_to_phone(?string $param): ?string {
        if (empty($param)) {
            return null;
        }

        if (strlen($param) == 10) {
            return
                '(' . substr($param, 0, 2) . ') ' .
                substr($param, 2, 4) . '-' .
                substr($param, 6, 4);
        }

        if (strlen($param) == 11) {
            return
                substr($param, 0, 2) . ' ' .
                substr($param, 2, 1) . ' ' .
                substr($param, 3, 4) . '-' .
                substr($param, 7, 4);
        }

        return $param;
    }
}

if (!function_exists('str_convert_to_document')) {
    /**
     * CPF
     * Convert 66200328005 to 662.003.280-05
     *
     * CNPJ
     * Convert 77978407000112 to 77.978.407/0001-12
     *
     * @param string|null $param
     * @return string|null
     */
    function str_convert_to_document(?string $param): ?string {
        if (empty($param)) {
            return null;
        }

        if (strlen($param) == 11) {
            return
                substr($param, 0, 3) . '.' .
                substr($param, 3, 3) . '.' .
                substr($param, 6, 3) . '-' .
                substr($param, 9, 2);
        }

        if (strlen($param) == 14) {
            return
                substr($param, 0, 2) . '.' .
                substr($param, 2, 3) . '.' .
                substr($param, 5, 3) . '/' .
                substr($param, 8, 4) . '-' .
                substr($param, 12, 2);
        }

        return $param;
    }
}

if (!function_exists('str_convert_to_zip_code')) {
    /**
     * Convert 12345678 to 12345-678
     *
     * @param string|null $param
     * @return string|null
     */
    function str_convert_to_zip_code(?string $param): ?string {
        if (empty($param)) {
            return null;
        }

        return substr($param, 0, 5) . '-' . substr($param, 5, 3);
    }
}

if (!function_exists('str_search')) {
    /**
     * @param string|null $search
     * @return string
     */
    function str_search(?string $search): string {
        if (!$search) {
            return 'all';
        }

        $search = preg_replace('/[^a-z0-9A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ\@\ ]/', null, $search);
        return (!empty($search) ? urlencode(mb_strtolower($search)) : 'all');
    }
}

if (!function_exists('clear_number')) {
    /**
     * @param string|null $number
     * @return string|null
     */
    function clear_number(?string $number): ?string {
        if (!$number) {
            return null;
        }
        return preg_replace('/[^0-9]/', null, $number);
    }
}

if (!function_exists('reading_time')) {
    /**
     * Slow Reading: 100 words per minute
     * Average Reading: 130 words per minute
     * Quick Reading: 160 words per minute
     *
     * @param string|null $text
     * @param int $wordsPerMinute
     * @param string $minute
     * @param string $second
     * @return string
     */
    function reading_time(
        ?string $text,
        int     $wordsPerMinute = 130,
        string  $minute = 'min',
        string  $second = 's'
    ): string {
        $countWords = str_word_count(strip_tags($text));

        $wordsPerSecond = $wordsPerMinute / 60;
        $totalSeconds = floor($countWords / $wordsPerSecond);

        return ($totalSeconds >= 60 ? floor($totalSeconds / 60) . $minute : $totalSeconds . $second);
    }
}

if (!function_exists('highlight_keywords')) {
    /**
     * @param string $text
     * @param string $keyword
     * @param string $delimiter
     * @return string
     */
    function highlight_keywords(string $text, string $keyword, string $delimiter = '+'): string {
        $words = explode($delimiter, $keyword);

        for ($i = 0; $i < count($words); $i++) {

            if (strlen($words[$i]) > 4) {
                $highlighted = "<mark>$words[$i]</mark>";
                $text = str_ireplace($words[$i], $highlighted, $text);
            }
        }

        return $text;
    }
}

if (!function_exists('getRangeYears')) {
    function getRangeYears() {
        $data = [];
        for ($y = 2000; $y <= date('Y'); $y++) {
            $data[] = ['id' => $y, 'name' => $y];
        }
        return json_encode($data, true);
    }
}

if (!function_exists('getPercent')) {
    function getPercent(int $value, int $total) {
        return $total > 0 && $value > 0 ? round((($value * 100) / $total), 2) : 0;
    }
}

if (!function_exists('setTextPercent')) {
    function setTextPercent(float $value = 0) {
        if ($value > 80) {
            echo '<span class="text-black-50 mr-3">Excelente</span>
                    <h4 class="mb-0 text-success">' . $value . '%</h4>';
        } else if ($value >= 60 && $value <= 80) {
            echo '<span class="text-black-50 mr-3">Bom</span>
                    <h4 class="mb-0">' . $value . '%</h4>';
        } else if ($value < 60) {
            echo '<span class="text-black-50 mr-3">Ruim</span>
                    <h4 class="mb-0 text-danger">' . $value . '%</h4>';
        } else {
            echo '';
        }
    }
}

if (!function_exists('extractUrl')) {
    function extractUrl(string $value) {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $value, $match);
        return $match[0] ?? null;
    }
}

if (!function_exists('str_textarea')) {
    function str_textarea(string $text): string {
        $text = filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS);
        $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;"];
        return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
    }
}

if (!function_exists('getStatusSimulate')) {
    function getStatusSimulate(int $value) {
        $arr = [
            0 => '<span class="badge badge-pill badge-primary">Pendente</span>',
            1 => '<span class="badge badge-pill badge-warning">Em Andamento</span>',
            2 => '<span class="badge badge-pill badge-success">Concluído</span>',
        ];

        echo $arr[$value];
    }
}

if (!function_exists('getCorrectSimulateQuestion')) {
    function getCorrectSimulateQuestion(int $correct = null, int $incorrect = null) {
        if (!$correct && !$incorrect) {
            echo '<span class="badge badge-primary">Não Respondida</span>';
        } else if ($correct == 1 && $incorrect != 1) {
            echo '<span class="badge badge-success">Certa</span>';
        } else if ($correct != 1 && $incorrect == 1) {
            echo '<span class="badge badge-danger">Errada</span>';
        } else {
            echo '<span class="badge badge-warning">Não definido</span>';
        }
    }
}

if (!function_exists('getLetter')) {
    function getLetter(int $value) {
        $letters = 'abcdefghijklmnopqrstuvxyz';
        return $letters[$value];
    }
}

if (!function_exists('parseArrayString')) {
    function parseArrayString(array $data = [], $key = null) {
        $str = '';
        foreach ($data as $value) {
            if ($key) {
                $str .= $value[$key] . ', ';
            }
        }
        return $str;
    }
}
