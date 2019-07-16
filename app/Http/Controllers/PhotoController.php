<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class PhotoController extends Controller
{
    private $table = 'photos';
    //Show the create form
    public function create($gallery_id) {
        if(!Auth::check()){
            return \redirect::route('gallery.index');
        }
        
        // Render view
        return view('photo/create', compact('gallery_id'));
    }

    //Store photo
    public function store(Request $request) {
        //Get request input
        $gallery_id = $request->input('gallery_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $location = $request->input('location');
        $image = $request->file('image');
        $owner_id = 1;
        //Check image upload
        if($image) {
            $image_filename = $image->getClientOriginalName();
            $image->move(public_path('images'), $image_filename);
        } else {
            $image_filename = 'noimage.jgp';
        }

        //Insert into database
        DB::table($this->table)->insert(
            [
                'gallery_id' => $gallery_id,
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'image' => $image_filename,
                'owner_id' => $owner_id
            ]
            );

            //Set messages
            \Session::flash('message', 'Photo uploaded');

            //Redirect
            return \Redirect::route('gallery.show', array('id' => $gallery_id));
    }

    //Show photo details
    public function details($id) {
        //Get photo
        $photo = DB::table($this->table)->where('id', $id)->first();
        //Render view
        return view('photo/details', compact('photo'));
    }
}
