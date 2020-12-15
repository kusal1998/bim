<?php

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
 

Auth::routes();

Route::get('/get-form14-get-record/{map}/{block}/{lotno}','SearchController@getForm14lot');
Route::get('/get-form14-lot-number/{map}/{block}/{lotno}','Form14Controller@getForm14lot');
Route::group(['middleware' => ['auth','web']], function()
{
  Route::get('lang/{locale}', 'HomeController@lang');
  Route::get('/', 'HomeController@index')->name('home');
  Route::get('text/file/{id}', 'HomeController@form12')->name('txt');
  Route::get('/get-districts-by-province/{id}','DistrictsController@getDistrictsByProvince');
  Route::get('/get-ags-by-district/{id}','AGDivisionsController@getAGsByDistrict');
  Route::get('/get-gns-by-ag/{id}','GNDivisionsController@getGnsByAG');
  Route::get('/get-villages-by-gn/{id}','VillageController@getVillagesByGn');
  Route::get('/get-permissions/{role}','UserRolesController@getPermissions');
  Route::get('/get-form12/{map}/{block}','Form12Controller@getForm12');
  Route::get('/get-form14/{map}/{block}/{lot}','Form14Controller@findForm');
  Route::get('/get-form12-maps-numbers/{map}','Form12Controller@getForm12Map');
  Route::get('/get-form14/{map}/{block}','Form14Controller@getForm14');
  Route::get('/get-amendments/{map}/{block}','AmendmentsController@getForm14');
  Route::get('/get-form14-maps-numbers/{map}','Form14Controller@getForm14Map');
  Route::get('/get-amendments-maps-numbers/{map}','AmendmentsController@getForm14Map');
  Route::get('/get-form14-map-number','Form14Controller@getMapNumbers');
  Route::get('/check-form12-map-number/{map}','Form12Controller@checkMapNumber');
  Route::get('/check-form12-block-number/{map}/{block}','Form12Controller@checkBlockNumber');
  Route::get('/get-form14-ref-number','Form14Controller@getrefNumbers');

  Route::get('12th-sentence-file/new-requests', 'Form12FileController@get_new_requests')->name('form12file-new-requests');
  Route::get('12th-sentence-file/new-list',  ['uses' => 'Form12FileController@newlist'])->name('form12file-pending.getnewdata');
  Route::get('12th-sentence-file/update/{id}',  ['uses' => 'Form12FileController@view'])->name('form12file-view');
  Route::post('12th-sentence-file/update/{id}',  ['uses' => 'Form12FileController@update'])->name('form12file-update');
  Route::get('12th-sentence-file/download/{id}', 'Form12FileController@downloadNotice')->name('form12-file-download');
//12
Route::get('amendment-sentence-file/new-requests', 'AmendmentFileController@get_new_requests')->name('amendmentfile-new-requests');
Route::get('14th-sentence-file/new-requests', 'Form14FileController@get_new_requests')->name('form14file-new-requests');
Route::get('14th-sentence-file/new-list',  ['uses' => 'Form14FileController@newlist'])->name('form14file-pending.getnewdata');
Route::get('amendment-sentence-file/new-list',  ['uses' => 'AmendmentFileController@newlist'])->name('amendmentfile-pending.getnewdata');
Route::get('14th-sentence-file/update/{id}',  ['uses' => 'Form14FileController@view'])->name('form14file-view');
Route::get('amendment-sentence-file/update/{id}',  ['uses' => 'AmendmentFileController@view'])->name('amendmentfile-view');
Route::post('14th-sentence-file/update/{id}',  ['uses' => 'Form14FileController@update'])->name('form14file-update');
Route::post('amendment-sentence-file/update/{id}',  ['uses' => 'AmendmentFileController@update'])->name('amendmentfile-update');
Route::get('14th-sentence-file/details-update/{id}',  ['uses' => 'Form14FileController@block_details'])->name('form14file-detail-view');
Route::get('amendment-sentence-file/details-update/{id}',  ['uses' => 'AmendmentFileController@block_details'])->name('amendmentfile-detail-view');
Route::get('14th-sentence-file/download/{id}', 'Form14FileController@downloadNotice')->name('form14-file-download');
Route::group(['middleware'=>'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
    Route::get('12th-sentence/new-requests', 'Form12Controller@new')->name('form12-new-requests');

    Route::get('12th-sentence/approved-requests', 'Form12Controller@approved')->name('form12-approved-requests');
    Route::get('12th-sentence/pending-requests', 'Form12Controller@pending')->name('form12-pending-requests');
    Route::get('12th-sentence/pending-list',  ['uses' => 'Form12Controller@pendinglist'])->name('form12-pending.getpendingdata');
    Route::get('12th-sentence/new-list',  ['uses' => 'Form12Controller@newlist'])->name('form12-pending.getnewdata');

    Route::get('12th-sentence/current-list',  ['uses' => 'Form12Controller@currentlist'])->name('form12.getcurrentfiles');
    Route::get('12th-sentence/rejected-list',  ['uses' => 'Form12Controller@getrejected'])->name('form12.getrejected');
    Route::get('12th-sentence/reference-list',  ['uses' => 'Form12Controller@getReference'])->name('form12.getreference');

    Route::get('12th-sentence/recheck-list',  ['uses' => 'Form12Controller@getrecheck'])->name('form12.getrechek');
    Route::get('12th-sentence/gazetted-list',  ['uses' => 'Form12Controller@getgazetted'])->name('form12.getgazetted');
    Route::get('12th-sentence/rejected-requests', 'Form12Controller@rejected')->name('form12-rejected-requests');
    Route::get('12th-sentence/recheck-requests', 'Form12Controller@recheck')->name('form12-recheck-requests');
    Route::get('12th-sentence/reference-requests', 'Form12Controller@reference')->name('form12-reference-requests');
    Route::get('12th-sentence/gazetted-requests', 'Form12Controller@gazetted')->name('form12-gazetted-requests');
    Route::get('12th-sentence/download', 'Form12Controller@downloadNotice')->name('form12-download');
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\CreatePermissionMiddleware'],function(){
        Route::get('12th-sentence/create', 'Form12Controller@create')->name('form12-create');
        Route::post('12th-sentence/create', 'Form12Controller@store')->name('12th-sentence-store');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\ViewPermissionMiddleware'],function(){
        Route::get('12th-sentence/view/{id}', 'Form12Controller@show')->name('form12-view');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'],function(){
        Route::get('12th-sentence/update/{id}', 'Form12Controller@edit')->name('form12-edit');
        Route::post('12th-sentence/update/{id}', 'Form12Controller@update')->name('12th-sentence-update');
        Route::post('12th-sentence/create/file','Form12FileController@create_file');
    });
});

