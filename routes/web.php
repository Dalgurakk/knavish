<?php
Auth::routes();
//Home
Route::get('/', 'HomeController@index')->name('home');
//Dashboard
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
//User
Route::get('/administration/user', 'UserController@index')->name('administration.user.index');
Route::post('/administration/user/read', 'UserController@read')->name('administration.user.read');
Route::post('/administration/user/create', 'UserController@create')->name('administration.user.create');
Route::post('/administration/user/update', 'UserController@update')->name('administration.user.update');
Route::post('/administration/user/delete', 'UserController@delete')->name('administration.user.delete');
Route::post('/administration/user/search/client/active', 'UserController@getClientsActivesByName')->name('administration.user.search.client.active');
Route::get('/administration/user/export/excel', 'UserController@toExcel')->name('administration.user.excel');
//Market
Route::get('/administration/price-rate', 'MarketController@index')->name('administration.market.index');
Route::post('/administration/price-rate/read', 'MarketController@read')->name('administration.market.read');
Route::post('/administration/price-rate/create', 'MarketController@create')->name('administration.market.create');
Route::post('/administration/price-rate/update', 'MarketController@update')->name('administration.market.update');
Route::post('/administration/price-rate/delete', 'MarketController@delete')->name('administration.market.delete');
Route::get('/administration/price-rate/export/excel', 'MarketController@toExcel')->name('administration.market.excel');
//Location
Route::get('/administration/location', 'LocationController@index')->name('administration.location.index');
Route::post('/administration/location/read', 'LocationController@read')->name('administration.location.read');
Route::post('/administration/location/create', 'LocationController@create')->name('administration.location.create');
Route::post('/administration/location/update', 'LocationController@update')->name('administration.location.update');
Route::post('/administration/location/delete', 'LocationController@delete')->name('administration.location.delete');
Route::post('/administration/location/read/active', 'LocationController@actives')->name('administration.location.read.active');
Route::get('/administration/location/export/excel', 'LocationController@toExcel')->name('administration.location.excel');
//Trace
Route::get('/administration/trace', 'TraceController@index')->name('administration.trace.index');
Route::post('/administration/trace/read', 'TraceController@read')->name('administration.trace.read');
//CarModel
Route::get('/car/model', 'CarBrandController@index')->name('car.model.index');
Route::post('/car/model/read', 'CarBrandController@read')->name('car.model.read');
Route::post('/car/model/create', 'CarBrandController@create')->name('car.model.create');
Route::post('/car/model/update', 'CarBrandController@update')->name('car.model.update');
Route::post('/car/model/delete', 'CarBrandController@delete')->name('car.model.delete');
Route::post('/car/model/duplicate', 'CarBrandController@duplicate')->name('car.model.duplicate');
//CarCategory
Route::get('/car/category', 'CarCategoryController@index')->name('car.category.index');
Route::post('/car/category/read', 'CarCategoryController@read')->name('car.category.read');
Route::post('/car/category/create', 'CarCategoryController@create')->name('car.category.create');
Route::post('/car/category/update', 'CarCategoryController@update')->name('car.category.update');
Route::post('/car/category/delete', 'CarCategoryController@delete')->name('car.category.delete');
//HotelPaxType
Route::get('/hotel/pax', 'HotelPaxTypeController@index')->name('hotel.paxtype.index');
Route::post('/hotel/pax/read', 'HotelPaxTypeController@read')->name('hotel.paxtype.read');
Route::post('/hotel/pax/create', 'HotelPaxTypeController@create')->name('hotel.paxtype.create');
Route::post('/hotel/pax/update', 'HotelPaxTypeController@update')->name('hotel.paxtype.update');
Route::post('/hotel/pax/delete', 'HotelPaxTypeController@delete')->name('hotel.paxtype.delete');
Route::get('/hotel/pax/export/excel', 'HotelPaxTypeController@toExcel')->name('hotel.paxtype.excel');
//HotelRoomType
Route::get('/hotel/room', 'HotelRoomTypeController@index')->name('hotel.roomtype.index');
Route::post('/hotel/room/read', 'HotelRoomTypeController@read')->name('hotel.roomtype.read');
Route::post('/hotel/room/create', 'HotelRoomTypeController@create')->name('hotel.roomtype.create');
Route::post('/hotel/room/update', 'HotelRoomTypeController@update')->name('hotel.roomtype.update');
Route::post('/hotel/room/delete', 'HotelRoomTypeController@delete')->name('hotel.roomtype.delete');
Route::post('/hotel/room/duplicate', 'HotelRoomTypeController@duplicate')->name('hotel.roomtype.duplicate');
Route::post('/hotel/room/search/active', 'HotelRoomTypeController@getActivesByCodeOrName')->name('hotel.roomtype.search.active');
Route::get('/hotel/room/export/excel', 'HotelRoomTypeController@toExcel')->name('hotel.roomtype.excel');
//HotelBoardType
Route::get('/hotel/board', 'HotelBoardTypeController@index')->name('hotel.boardtype.index');
Route::post('/hotel/board/read', 'HotelBoardTypeController@read')->name('hotel.boardtype.read');
Route::post('/hotel/board/create', 'HotelBoardTypeController@create')->name('hotel.boardtype.create');
Route::post('/hotel/board/update', 'HotelBoardTypeController@update')->name('hotel.boardtype.update');
Route::post('/hotel/board/delete', 'HotelBoardTypeController@delete')->name('hotel.boardtype.delete');
Route::get('/hotel/board/export/excel', 'HotelBoardTypeController@toExcel')->name('hotel.boardtype.excel');
//HotelChain
Route::get('/hotel/chain', 'HotelChainController@index')->name('hotel.hotelchain.index');
Route::post('/hotel/chain/read', 'HotelChainController@read')->name('hotel.hotelchain.read');
Route::post('/hotel/chain/create', 'HotelChainController@create')->name('hotel.hotelchain.create');
Route::post('/hotel/chain/update', 'HotelChainController@update')->name('hotel.hotelchain.update');
Route::post('/hotel/chain/delete', 'HotelChainController@delete')->name('hotel.hotelchain.delete');
Route::get('/hotel/chain/export/excel', 'HotelChainController@toExcel')->name('hotel.hotelchain.excel');
//Hotel
Route::get('/hotel', 'HotelController@index')->name('hotel.index');
Route::post('/hotel/read', 'HotelController@read')->name('hotel.read');
Route::post('/hotel/create', 'HotelController@create')->name('hotel.create');
Route::post('/hotel/update', 'HotelController@update')->name('hotel.update');
Route::post('/hotel/delete', 'HotelController@delete')->name('hotel.delete');
Route::post('/hotel/upload/image', 'HotelController@uploadImage')->name('hotel.upload.image');
Route::post('/hotel/delete/image', 'HotelController@deleteImage')->name('hotel.delete.image');
Route::post('/hotel/images', 'HotelController@images')->name('hotel.images');
Route::post('/hotel/search/active', 'HotelController@getActivesByName')->name('hotel.search.active');
Route::post('/hotel/search/contract', 'HotelController@getContractsByName')->name('hotel.search.contract');
Route::get('/hotel/excel', 'HotelController@toExcel')->name('hotel.excel');
//HotelContractProvider
Route::get('/contract/provider/hotel', 'HotelContractController@index')->name('contract.provider.hotel.index');
Route::post('/contract/provider/hotel/read', 'HotelContractController@read')->name('contract.provider.hotel.read');
Route::post('/contract/provider/hotel/create', 'HotelContractController@create')->name('contract.provider.hotel.create');
Route::post('/contract/provider/hotel/update', 'HotelContractController@update')->name('contract.provider.hotel.update');
Route::post('/contract/provider/hotel/delete', 'HotelContractController@delete')->name('contract.provider.hotel.delete');
Route::get('/contract/provider/hotel/settings', 'HotelContractController@settings')->name('contract.provider.hotel.settings');
Route::post('/contract/provider/hotel', 'HotelContractController@getContract')->name('contract.provider.hotel');
Route::post('/contract/provider/hotel/search', 'HotelContractController@getByName')->name('contract.provider.hotel.search');
Route::post('/contract/provider/hotel/settings/save', 'HotelContractController@saveSettings')->name('contract.provider.hotel.settings.save');
Route::post('/contract/provider/hotel/settings/data', 'HotelContractController@settingsByContract')->name('contract.provider.hotel.settings.data');
Route::post('/contract/provider/hotel/settings/import/costFromPriceRate', 'HotelContractController@importCostFromPriceRate')->name('contract.provider.hotel.settings.import.costFromPriceRate');
Route::post('/contract/provider/hotel/settings/import/costFromRoomType', 'HotelContractController@importCostFromRoomType')->name('contract.provider.hotel.settings.import.costFromRoomType');
Route::post('/contract/provider/hotel/search/active', 'HotelContractController@getActivesByName')->name('contract.provider.hotel.search.active');
Route::get('/contract/provider/hotel/search/excel', 'HotelContractController@toExcel')->name('contract.provider.hotel.excel');
Route::post('/contract/provider/hotel/duplicate', 'HotelContractController@duplicate')->name('contract.provider.hotel.duplicate');
//HotelContractClient
Route::get('/contract/client/hotel', 'HotelContractClientController@index')->name('contract.client.hotel.index');
Route::post('/contract/client/hotel/create', 'HotelContractClientController@create')->name('contract.client.hotel.create');
Route::post('/contract/client/hotel/read', 'HotelContractClientController@read')->name('contract.client.hotel.read');
Route::post('/contract/client/hotel/update', 'HotelContractClientController@update')->name('contract.client.hotel.update');
Route::post('/contract/client/hotel/delete', 'HotelContractClientController@delete')->name('contract.client.hotel.delete');
Route::get('/contract/client/hotel/settings', 'HotelContractClientController@settings')->name('contract.client.hotel.settings');
Route::post('/contract/client/hotel', 'HotelContractClientController@getContract')->name('contract.client.hotel');
Route::post('/contract/client/hotel/search', 'HotelContractClientController@getByName')->name('contract.client.hotel.search');
Route::post('/contract/client/hotel/settings/data', 'HotelContractClientController@settingsByContract')->name('contract.client.hotel.settings.data');
Route::post('/contract/client/hotel/settings/save', 'HotelContractClientController@saveSettings')->name('contract.client.hotel.settings.save');
Route::get('/contract/client/hotel/search/excel', 'HotelContractClientController@toExcel')->name('contract.client.hotel.excel');
//Client
Route::get('/client/hotel', 'ClientHotelController@hotel')->name('client.hotel.index');
Route::post('/client/hotel/read', 'ClientHotelController@readHotel')->name('client.hotel.read');
Route::get('/client/hotel/settings', 'ClientHotelController@settingsHotel')->name('client.hotel.settings');
Route::post('/client/hotel', 'ClientHotelController@getContract')->name('client.hotel');
Route::post('/client/hotel/search', 'ClientHotelController@getByName')->name('client.hotel.search');
Route::post('/client/hotel/settings/data', 'ClientHotelController@settingsHotelData')->name('client.hotel.settings.data');
Route::get('/client/hotel/excel', 'ClientHotelController@toExcel')->name('client.hotel.excel');