<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'contractList'])->name('root');
 
Route::get('/Contract-List', [App\Http\Controllers\HomeController::class, 'contractList'])->name('contract.list');
 
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.list');
 
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');

Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

 
Route::post('/save-project', [App\Http\Controllers\ProjectController::class, 'saveProject']);

Route::post('/edit-variable/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('edit-variable');


// routes/web.php
Route::get('/arifurtable', [App\Http\Controllers\ProjectController::class, 'showProjects']);

// for Contractlist page  in office -----------------
 
Route::get('/Contract-History', [App\Http\Controllers\ContractHistoryController::class, 'showAll']);

Route::delete('/sales-list-draft/{id}', [App\Http\Controllers\ContractHistoryController::class, 'destroy']);

Route::get('/insert-mandatory-status', [App\Http\Controllers\EditContractListController::class, 'insertMandatoryStatus']);


Route::get('/check-unique', [App\Http\Controllers\SalesDetailsController::class, 'checkUnique']);

Route::get('/display-checked-products', [App\Http\Controllers\SalesDetailsController::class, 'displayChecked']);
 
Route::get('/sales.details.displayChecked', [App\Http\Controllers\SalesDetailsController::class, 'displayChecked']);

 
Route::get('/contract/save-shareable-link/{id}', [App\Http\Controllers\ContractHistoryController::class, 'saveShareableLink']);


Route::get('/contract/get-signed-pdf-url/{id}', [App\Http\Controllers\ContractHistoryController::class, 'getSignedPdfUrl']);


//-------
Route::post('/update-product-status', [App\Http\Controllers\SalesDetailsController::class, 'updateProductStatus']);

Route::get('/Sales-Details', [App\Http\Controllers\SalesDetailsController::class, 'Productshow']);

Route::get('/Sales-Lists', [App\Http\Controllers\SalesDetailsController::class, 'show']);

Route::post('/save-product-to-sales', [App\Http\Controllers\SalesDetailsController::class, 'saveProductToSales']);
 
Route::post('/save-sales-details/{id?}', [App\Http\Controllers\SalesDetailsController::class, 'save']);


Route::get('/Sales-Details/{id}', [App\Http\Controllers\SalesDetailsController::class, 'editSales']);

Route::delete('/delete-sales/{id}', [App\Http\Controllers\SalesDetailsController::class,'destroy']);


Route::get('/createpricewithupdate', [App\Http\Controllers\PriceListController::class, 'createpricewithupdate'])->name('createpricewithupdate');

Route::post('/getMandatoryFieldValues', [App\Http\Controllers\EditContractListController::class, 'getMandatoryFieldValues']);
 
Route::post('/delete-selected-product', [App\Http\Controllers\EditContractListController::class, 'deleteSelectedProduct']);

Route::post('/save-selected-product', [App\Http\Controllers\EditContractListController::class, 'saveSelectedProduct']);

Route::get('/get-product-id', [App\Http\Controllers\EditContractListController::class, 'getProductId']);

Route::get('/get-price-id', [App\Http\Controllers\EditContractListController::class, 'getPriceId'])->name('get.price.id');

Route::post('/insert-price-id', [App\Http\Controllers\EditContractListController::class, 'insertPriceId'])->name('insert.price.id');
Route::post('/delete-price-id', [App\Http\Controllers\EditContractListController::class, 'deletePriceId'])->name('delete.price.id');

Route::delete('/price-lists/{id}', [App\Http\Controllers\PriceListController::class,'destroy'])->name('price-lists.destroy');

Route::get('/edit-price/{id}', [App\Http\Controllers\PriceListController::class, 'editPrice'])->name('edit.price');

Route::post('/update-price/{id}', [App\Http\Controllers\PriceListController::class, 'updatePrice'])->name('update.price');

Route::get('/Price-List', [App\Http\Controllers\PriceListController::class, 'getpricedata'])->name('get.data');

Route::post('/save-price-list', [App\Http\Controllers\PriceListController::class, 'savePriceList'])->name('save.price.list');
 
//land on new update page when create new contract
Route::get('/createcontractwithupdatepage', [App\Http\Controllers\ContractController::class, 'createContractWithUpdatePage'])->name('createcontractwithupdatepage');

//to see checked variable from database
Route::post('/checkedVariable', [App\Http\Controllers\EditContractListController::class, 'checkedVariable']);

Route::post('/HowmanyVariable', [App\Http\Controllers\VariableListController::class,'countVariableIDs']);

// to insert into contractvariablecheckbox when variable pop up is checked 
Route::post('/insert-contract-variable', [App\Http\Controllers\EditContractListController::class, 'insertContractVariable']);
 
Route::post('/delete-contract-variable', [App\Http\Controllers\EditContractListController::class,'deleteContractVariable']);

Route::get('/Sales-Performence', [App\Http\Controllers\SalesPerformanceController::class, 'index']);
 
Route::post('/header-and-footer/save', [App\Http\Controllers\HeaderAndFooterController::class, 'save'])->name('header-and-footer.save');

Route::post('/header-and-footer/{id}', [App\Http\Controllers\HeaderAndFooterController::class, 'deleteContract'])->name('entry.delete');
 
Route::post('/header-and-footer/update/{id}', [App\Http\Controllers\HeaderAndFooterController::class, 'update'])->name('header-and-footer.update');

Route::get('/HeaderAndFooter', [App\Http\Controllers\HeaderAndFooterController::class, 'show']);

//for generate preview pdf 

Route::post('/generate-pdf', [App\Http\Controllers\createContractController::class, 'generatePDF']);
 
 Route::delete('contracts/{id}', [ App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.destroy');
 
Route::get('/edit-contract-list/{id}', [App\Http\Controllers\EditContractListController::class, 'edit']);
 
Route::get('/edit-contract-list', [App\Http\Controllers\EditContractListController::class, 'showvariable']);

Route::post('/get-pdf-sales', [App\Http\Controllers\ProductController::class, 'generatePdfforSales']);

Route::post('/saveHeaderFooter', [App\Http\Controllers\HeaderAndFooterController::class, 'saveHeaderFooter']);
 

Route::post('/get-pdf-sales-new', [App\Http\Controllers\HeaderAndFooterController::class, 'generatePdf']);


Route::post('/delete-pdf', [App\Http\Controllers\ProductController::class, 'deletePdf']);
 
Route::post('/edit-contract-list/update', [App\Http\Controllers\EditContractListController::class, 'updateContract'])->name('edit-contract-list.update');
 
Route::get('/Contract-List', [App\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');

Route::post('/update-variable/{id}', [App\Http\Controllers\VariableListController::class, 'updateVariable']);
 
// for Product list page  in office 
Route::get('/Product-List', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'productforcreatepage'])->name('product.index');

Route::post('/save-product', [App\Http\Controllers\ProductController::class, 'saveProduct']);

//Variable-List page  to show all variable
Route::get('/Variable-List', [ App\Http\Controllers\VariableListController::class, 'index'])->name('variable.index');

// for delete variable list row
 
Route::post('/delete-contract/{id}', [App\Http\Controllers\VariableListController::class, 'deleteContract'])->name('contract.delete');

Route::post('/product-contract/{id}', [App\Http\Controllers\ProductController::class, 'deleteproduct'])->name('product.delete');

Route::post('/save-variable', [App\Http\Controllers\VariableListController::class, 'saveVariable']);

Route::get('/check-variable-name', [App\Http\Controllers\VariableListController::class, 'checkVariableName']);

//Route::post('/save-variable', [App\Http\Controllers\VariableListController::class, 'saveProduct']);
Route::get('/fetch-variables', [App\Http\Controllers\VariableListController::class, 'fetchVariables']);

//to pass variables to createcontract.blade.php
Route::get('/createcontract', [App\Http\Controllers\createContractController::class, 'show'])->name('createcontract.show');

 //header footer entries

Route::get('/createvariablecontract', [App\Http\Controllers\ContractController::class, 'show']);

Route::get('/products', [App\Http\Controllers\createContractController::class, 'productforcreatepage'])->name('createcontract.productforcreatepage');

Route::post('/createcontract', [App\Http\Controllers\CreateContractController::class, 'store'])->name('createcontract.store');

//main one for save contract
Route::post('/savecontract', [App\Http\Controllers\ContractController::class, 'savecontract']);

//for image 
Route::post('/upload', [App\Http\Controllers\ContractController::class, 'upload'])->name('ckeditor.upload');

// edit purposes 
// View Contract History
Route::get('/contracts/{id}/history', [App\Http\Controllers\ContractController::class, 'history'])->name('contracts.history');

Route::post('/save', [App\Http\Controllers\createcontractController::class, 'save']);

Route::post('/updatecontract', [App\Http\Controllers\ContractController::class, 'updatecontract']);
 
Route::get('list',[App\Http\Controllers\MemberController::class,'show']);

Route::post('/update-project/{id}', [App\Http\Controllers\ProjectController::class, 'updateProject']);

Route::get('/delete/{id}', 'App\Http\Controllers\ProjectController@deleteProject');
 
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
 