Route::group(['middleware'=>'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('14th-sentence/download/file/{id}/', 'Form14Controller@downloadFile');
  Route::get('14th-sentence/certification/update', 'Form14Controller@certificate');
  Route::post('14th-sentence/dupicate', 'Form14Controller@checkDuplicateEntry')->name('14th-sentence-duplicate');
  Route::get('14th-sentence/reject/update', 'Form14Controller@detailsRecheck');
  Route::get('14th-sentence/create', 'Form14Controller@create')->name('form14-create');
  Route::post('14th-sentence/create', 'Form14Controller@store')->name('14th-sentence-store');
  Route::get('14th-sentence/view/{id}', 'Form14Controller@show')->name('form14-view');
  Route::get('14th-sentence/update/{id}', 'Form14Controller@edit')->name('form14-edit');
  Route::get('14th-sentence/update/{id}#profile', 'Form14Controller@edit')->name('form14-profile');
  Route::get('14th-sentence/update/{id}#history', 'Form14Controller@edit')->name('form14-history');
  Route::post('14th-sentence/update/{id}', 'Form14Controller@update')->name('14th-sentence-update');
  Route::get('14th-sentence/new-requests', 'Form14Controller@new')->name('form14-new-requests');
  Route::get('14th-sentence/approved-requests', 'Form14Controller@approved')->name('form14-approved-requests');
  Route::get('14th-sentence/pending-requests', 'Form14Controller@pending')->name('form14-pending-requests');
  Route::get('14th-sentence/rejected-requests', 'Form14Controller@rejected')->name('form14-rejected-requests');
  Route::get('14th-sentence/rejected-lands', 'Form14Controller@rejlands')->name('form14-rejected-lands');
  Route::get('14th-sentence/gazetted-requests', 'Form14Controller@gazetted')->name('form14-gazetted-requests');
  Route::get('14th-sentence/new-list',  ['uses' => 'Form14Controller@newlist'])->name('form14.getnewdata');
  Route::get('14th-sentence/current-list',  ['uses' => 'Form14Controller@currentlist'])->name('form14.getcurrentfiles');
  Route::get('14th-sentence/pending-list',  ['uses' => 'Form14Controller@getpendingdata'])->name('form14.getpendingdata');
  Route::get('14th-sentence/rejected-list',  ['uses' => 'Form14Controller@getrejected'])->name('form14.getrejected');

  Route::get('14th-sentence/recheck-list',  ['uses' => 'Form14Controller@getrecheck'])->name('form14.getrechek');
  Route::get('14th-sentence/gazetted-list',  ['uses' => 'Form14Controller@getgazetted'])->name('form14.getgazetted');
  Route::get('14th-sentence/new-requests', 'Form14Controller@new')->name('form14-new-requests');
  Route::get('14th-sentence/approved-requests', 'Form14Controller@approved')->name('form14-approved-requests');
  Route::get('14th-sentence/pending-requests', 'Form14Controller@pending')->name('form14-pending-requests');
    Route::get('14th-sentence/rejected-requests', 'Form14Controller@rejected')->name('form14-rejected-requests');
    Route::get('14th-sentence/recheck-requests', 'Form14Controller@recheck')->name('form14-recheck-requests');
    Route::get('14th-sentence/rejected-lands', 'Form14Controller@rejlands')->name('form14-rejected-lands');
    Route::get('14th-sentence/gazetted-requests', 'Form14Controller@gazetted')->name('form14-gazetted-requests');
    Route::get('14th-sentence/details/{id}', 'Form14Controller@getDetails')->name('form14-details');
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\ViewPermissionMiddleware'],function(){
        Route::get('14th-sentence/view/{id}', 'Form14Controller@show')->name('form14-view');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\CreatePermissionMiddleware'],function(){
        Route::get('14th-sentence/create', 'Form14Controller@create')->name('form14-create');
        Route::post('14th-sentence/create', 'Form14Controller@store')->name('14th-sentence-store');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'],function(){
        Route::get('14th-sentence/update/{id}', 'Form14Controller@edit')->name('form14-edit');
        Route::post('14th-sentence/update/{id}', 'Form14Controller@update')->name('14th-sentence-update');
        Route::post('14th-sentence/create/file','Form14FileController@create_file');
    });
});

