<?php

namespace Jailbird;

class Image 
{
	/**
	 * Create an image object from path
	 * 
	 * @param string 				$path
	 * @return Jailbird\Image
	 */
	public static function path( $path )
	{
		return new static( file_get_contents( $path ) );
	}
	
	/**
	 * The image resource object
	 * 
	 * @var resource
	 */
	protected $image = null;
	
	/**
	 * The encoder constructor
	 *
	 * @param resource			$imageResource
	 * @return void
	 */
	public function __construct( $imageResource )
	{
		$this->image = imagecreatefromstring( $imageResource );
	}
	
	/**
	 * Return the image width
	 *
	 * @return int
	 */
	public function width()
	{
		return imagesx( $this->image );
	}
	
	/**
	 * Return the image height
	 *
	 * @return int
	 */
	public function height()
	{
		return imagesy( $this->image );
	}
	
	/**
	 * Get the color of a pixel
	 * 
	 * @param int 				$x
	 * @param int 				$y
	 * @return array[r,g,b]
	 */
	public function colorAt( $x, $y )
	{
		$rgb = imagecolorat( $this->image, $x, $y );
		
		$color = imagecolorsforindex( $this->image, $rgb );
		
		return [ $color['red'], $color['green'], $color['blue'] ];
	}
	
	/**
	 * Write pixel at location
	 * 
	 */
	public function setColor( $x, $y, $r, $g, $b )
	{
		$color = imagecolorallocate( $this->image, $r, $g, $b); 
		
		imagesetpixel( $this->image, $x, $y, $color );
	}
	
	/**
	 * Write the image to disk
	 */
	public function write( $path )
	{
		imagepng( $this->image, $path, 0 );
	}
}