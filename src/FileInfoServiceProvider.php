<?php

namespace VincoWeb\FileInfo;

class FileInfoServiceProvider extends \Illuminate\Support\ServiceProvider
{

	protected $defer = true;

	public function register()
	{
		$this->app->singleton('fileinfo', function()
		{
			return new FileInfo();
		});
	}
}
