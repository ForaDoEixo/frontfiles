<?php

namespace FrontFiles\Http\Controllers;

use FrontFiles\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideosController extends Controller
{
    /**
     * VideosController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::where('user_id', auth()->user()->id)->get();

        if(request()->wantsJson())
            return $videos;

        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'video' => 'required',
        ]);

        \Log::info($request);

        if ($request->hasFile('video')) {
            if ($request->file('video')->isValid()) {

                $file = $request->file('video');
                $extension = $file->getClientOriginalExtension();
                $name = $request->get('title') . uniqid() . '.' . $extension;
                $destinationPath = public_path('/videos/');
                //$file = $request->file('video')->move($destinationPath,$name);

                $video = new Video();
                $video->title = $request->title;
                $video->description = $request->description;
                $video->user_id = \Auth::user()->id;
                $video->filename=$name;
                $video->short_id=1;
                $video->what=$request->what;
                $video->where=$request->where;
                $video->who=$request->who;
                $video->when=$request->when;

                // Copy to remote
                ini_set('memory_limit', '-1');
                $path =  $request->file('video')->storeAs(
                    'usercontents',$name, 'azure'
                );

                $url = config('filesystems.disks.azure.url').$path;
                $video->url=$url;
                $video->save();
                if(request()->wantsJson())
                    return response()->json(array('status' => 'Video uploaded!','data'=>$video));

                return redirect('/video/upload')->with(array('status'=>'Video uploaded!'));
            }
            if(request()->wantsJson())
                return response()->json(array('error' => 'Video file is not valid'));
            return redirect('/video/upload')->with(array('error'=>'Video file is not valid'));
        }
        if(request()->wantsJson())
            return response()->json(array('error' => 'Video file is not available'));
        return redirect('/video/upload')->with(array('error'=>'Video file is not available'));
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
