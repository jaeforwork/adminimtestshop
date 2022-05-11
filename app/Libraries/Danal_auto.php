<?php
namespace App\Libraries;

//다날 자동결제관련

class Danal_auto{
    /******************************************************
     *
     * Client Module 경로설정
     *
     ******************************************************/
    private $TeleditBinPath = "";
    
    
    
    public function __construct(){
        if( isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"])) {
            $this->TeleditBinPath="D:\\danal\\bin";
        }else{
            $this->TeleditBinPath=FCPATH."../danal_bin";
        }
    }
    
    
    function CallTeledit($TransR,$Debug=false) {
        
        
        
        $Bin = "SClient";
        $arg = $this->MakeParam( $TransR );
        
        $Input = $this->TeleditBinPath."/".$Bin." \"$arg\"";
        
        exec( $Input,$Output,$Ret );
        
        if( $Debug )
        {
            echo "Exec : ".trim($Input)."<BR>";
            echo "Ret : ".$Ret."<BR>";
            
            for( $i=0;$i<count($Output);$i++ )
            {
                echo( "Out Line[$i]: ".trim($Output[$i])."<BR>" );
            }
        }
        
        $MapOutput = $this->Parsor( $Output );
        
        return $MapOutput;
    }
    
    function CallTeleditCancel($TransR,$Debug=false) {
        
        
        $Bin = "BackDemo";
        //      $Bin = "AutoCancel"; // For Window
        
        $arg = $this->MakeParam( $TransR );
        
        $Input = $this->TeleditBinPath."/".$Bin." \"$arg\"";
        
        exec( $Input,$Output,$Ret );
        
        if( $Debug )
        {
            echo "Exec : ".trim($Input)."<BR>";
            echo "Ret : ".$Ret."<BR>";
            
            for( $i=0;$i<count($Output);$i++ )
            {
                echo( "Out Line[$i]: ".trim($Output[$i])."<BR>" );
            }
        }
        
        $MapOutput = $this->Parsor( $Output );
        
        return $MapOutput;
    }
    
    function Parsor($str,$sep1="&",$sep2="=") {
        
        $Out = array();
        $in = "";
        
        if( is_array($str) )
        {
            for( $i=0;$i<count($str);$i++ )
            {
                $in .= $str[$i].$sep1;
            }
        }
        else
        {
            $in = $str;
        }
        
        $tok = explode( $sep1,$in );
        
        for( $i=0;$i<count($tok);$i++ )
        {
            $tmp = explode( $sep2,$tok[$i] );
            if(count($tmp)==2){
                $name = trim($tmp[0]);
                $value = trim($tmp[1]);
            }else{
                $name = trim($tmp[0]);
                $value = "";
            }
            
            for( $j=2;$j<count($tmp);$j++ )
                $value .= $sep2.trim($tmp[$j]);
                
                $Out[$name] = urldecode($value);
        }
        
        return $Out;
    }
    
    function MakeFormInput($arr,$ext=array(),$Prefix="") {
        
        $PreLen = strlen( trim($Prefix) );
        
        $keys = array_keys($arr);
        
        for( $i=0;$i<count($keys);$i++ )
        {
            $key = $keys[$i];
            
            if( trim($key) == "" ) continue;
            
            if( !in_array($key,$ext) && substr($key,0,$PreLen) == $Prefix )
            {
                echo( "<input type=\"hidden\" name=\"".$key."\" value=\"".$arr[$key]."\">\n" );
            }
        }
    }
    
    function MakeAddtionalInput($Trans,$HTTPVAR,$Names) {
        
        while( $name=array_pop($Names) )
        {
            $Trans[$name] = $HTTPVAR[$name];
        }
        
        return $Trans;
    }
    
    function MakeItemInfo($ItemAmt,$ItemCode,$ItemName) {
        
        $ItemInfo = substr($ItemCode,0,1) ."|". $ItemAmt ."|1|". $ItemCode ."|". $ItemName;
        return $ItemInfo;
    }
    
    function MakeParam($arr) {
        
        $ret = array();
        $keys = array_keys($arr);
        
        for( $i=0;$i<count($keys);$i++ )
        {
            $key = $keys[$i];
            array_push( $ret,$key."=".$arr[$key] );
        }
        
        return $this->MakeInfo($ret);
    }
    
    function MakeInfo($Arr,$joins=";") {
        
        return join( $joins,$Arr );
    }
    
    function GetItemName($CPName,$nCPName,$ItemName,$nItemName) {
        
        $convItemName = "(". substr($CPName,0,$nCPName) .") ". substr($ItemName,0,$nItemName);
        
        return $convItemName;
    }
    
    function GetCIURL($IsUseCI,$CIURL) {
        
        /*
         * Default Danal CI
         */
        $URL = "https://ui.teledit.com/Danal/Teledit/Web/images/customer_logo.gif";
        
        if( $IsUseCI == "Y" && !is_null($CIURL) )
        {
            $URL = $CIURL;
        }
        
        return $URL;
    }
    
    function Map2Str($arr) {
        
        $ret = array();
        $keys = array_keys($arr);
        
        for( $i=0;$i<count($keys);$i++ )
        {
            $key = $keys[$i];
            
            if( !trim($key) ) continue;
            
            array_push( $ret,$key." = ".$arr[$key] );
        }
        
        return join( "<BR>",$ret );
    }
    
    function GetBgColor($BgColor) {
        
        /*
         * Default : Blue
         */
        $Color = 0;
        
        if( intval($BgColor) > 0 && intval($BgColor) < 11 )
        {
            $Color = $BgColor;
        }
        
        return sprintf( "%02d",$Color );
    }
}