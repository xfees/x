<?php
$top_dir = __DIR__;
if ( empty( $_SERVER[ 'DOCUMENT_ROOT' ] ) )
{
    $current_dir = dirname( __FILE__ );
    $_SERVER[ 'DOCUMENT_ROOT' ] = dirname( $current_dir ) . '/';// for live   
}
include_once($top_dir . '/../inc/constants.php');
include_once(FUNCTIONPATH);
include_once(IMAGEFUNCTIONPATH);

class Common
{
    private $db = '';
    private $allCities = array( );
    private static $allAuthors = array( );
    private static $allSource = array( );
    private $contentTypes = array( );

    function __construct()
    {
        $this->db = Database::Instance();
    }
    public static function saveImage($url, $path, $image_name)
    {
        //print "\n image_save: {$url} \n";
        $originalpath = $path . '/';
        $path = $path . '/' . $image_name;
        file_put_contents( $path, @file_get_contents( $url ) );

        list($width, $height, $type, $attr) = getimagesize( $path );
        if ( ($type == 1) || ($type == 3) )
        {
            switch ($type)
            {
                case 1:
                    $image_name = str_replace( '.jpg', '.gif', $image_name );
                    break;
                case 2:
                    $image_name = str_replace( '.jpg', '.png', $image_name );
                    break;
            }
            $filepath = $originalpath . $image_name;
            file_put_contents( $filepath, @file_get_contents( $path ) );
            unlink( $path );
            $path = $filepath;
        }

        //print "\n image_save: {$path} \n";

        return $path;
    }
    public static function createCombo($params)
    {
        $select = NULL;
        $multi = isset( $params[ 'multiple' ] ) ? ' multiple=true ' : NULL;
        $css_class = (isset( $params[ 'class' ] ) && ! empty( $params[ 'class' ] )) ? "class='{$params[ 'class' ]}' " : NULL;
        $style = (isset( $params[ 'style' ] ) && ! empty( $params[ 'style' ] )) ? "style='{$params[ 'style' ]}' " : NULL;
        $default = isset( $params[ 'default' ] ) ? $params[ 'default' ] : 0;
        $disabled = (isset( $params[ 'disabled' ] ) && 1 == $params[ 'disabled' ] ) ? 'disabled="disabled"' : NULL;
        $select = "<select {$disabled} {$multi} {$css_class} {$style} id=\"{$params[ 'id' ]}\" name=\"{$params[ 'name' ]}\" >";


        if ( 1 == $default )
        {
            $resource_type = isset( $params[ 'resource_type' ] ) ? 'Select ' . ucfirst( $params[ 'resource_type' ] ) : 'Select';
            $select .= '<option value="0">' . $resource_type . '</option>';
        }

        foreach ($params[ 'data' ] as $key => $value)
        {
            $isSelected = ( isset( $params[ 'selected' ] ) &&
              ($key == $params[ 'selected' ] || (is_array( $params[ 'selected' ] ) && in_array( $key, $params[ 'selected' ] ))) ) ? ' selected="selected"' : NULL;
            $select .= '<option ' . $isSelected . ' value="' . $key . '">' . $value . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public static function getAuthorList($type)
    {
        if ( count( self::$allAuthors ) == 0 )
        {
            $auth = new Author();
            self::$allAuthors = $auth->getAuthorsbyType( $type );
        }
        return self::$allAuthors;
    }

    public static function getAuthorCombo($id = '', $sel = '', $css = '', $onchange = '', $isList = FALSE, $isMultiple = FALSE)
    {
        $data = self::getAuthorList( '2,3' );
        $selectId = ($id == '') ? '' : ' id="' . $id . '" name="' . $id . '"';
        $cls = ($css == '') ? '' : ' ' . $css;
        $change = ($onchange == '') ? '' : ' onchange="' . $onchange . '"';
        $list = ($isList == TRUE) ? ' size=5 ' : '';
        $multiple = ($isMultiple == TRUE) ? ' multiple=true ' : '';

        $select = "<select $selectId $cls $change $list $multiple>";
        $select .= '<option value="">All Authors</option>';
        $select .= '<option value="0">Anonymous</option>';
        foreach ($data as $key => $value)
        {
            $isSelected = ($value[ 'id' ] == $sel) ? ' selected="selected"' : '';
            $select .= '<option ' . $isSelected . ' value="' . $value[ 'id' ] . '">' . ucfirst( strtolower( $value[ 'name' ] ) ) . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public static function getBylineCombo($id = '', $sel = '', $css = '', $onchange = '', $isList = FALSE, $isMultiple = FALSE)
    {
        $data = self::getAuthorList( '1,3' );
        $selectId = ($id == '') ? '' : ' id="' . $id . '" name="' . $id . '"';
        $cls = ($css == '') ? '' : ' ' . $css;
        $change = ($onchange == '') ? '' : ' onchange="' . $onchange . '"';
        $list = ($isList == TRUE) ? ' size=5 ' : '';
        $multiple = ($isMultiple == TRUE) ? ' multiple=true ' : '';

        $select = "<select $selectId $cls $change $list $multiple>";
        $select .= '<option value="">All By line</option>';
        $select .= '<option value="0">Anonymous</option>';
        foreach ($data as $key => $value)
        {
            $isSelected = ($value[ 'id' ] == $sel) ? ' selected="selected"' : '';
            $select .= '<option ' . $isSelected . ' value="' . $value[ 'id' ] . '">' . ucfirst( strtolower( $value[ 'name' ] ) ) . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public static function getCategoryCombo($id = '', $sel = '', $css = 'class="blueText"', $onchange = '', $isList = FALSE, $isMultiple = FALSE) {
        $objCategory = new Category();
        if ( $_SESSION[ 'TOPMENU' ] == "category" ) {
            $data = $objCategory->getCategoryTreeparent();
        } else {
            $data = $objCategory->getCategoryTree();
        }
        $selectId = ($id == '') ? '' : ' id="' . $id . '" name="' . $id . '"';
        $cls = ($css == '') ? '' : ' ' . $css;
        $change = ($onchange == '') ? '' : ' onchange="' . $onchange . '"';
        $list = ($isList == TRUE) ? ' size=5 ' : '';
        $multiple = ($isMultiple == TRUE) ? ' multiple=true ' : '';

        $select = "<select $selectId $cls $change $list $multiple>";

        if ( $isList == TRUE || $isMultiple == TRUE )
        {
            //will add something
        }
        else
        {
            $select .= '<option value="">Select Category</option>';
        }
        foreach ($data as $key => $value)
        {
            $isSelected = (strtolower( $value ) == strtolower( ( string ) $sel ) || $key == $sel) ? ' selected="selected"' : '';
            $select .= '<option ' . $isSelected . ' value="' . $key . '">' . $value . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    //get common combo
    public static function getCombo($array, $id = '', $sel = '', $css = 'class="blueText"', $onchange = '', $isList = FALSE, $isMultiple = FALSE)
    {
        $data = $array;
        $selectId = ($id == '') ? '' : ' id="' . $id . '" name="' . $id . '"';
        $cls = ($css == '') ? '' : ' ' . $css;
        $change = ($onchange == '') ? '' : ' onchange="' . $onchange . '"';
        $list = ($isList == TRUE) ? ' size=5 ' : '';
        $multiple = ($isMultiple == TRUE) ? ' multiple=true ' : '';

        $select = "<select $selectId $cls $change $list $multiple>";
        foreach ($data as $key => $value)
        {
            $isSelected = ($value[ 'id' ] == $sel) ? ' selected="selected"' : '';
            $label = ($ucFirst == TRUE) ? ucfirst( strtolower( $value[ 'name' ] ) ) : $value[ 'name' ];
            $select .= '<option ' . $isSelected . ' value="' . $value[ 'id' ] . '">' . $label . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public static function getGrammerChar($c = 1)
    {
        if ( $c <= 1 )
        {
            $reseult[ 'plural' ] = '';
            $reseult[ 'isText' ] = 'is';
            $reseult[ 'hasText' ] = 'has';
        }
        else
        {
            $reseult[ 'plural' ] = 's';
            $reseult[ 'isText' ] = 'are';
            $reseult[ 'hasText' ] = 'have';
        }
        return $reseult;
    }

    public static function getUserId($id = '')
    {
        $userId = ($id == '') ? $_COOKIE[ 'ID' ] : $id;
        return ($userId == '') ? 0 : decryptdata( $userId );
    }

    public static function l($str)
    {   //its lower L;  
        return (isset( $str ) == 1) ? $str : '';
    }

    public static function isSuperAdmin($rights = '')
    {
        return ($rights == "Super Admin") ? TRUE : FALSE;
    }

    public static function strStop($str = '', $limit = -1, $append = '...')
    {
        if ( strlen( $str ) <= $limit )
        {
            return $str;
        }
        $string = substr( $str, 0, $limit ) . '' . $append;
        $str = htmlspecialchars( strip_tags( $str ), ENT_QUOTES );
        //echo $str;
        return '<span title="' . $str . '">' . $string . "</span>";
    }

    public static function strStop2($str = '', $limit = -1, $append = '...')
    {
        if ( strlen( $str ) <= $limit )
        {
            return $str;
        }
        $string = substr( $str, 0, $limit ) . '' . $append;
        $str = htmlspecialchars( strip_tags( $str ), ENT_QUOTES );
        //echo $str;
        return '<span>' . $string . "</span>";
    }

    public static function filterWebsite($web = '')
    {
        if ( strpos( $web, "http://" ) > 0 )
        {
            return $web;
        }
        else
        {
            return "http://" . $web;
        }
        return $web; // :P
    }

    public static function selectedCSS($v = '')
    {
        return (trim( $v ) == "") ? "" : "current";
    }

    public static function formatDate($dt = '', $wt = FALSE)
    {
        if ( $dt == '' || $dt == '0000-00-00 00:00:00' )
            return '';
        $withTime = ($wt == TRUE) ? ' H:i:s' : '';
        $date = date( "d-F-Y$withTime", strtotime( $dt ) );
        return $date;
    }

    /************* previews *****************/
    public static function getSitePreviewLink($id = '')
    {
        return SITEPATH . "/details.php?id=$id&flag=p";
    }

    public static function author_check($author_name = '')
    {
        if ( is_array( $author_name ) )
        {
            return $author_name;
        }
        else if ( $author_name != '' )
        {
            return array( $author_name );
        }
        else
        {
            return 'Anonymous';
        }
    }
}

