<?php

namespace App\Controllers;

use LDAP\Result;
use Slim\Http\Request;

class StudentController extends Controller

{

    public function index($request, $response, $args)
    {
        $dataPerPage = 50;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $totalData = $this->db->count("students");
        $totalPages = ceil($totalData / $dataPerPage);
        $offset = ($currentPage - 1) * $dataPerPage;


        $currentPage = intval($request->getQueryParam('page', 1));
        $currentUrl = $request->getUri();
        $previousPage = $currentPage - 1;
        $nextPage = $currentPage + 1;
        $previousUrl = $previousPage > 0 ? $currentUrl->withQuery(http_build_query(['page' => $previousPage])) : false;
        $nextUrl = $nextPage <= $totalPages ? $currentUrl->withQuery(http_build_query(['page' => $nextPage])) : false;
        $baseUrl = $request->getUri()->getBaseUrl();



        if ($request->getParam('filter') == "Semua" or $request->getParam('filter') == Null) {

            $this->view->render($response, 'Student.html', [
                'students' => $this->db->select(
                    "students",
                    [
                        '[>]schools' => ['school_id' => 'id']
                    ],
                    [
                        'students.id',
                        'students.name',
                        'students.email',
                        'students.school_id',
                        'schools.name(school)',
                        'students.is_delete(delete)'

                    ],
                    [
                        'AND' => [
                            'students.is_delete' => false,
                        ],
                        "LIMIT" => [$offset, $dataPerPage]
                    ]
                ),
                'paginate' => [
                    'totalData' => $totalData,
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'baseUrl' => $baseUrl,
                    'nextUrl' => $nextUrl,
                    'previousUrl' => $previousUrl,
                ],
                'schools' => $this->db->select("schools", '*', ['is_delete' => false])

            ]);
        } else {
            $this->view->render($response, 'Student.html', [
                'students' => $this->db->select(
                    "students",
                    [
                        '[>]schools' => ['school_id' => 'id']
                    ],
                    [
                        'students.id',
                        'students.name',
                        'students.email',
                        'students.school_id',
                        'schools.name(school)',
                        'students.is_delete(delete)'

                    ],
                    [
                        'AND' => [
                            'students.is_delete' => false,
                            'students.school_id' => $request->getParam('filter')
                        ],
                        "LIMIT" => [$offset, $dataPerPage]
                    ]
                ),
                'paginate' => [
                    'totalData' => $totalData,
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'baseUrl' => $baseUrl,
                    'nextUrl' => $nextUrl,
                    'previousUrl' => $previousUrl,
                ],
                'schools' => $this->db->select("schools", '*', ['is_delete' => false]),
                'filter' => $request->getParam('filter') == Null ? '' : $request->getParam('filter')


            ]);
        }
    }

    public function store($request, $response, $args)
    {
        $data = [
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
        ];
        $this->db->insert("students", $data);
        return $response->withRedirect('/Educa/student');
    }


    public function edit($request, $response, $args)
    {
        $data = [
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'school_id' => $request->getParam('school_id'),
            'is_delete' => false
        ];
        $where = ['id' => $args['id']];
        $this->db->update("students", $data, $where);
        return $response->withRedirect('/Educa/student');
    }


    public function delete($request, $response, $args)
    {
        $data = [
            'is_delete' => true
        ];
        $where = ['id' => $args['id']];
        $this->db->update("students", $data, $where);
        return $response->withRedirect('/Educa/student');
    }
}
