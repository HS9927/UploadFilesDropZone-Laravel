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

            /// TODO: Validate Datas
            $validate = $request->validate([
                "name" => "required",
                "subject" => "required",
                "folder_name" => "required"
            ]);

            /// TODO: Insert Data to DB
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

                /// TODO: Delete Folder from Temp
                File::deleteDirectory(storage_path($this->path_temp . $request->folder_name));

            }

            DB::commit();

            return redirect()->route("index")->with("message", "Data added Successfully");

        } catch (Exception $exce) {
            DB::rollBack();
            return redirect()->back()->with("error", "Something went wrong, please try again !");
        }
    }

    public function edit($id)
    {

        /// TODO: Get Record from DB
        $data = DropZone::where("is_active", 1)
            ->where("id", $id)
            ->first();

        return view("crud/edit")
            ->with("data", $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            /// TODO: Validate Datas
            $validate = $request->validate([
                "name" => "required",
                "subject" => "required"
            ]);

            /// TODO: Update Data in DB
            $data = DropZone::where("is_active", 1)
                ->where("id", $id)
                ->update([
                    "name" => $request->name,
                    "subject" => $request->subject
                ]);

            DB::commit();

            return redirect()->route("index")->with("message", "Data Updated Successfully .");

        } catch (Exception $exce) {
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong, please try again !");
        }
    }

    public function show($id)
    {
        /// TODO: Get Record from DB
        $data = DropZone::where("is_active", 1)
            ->where("id", $id)
            ->first();

        return view("crud/show")
            ->with("data", $data);
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            /// TODO: Update Data in DB
            $data = DropZone::where("is_active", 1)
                ->where("id", $id)
                ->update([
                    "is_active" => 0
                ]);

            DB::commit();

            return redirect()->route("index")->with("message", "Data Deleted Successfully.");

        } catch (Exception $exce) {
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong, please try again !");
        }


    }

    /// TODO: Upload Files into Temp Location
    /// TODO: for Create Function
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

    /// TODO: Upload Files into Permanent Location
    /// TODO: for Edit/Update Function
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

    /// TODO: Fetch Files from Temp Location
    /// TODO: for Create Function
    public function fetch_file(Request $request)
    {
        /// TODO: Get Folder Name
        $folder_name = $request->get("folder");

        /// TODO: Get All Files from Folder in Temp Folder
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

    /// TODO: Fetch Files from Permanent Location
    /// TODO: for Edit/Update Function
    public function fetch_permanent_file (Request $request)
    {
        /// TODO: Get Folder Name
        $folder_name = $request->get("folder");

        /// TODO: Get All Files from Folder in Permanent Folder
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

    /// TODO: Download File from Temp Location
    /// TODO: for Create Function
    public function download_file($folder_name, $file_name)
    {
        /// TODO: Get File from Temp Location
        $file = storage_path() ."/". $this->path_temp . $folder_name . "/" . $file_name;

        /// TODO: Download
        return Response::download($file, $file_name);
    }

    /// TODO: Download File from Permanent Location
    /// TODO: for Edit/Update Function
    public function download_permanent_file($folder_name, $file_name)
    {
        /// TODO: Get File from Permanent Location
        $file = storage_path() ."/". $this->path_permanent . $folder_name . "/" . $file_name;

        /// TODO: Download
        return Response::download($file, $file_name);
    }

    /// TODO: Destroy File from Temp Location
    /// TODO: for Create Function
    public function destroy_file(Request $request)
    {
        /// TODO: Get Folder Name
        $folder_name = $request->get("folder");

        /// TODO: Check and Get File Name
        if ($request->get("name")) {
            /// TODO: Delete File base on File Name in Temp Location
            File::delete(storage_path($this->path_temp . $folder_name . "/" . $request->get("name")));
        }
    }

    /// TODO: Destroy File from Permanent Location
    /// TODO: for Edit/Update Function
    public function destroy_permanent_file(Request $request)
    {
        /// TODO: Get Folder Name
        $folder_name = $request->get("folder");

        /// TODO: Check and Get File Name
        if ($request->get("name")) {
            /// TODO: Delete File base on File Name in Permanent Location
            File::delete(storage_path($this->path_permanent . $folder_name . "/" . $request->get("name")));
        }
    }
}
