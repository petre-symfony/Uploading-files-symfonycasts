<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
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

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $publicAssetBaseUrl;

	public function __construct(
        FilesystemInterface $publicUploadFilesystem,
		RequestStackContext  $requestStackContext,
        LoggerInterface $logger,
        string $uploadedAssetsBaseUrl
	) {
        $this->filesystem = $publicUploadFilesystem;
		$this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
	}

	public function uploadArticleImage(File $file, ?string $existingFilename):string {
        if($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

		$newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');

        $result = $this->filesystem->writeStream(
            self::ARTICLE_IMAGE.'/'.$newFilename,
            $stream
		);

        if($result === false){
            throw new \Exception(sprintf(
                "Could not write uploaded file %s",
                $newFilename
            ));
        }

        if(is_resource($stream)){
            fclose($stream);
        }

        if($existingFilename){
            try {
                $result = $this->filesystem->delete(self::ARTICLE_IMAGE . '/' . $existingFilename);
                if($result === false){
                    throw new \Exception(sprintf(
                        "Could not write uploaded file %s",
                        $newFilename
                    ));
                }
            }  catch(FileNotFoundException $e){
                $this->logger->alert(sprintf(
                    'Old uploaded file "%s" was missing when trying to delete',
                    $existingFilename
                ));
            }
        }

		return $newFilename;
	}

    public function uploadArticleReference(File $file):string{
        dd($file);
    }
    
	public function getPublicPath(string $path):string{
		return $this->requestStackContext
				->getBasePath() . $this->publicAssetBaseUrl .'/'. $path;
	}
}