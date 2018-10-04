<?php
Auth::routes();
//Home
Route::get('/', 'HomeController@index')->name('home');
//Dashboard
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
//User
Route::get('/user', 'UserController@index')->name('user.index');
Route::post('/user/read', 'UserController@read')->name('user.read');
Route::post('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/update', 'UserController@update')->name('user.update');
Route::post('/user/delete', 'UserController@delete')->name('user.delete');
Route::post('/user/search/client/active', 'UserController@getClientsActivesByName')->name('user.search.client.active');
//Market
Route::get('/market', 'MarketController@index')->name('market.index');
Route::post('/market/read', 'MarketController@read')->name('market.read');
Route::post('/market/create', 'MarketController@create')->name('market.create');
Route::post('/market/update', 'MarketController@update')->name('market.update');
Route::post('/market/delete', 'MarketController@delete')->name('market.delete');
//Location
Route::get('/location', 'LocationController@index')->name('location.index');
Route::post('/location/read', 'LocationController@read')->name('location.read');
Route::post('/location/create', 'LocationController@create')->name('location.create');
Route::post('/location/update', 'LocationController@update')->name('location.update');
Route::post('/location/delete', 'LocationController@delete')->name('location.delete');
Route::post('/location/read/active', 'LocationController@actives')->name('location.read.active');
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
//HotelRoomType
Route::get('/hotel/room', 'HotelRoomTypeController@index')->name('hotel.roomtype.index');
Route::post('/hotel/room/read', 'HotelRoomTypeController@read')->name('hotel.roomtype.read');
Route::post('/hotel/room/create', 'HotelRoomTypeController@create')->name('hotel.roomtype.create');
Route::post('/hotel/room/update', 'HotelRoomTypeController@update')->name('hotel.roomtype.update');
Route::post('/hotel/room/delete', 'HotelRoomTypeController@delete')->name('hotel.roomtype.delete');
Route::post('/hotel/room/duplicate', 'HotelRoomTypeController@duplicate')->name('hotel.roomtype.duplicate');
Route::post('/hotel/room/search/active', 'HotelRoomTypeController@getActivesByCodeOrName')->name('hotel.roomtype.search.active');
//HotelBoardType
Route::get('/hotel/board', 'HotelBoardTypeController@index')->name('hotel.boardtype.index');
Route::post('/hotel/board/read', 'HotelBoardTypeController@read')->name('hotel.boardtype.read');
Route::post('/hotel/board/create', 'HotelBoardTypeController@create')->name('hotel.boardtype.create');
Route::post('/hotel/board/update', 'HotelBoardTypeController@update')->name('hotel.boardtype.update');
Route::post('/hotel/board/delete', 'HotelBoardTypeController@delete')->name('hotel.boardtype.delete');
//HotelChain
Route::get('/hotel/chain', 'HotelChainController@index')->name('hotel.hotelchain.index');
Route::post('/hotel/chain/read', 'HotelChainController@read')->name('hotel.hotelchain.read');
Route::post('/hotel/chain/create', 'HotelChainController@create')->name('hotel.hotelchain.create');
Route::post('/hotel/chain/update', 'HotelChainController@update')->name('hotel.hotelchain.update');
Route::post('/hotel/chain/delete', 'HotelChainController@delete')->name('hotel.hotelchain.delete');
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
//HotelContract
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
Route::post('/contract/provider/hotel/search/active', 'HotelContractController@getActivesByName')->name('contract.provider.hotel.search.active');
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
//Client
Route::get('/client/contract/hotel', 'ClientController@hotel')->name('client.contract.hotel.index');
Route::post('/client/contract/hotel/read', 'ClientController@readHotel')->name('client.contract.hotel.read');
Route::get('/client/contract/hotel/settings', 'ClientController@settingsHotel')->name('client.contract.hotel.settings');
Route::post('/client/contract/hotel', 'ClientController@getContract')->name('client.contract.hotel');
Route::post('/client/contract/hotel/search', 'ClientController@getByName')->name('client.contract.hotel.search');
Route::post('/client/contract/hotel/settings/data', 'ClientController@settingsHotelData')->name('client.contract.hotel.settings.data');