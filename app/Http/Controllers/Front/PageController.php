<?php

namespace App\Http\Controllers\Front;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * TODO Not yet used!
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $page = Page::isPublish()->where('blade', 'front.pages.home')->firstOrFail();

        return view('front.pages.home', compact('page'));
    }

    public function show($id)
    {
        $page = Page::isPublish()->findOrFail($id);

        return view()->first([$page->blade, 'front.pages.default'], compact('page'));
    }
}
