<?php


namespace App\Controller;


use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleReferenceAdminController extends BaseController {
	/**
	 * @Route("/admin/article/{id}/references", name="admin_article_add_reference", methods={"POST"})
	 * @IsGranted("MANAGE", subject="article")
	 */
	public function uploadArticleReference(
		Article $article,
		Request $request
	){
		dd($request->files->get('reference'));
	}
}