<?php

namespace VincoWeb\FileInfo\FileType;

class ImageInfo
{

	//link is url or path
	protected $file_location;
	//integer (represent imagetype constant)
	protected $imagetype;

	public function get($file_link)
	{
		if($imageSizes = @getimagesize($file_link)){
			
			return [
				'width' => $imageSizes[0],
				'height' => $imageSizes[2],
				'mime' => $imageSizes['mime'],
				'extension' => image_type_to_extension($imageSizes[2], false) //return file extension from mime type constant without dot
			];
		}
		
		return false;
	}

}
