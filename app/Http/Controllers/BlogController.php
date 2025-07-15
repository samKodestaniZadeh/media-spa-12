<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\Comment;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Blog $blog,Company $company,Route $route,Page $page,User $user)
    {
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart'):null;
        $cart = new Cart($oldCart);
        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $time = new Carbon;
        $menus = $route->where('name',$request->path())->first() && $route->where('name',$request->path())->first()?
            $route->where('name',$request->path())->first()->menus:null;
        $menu = Menu::where('parent_id',null)->where('status',4)->with('children','sections','routes')->get();

        $search =  $request->query->get('q');
        $blogs = $blog->with('image')->with('group')->with('type')->with('category')->withCount('comments')->withCount('views')->where('status',4)->paginate(9)->withQueryString();
        $companies = $user->with('image')->first();

        $results = $blog->where('id',0)->paginate(9)->WithQueryString();
        // dd($blogs,$results );
        if($companies)
        {
            if($search == null)
            {
                return  $blogs ? Inertia::render('Guest/Blog-index',['blogs'=>$blogs,'companies'=>$companies,'results'=>$results,'q'=>$search,'path'=>$request->path(),
                'menus' => $menus, 'menu' => $menu ]) : abort(404);
            }
            else if($search !== null)
            {
                if($search == null)
                {
                    $request->validate([
                    'search' => 'required',
                    ]);
                }

                $searchResults = $blog->search($search)->get();

                $ids = $searchResults->pluck('id')->toArray();

                $results = $blog->whereIn('id', $ids)
                    ->with('user', 'image','group','type','category')
                    ->withCount('comments','views')
                    ->whereIn('status', ['4'])
                    ->paginate(9)
                    ->withQueryString();
                // $results = $blog->search($search)->with('image')->with('group')->with('type')->with('category')->withCount('comments')->withCount('views')->paginate(9)->WithQueryString();
                // dd($results->total() > 1,$results);
                if( $results->total() > 1)
                {
                    return Inertia::render('Guest/Blog-index',['blogs'=> $blog->where('id',0)->paginate(9)->WithQueryString(),'results'=>$results,'companies'=>$companies,
                    'q'=>$search,'path'=>$request->path(),'menus' => $menus, 'menu' => $menu]);
                }
                elseif( $results->total() == 1)
                {
                    $results = $blog->with('image')->with('group')->with('type')->with('category')->withCount('comments')->withCount('views')->where('id',$results[0]->id)->paginate(9)->WithQueryString();
                    // dd($results);
                    return  Inertia::render('Guest/Blog-index',['blogs'=> $blog->where('id',0)->paginate(9)->WithQueryString(),'results'=>$results,'companies'=>$companies,
                    'q'=>$search,'path'=>$request->path(),'menus' => $menus, 'menu' => $menu]);
                }
                else
                {
                    $results = $blog->where('id',0)->paginate(9)->WithQueryString();
                    return Inertia::render('Guest/Blog-index',['blogs'=> $blog->where('id',0)->paginate(9)->WithQueryString() ,'results'=>$results,'companies'=>$companies,
                    'q'=>$search,'path'=>$request->path(),'menus' => $menus, 'menu' => $menu]);
                }

            }
        }
        else
        {
            return abort(503);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id,Company $company,Route $route,Comment $comment,User $user)
    {
        // Gate::authorize('create', $blog);

        $alert = $request->session()->has('alert') ? $request->session()->get('alert'):null;
        $blogs = Blog::with('comments')->with('user')->with('group')->with('favorite')->with('image')->withCount('comments')->where('slug',$id)->where('status',4)->first();
        if($blogs)
        {
            // dd($blogs);
        $comments = $comment->with('user')->where('commentable_id',$blogs->id)
            ->where('commentable_type',Blog::class)->where('parent_id',null)->where('status',4)->get();

        $replies = $comment->with('user')->where('commentable_id',$blogs->id)
            ->where('commentable_type',Blog::class)->where('parent_id','>',0)->where('status',4)->get();

        $product_count = $blogs->views()->count();
        $time = new Carbon;
        views($blogs)->cooldown($time->addDays(1))->record();
        $companies = $user->with('image')->first();
        $descriptions = $route->where('name','route(blog.show)')->first() && $route->where('name','route(blog.show)')->first()->descriptions?
            $route->where('name','route(blog.show)')->first()->descriptions->first():null;
        $latestBlogs = Blog::with('image')->latest()->take(3)->where([['id','<>',$blogs->id],['type',$blogs->type]])->get();
        // dd($blogs);
        return Inertia::render('Guest/Blog-show',['product'=>$blogs,'alert' => $alert,'product_count' => $product_count,'comments' => $comments,'replies'=>$replies,
        'companies' => $companies,'descriptions'=>$descriptions,'latestBlogs'=>$latestBlogs]);
        }
        else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        return abort(404);
    }
}
