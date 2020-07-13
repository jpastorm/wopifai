<?php 

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/dir',function(RouteCollectorProxy $group){

    $group->get('/','App\Controllers\ExploradorController:getDir');
    $group->get('/tag','App\Controllers\ExploradorController:getTags');
    $group->get('/cover','App\Controllers\ExploradorController:getAlbumCover');
    $group->get('/stream','App\Controllers\ExploradorController:StreamFile');
   
});

$app->group('/api/library',function(RouteCollectorProxy $group){

    $group->get('/','App\Controllers\LibraryController:GetLibrary');
    $group->post('/create','App\Controllers\LibraryController:AddLibrary');
   
});


 ?>


