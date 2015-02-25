<?php

namespace Jailbird;

use Jailbird\Image;

class Decoder 
{
	/**
	 * The image object
	 *
	 * @var Jailbird\Image
	 */
	protected $image = null;
	
	/**
	 * The encoder constructor
	 *
	 * @param Jailbird\Image			$image
	 * @return void
	 */
	public function __construct( Image $image )
	{
		$this->image = $image;
	}
	
	/**
	 * Write data on the Image object
	 * 
	 * @return void
	 */ 
	public function decode()
	{
		$data = "";
		
		// y
		for ( $iy=0;$iy<$this->image->height();$iy++ )
		{
			// x
			for ( $ix=0;$ix<$this->image->width();$ix++ )
			{
				$color = $this->image->colorAt($ix,$iy);

				// for every color
				foreach( $color as $space )
				{
					if ( $space % 2 == 0 ) 
					{
						$data .= '0';
					}
					else
					{
						$data .= '1';
					}
				}
			}
		}
		
		// create an array of nibbles
		$data = explode( ':', chunk_split($data, 8, ':') );
		
		$string = "";
		
		foreach( $data as $char )
		{
			$string .= chr( bindec( $char ) );
		}
		
		var_dump( substr($string, 0, 255) );
		
		/*$this->image->colorAt(1,1); die;
		
		$this->image->setColor(1,1, 0, 0, 0 );
		
		$this->image->write( __DIR__.'/../jail.png' );*/
	}
}