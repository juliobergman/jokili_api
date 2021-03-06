<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected $maxSize = [
        'width' => '2880',
        'height' => '2880',
    ];

    protected $avsize = [
        'width' => '900',
        'height' => '900',
    ];

    public function useravatar(Request $request)
    {
        
        // Validation
        $this->validate($request, [
            'image' => 'required',
            'image.*' => 'mimes:png,jpg,jpeg',
        ]);

        $file = $request->file('image');
        if(!$file){
            return new JsonResponse(['message' => 'No File'], 200);
        }
        $user_id = $request->user()->id;
        $folder = 'user';

        // Create Directory if not Exists
        if(!file_exists(storage_path('app/public/avatars/'.$folder.'/'.$user_id.'/'))) {
            Storage::makeDirectory('/public/avatars/'.$folder.'/'.$user_id); //creates directory
        }

        // Delete Old Avatar
        $oldpath = UserData::select('avatar')->where('user_id', $user_id)->first();
        $deletepath = str_replace('/storage/', '/public/', $oldpath->avatar);
        $factory = strstr($deletepath, '/factory/');
        if(!$factory){
            Storage::delete($deletepath);
        };
        // Upload New Avatar
        $original_filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $random_number = rand(1,5000);
        $hash = hash('md5', $original_filename.time().$random_number);

        $ext = 'jpg';
        $img = Image::make($file->getRealPath());
        $name = $hash.'.'.$ext;
        $path = storage_path('app/public/avatars/'.$folder.'/'.$user_id.'/'.$name);
        $img->orientate();
        $img->fit($this->avsize['width'], $this->avsize['height'], function ($constraint) {
            $constraint->upsize();
        });
        $img->save($path, 60, $ext);
        $newpath = '/storage/avatars/'.$folder.'/'.$user_id.'/'.$name;
        $update = array('avatar' => $newpath);
        UserData::where('user_id', $user_id)->update($update);
        // Response
        return new JsonResponse(['message' => 'Success', 'path' => $newpath], 200);

    }


}
