<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class VendorsController extends Controller
{


    public function index()
    {
         $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'password' => $request->password,
                'logo' => $filePath,
                'category_id'  => $request -> category_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
                # when admin create vendor send email to vendor
            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {

            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function edit($vendorId)
    {
        $vendor = Vendor::selection()->find($vendorId);
         if(!$vendor)
             return redirect()->route('admin.vendor')->with(['error'=>'حدث خطا ما برجاء المحاوله لاحقا']);
         $categories = MainCategory::where('translation_of', 0)->active()->get();
         return view('admin.vendors.edit',compact('vendor','categories'))->with(['success'=>'تم الحفظ بنجاح']);
    }

    public function update($id,VendorRequest $request)
    {

        try {

            $vendor = Vendor::find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);


            DB::beginTransaction();
            //photo
            if ($request->has('logo') ) {
                #delete oldImage from folder
                    ######
                    ####
                 $filePath = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)
                    ->update([
                        'logo' => $filePath,
                    ]);
            }


            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $data = $request->except('_token', 'id', 'logo', 'password');


            if ($request->has('password') && !is_null($request->  password)) {

                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)
                ->update(
                    $data
                );

            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $exception) {
            return $exception;
            DB::rollback();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function destroy($vendorId)
    {
       try{
            $vendor = Vendor::find($vendorId);
            if(!$vendor)
                return redirect()->route('admin.vendors')->with(['error'=>'حدث خطا ما برجاء المحاوله لاحقا']);
            ## Delete photo from folder
            $image = Str::after($vendor->logo,'assets/');
            $path  = base_path('assets/'.$image);
            unlink($path);

            $vendor->delete();

            return redirect()->route('admin.vendors')->with(['success'=>'تم الحذف بنجاح']);
        }catch(\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error'=>'حدث خطا ما برجاء المحاوله لاحقا']);

        }

    }
    public function changeStatus($id){

        try{
            $vendors = Vendor::find($id);
            if(!$vendors)
                return redirect()->route('admin.vendors')->with(['error'=>'حدث خطا ما برجاء المحاوله لاحقا']);
            $status= $vendors->active == 0 ? 1 : 0;
            $vendors->update(['active'=>$status]);
            return redirect()->route('admin.vendors')->with(['success' => 'تم بنجاح']);

        }catch(\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }
}
