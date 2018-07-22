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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/range/calendar', 'HomeController@rangeCalendar')->name('range.calendar');

//User
Route::get('/user', 'UserController@index')->name('user.index');
Route::post('/user/read', 'UserController@read')->name('user.read');
Route::post('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/update', 'UserController@update')->name('user.update');
Route::post('/user/delete', 'UserController@delete')->name('user.delete');
//Location
Route::get('/location', 'LocationController@index')->name('location.index');
Route::post('/location/read', 'LocationController@read')->name('location.read');
Route::post('/location/create', 'LocationController@create')->name('location.create');
Route::post('/location/update', 'LocationController@update')->name('location.update');
Route::post('/location/delete', 'LocationController@delete')->name('location.delete');
Route::post('/location/read/active', 'LocationController@readActive')->name('location.read.active');
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
Route::post('/hotel/room/search/active', 'HotelRoomTypeController@searchActive')->name('hotel.roomtype.searchActive');
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