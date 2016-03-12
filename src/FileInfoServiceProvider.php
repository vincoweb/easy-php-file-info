<?php

namespace VincoWeb\FileInfo;

use VincoWeb\FileInfo\FileInfo;

class FileInfoServiceProvider extends \Illuminate\Support\ServiceProvider
{

	protected $defer = true;

	public function boot()
	{
		
	}

	public function register()
	{
		$this->app->singleton(FileInfo::class, function()
		{
			return new FileInfo;
		});
	}

	public function provides()
	{
		return [FileInfo::class];
	}
}
