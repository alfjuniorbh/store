<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Configuration;
use App\Models\DocumentType;
use App\Models\Status;
use App\Services\InputFields;
use App\Services\Messages;
use App\Services\UploadImage;
use App\Traits\DataTableTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    use DataTableTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //index
    public function index()
    {
        return view('admin.banner.home');
    }

    //get
    public function getDatatable(Request $request)
    {
        $model = new \App\Models\Banner;
        $columns = ['id',  'name',  'file', 'extension',  'created_at', 'updated_at', 'status_id'];
        $result  = $this->dataTable($model, $columns);

        return DataTables::eloquent($result)
            ->addColumn('status', function ($data) {
                return $data->status->status;
            })
            ->addColumn('created_at', function ($data) {
                return format_date($data->created_at);
            })
            ->addColumn('updated_at', function ($data) {
                return format_date($data->updated_at);;
            })
            ->addColumn('file', function ($data) {
                if($data->file) {
                    return '<a href="' . route('banner-download', base64_encode($data->file)) . '"  title="Baixar" class="btn bg-green btn-xs"><i class="fa fa-download"></i></a>';
                }else{
                    return '<a href="javascript:void(0);"  title="Baixar" class="btn bg-green btn-xs disabled"><i class="fa fa-close"></i></a>';
                }
            })
            ->addColumn('action', function ($data) {
                if($data->status_id == canceledRegister()) {
                    return '<a onclick="localStorage.clear();" href="' . route('banner-edit', [base64_encode($data->id)]) . '"     title="Editar" class="btn bg-aqua btn-xs"><i class="fa fa-pencil"></i></a>
                         <a href="javascript:void(0);"  title="Excluir" class="btn bg-red btn-xs disabled"><i class="fa fa-trash"></i></a>
                        ';
                }else{
                    return '<a onclick="localStorage.clear();" href="' . route('banner-edit', [base64_encode($data->id)]) . '"     title="Editar" class="btn bg-aqua btn-xs"><i class="fa fa-pencil"></i></a>
                        <a href="' . route('banner-destroy', [base64_encode($data->id)]) . '"  title="Excluir" class="btn bg-red btn-xs"><i class="fa fa-trash"></i></a>
                        ';
                }
            })
            ->rawColumns(['action', 'file'])
            ->toJson();
    }

    //create
    public function create()
    {
        $status = Status::where('flag', 'default')->get();
        return view('admin.banner.create', compact('status'));
    }


    //store
    public static function store(Request $request)
    {
        try{
            if($request->hasFile('file')) {
                $messages = Messages::msgBanner();
                $validator = Validator::make($request->all(), [
                    'name'             => 'required|string|min:5|max:50',
                    'file'             => 'required|mimes:jpeg,jpg,png|dimensions:min_width=2000',
                ], $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                    exit();
                }

                //get file attr
                $image = $request->file('file');
                $file = $image;
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . random_int(100, 999) .'.' . $extension;
                $path = defineUploadPath('banners', null);
                $size =  convertFileSize($image->getSize());
                
                Banner::create(InputFields::inputFieldsBanner($request, $extension, $size,  $fileName));

                //upload file
                UploadImage::uploadBanner(1920, 100, $file, $fileName, $path);


                session()->flash('success', 'Salvo com sucesso!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Favor selecionar o arquivo!');
                return redirect()->back();
            }
        }catch(\Exception $e){
            session()->flash('error', 'Erro ao salvar!');
            return redirect()->back();
        }
    }

    //edit
    public static function edit($banner_id)
    {
        $id = base64_decode($banner_id);
        $banner = Banner::findOrfail($id);
        $status = Status::where('flag', 'default')->get();
        $users = User::where('id', '!=', Auth::user()->id)
            ->where('configuration_id', '!=', '')
            ->where('configuration_id', Auth::user()->configuration_id)
            ->get();
        $doc_types = DocumentType::get();
        return view('admin.banner.edit', compact('banner', 'status', 'users', 'doc_types'));
    }


    //update
    public static function update(Request $request)
    {
        try{
            if($request->hasFile('file')) {
                $banner = Banner::findOrFail($request->id);

                $messages = Messages::msgBanner();
                $validator = Validator::make($request->all(), [
                    'name'             => 'required|string|min:5|max:50',
                    'file'             => 'required|mimes:jpeg,jpg,png|dimensions:min_width=2000',
                ], $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                    exit();
                }

                //get file attr
                $image = $request->file('file');
                $file = $image;
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . random_int(100, 999) .'.' . $extension;
                $path = defineUploadPath('banners', null);
                $size =  convertFileSize($image->getSize());

                $data = InputFields::inputFieldsBanner($request, $extension, $size,  $fileName);
                $banner->update($data);

                UploadImage::uploadBanner(1920, 100, $file, $fileName, $path);

                session()->flash('success', 'Salvo com sucesso!');
                return redirect()->back();
            }else{
                $banner = Banner::findOrFail($request->id);

                $messages = Messages::msgBanner();
                $validator = Validator::make($request->all(), [
                    'name'             => 'required|string|min:5|max:50'
                ], $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                    exit();
                }

                $data = InputFields::inputFieldsBanner($request, null, null, null);
                $banner->update($data);

                session()->flash('success', 'Salvo com sucesso!');
                return redirect()->back();
            }
        }catch(\Exception $e){
            session()->flash('error', 'Erro ao salvar!');
            return redirect()->back();
        }
    }


    //destroy
    public static function destroy($banner_id)
    {
        $id = base64_decode($banner_id);
        $file = Banner::findOrfail($id);
        //destroy file
        destroyFile('banners', $file->file, 'thumb');

        if($file){
            $file->delete();
        }
        session()->flash('success', 'Excluído com sucesso!');
        return redirect()->back();
    }

    //destroy file
    public static function destroyFile($banner_id)
    {
        $id = base64_decode($banner_id);
        $file = Banner::findOrfail($id);

        destroyFile('banners', $file->file, 'thumb');

        if($file){
            $file->update(['file' => '']);
        }
        session()->flash('success', 'Documento excluído com sucesso!');
        return redirect()->back();
    }

    public static function download($download_file)
    {
        $file = base64_decode($download_file);
        $download = defineDownloadPath('banners').'/'.$file;
        return response()->download($download, $file);
    }
}