Route::group(['middleware'=>'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
    Route::get('55th-sentence/reject/update', 'Form55Controller@detailsRecheck');
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\CreatePermissionMiddleware'],function(){
        Route::get('55th-sentence/create', 'Form55Controller@create')->name('form55-create');
        Route::post('55th-sentence/create', 'Form55Controller@store')->name('55th-sentence-store');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\ViewPermissionMiddleware'],function(){
        Route::get('55th-sentence/view/{id}', 'Form55Controller@show')->name('form55-view');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'],function(){
        Route::get('55th-sentence/update/{id}', 'Form55Controller@edit')->name('form55-edit');
        Route::post('55th-sentence/update/{id}', 'Form55Controller@update')->name('55th-sentence-update');
        Route::get('55th-sentence/update/{id}#profile', 'Form55Controller@edit')->name('form55-profile');
        Route::get('55th-sentence/update/{id}#history', 'Form55Controller@edit')->name('form55-history');
    });
    Route::get('55th-sentence/new-requests', 'Form55Controller@new')->name('form55-new-requests');
    Route::get('55th-sentence/approved-requests', 'Form55Controller@approved')->name('form55-approved-requests');
    Route::get('55th-sentence/pending-requests', 'Form55Controller@pending')->name('form55-pending-requests');
    Route::get('55th-sentence/rejected-requests', 'Form55Controller@rejected')->name('form55-rejected-requests');
    Route::get('55th-sentence/recheck-requests', 'Form55Controller@recheck')->name('form55-recheck-requests');
    Route::get('55th-sentence/gazetted-requests', 'Form55Controller@gazetted')->name('form55-gazetted-requests');
    Route::get('55th-sentence/details/{id}', 'Form55Controller@getDetails')->name('form55-details');
    Route::get('55th-sentence/new-list',  ['uses' => 'Form55Controller@newlist'])->name('form55.getnewdata');
    Route::get('55th-sentence/current-list',  ['uses' => 'Form55Controller@currentlist'])->name('form55.getcurrentfiles');
    Route::get('55th-sentence/pending-list',  ['uses' => 'Form55Controller@getpendingdata'])->name('form55.getpendingdata');
    Route::get('55th-sentence/rejected-list',  ['uses' => 'Form55Controller@getrejected'])->name('form55.getrejected');
    Route::get('55th-sentence/gazetted-list',  ['uses' => 'Form55Controller@getgazetted'])->name('form55.getgazetted');
    Route::get('55th-sentence/recheck-list',  ['uses' => 'Form55Controller@getrecheck'])->name('form55.getrechek');
});

