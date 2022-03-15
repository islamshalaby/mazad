<?php

namespace App\Http\Controllers\Admin\Ads\categories_ads;

use App\Http\Controllers\Admin\AdminController;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\SubThreeCategory;
use App\Categories_ad;

class SubThreeCategoriesAdsController extends AdminController
{

    public function index($id)
    {
        $data = SubThreeCategory::where('sub_category_id' , $id)->where('deleted' , 0)->orderBy('sort' , 'asc')->get();
        $cat_id = $id ;
        return view('admin.ads.categories_ads.sub_catyegory.sub_two_category.sub_three_category.index' , compact('data','cat_id'));
    }
    public function create($id)
    {
        return view('admin.ads.categories_ads.sub_catyegory.sub_two_category.sub_three_category.create' , compact('id'));
    }
    public function create_all($id)
    {
        return view('admin.ads.categories_ads.sub_catyegory.sub_two_category.sub_three_category.create' ,compact('id'));
    }

    public function store(Request $request)
    {

        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null);
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;

        $data['image'] = $image_new_name;
        $data['cat_id'] = $request->id;
        $data['type'] = 'sub_three_category';
        $data['content'] = $request->content;
        if($request->ad_type == 'out'){
            $data['ad_type'] = 'link';
        }else{
            $data['ad_type'] = 'id';
        }
        Categories_ad::create($data);

        session()->flash('success', trans('messages.added_s'));
        return redirect(route('sub_three_cat_ads.show',$request->id));
    }
    public function store_all_categories(Request $request,$id)
    {
        $cats = SubThreeCategory::where('sub_category_id' , $id)->where('deleted' , 0)->orderBy('id' , 'desc')->get();
        if(count($cats) > 0) {
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id . '.' . $image_format;
            foreach ($cats as $key => $row) {
                $data['image'] = $image_new_name;
                $data['cat_id'] = $row->id;
                $data['type'] = 'sub_three_category';
                $data['content'] = $request->content;
                if($request->ad_type == 'out'){
                    $data['ad_type'] = 'link';
                }else{
                    $data['ad_type'] = 'id';
                }
                Categories_ad::create($data);
            }
            session()->flash('success', trans('messages.added_s'));
        }else{
            session()->flash('danger', trans('messages.not_added_s'));
        }
        return redirect(route('sub_three_cat_ads.index',$request->id));
    }

    public function show($id)
    {
        $data = Categories_ad::where('cat_id',$id)->where('type','sub_three_category')->where('deleted' , '0')->orderBy('id' , 'desc')->get();
        return view('admin.ads.categories_ads.sub_catyegory.sub_two_category.sub_three_category.ads' , compact('data','id'));
    }
}