<?php
namespace thom855j\filesystem;

/*
 * To change this license header, choose License Headers in Project Properties. 
 * To change this template file, choose Tools | Templates 
 * and open the template in the editor. 
 */

class Image
{

    // object instance
    protected static
            $_instance = null ;
    private
            $image ;
    private
            $type ;

    public static
            function load()
    {
        if ( !isset( self::$_instance ) )
        {
            self::$_instance = new Image() ;
        }
        return self::$_instance ;
    }

    public
            function open( $filename )
    {

        $image_info = getimagesize( $filename ) ;
        $this->type = $image_info[ 2 ] ;

        if ( $this->type == IMAGETYPE_JPEG )
        {

            $this->image = imagecreatefromjpeg( $filename ) ;
        }
        elseif ( $this->type == IMAGETYPE_GIF )
        {

            $this->image = imagecreatefromgif( $filename ) ;
        }
        elseif ( $this->type == IMAGETYPE_PNG )
        {

            $this->image = imagecreatefrompng( $filename ) ;
        }
    }

    public
            function save( $filename , $type = IMAGETYPE_JPEG ,
                           $compression = 75 , $permissions = null )
    {

        if ( $type == IMAGETYPE_JPEG )
        {
            imagejpeg( $this->image , $filename , $compression ) ;
        }
        elseif ( $type == IMAGETYPE_GIF )
        {

            imagegif( $this->image , $filename ) ;
        }
        elseif ( $type == IMAGETYPE_PNG )
        {

            imagepng( $this->image , $filename ) ;
        }
        if ( $permissions != null )
        {

            chmod( $filename , $permissions ) ;
        }
    }

    public
            function output( $type = IMAGETYPE_JPEG )
    {

        if ( $type == IMAGETYPE_JPEG )
        {
            imagejpeg( $this->image ) ;
        }
        elseif ( $type == IMAGETYPE_GIF )
        {

            imagegif( $this->image ) ;
        }
        elseif ( $type == IMAGETYPE_PNG )
        {

            imagepng( $this->image ) ;
        }
    }

    public
            function getWidth()
    {

        return imagesx( $this->image ) ;
    }

    public
            function getHeight()
    {

        return imagesy( $this->image ) ;
    }

    public
            function resizeToHeight( $height )
    {

        $ratio = $height / $this->getHeight() ;
        $width = $this->getWidth() * $ratio ;
        $this->resize( $width , $height ) ;
    }

    public
            function resizeToWidth( $width )
    {
        $ratio  = $width / $this->getWidth() ;
        $height = $this->getheight() * $ratio ;
        $this->resize( $width , $height ) ;
    }

    public
            function scale( $scale )
    {
        $width  = $this->getWidth() * $scale / 100 ;
        $height = $this->getheight() * $scale / 100 ;
        $this->resize( $width , $height ) ;
    }

    public
            function resize( $width , $height )
    {
        $new_image = imagecreatetruecolor( $width , $height ) ;
        if ( $this->type == IMAGETYPE_GIF || $this->type == IMAGETYPE_PNG )
        {
            $current_transparent = imagecolortransparent( $this->image ) ;
            if ( $current_transparent != -1 )
            {
                $transparent_color   = imagecolorsforindex( $this->image ,
                                                            $current_transparent ) ;
                $current_transparent = imagecolorallocate( $new_image ,
                                                           $transparent_color[ 'red' ] ,
                                                           $transparent_color[ 'green' ] ,
                                                           $transparent_color[ 'blue' ] ) ;
                imagefill( $new_image , 0 , 0 , $current_transparent ) ;
                imagecolortransparent( $new_image , $current_transparent ) ;
            }
            elseif ( $this->type == IMAGETYPE_PNG )
            {
                imagealphablending( $new_image , false ) ;
                $color = imagecolorallocatealpha( $new_image , 0 , 0 , 0 , 127 ) ;
                imagefill( $new_image , 0 , 0 , $color ) ;
                imagesavealpha( $new_image , true ) ;
            }
        }
        imagecopyresampled( $new_image , $this->image , 0 , 0 , 0 , 0 , $width ,
                            $height , $this->getWidth() , $this->getHeight() ) ;
        $this->image = $new_image ;
    }

}
