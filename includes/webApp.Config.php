<?php
//error_reporting(0);
class configurations {
    private $lng="ES";
    private $ecd="UTF-8";
    private $hdr="Content-Type: text/html; charset=UTF-8";
    private $bsref="http://dev.blog.com";
    private $ccsf="cssFiles/default.css";
    private $icf="images/icon.png";

    public static function Language(){
        return "ES";
    }
    public static function Encoding(){
        return "UTF-8";
    }
    public static function IndexTitle(){
        return "Blog";
    }
    public static function PagesTitlePref(){
        return "Blog";
    }
    public static function KeyWords(){
        return "Blog";
    }
    public static function PageDescription(){
        return "Blog";
    }
    public static function HeaderParameter(){
        return "Content-Type: text/html; charset=UTF-8";
    }
    public static function BaseRef(){
        return "http://ping.jesusmontes.mx/";
    }
    public static function CssFilePath(){
        return "css/default.css";
    }
    public static function IconFilePath(){
        return "fav_16x16.png";
    }
    public  static function DocType(){
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
    }
}
?>