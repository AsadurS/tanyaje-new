<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
//use App\manufacturers_info;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Makes extends Model
{
  public function __construct()
  {
      $varsetting = new SiteSettingController();
      $this->varsetting = $varsetting;
  }
    //
    use Sortable;
    /*public function manufacturers_info(){
        return $this->hasOne('App\manufacturers_info');
    }

    public function images(){
        return $this->belongsTo('App\Images');
    }*/

    //public $sortableAs = ['manufacturers_url'];
    public $sortable = ['make_id', 'make_name','created_at','is_feature','updated_at'];

    public function paginator(){
        $make =  Makes::sortable(['make_id'=>'desc'])
        ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'makes.image')
        ->select('makes.make_id as id', 'is_feature','makes.make_name as name', 'categoryTable.path as imgpath')
        ->where('categoryTable.image_type','ACTUAL')
        ->orwhere('categoryTable.image_type','=',null)->paginate(20);
        return $make;
    }

    /*public function getter($language_id){
         if($language_id == null){
           $language_id = '1';
         }
         $manufacturers =  Manufacturers::sortable(['manufacturers_id'=>'desc'])->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                                   ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                                   ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                                   ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                                   ->where('manufacturers_info.languages_id', $language_id)->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->get();
        return $manufacturers;
    }*/

    public function insert($request){

          //$slug = $request->name;
          $date_added	= date('y-m-d h:i:s');
          //$languages_id 	=  '1';
          /*$slug_count = 0;

          do{
              if($slug_count==0){
                  $currentSlug = $this->varsetting->slugify($request->name);
              }else{
                  $currentSlug = $this->varsetting->slugify($request->name.'-'.$slug_count);
              }
              $slug = $currentSlug;

              $checkSlug = $this->slug($currentSlug);

              $slug_count++;
          }

          while(count($checkSlug)>0);*/

          $make_id = DB::table('makes')->insertGetId([
              'make_name' 	=>   $request->name,
              'image'       =>   $request->image_label,
              'is_feature'  =>   $request->is_feature,
              'created_at'	=>   $date_added
          ]);

          /*DB::table('manufacturers_info')->insert([
              'manufacturers_id'  	=>     $manufacturers_id,
              'manufacturers_url'     =>     $request->manufacturers_url,
              'languages_id'			=>	   $languages_id,
              //'url_clickeded'			=>	   $request->url_clickeded
          ]);*/

    }

    public function edit($make_id){

        $editMake= DB::table('makes')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'makes.image')
            ->select('makes.make_id as id', 'is_feature','makes.make_name as name','makes.image as image', 'categoryTable.path as imgpath')
            ->where( 'makes.make_id', $make_id )
            ->get();

         return $editMake;
    }

    public function filter($name,$param){
      switch ( $name )
      {
          case 'Name':
              $make = Makes::sortable(['make_id'=>'desc'])
                ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'makes.image')
                  ->select('makes.make_id as id','is_feature','makes.make_name as name', 'categoryTable.path as imgpath')
                  ->where('makes.make_name', 'LIKE', '%' . $param . '%')
                  ->where('categoryTable.image_type','ACTUAL')
                  ->paginate('20');
              break;

          /*case 'URL':
              $manufacturers = Manufacturers::sortable(['manufacturers_id'=>'desc'])
                  ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                  ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                  ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                  ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                  ->where('manufacturers_info.manufacturers_url', 'LIKE', '%' . $param . '%')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate('10');
              break;*/


          default:
              $make = Makes::sortable(['make_id'=>'desc'])
                  ->select('makes.make_id as id', 'makes.make_name as name','is_feature')
                  ->paginate('20');
      }
        return $make;
    }

    public function checkexistname($request){

        $checkMakeName= DB::table('makes')
            ->where( 'makes.make_name', $request->name )
            ->get();

        return $checkMakeName;
    }

    public function checkexistnameupdate($request){

        $checkMakeName= DB::table('makes')
            ->where( 'makes.make_name', $request->name )
            ->where('makes.make_id','!=', $request->id)
            ->get();
        return $checkMakeName;
    }

    /*public function fetchAllmanufacturers($language_id){

        $getManufacturers = DB::table('manufacturers')
            ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
            ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date')
            ->where('manufacturers_info.languages_id', $language_id)->get();
        return $getManufacturers;
    }

    public function fetchmanufacturers(){

        $manufacturers = DB::table('manufacturers')
            ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
            ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
            ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
            ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
            ->where('manufacturers_info.languages_id', '1')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL');


        return $manufacturers;


    }



    public function slug($currentSlug){

        $checkSlug = DB::table('manufacturers')->where('manufacturers_slug',$currentSlug)->get();

        return $checkSlug;
    }*/



    public function updaterecord($request){
        if($request->image_id!==null){
            $uploadImage = $request->image_id;
        }else{
            $uploadImage = $request->oldImage;
        }
                  $last_modified 	=   date('y-m-d h:i:s');
                  //$languages_id = '1';

                  //check slug
                  /*if($request->old_slug!=$request->slug ){
                      $slug = $request->slug;
                      $slug_count = 0;
                      do{
                          if($slug_count==0){
                              $currentSlug = $this->varsetting->slugify($request->slug);
                          }else{
                              $currentSlug = $this->varsetting->slugify($request->slug.'-'.$slug_count);
                          }
                          $slug = $currentSlug;

                          $checkSlug = $this->slug($currentSlug);
                          $slug_count++;
                      }

                      while(count($checkSlug)>0);

                  }else{
                      $slug = $request->slug;
                  }

                  if($request->image_id==null){

                      $uploadImage = $request->oldImage;

                  }else{

                      $uploadImage = $request->image_id;
                  }*/

                DB::table('makes')->where('make_id', $request->id)->update([
                    'make_name' 	=>   $request->name,
                    'is_feature'  => $request->is_feature,
                      'image'       =>   $request->image_label,
                    'updated_at'	=>   $last_modified
                ]);
                /*DB::table('manufacturers_info')->where('manufacturers_id', $request->id)->update([
                    'manufacturers_url'     =>     $request->manufacturers_url,
                    'languages_id'			=>	   $languages_id,
                    //'url_clickeded'			=>	   $request->url_clickeded
                ]);

        $editCategory = DB::table('categories')
            ->leftJoin('categories_description','categories_description.categories_id', '=', 'categories.categories_id')
            ->select('categories.categories_id as id', 'categories.categories_image as image',  'categories.created_at as date_added', 'categories.updated_at as last_modified', 'categories_description.categories_name as name')
            ->where('categories.categories_id', $request->id)
            ->get();
        return $editCategory;*/
    }


    //delete Manufacturers

    public function destroyrecord($request){

        DB::table('makes')->where('make_id', $request->make_id)->delete();
        //DB::table('manufacturers_info')->where('manufacturers_id', $request->manufacturers_id)->delete();

    }

    public function getter(){
//        $makes = Makes::sortable(['make_name'=>'ASC'])->get();
        $makes = Makes::OrderBy('make_name', 'ASC')->get();
        return $makes;
    }

    public  function allmake()
    {
        $makeInfo = DB::table('makes')->get();
        return $makeInfo;
    }
    /*public function fetchsortmanufacturers($name, $param){

        switch ( $name )
        {
            case 'Name':
                $manufacturers = DB::table('manufacturers')
                ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                ->where('manufacturers.manufacturer_name', 'LIKE', '%' . $param . '%')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate('10');
                  break;

            case 'URL':
                $manufacturers = DB::table('manufacturers')
                    ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                    ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                    ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                    ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                    ->where('manufacturers_info.manufacturers_url', 'LIKE', '%' . $param . '%')->where('image_categories.image_type','=','THUMBNAIL' or 'image_categories.image_type','=','ACTUAL')->paginate('10');
                break;


            default:
            $manufacturers = DB::table('manufacturers')
                ->leftJoin('manufacturers_info','manufacturers_info.manufacturers_id', '=', 'manufacturers.manufacturers_id')
                ->leftJoin('images','images.id', '=', 'manufacturers.manufacturer_image')
                ->leftJoin('image_categories','image_categories.image_id', '=', 'manufacturers.manufacturer_image')
                ->select('manufacturers.manufacturers_id as id', 'manufacturers.manufacturer_image as image',  'manufacturers.manufacturer_name as name', 'manufacturers_info.manufacturers_url as url', 'manufacturers_info.url_clicked', 'manufacturers_info.date_last_click as clik_date','image_categories.path as path')
                ->where('manufacturers_info.languages_id', '1')->paginate('10');
        }


        return $manufacturers;


    }*/

    public function get_feature_brand(){
        $make =  Makes::sortable(['make_id'=>'desc'])
        ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'makes.image')
           ->select('makes.make_id as id', 'is_feature', 'makes.make_name as name', 'categoryTable.path as imgpath')
          ->where('categoryTable.image_type','ACTUAL')
          ->whereNotNull('is_feature')
          ->whereNotNull('image')
          ->get();         
        return $make;
    }


}