Route::post('amendment-sentence/create/file','AmendmentFileController@create_file');
Route::get('amendment-sentence-file/new-list',  ['uses' => 'AmendmentFileController@newlist'])->name('amendmentfile-pending.getnewdata');

Route::group(['middleware'=>'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
    Route::get('amendment/reject/update', 'AmendmentsController@detailsRecheck');
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\CreatePermissionMiddleware'],function(){
        Route::get('amendments/create', 'AmendmentsController@create')->name('amendments-create');
        Route::post('amendments/create', 'AmendmentsController@store')->name('amendments-store');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\ViewPermissionMiddleware'],function(){
        Route::get('amendments/view/{id}', 'AmendmentsController@show')->name('amendments-view');
    });
    Route::group(['middleware'=>'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'],function(){
        Route::get('amendments/update/{id}', 'AmendmentsController@edit')->name('amendments-edit');
        Route::post('amendments/update/{id}', 'AmendmentsController@update')->name('amendments-update');
        Route::get('amendments/update/{id}#profile', 'AmendmentsController@edit')->name('amendments-profile');
        Route::get('amendments/update/{id}#history', 'AmendmentsController@edit')->name('amendments-history');
    });
    Route::get('amendments/new-requests', 'AmendmentsController@new')->name('amendments-new-requests');
    Route::get('amendments/approved-requests', 'AmendmentsController@approved')->name('amendments-approved-requests');
    Route::get('amendments/pending-requests', 'AmendmentsController@pending')->name('amendments-pending-requests');
    Route::get('amendments/rejected-requests', 'AmendmentsController@rejected')->name('amendments-rejected-requests');
    Route::get('amendments/recheck-requests', 'AmendmentsController@recheck')->name('amendments-recheck-requests');
    Route::get('amendments/gazetted-requests', 'AmendmentsController@gazetted')->name('amendments-gazetted-requests');
    Route::get('amendments/details/{id}', 'AmendmentsController@getDetails')->name('amendments-details');
    Route::get('amendments/new-list',  ['uses' => 'AmendmentsController@newlist'])->name('amendments.getnewdata');
    Route::get('amendments/current-list',  ['uses' => 'AmendmentsController@currentlist'])->name('amendments.getcurrentfiles');
    Route::get('amendments/pending-list',  ['uses' => 'AmendmentsController@getpendingdata'])->name('amendments.getpendingdata');
    Route::get('amendments/rejected-list',  ['uses' => 'AmendmentsController@getrejected'])->name('amendments.getrejected');
    Route::get('amendments/gazetted-list',  ['uses' => 'AmendmentsController@getgazetted'])->name('amendments.getgazetted');
    Route::get('amendments/recheck-list',  ['uses' => 'AmendmentsController@getrecheck'])->name('amendments.getrechek');
});

  Route::get('on-process/all', 'ReportsController@on_process')->name('on_process');
  Route::get('report1', 'ReportsController@report1')->name('report1');
  Route::get('report1/list/{id}', 'ReportsController@report1list');
  Route::get('report2', 'ReportsController@report2')->name('report2');
  Route::get('report3', 'ReportsController@report3')->name('report3');
  Route::get('report4', 'ReportsController@report4')->name('report4');
  Route::get('report5', 'ReportsController@report5')->name('report5');
  Route::get('report6', 'ReportsController@report6')->name('report6');
  Route::get('report7', 'ReportsController@report7')->name('report7');
  Route::get('report8', 'ReportsController@report8')->name('report8');
  Route::get('report9', 'ReportsController@report9')->name('report9');
  Route::get('report10', 'ReportsController@report10')->name('report10');
  Route::get('report11', 'ReportsController@report11')->name('report11');
  Route::get('report12', 'ReportsController@report12')->name('report12');
  Route::get('report13', 'ReportsController@report13')->name('report13');
  Route::get('report14', 'ReportsController@report14')->name('report14');

  Route::get('report1/export', 'ReportsController@report1export')->name('report1.export');
  Route::get('report2/export/{date}', 'ReportsController@report2export')->name('report2.export');
  Route::get('report3/export/{date}', 'ReportsController@report3export')->name('report3.export');
  Route::get('report4/export/{date}', 'ReportsController@report4export')->name('report4.export');
  Route::get('report5/export/{fdate}/{tdate}', 'ReportsController@report5export')->name('report5.export');
  Route::get('report6/export/{fdate}/{tdate}', 'ReportsController@report6export')->name('report6.export');
  Route::get('report7/export/{fdate}/{tdate}', 'ReportsController@report7export')->name('report7.export');
  Route::get('report8/export/{fdate}/{tdate}', 'ReportsController@report8export')->name('report8.export');
  Route::get('report9/export/{fdate}/{tdate}', 'ReportsController@report9export')->name('report9.export');
  Route::get('report10/export/{fdate}/{tdate}', 'ReportsController@report10export')->name('report10.export');
  Route::get('report11/export', 'ReportsController@report11export')->name('report11.export');
  Route::get('report12/export/{fdate}/{tdate}', 'ReportsController@report12export')->name('report12.export');
  Route::get('report13/export/{year}', 'ReportsController@report13export')->name('report13.export');
  Route::get('report14/export/{fyear}/{tyear}', 'ReportsController@report14export')->name('report14.export');

  //Modules
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('modules/all', 'ModulesController@index')->name('modules-all-list');
  Route::get('modules',  ['uses' => 'ModulesController@list'])->name('modules.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('modules/view/{id}', 'ModulesController@show')->name('modules-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('modules/create', 'ModulesController@create')->name('modules-create');
  Route::post('modules/create', 'ModulesController@store')->name('modules-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('modules/update/{id}', 'ModulesController@edit')->name('modules-edit');
  Route::post('modules/update/{id}', 'ModulesController@update')->name('modules-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('modules/delete/{id}', 'ModulesController@destroy')->name('modules-destroy');
  });
  });


  //Provinces
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('provinces/all', 'ProvincesController@index')->name('provinces-all-list');
  Route::get('provinces',  ['uses' => 'ProvincesController@list'])->name('provinces.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('provinces/view/{id}', 'ProvincesController@show')->name('provinces-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('provinces/create', 'ProvincesController@create')->name('provinces-create');
  Route::post('provinces/create', 'ProvincesController@store')->name('provinces-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('provinces/update/{id}', 'ProvincesController@edit')->name('provinces-edit');
  Route::post('provinces/update/{id}', 'ProvincesController@update')->name('provinces-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('provinces/delete/{id}', 'ProvincesController@destroy')->name('provinces-destroy');
  });
  });


  //regional-office
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
    Route::get('regional-office/all', 'RegionalOfficeController@index')->name('regional-office-all-list');
    Route::get('regional-office',  ['uses' => 'RegionalOfficeController@list'])->name('regional-office.getdata');
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
    Route::get('regional-office/view/{id}', 'RegionalOfficeController@show')->name('regional-office-view');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
    Route::get('regional-office/create', 'RegionalOfficeController@create')->name('regional-office-create');
    Route::post('regional-office/create', 'RegionalOfficeController@store')->name('regional-office-store');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
    Route::get('regional-office/update/{id}', 'RegionalOfficeController@edit')->name('regional-office-edit');
    Route::post('regional-office/update/{id}', 'RegionalOfficeController@update')->name('regional-office-update');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
    Route::delete('regional-office/delete/{id}', 'RegionalOfficeController@destroy')->name('regional-office-destroy');
    });
    });

  //Districts
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('districts/all', 'DistrictsController@index')->name('districts-all-list');
  Route::get('districts',  ['uses' => 'DistrictsController@list'])->name('districts.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('districts/view/{id}', 'DistrictsController@show')->name('districts-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('districts/create', 'DistrictsController@create')->name('districts-create');
  Route::post('districts/create', 'DistrictsController@store')->name('districts-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('districts/update/{id}', 'DistrictsController@edit')->name('districts-edit');
  Route::post('districts/update/{id}', 'DistrictsController@update')->name('districts-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('districts/delete/{id}', 'DistrictsController@destroy')->name('districts-destroy');
  });
  });

  //ag-divisions
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('ag-divisions/all', 'AGDivisionsController@index')->name('ag-divisions-all-list');
  Route::get('ag-divisions',  ['uses' => 'AGDivisionsController@list'])->name('ag-divisions.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('ag-divisions/view/{id}', 'AGDivisionsController@show')->name('ag-divisions-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('ag-divisions/create', 'AGDivisionsController@create')->name('ag-divisions-create');
  Route::post('ag-divisions/create', 'AGDivisionsController@store')->name('ag-divisions-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('ag-divisions/update/{id}', 'AGDivisionsController@edit')->name('ag-divisions-edit');
  Route::post('ag-divisions/update/{id}', 'AGDivisionsController@update')->name('ag-divisions-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('ag-divisions/delete/{id}', 'AGDivisionsController@destroy')->name('ag-divisions-destroy');
  });
  });

   //gn-divisions
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('gn-divisions/all', 'GNDivisionsController@index')->name('gn-divisions-all-list');
  Route::get('gn-divisions',  ['uses' => 'GNDivisionsController@list'])->name('gn-divisions.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('gn-divisions/view/{id}', 'GNDivisionsController@show')->name('gn-divisions-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('gn-divisions/create', 'GNDivisionsController@create')->name('gn-divisions-create');
  Route::post('gn-divisions/create', 'GNDivisionsController@store')->name('gn-divisions-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('gn-divisions/update/{id}', 'GNDivisionsController@edit')->name('gn-divisions-edit');
  Route::post('gn-divisions/update/{id}', 'GNDivisionsController@update')->name('gn-divisions-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('gn-divisions/delete/{id}', 'GNDivisionsController@destroy')->name('gn-divisions-destroy');
  });
  });

