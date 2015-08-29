<?php 
namespace thom855j\filesystem; 

/* 
 * To change this license header, choose License Headers in Project Properties. 
 * To change this template file, choose Tools | Templates 
 * and open the template in the editor. 
 */ 

class File 
{ 

    public static 
            function inc( $path ) 
    { 
        include_once $path . '.php' ; 
    } 

    public static 
            function req( $path ) 
    { 
        require_once $path . '.php' ; 
    } 

} 
