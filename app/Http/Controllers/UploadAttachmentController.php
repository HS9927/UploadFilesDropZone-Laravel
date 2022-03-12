<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Exception;

/// TODO: Models
use App\Models\DropZone;

class UploadAttachmentController extends Controller
{
    private $path_temp = "app/img-uploads/temp/";
    private $path_permanent = "app/img-uploads/uploaded/";

    public function index()
    {
        /// TODO: Check has folder img-uploads/temp or not
        if (!File::exists(storage_path("app/img-uploads"))) {
            File::makeDirectory(storage_path("app/img-uploads"));
            File::makeDirectory(storage_path("app/img-uploads/temp"));
            File::makeDirectory(storage_path("app/img-uploads/uploaded"));
        }

        /// TODO: Get All Datas from DropZone Database
        $datas = DropZone::where("is_active", 1)
            ->get();

        return view("crud/index")
            ->with("datas", $datas);
    }

    public function create()
    {
        /// TODO: Generate Folder Name
        $folder_name = (string)time() . (string)rand();

        /// TODO: Make Folder
        File::makeDirectory(storage_path($this->path_temp . $folder_name));

        return view("crud/create")
            ->with("folder_name", $folder_name);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validate = $request->validate([
                "name" => "required",
                "subject" => "required",
                "folder_name" => "required"
            ]);

            $insertToDB = DropZone::create([
                "name" => $request->name,
                "subject" => $request->subject,
                "folder_name" => $request->folder_name
            ]);

            /// TODO: Make Folder
            File::makeDirectory(storage_path($this->path_permanent . $request->folder_name));

            /// TODO: Check Exists Folder
            if (File::exists(storage_path($this->path_temp . $request->folder_name))) {
                /// TODO: Get All Files
                $files = File::allFiles(storage_path($this->path_temp . $request->folder_name));

                /// TODO: Move Files
                foreach ($files as $key => $val) {
                    File::move(storage_path($this->path_temp . $request->folder_name . "/" . $files[$key]->getFilename()), storage_path($this->path_permanent . $request->folder_name . "/" . $files[$key]->getFilename()));
                }

                File::deleteDirectory(storage_path($this->path_temp . $request->folder_name));

            }



            DB::commit();

            return redirect()->route("index")->with("message", "Error: Data added Successfully");
        } catch (Exception $exce) {
            DB::rollBack();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = DropZone::where("is_active", 1)
            ->where("id", $id)
            ->first();

        return view("crud/edit")
            ->with("data", $data);
    }

    public function update(Request $request, $id) {}

    public function show($id) {}

    public function destroy($id) {}

    /// TODO: Upload File into Temp Folder
    public function store_file(Request $request, $folder_name)
    {
        /// TODO: Get File
        $files = $request->file("file");

        /// TODO: Get File Name
        $file_name = $files->getClientOriginalName();


        /// TODO: Move File to Folder
        $files->move(storage_path($this->path_temp . $folder_name), $file_name);

        /// TODO: Return
        return response()->json(["success" => $file_name]);

    }

    /// TODO:
    public function store_permanent_file (Request $request, $folder_name)
    {
        /// TODO: Get File
        $files = $request->file("file");

        /// TODO: Get File Name
        $file_name = $files->getClientOriginalName();

        /// TODO: Move File to Folder
        $files->move(storage_path($this->path_permanent . $folder_name), $file_name);

        /// TODO: Return
        return response()->json(["success" => $file_name]);
    }

    /// TODO: Fetch File from Temp Folder
    public function fetch_file(Request $request)
    {
        $folder_name = $request->get("folder");

        $files = File::allFiles(storage_path($this->path_temp . $folder_name));

        $output = '';
        foreach ($files as $file) {
            /// TODO: TODO : /upload-file/download is a Link in Route
            $output .= '
            <div class="alert alert-success border border-primary rounded py-1 px-2 mt-1" >
                <a style="font-size: 15px;" href="/file/download/temp/' . $folder_name . '/' . $file->getFilename() . '" >' . $file->getFilename() . '</a>
                <button type="button" class="btn btn-link text-danger remove_image" id="' . $file->getFilename() . '">Remove</button>
            </div>
            ';
        }
        $output .= '';
        echo $output;
    }

    /// TODO:
    public function fetch_permanent_file (Request $request)
    {
        $folder_name = $request->get("folder");

        $files = File::allFiles(storage_path($this->path_permanent . $folder_name));

        $output = '';
        foreach ($files as $file) {
            /// TODO: TODO : /upload-file/download is a Link in Route
            $output .= '
            <div class="alert alert-success border border-primary rounded py-1 px-2 mt-1" >
                <a style="font-size: 15px;" href="/file/download/permanent/' . $folder_name . '/' . $file->getFilename() . '" >' . $file->getFilename() . '</a>
                <button type="button" class="btn btn-link text-danger remove_image" id="' . $file->getFilename() . '">Remove</button>
            </div>
            ';
        }
        $output .= '';
        echo $output;
    }
    /// TODO:

    /// TODO:

    /// TODO: Download File from Temp Folder
    public function download_file($folder_name, $file_name)
    {
        $file = storage_path() . $this->path_temp . $folder_name . "/" . $file_name;
        return Response::download($file, $file_name);
    }

    /// TODO: Download File from Permanent Folder
    public function download_permanent_file($folder_name, $file_name)
    {
        $file = storage_path() . $this->path_permanent . $folder_name . "/" . $file_name;
        return Response::download($file, $file_name);
    }

    /// TODO: Destroy File from Temp Folder
    public function destroy_file(Request $request)
    {
        $folder_name = $request->get("folder");
        if ($request->get("name")) {
            File::delete(storage_path($this->path_temp . $folder_name . "/" . $request->get("name")));
        }
    }

    /// TODO: Destroy File from Permanent Folder
    public function destroy_permanent_file(Request $request)
    {
        $folder_name = $request->get("folder");
        if ($request->get("name")) {
            File::delete(storage_path($this->path_permanent . $folder_name . "/" . $request->get("name")));
        }
    }


}
