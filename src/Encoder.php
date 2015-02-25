<?php

namespace Jailbird;

use Jailbird\Image;

class Encoder 
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
	 * @param string 			$data
	 * @return void
	 */ 
	public function encode( $data )
	{
		$chars = [];
		
		for( $i=0;$i<strlen( $data );$i++ )
		{
			$chars[] = sprintf( "%08d", decbin( ord( $data[$i] ) ) );
		}
		
		$chars = implode('', $chars);
		
		$index = 0;
		
		// y
		for ( $iy=0;$iy<$this->image->height();$iy++ )
		{
			// x
			for ( $ix=0;$ix<$this->image->width();$ix++ )
			{
				$color = $this->image->colorAt($ix,$iy);
				
				// for every color
				foreach( $color as &$space )
				{
					if ( !isset( $chars[$index] ) )
					{
						break 2;	
					}
					
					$char = $chars[$index];
					
					// what is the current pixel
					$negative = ( $space % 2 == 0 );
					
					// should it be positive
					if ( $char == '1' )
					{
						// should be positive but is negative
						if ( $negative )
						{
							if ( $space < 255 )
							{
								$space++;
							}
							else
							{
								$space--;
							}
						}
					}
					// should be negative
					else
					{
						// should be negative but is positive
						if ( !$negative )
						{
							if ( $space < 255 )
							{
								$space++;
							}
							else
							{
								$space--;
							}
						}
					}
					
					$index++;
				}
				
				// overwrite the pixel
				$this->image->setColor($ix,$iy, $color[0], $color[1], $color[2] );
			}
		}
		
		$this->image->write( __DIR__.'/../jail.png' );
	}
}