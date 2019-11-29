<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper {
	const ARTICLE_IMAGE = 'article_image';

	/**
	 * @var string
	 */
	private $uploadsPath;

	/**
	 * @var RequestStackContext
	 */
	private $requestStackContext;

	public function __construct(
		string $uploadsPath,
		RequestStackContext  $requestStackContext
	) {
		$this->uploadsPath = $uploadsPath;
		$this->requestStackContext = $requestStackContext;
	}

	public function uploadArticleImage(File $file):string {
		$destination = $this->uploadsPath . '/article_image';
		
		$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
			$destination,
			$newFilename
		);
		
		return $newFilename;
	}

	public function getPublicPath(string $path):string{
		return $this->requestStackContext
				->getBasePath() . '/uploads/'. $path;
	}
}