<?php
Route::get('/', 'AuthController@index');
Route::post('auth/login', 'AuthController@store');
Route::get('logout', 'AuthController@getLogout');
//UserController
Route::get('users','UserController');
Route::get('dashboard/','UserController@getDashboard');
Route::get('add/','UserController@getUserAdd');
Route::post('users/saveUser','UserController@postSaveUser');
Route::get('list/','UserController@getIndex');
Route::get('profile','UserController@getProfile');
Route::get('/edit/{id}','UserController@getEdit');
Route::put('/update/{id}','UserController@putCheckupdate');
Route::get('changeStatus/{status}/{id}','UserController@postChangeStatus');
//Branch Controller
Route::controller('branches','BranchController');
Route::get('branchAdd','BranchController@getAddBranch');
Route::post('branch/saveBranch','BranchController@postSaveBranch');
Route::get('branchList','BranchController@getIndex');
Route::get('branch/edit/{id}','BranchController@getEdit');
Route::post('branch/update/{id}','BranchController@postUpdate');
Route::get('changeStatusBranch/{status}/{id}','BranchController@getChangeStatus');

//ProductCategory Controller
Route::controller('productCategories','ProductCategoryController');
Route::post('/saveProductCategory','ProductCategoryController@postSaveCategory');
Route::post('update/{id}','ProductCategoryController@postUpdateCategory');

//ProductSubCategory Controller
Route::controller('productsubcategories','ProductSubCategoryController');
Route::get('/productsubcategoriesAdd','ProductSubCategoryController@getCreate');
Route::get('/branchCategory/{id}','ProductSubCategoryController@getCategorybybranch');
Route::post('/saveProductSubCategory','ProductSubCategoryController@postSaveSubCategory');
Route::post('/updateProductSubCategory/{id}','ProductSubCategoryController@postUpdateSubCategory');

//Product Controller
Route::controller('products','ProductController');
Route::post('/saveProducts','ProductController@postSaveProduct');
Route::get('/category/{id}','ProductController@getCategory');
Route::get('/sub/{id}','ProductController@getSub');

//Import Controller
Route::controller('imports','ImportController');
Route::post('saveImport','ImportController@postSaveImport');
Route::post('saveImportDetails','ImportController@postSaveImportDetail');
Route::post('saveImportBankCost','ImportController@postSaveBankCost');
Route::post('saveImportCnfCost','ImportController@postSaveCnfCost');
Route::post('saveProformaInvoice','ImportController@postProformaInvoice');
Route::post('saveOtherCost','ImportController@postOtherCost');
Route::get('changeStatus/{status}/{id}','ImportController@getChange');
