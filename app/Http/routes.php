<?php
Route::get('/', 'AuthController@index');
Route::post('auth/login', 'AuthController@store');
Route::get('logout', 'AuthController@getLogout');
//UserController
Route::get('users','UserController');
Route::get('dashboard/','UserController@getDashboard');

Route::get('add', [
    'middleware' => 'auth',
    'uses' => 'UserController@getUserAdd'
]);
Route::post('users/saveUser','UserController@postSaveUser');
Route::get('list/','UserController@getIndex');
Route::get('profile','UserController@getProfile');
Route::get('/edit/{id}','UserController@getEdit');
Route::put('/update/{id}','UserController@putCheckupdate');
Route::get('changeStatus/{status}/{id}','UserController@getChangeStatus');
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
Route::get('changeStatusImport/{status}/{id}','ImportController@getChangeStatus');

//Stock Controller
Route::controller('stocks','StockController');
Route::get('products/{type}','StockController@getProducts');
Route::get('imports/','StockController@getImports');
Route::get('/stocks/infos','StockController@getStocks');
Route::post('saveStocks','StockController@postSaveStock');
Route::post('updateStocks/{id}','StockController@postUpdateStock');
Route::get('/quantity','StockController@getQuantity');


Route::get('stocks/add', [
    'as' => 'stock_add', 'uses' => 'StockController@getCreateStock'
]);

//Party Controller
Route::controller('parties','PartyController');
Route::post('saveParty','PartyController@postSaveParty');
Route::get('changeStatusParty/{status}/{id}','PartyController@getChangeStatus');

//Requisition Controller
Route::controller('requisitions','StockRequisitionController');
Route::post('/saveRequisition','StockRequisitionController@postSaveRequisition');
Route::get('/delete/{id}','StockRequisitionController@getDelete');
Route::post('updateRequisition/{id}','StockRequisitionController@postUpdateRequisition');
Route::post('/updateIssuedRequisition','StockRequisitionController@postUpdateIssuedRequisition');
Route::get('/del/{id}','StockRequisitionController@getDel');

//AccountCategory Controller
Route::controller('accountcategory','AccountCategoryController');
Route::post('saveAccountCategory','AccountCategoryController@postSaveAccountCategory');
Route::post('updateAccountCategory','AccountCategoryController@postUpdate');

//AccountName Controller
Route::controller('accountnames','AccountNameController');
Route::post('saveAccountName','AccountNameController@postSaveAccountName');
Route::post('updateAccountName/{id}','AccountNameController@postUpdate');

//Purchase Controller
Route::controller('purchases','PurchaseInvoiceController');
Route::post('savePurchases','PurchaseInvoiceController@postSavePurchaseInvoice');
Route::get('/details/{id}','PurchaseInvoiceController@getDetails');
Route::get('/delete/{id}','PurchaseInvoiceController@getDelete');
Route::get('/delete/{id}','PurchaseInvoiceController@getDelete');
Route::get('/make','PurchaseInvoiceController@getMake');
Route::post('/saveMakePurchase','PurchaseInvoiceController@postSaveMake');
Route::get('/categories/{id}','PurchaseInvoiceController@getCategories');
Route::get('/deleteTransaction/{id}','PurchaseInvoiceController@getDeleteTransaction');

//Expense Controller
Route::controller('expenses','ExpenseController');
Route::post('saveExpense','ExpenseController@postSaveExpense');
Route::post('updateExpense/{id}','ExpenseController@postUpdateExpense');
Route::get('/make','ExpenseController@getMake');
Route::post('/saveMake','ExpenseController@postSaveMake');
Route::get('/deleteTransaction/{id}','ExpenseController@getDeleteTransaction');

//Sale Controller
Route::controller('sales','SaleController');
Route::post('saveSale','SaleController@postSaveSales');
Route::post('updatePurchases/{id}','SaleController@updateSaleData');
Route::get('/details/{id}','SaleController@getDetails');
Route::get('/delete/{id}','SaleController@getDelete');
Route::get('/deleteDetail/{id}','SaleController@getDeleteDetail');
Route::get('/make/{id}','SaleController@getMake');
Route::post('/saveReceive','SaleController@postSaveReceive');
Route::get('/categories/{id}','SaleController@getCategories');
Route::get('/deleteTransaction/{id}','SaleController@getDeleteTransaction');

//StockInfo Controller
Route::controller('stockInfos','StockInfoController');
Route::post('saveStockInfo','StockInfoController@postSaveStockInfo');
Route::get('changeStatusStock/{status}/{id}','StockInfoController@getChangeStatus');

//Search Controller
Route::controller('searches','SearchController');
Route::get('/entry','SearchController@getEntry');
Route::get('/stock-products','SearchController@getStockProducts');
Route::get('/requisition','SearchController@getRequisition');
Route::post('/resultSearch','SearchController@postSearchResult');
Route::post('/resultRequisition','SearchController@postRequisitionResult');
Route::post('/stock-product-search-result','SearchController@postStockProductResult');

//Report Controller
Route::controller('reports','ReportController');
Route::get('/stocks','ReportController@getStocks');
Route::post('/stock-report','ReportController@postReportResult');
Route::post('/salesreport','ReportController@postSalesereportresult');
Route::post('/sales-details-report','ReportController@postSalesDetailsReportResult');
Route::post('/sales-due-report','ReportController@postSalesDueReportResult');
Route::get('/stocksproducts','ReportController@getStocksproducts');
Route::get('/stocksproductsresult','ReportController@postStocksproductsresult');

