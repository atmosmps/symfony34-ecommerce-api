<?php

namespace ApiBundle\Service\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class PaginationFactory
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function paginate(QueryBuilder $queryBuilder, Request $request)
    {
        $pageCurrent = $request->get('page', 1);

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerFanta = new Pagerfanta($adapter);
        $pagerFanta->setMaxPerPage(3);
        $pagerFanta->setCurrentPage($pageCurrent);

        $products = [];
        foreach ($pagerFanta->getCurrentPageResults() as $p) {
            $products[] = $p;
        }

        $data = [
            'data' => $products,
            'total' => $pagerFanta->getNbResults(),
            'count' => count($products),
            'page' => $request->get('page')
        ];

        $route = 'product_index';
        $routeParams = [];
        $generateUrlPagination = function ($page) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge(
                ['page' => $page],
                $routeParams
            ));
        };

        $data['_links'] = [
            'self' => $generateUrlPagination($pageCurrent),
            'first' => $generateUrlPagination(1),
            'last' => $generateUrlPagination($pagerFanta->getNbPages())
        ];

        if ($pagerFanta->hasPreviousPage()) {
            $data['_links']['prev'] = $generateUrlPagination($pagerFanta->getPreviousPage());
        }

        if ($pagerFanta->hasNextPage()) {
            $data['_links']['next'] = $generateUrlPagination($pagerFanta->getNextPage());
        }

        return $data;
    }
}