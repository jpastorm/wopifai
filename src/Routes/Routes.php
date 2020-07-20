<?php 

use Slim\Routing\RouteCollectorProxy;
use App\Middlewares;

$app->group('/api/dir',function(RouteCollectorProxy $group){

    $group->get('','App\Controllers\ExploradorController:getDir');
    $group->get('/tag','App\Controllers\ExploradorController:getTags');
    $group->get('/cover','App\Controllers\ExploradorController:getAlbumCover');
    $group->get('/stream','App\Controllers\ExploradorController:StreamFile');
    $group->get('/scan','App\Controllers\ExploradorController:ScanFile');
   
});

$app->group('/api/library',function(RouteCollectorProxy $group){

    $group->get('','App\Controllers\LibraryController:GetLibrary');
    $group->post('/addlibrary','App\Controllers\LibraryController:AddLibrary');
    $group->get('/getAll/{id}','App\Controllers\LibraryController:GetContentLibrary');
    $group->get('/getAll','App\Controllers\LibraryController:GetContentLibrary');
});

$app->group('/api/cancion',function(RouteCollectorProxy $group){
    $group->get('/{hash}','App\Controllers\CancionController:getCancion');
    $group->get('/{hash}/cover','App\Controllers\CancionController:getAlbumCover');
    $group->get('/{hash}/meta','App\Controllers\CancionController:getMeta');
    $group->get('/{hash}/streamtrack','App\Controllers\CancionController:getStreamTrack');
    $group->get('/song/{nombre}','App\Controllers\CancionController:getSong');
});


 ?>