//Villages
Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
        Route::get('villages/all', 'VillageController@index')->name('villages-all-list');
        Route::get('villages',  ['uses' => 'VillageController@list'])->name('villages.getdata');
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
        Route::get('villages/view/{id}', 'VillageController@show')->name('villages-view');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
        Route::get('villages/create', 'VillageController@create')->name('villages-create');
        Route::post('villages/create', 'VillageController@store')->name('villages-store');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
        Route::get('villages/update/{id}', 'VillageController@show')->name('villages-edit');
        Route::post('villages/update/{id}', 'VillageController@update')->name('villages-update');
    });
    Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
        Route::delete('villages/delete/{id}', 'VillageController@destroy')->name('villages-destroy');
    });
});

  //Users
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('users/all', 'UsersController@index')->name('users-all-list');
  Route::get('users',  ['uses' => 'UsersController@list'])->name('users.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('users/view/{id}', 'UsersController@show')->name('users-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('users/create', 'UsersController@create')->name('users-create');
  Route::post('users/create', 'UsersController@store')->name('users-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('users/update/{id}', 'UsersController@edit')->name('users-edit');
  Route::post('users/update/{id}', 'UsersController@update')->name('users-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('users/delete/{id}', 'UsersController@destroy')->name('users-destroy');
  });
  });




  //User Roles
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('user-roles/all', 'UserRolesController@index')->name('user-roles-all-list');
  Route::get('user-roles',  ['uses' => 'UserRolesController@list'])->name('userroles.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('user-roles/view/{id}', 'UserRolesController@show')->name('user-roles-view');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\CreatePermissionMiddleware'], function() {
  Route::get('user-roles/create', 'UserRolesController@create')->name('user-roles-create');
  Route::post('user-roles/create', 'UserRolesController@store')->name('user-roles-store');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\UpdatePermissionMiddleware'], function() {
  Route::get('user-roles/update/{id}', 'UserRolesController@edit')->name('user-roles-edit');
  Route::post('user-roles/update/{id}', 'UserRolesController@update')->name('user-roles-update');
  });
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\DeletePermissionMiddleware'], function() {
  Route::delete('user-roles/delete/{id}', 'UserRolesController@destroy')->name('user-roles-destroy');
  });
  });


  //activity-log
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\PermissionMiddleware'], function() {
  Route::get('activity-log/all', 'AuditController@index')->name('activity-log-all-list');
  Route::get('activity-log',  ['uses' => 'AuditController@list'])->name('activity-log.getdata');
  Route::group(['middleware' => 'App\Http\Middleware\Permissions\ViewPermissionMiddleware'], function() {
  Route::get('activity-log/view/{id}', 'AuditController@show')->name('activity-log-view');
  });

});
});
