<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('feedback', function () {
    return view('feedback');
});
Route::get('sm', function () {
    \Spatie\Sitemap\Sitemap::create("http://recovery.diandi.org")
        ->add(\Spatie\Sitemap\Tags\Url::create('/')
//            ->setLastModificationDate(\Illuminate\Support\Carbon::yesterday())
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_ALWAYS)
//            ->setPriority(0.1)
            )
        ->add(\Spatie\Sitemap\Tags\Url::create('/feedback')
//            ->setLastModificationDate(\Illuminate\Support\Carbon::yesterday())
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.1)
        )
        -> writeToFile("sitemap.xml");
    //\Spatie\Sitemap\Sitemap::create("http://diandi.org")->writeToDisk('public','sitemap.xml');
});
