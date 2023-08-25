<?php

$app->get('/', 'SchoolController:index');
$app->post('/', 'SchoolController:store');
$app->post('/edit/{id}', 'SchoolController:edit');
$app->post('/delete/{id}', 'SchoolController:delete');
$app->get('/student', 'StudentController:index');
$app->post('/student', 'StudentController:store');
$app->post('/student/edit/{id}', 'StudentController:edit');
$app->post('/student/delete/{id}', 'StudentController:delete');
