<?php

namespace VincoWeb\FileInfo;

use finfo;

class FileInfo
{

	// returned object with file info
	protected $file_info;
	//file url or path holder
	protected $file_link;

	public function get($file_link)
	{
		$this->file_link = $file_link;

		//detect if link is path or link
		$file_location = $this->getFileLocation($file_link);

		if (!$file_location) {
			throw new FileNotFoundException("This link to file does not exists or link is broken: $file_link");
		}

		if ($file_location == 'path') {
			$finfo = new finfo(FILEINFO_MIME_TYPE);

			$this->file_info = [
				'link' => $file_link,
				'mime' => $finfo->file($file_link),
				'size' => filesize($file_link),
				'last_modified' => date("D, d M Y G:i:s", filemtime($file_link)),
				'etag' => md5_file($file_link),
			];
		} else {
			$info = $this->getFileInfoFromUrl($file_link);
			
			$this->file_info = [
				'link' => isset($info['info']['url']) ? $info['info']['url'] : null,
				'mime' => isset($info['info']['content_type']) ? $info['info']['content_type'] : null,
				'size' => isset($info['info']['download_content_length']) ? $info['info']['download_content_length'] : null,
				'last_modified' => isset($info['headers']['Last-Modified']) ? $info['headers']['Last-Modified'] : null,
				'etag' => isset($info['headers']['ETag']) ? $info['headers']['ETag'] : null
			];
		}

		$this->file_info['extension'] = pathinfo($file_link, PATHINFO_EXTENSION);
		$this->file_info['type'] = $this->file_info['mime'];
		$this->file_info['location'] = $file_location;

		if ($this->isImage($file_link)) {
			$imageInfo = new FileType\ImageInfo();
			$imageInfoArray = $imageInfo->get($file_link, $file_location);
			$this->file_info = array_merge($this->file_info, $imageInfoArray);
			$this->file_info['type'] =  'image';
		}

		return $this->file_info;
	}

	public function getFileLocation($file_link)
	{
		//chech $file_link as path
		if ($this->isFilePath($file_link)) {
			return 'path';
		}

		//chech $file_link as url
		if ($this->isFileUrl($file_link)) {
			return 'url';
		}

		return false;
	}

	protected function isFilePath($file_path)
	{
		$is_file = is_file($file_path);
		
		clearstatcache();
		
		return $is_file;
	}

	protected function isFileUrl($file_url)
	{
		$ua = 'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0 (ROBOT)';

		$ch = curl_init($file_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);

		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return $httpStatus < 400 ? true : false;
	}

	public function getFileInfoFromUrl($file_url)
	{
		$ua = 'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0 (ROBOT)';

		$ch = curl_init($file_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);

		$headers = curl_exec($ch);
		$info = curl_getinfo($ch);

		//explode headers string by new line
		$ha = explode("\n", $headers);

		//remove http header
		unset($ha[0]);

		//remove empty values 
		$ha = array_values(array_filter($ha, function ($value)
			{
				$value = trim($value, "\t\n\r\0\x0B");

				return (!empty($value));
			}));


		$headers = [];

		//get heders like key(header name) value (header value) 
		foreach ($ha as $key => $header){
			$hp = explode(':', $header, 2);
			$headers[$hp[0]] = $hp[1];
		}

		return ['headers' => $headers, 'info' => $info];
	}

	public function isImage($file_link)
	{
		//return false or image type constant(integer)
		return @exif_imagetype($file_link);
	}
}
