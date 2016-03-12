<?php

namespace VincoWeb\FileInfo;

class FileInfoFacade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return FileInfo::class;
	}
}
