<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\HikingAssociation;
use Pimcore\Model\DataObject\NewsArticle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends BaseController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private ArticleRepository  $articleRepository,
    )
    {
    }

    #[Route('/hiking-association/{hikingAssociation}/article-list', name: 'article_list')]
    public function articles(Request $request, HikingAssociation $hikingAssociation): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $page = $request->get('page', 1);
        $limit = 2;

        $articlesListing = $this->articleRepository->getNewsArticleListingByHikingAssociation($hikingAssociation);
        $articles = $this->paginator->paginate(
            $articlesListing,
            $page,
            $limit
        );

        $htmlString = $this->renderView('articles/article-list.html.twig', [
            'hikingAssociation' => $hikingAssociation,
            'articles' => $articles,
            'totalPageCount' => ceil($articles->getTotalItemCount() / $limit),
            'currentPage' => $page,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }

    #[Route('/hiking-association/{hikingAssociation}/articles/{article}', name: 'article_single')]
    public function article(Request $request, HikingAssociation $hikingAssociation, NewsArticle $article): Response
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->getMainFrameView($hikingAssociation);
        }

        $htmlString = $this->renderView('articles/article-single.html.twig', [
            'article' => $article,
        ]);

        return $this->respondWithSuccess(['html_string' => json_encode($htmlString)]);
    }
}
