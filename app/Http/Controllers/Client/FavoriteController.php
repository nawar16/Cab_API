<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function favorites(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $data = $client->favorites;
            if($data)
            {
                $data = $data->map(function($d){
                    return [
                        'id' => $d['id'],
                        'title' => $d['title'],
                        'geom' => [
                            'coordinates' => $d['coordinate']
                        ]
                    ];
                });
                return success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'err' => 'No Favorites'
                ];
                return response()->json($data);
            }
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function new_favorites(Request $request)
    {
        $this->validate($request, [
            'f_item.title' => 'required',
            'f_item.coor' => 'required'
        ]);
        try{
            $client = auth('clients')->user();
            $fav = $request['f_item'];
            $new_fav = $client->favorites()->create([
                'title' => $fav['title'],
                'coordinate' => $fav['coor'],
                'site_address' => "NoDirection"
            ]);
            if($new_fav)
            {
                $data = ['msg' => 'Favorite Added'];
                return success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'err' => 'Server error'
                ];
                return response()->json($data, 500);
            }
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function delete_favorite(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $fav = Favorite::find($request['fav']);
            if($fav) {
                $fav->timestamps = false;
                $deleted = $fav->delete();
                if ($deleted) {
                    $fav->deleted_by = $client->id;
                    $fav->deleted_at = \Carbon\Carbon::now();
                    $fav->save();
                    $data = ['msg' => 'Favorite Deleted'];
                    return success_response($data);
                }
            }
            return error_response(new \Illuminate\Database\Eloquent\ModelNotFoundException);
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function update_favorite(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $fav = Favorite::find($request['fav']);
            if($fav) {
                $new_fav = $fav->update([
                    'title' => $request['newTitle'] 
                ]);
                if ($new_fav) {
                    $fav->updated_by = $client->id;
                    $fav->updated_at = \Carbon\Carbon::now();
                    $fav->save();
                    $data = ['msg' => 'Favorite Updated'];
                    return success_response($data);
                }
            }
            return error_response(new \Illuminate\Database\Eloquent\ModelNotFoundException);
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
}
