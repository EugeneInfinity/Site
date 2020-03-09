<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageRequest;
use App\Managers\MetaTagManager;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize("static-page.read");

        $pages = Page::orderBy('id', 'desc')->paginate();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        $this->authorize("static-page.create");

        $page = Page::create($request->only('name', 'body', 'blade', 'publish'));
        $page->generateUrlAlias($request->name);

        $destination = $request->get('destination', route('admin.pages.edit', $page));
        return redirect()->to($destination)
            ->with('success', trans('notifications.store.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize("static-page.update");

        $page = Page::findOrFail($id);

        return view('admin.pages.edit', compact('page'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, $id)
    {
        $this->authorize("static-page.update");

        $page = Page::findOrFail($id);
        $page->update($request->only('name', 'body', 'blade', 'publish'));

        $destination = $request->get('destination', route('admin.pages.edit', $page));
        return redirect()->to($destination)
            ->with('success', trans('notifications.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize("static-page.delete");

        $page = Page::findOrFail($id);
        $page->delete();

        $destination = $request->session()->pull('destination', route('admin.pages.index'));
        return redirect()->to($destination)
            ->with('success', trans('notifications.destroy.success'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function seo($id)
    {
        $this->authorize("static-page.update");

        $page = Page::findOrFail($id);
        $tab = 'seo';

        return view('admin.pages.edit', compact('page', 'tab'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function seoSave(Request $request, $id, MetaTagManager $metaTagMng)
    {
        $this->authorize("static-page.update");

        $page = Page::findOrFail($id);

        if ($request->has('url_alias')) {
            $page->updateOrCreateUrlAlias($request->url_alias);
        }

        $metaTagMng->updateOrCreateForEntity($page, $request);

        $destination = $request->session()->pull('destination', route('admin.pages.edit', $page));
        return redirect()->to($destination)
            ->with('success', trans('notifications.update.success'));
    }
}
