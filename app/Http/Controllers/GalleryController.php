<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class GalleryController extends Controller {

    //List Galleries
    public function index() {
        //Get all galleries
        $galleries = DB::table('galleries')->get();

        //Render view
        return view('gallery/index', compact('galleries'));
    }

    //Show the create form
    public function create() {
        if(!Auth::check()){
            return \redirect::route('gallery.index');
        }
        return view('gallery/create');
    }

    //Store gallery
    public function store(Request $request) {
        //Get request input
        $name = $request->input('name');
        $description = $request->input('description');
        $cover_image = $request->file('cover_image');
        $owner_id = 1;
        //Check image upload
        if($cover_image) {
            $cover_image_filename = $cover_image->getClientOriginalName();
            $cover_image->move(public_path('images'), $cover_image_filename);
        } else {
            $cover_image_filename = 'noimage.jgp';
        }

        //Insert into database
        DB::table('galleries')->insert(
            [
                'name' => $name,
                'description' => $description,
                'cover_image' => $cover_image_filename,
                'owner_id' => $owner_id
            ]
            );

            //Set messages
            \Session::flash('message', 'Gallery Added');

            //Redirect
            return \Redirect::route('gallery.index');
    }

    //Show photos
    public function show($id) {
        // Get gallery
        $gallery = DB::table('galleries')->where('id', $id)->first();
        // Get photos
        $photos = DB::table('photos')->where('gallery_id', $id)->get();
        //Render view
        return view('gallery/show', compact('gallery', 'photos'));
    }
}