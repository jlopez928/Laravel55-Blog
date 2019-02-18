<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

//Request de Post
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;

use App\Http\Controllers\Controller;

//Facade para guardar los archivos
use Illuminate\Support\Facades\Storage;

use App\Post;
use App\Category;
use App\Tag;

class PostController extends Controller
{
    
    public function __construct() {

        $this->middleware('auth');

    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$posts = Post::orderBy('id', 'DESC')->paginate();
        
        $posts = Post::orderBy('id', 'DESC')
            ->where('user_id', auth()->user()->id)
            ->paginate();

        //return $posts;
        //return view('admin.posts.index', ['posts' => $posts];

        return view('admin.posts.index', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        //Listar categorias
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id');
        
        //Listar etiquetas
        $tags = Tag::orderBy('name', 'ASC')->get();

        return view('admin.posts.create', compact('categories', 'tags'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        //
        $post = Post::create($request->all());

        //Guardar Imagen
        if($request->file('file')) {
            $path = Storage::disk('public')->put('image', $request->file('file'));
            $post->fill(['file' => asset($path)])->save();
        }

        //Sincronizando tags con post
        //Con attach
        $post->tags()->attach($request->get('tags'));
        //Con Sync
        //$post->tags()->sync($request->get('tags'));

        return redirect()->route('posts.edit', $post->id)
                ->with('info', 'Post creado con éxito');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);

        //Aplicar las politicas de acceso al usuario
        $this->authorize('pass', $post);

        return view('admin.posts.show', compact('post'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);

        //Aplicar las politicas de acceso al usuario
        $this->authorize('pass', $post);

        //Listar categorias
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id');
        
        //Listar etiquetas
        $tags = Tag::orderBy('name', 'ASC')->get();
        
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, $id)
    {
        //
        $post = Post::find($id);

        //Aplicar las politicas de acceso al usuario
        $this->authorize('pass', $post);

        $post->fill($request->all())->save();

        //Guardar Imagen
        if($request->file('file')) {
            $path = Storage::disk('public')->put('image', $request->file('file'));
            $post->fill(['file' => asset($path)])->save();
        }
        
        //Sincronizando tags con post
        //Con Sync
        $post->tags()->sync($request->get('tags'));

        return redirect()->route('posts.edit', $post->id)
                ->with('info', 'Post actualizado con éxito');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        
        //Aplicar las politicas de acceso al usuario
        $this->authorize('pass', $post);
        
        $post->delete();

        //Post::find($id)->delete();

        return back()->with('info', 'Post eliminado con éxito');

    }

}
