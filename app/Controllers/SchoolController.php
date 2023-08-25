<?php

namespace App\Controllers;

use LDAP\Result;
use Slim\Http\Request;

class SchoolController extends Controller

{
    public function index($request, $response, $args)
    {
        $dataPerPage = 50;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $totalData = $this->db->count("schools");
        $totalPages = ceil($totalData / $dataPerPage);
        $offset = ($currentPage - 1) * $dataPerPage;


        $currentPage = intval($request->getQueryParam('page', 1));
        $currentUrl = $request->getUri();
        $previousPage = $currentPage - 1;
        $nextPage = $currentPage + 1;
        $previousUrl = $previousPage > 0 ? $currentUrl->withQuery(http_build_query(['page' => $previousPage])) : false;
        $nextUrl = $nextPage <= $totalPages ? $currentUrl->withQuery(http_build_query(['page' => $nextPage])) : false;
        $baseUrl = $request->getUri()->getBaseUrl();

        $this->view->render($response, 'School.html', [
            'schools' => $this->db->select("schools", '*', [
                'AND' => [
                    'is_delete' => false,
                    // Kondisi WHERE lainnya jika ada
                ],
                "LIMIT" => [$offset, $dataPerPage]
            ]),
            'paginate' => [
                'totalData' => $totalData,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'baseUrl' => $baseUrl,
                'nextUrl' => $nextUrl,
                'previousUrl' => $previousUrl,
            ]

        ]);
    }

    public function store($request, $response, $args)
    {
        $data = [
            'name' => $request->getParam('name'),
            'alamat' => $request->getParam('alamat'),
        ];

        $this->db->insert("schools", $data);

        return $response->withRedirect($request->getUri()->getBaseUrl());
    }


    public function edit($request, $response, $args)
    {
        $data = [
            'name' => $request->getParam('name'),
            'alamat' => $request->getParam('alamat'),
            'is_delete' => false
        ];
        $where = ['id' => $args['id']];
        $this->db->update("schools", $data, $where);
        return $response->withRedirect($request->getUri()->getBaseUrl());
    }


    public function delete($request, $response, $args)
    {
        $data = [
            'is_delete' => true
        ];
        $where = ['id' => $args['id']];
        $this->db->update("schools", $data, $where);
        return $response->withRedirect('/Educa');
    }
}
