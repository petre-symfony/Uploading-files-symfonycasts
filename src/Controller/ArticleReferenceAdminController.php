<?php


namespace App\Controller;


use App\Entity\Article;
use App\Service\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleReferenceAdminController extends BaseController {
	/**
	 * @Route("/admin/article/{id}/references", name="admin_article_add_reference", methods={"POST"})
	 * @IsGranted("MANAGE", subject="article")
	 */
	public function uploadArticleReference(
		Article $article,
		Request $request,
        UploaderHelper $uploaderHelper
	){
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile=$request->files->get('reference');

        $filename = $uploaderHelper->uploadArticleReference($uploadedFile);
	}
}