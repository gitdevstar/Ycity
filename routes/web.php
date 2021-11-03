<?php

use App\Http\Middleware\getClientInfos;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Startseite

Route::post('getMessages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');
Route::post('chatUpload', 'ChatsController@chatUpload');

Route::get('/',  ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/creator',  ['as' => 'creator', 'uses' => 'HomeController@index']);
//Route::post('/getSubcategories',  ['as' => 'job.getSubcategories', 'uses' => 'JobController@getSubcategories']);


// Job Suche
Route::get('/ycity/job/search',  ['as' => 'job.searchJobs', 'uses' => 'JobController@searchJobs']);
Route::post('/getJobs',  ['as' => 'job.getJobs', 'uses' => 'JobController@getJobs']);

// nur Zugriff als Client
Route::group(['middleware' => ['auth', 'checkClientAccess']], function() {
    Route::resource('/ycity/client', 'ClientController')
        ->except([
            'show'
        ]);

    Route::resource('/ycity/job', 'JobController')
        ->except([
            'show', 'edit', 'create'
        ]);

    Route::get('/ycity/job/{job}/{name}/edit',  ['as' => 'job.edit', 'uses' => 'JobController@edit']);

    // Änderung des aktiven Clients
    Route::post('/updateActiveClient',  ['as' => 'client.updateActiveClient', 'uses' => 'ClientController@updateActiveClient']);

    // Creator Suche
    Route::get('/ycity/creator/search',  ['as' => 'creator.searchCreators', 'uses' => 'CreatorController@searchCreators']);
    Route::post('/getCreators',  ['as' => 'creator.getCreators', 'uses' => 'CreatorController@getCreators']);

    // Bewerber anheuern
    Route::post('/hireApplicant',  ['as' => 'client.hireApplicant', 'uses' => 'ClientController@hireApplicant']);

    // Creator anfragen
    Route::post('/sendRequestToCreator',  ['as' => 'client.sendRequestToCreator', 'uses' => 'ClientController@sendRequestToCreator']);

    // Job abschliessen
    Route::post('/closeJob',  ['as' => 'job.closeJob', 'uses' => 'JobController@close']);
});

// nur Zugriff als Creator
Route::group(['middleware' => ['auth', 'checkCreatorAccess']], function() {
    Route::get('/ycity/creator/portfolio/edit',  ['as' => 'creator.portfolioEdit', 'uses' => 'CreatorController@editPortfolio']);
    Route::get('/ycity/creator/skills/edit',  ['as' => 'creator.skillsEdit', 'uses' => 'CreatorController@editSkills']);

    Route::resource('/ycity/creator', 'CreatorController')
    ->except([
        'show', 'store', 'create'
    ]);


    Route::post('/updateCreatorSkills',  ['as' => 'creator.updateCreatorSkills', 'uses' => 'CreatorController@updateCreatorSkills']);

    // Portfolio hochladen / löschen / bewegen
    Route::post('/addTempPortfolioUpload',  ['as' => 'creator.addTempPortfolioUpload', 'uses' => 'CreatorController@addTempPortfolioUpload']);
    Route::post('/deleteTempPortfolioUpload',  ['as' => 'creator.deleteTempPortfolioUpload', 'uses' => 'CreatorController@deleteTempPortfolioUpload']);
    Route::post('/storeTempPortfolioUploads',  ['as' => 'creator.storeTempPortfolioUploads', 'uses' => 'CreatorController@storeTempPortfolioUploads']);
    Route::post('/deletePortfolioFile',  ['as' => 'creator.deletePortfolioFile', 'uses' => 'CreatorController@deletePortfolioFile']);

    // Job apply
    Route::post('/applyToJob',  ['as' => 'job.applyToJob', 'uses' => 'JobController@applyToJob']);

    // Final Draft Upload
    Route::post('finalDraftUpload', 'JobController@finalDraftUpload');
});

// Sprachwahl
Route::get('/change-language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'tr'])) {
        abort(404);
    }

    App::setLocale($locale);
    // Session
    session()->put('locale', $locale);

    return redirect()->back();
});
// nur Zugriff wenn eingeloggt
Route::group(['middleware' => ['auth']], function() {

    Route::get('/ycity',  ['as' => 'ycity', 'uses' => 'HomeController@redirect']);

    Route::resource('/ycity/client', 'ClientController')
    ->only([
        'create',
        'store',
    ]);

    Route::get('/ycity/creator/apply', 'CreatorController@applyCreator');
    Route::get('/ycity/creator/deny', 'CreatorController@denyCreator');

    // Creator erstellen SVA Upload
    Route::post('sva', 'CreatorController@storeSVA');
    Route::delete('sva', 'CreatorController@deleteSVA');

    Route::post('profileImage', 'UserController@storeProfileImage');
    Route::delete('profileImage', 'UserController@deleteProfileImage');

    Route::post('insertActivity', 'HomeController@insertActivityIfChatNotOpen');


    // Creator erstellen: Validierung
    Route::post('/validateCreatorInfos',  ['as' => 'creator.validateCreatorInfos', 'uses' => 'CreatorController@validateCreatorInfos']);
    Route::post('/validatePreCreatorInfos',  ['as' => 'creator.validatePreCreatorInfos', 'uses' => 'CreatorController@validatePreCreatorInfos']);

    // User Routes
    Route::get('/ycity/user/billing-options',  ['as' => 'user.billingOptions', 'uses' => 'UserController@billingOptions']);
    Route::get('/ycity/user/notifications',  ['as' => 'user.notifications', 'uses' => 'UserController@notifications']);
    Route::get('/ycity/user/delete',  ['as' => 'user.delete', 'uses' => 'UserController@delete']);
    Route::get('/ycity/user/edit',  ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::get('/ycity/user/settings',  ['as' => 'user.settings', 'uses' => 'UserController@settings']);
    Route::patch('/ycity/user/update',  ['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::get('/ycity/user',  ['as' => 'user.index', 'uses' => 'UserController@index']);

    // Aktive Skills von Job oder Creator ausfindig machen
    Route::post('/getActiveSkills',  ['as' => 'home.getActiveSkills', 'uses' => 'HomeController@getActiveSkills']);

    // Ungelesene Benachrichtigungen auf gelesen ändern
    Route::post('/clearUnseenMessages',  ['as' => 'home.clearUnseenMessages', 'uses' => 'HomeController@clearUnseenMessages']);
});

// Creator erstellen nur möglich, wenn noch nicht erstellt (einmalig)
Route::group(['middleware' => ['auth','checkAlreadyCreator']], function() {
    Route::resource('/ycity/creator', 'CreatorController')
    ->only([
        'create',
        'store',
    ]);

    Route::get('/ycity/creator',  ['as' => 'creator.creatorType', 'uses' => 'CreatorController@creatorType']);
});

// Job erstellen
Route::resource('/ycity/job', 'JobController')
    ->only([
        'create', 'store'
    ]);

Route::post('/storeTempJobAttachments',  ['as' => 'job.storeTempJobAttachments', 'uses' => 'JobController@storeTempJobAttachments']);
Route::post('/deleteTempJobAttachments',  ['as' => 'job.deleteTempJobAttachments', 'uses' => 'JobController@deleteTempJobAttachments']);

Route::post('/getSubcategoryPrices',  ['as' => 'job.getSubcategoryPrices', 'uses' => 'JobController@getSubcategoryPrices']);
Route::post('/getSpecificationPrices',  ['as' => 'job.getSpecificationPrices', 'uses' => 'JobController@getSpecificationPrices']);
Route::post('/getSpecifications',  ['as' => 'job.getSpecifications', 'uses' => 'JobController@getSpecifications']);

Route::get('/ycity/creator/{creator}/{firstname}-{lastname}',  ['as' => 'creator.show', 'uses' => 'CreatorController@show']);
Route::post('/ycity/creator',  ['as' => 'creator.store', 'uses' => 'CreatorController@store']);
Route::get('/ycity/job/{job}/{name}',  ['as' => 'job.show', 'uses' => 'JobController@show']);

// Ort anhand der PLZ aus der DB herauslesen
Route::post('/getPlaceFromDB',  ['as' => 'home.getPlaceFromDB', 'uses' => 'HomeController@getPlaceFromDB']);

// nicht öffentlich zugängliche Dateien
Route::get('/uploads/finals/{job}/final.zip', 'PrivateController@finalDraftFile');
Route::get('/uploads/chat/{job}/{file}', 'PrivateController@chatFiles');
Route::get('/uploads/clients/{client}/{job}/{file}', 'PrivateController@jobFiles');
Route::get('/uploads/creators/{creator}/{file}', 'PrivateController@creatorFiles');
Route::get('/uploads/creators/{creator}/portfolio/{file}', 'PrivateController@portfolioFiles');

// Authentification Routes
Auth::routes();

// Logout
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');





