<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper {
	const ARTICLE_IMAGE = 'article_image';

	/**
	 * @var RequestStackContext
	 */
	private $requestStackContext;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

	public function __construct(
        FilesystemInterface $publicUploadFilesystem,
		RequestStackContext  $requestStackContext
	) {
        $this->filesystem = $publicUploadFilesystem;
		$this->requestStackContext = $requestStackContext;
	}

	public function uploadArticleImage(File $file):string {
        if($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

		$newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');

        $this->filesystem->writeStream(
            self::ARTICLE_IMAGE.'/'.$newFilename,
            $stream
		);

        if(is_resource($stream)){
            fclose($stream);
        }
        
		return $newFilename;
	}

	public function getPublicPath(string $path):string{
		return $this->requestStackContext
				->getBasePath() . '/uploads/'. $path;
	}
}