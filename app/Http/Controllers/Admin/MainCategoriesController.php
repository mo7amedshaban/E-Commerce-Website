<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;    //for use commit begin_transaction rollback()
use Illuminate\Support\Str;
class MainCategoriesController extends Controller
{
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)
            ->selection()
            ->get();

        return view('admin.maincategories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.maincategories.create');
    }


    public function store(MainCategoryRequest $request)
    {


     try {

             # appear all data in category like --> shape json
             $main_categories = collect($request->category);

             $filter = $main_categories->filter(function ($value, $key) {
                 return $value['abbr'] == get_default_lang();
             });
                #appear all data in array only appear first index only
                #array_values(arrayName)
              $default_category = array_values($filter->all())[0];


            $filePath = "";
            if ($request->has('photo')) {
                    $filePath = uploadImage('maincategories', $request->photo);
            }

            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });


            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }

                MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($mainCat_id)
    {
           //get specific categories and its translations
                                         # relationShip
        $mainCategory = MainCategory::with('categories')->selection()->find($mainCat_id);

        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategories.edit', compact('mainCategory'));
    }


    public function update($mainCat_id, MainCategoryRequest $request)
    {


       try {
             $main_category = MainCategory::find($mainCat_id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            // update date

            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            MainCategory::where('id', $mainCat_id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image

            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id', $mainCat_id)
                    ->update([
                         'photo' => $filePath,
                    ]);
            }




            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
    public function destroy($id){
        try{
            $mainCategory =  MainCategory::find($id);
            if(!$mainCategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

            # if mainCategory has vendors not delete
            $vendors = $mainCategory->vendors();
            if(isset($vendors) && $vendors->count()>0)
                return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف القسم القسم به تجار']);


            $image = Str::after($mainCategory->photo, 'assets/'); //cut from assest instead of ecommerce because my project can renamed at anyTime
            $path = base_path('assets/'.$image);
            unlink($path);  //delet image from folder   not delet image when path can  http://ecommerce.test.hello.png

                            #relationShip
            $mainCategory->categories()->delete(); # delete all translation  can use it in observer

            $mainCategory->delete();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح']);
        }catch(\Exception $ex){
           // return $ex;
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }

    }

    public function changeStatus($id){
        try{
         $mainCategory = MainCategory::find($id);
         if(!$mainCategory)
             return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        $status =  $mainCategory -> active  == 0 ? 1 : 0;
        $mainCategory -> update(['active' =>$status ]);
        // not forget active at vendor because relationShip
        // use observe   and  use boot function in this model
        //$ php artisan make:observer Category --model=MainCategory
        return redirect()->route('admin.maincategories')->with(['success' => 'تم بنجاح']);
        }catch(\Exception $ex){
            return $ex;
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }


//ok all ok


    }


}
