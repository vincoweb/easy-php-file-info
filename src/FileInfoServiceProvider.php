<?php

namespace VincoWeb\FileInfo;

use VincoWeb\FileInfo\FileInfo;

class FileInfoServiceProvider extends \Illuminate\Support\ServiceProvider
{

	protected $defer = true;

	public function register()
	{
		$this->app->singleton(FileInfo::class, function()
		{
			return new FileInfo();
		});
	}
}
