<?php

namespace App\Http\Controllers;

use App\File;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class FileController extends Controller
{
    private $allowedFileExtension = ['pdf', 'docx', 'jpg', 'png', 'mp4', 'mp3'];


    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws
     */
    public function index()
    {

        $files = File::all()->where('user_id', auth()->user()->id);
        return view('files.plural')->with('files', $files);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     * @return Response
     */
    public function create()
    {
        return view('files.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param File $file
     * @return Response
     * @return string
     * @throws
     */
    public function store(Request $request, File $file)
    {


        $this->validate(
            $request,
            [
                'files.*' => 'required|mimes:pdf,docx,mp4,jpg,jpeg,png|max:20000'
            ],
            [
                'files.*.required' => 'Please upload an file',
                'files.*.mimes' => 'Only pdf,docx,mp4,jpeg, png and bmp images are allowed',
                'files.*.max' => 'Sorry! Maximum allowed size for an image is 20MB',
            ]);

        try {

            if ($request->hasFile('files')) {

                $files = $request->file('files');
                foreach ($files as $input_file) {

                    $file_name = $input_file->getClientOriginalName();
                    $file_extension = $input_file->getClientOriginalExtension();
                    $check = in_array($file_extension, $this->allowedFileExtension);
                    $user_id = $request->user()->id;

                    if ($check) {


                        if ($file->isImage($file_extension)) {

                            $path = Storage::putFileAs('folder_' . $user_id . '_image', new UploadedFile($input_file, $file->user_name($request) . '_' . $file_name), $file->user_name($request) . '_' . $file_name);

                        } elseif ($file->isVideo($file_extension)) {

                            $path = Storage::putFileAs('folder_' . $user_id . '_video', new UploadedFile($input_file, $file->user_name($request) . '_' . $file_name), $file->user_name($request) . '_' . $file_name);

                        } else {

                            $path = Storage::putFileAs('folder_' . $user_id . '_' . $file_extension, new UploadedFile($input_file, $file->user_name($request) . '_' . $file_name), $file->user_name($request) . '_' . $file_name);
                        }

                        $file_db = File::create([
                            'user_id' => $user_id,
                            'name' => $file_name,
                            'type' => $file_extension,
                            'path' => $path
                        ]);
                        $file_db->save();

                    } else {
                        session()->flash('ErrorMassage', 'This is file not able to upload');
                        return back();
                    }
                }
            } else {
                session()->flash('ErrorMassage', 'This field is require');
                return back();
            }
        } catch (QueryException $error) {
            //This is for General Query Exception

//            $error_massage = $error->errorInfo[2];
//            session()->flash('ErrorStoreMassage', $error_massage);
//            return back();

//          This is for specific error which is Duplicate entry
            $error_code = $error->errorInfo[1];
            if ($error_code == 1062) {
                //using flash method for alert
                session()->flash('ErrorMassage', 'Duplicate entry');
                return back();

            }
        }

        session()->flash('SuccessMassage', 'File Uploaded Successfully');
        return redirect(route('files.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param File $file
     * @return Response
     */
    public function show(File $file)
    {
        if ($file->user_id !== auth()->id()) {

//          abort(403, 'You do not have permission to view this file');
            session()->flash('ErrorMassage', 'You do not have permission to view this file');
            return redirect(route('files.index'));
        }

        if ($file->isImage($file->type)) {
            return view('files.types.image')->with('file', $file);
        } elseif ($file->isVideo($file->type)) {
            return view('files.types.video')->with('file', $file);
        } else {
            return view("files.types.{$file->type}")->with('file', $file);
        }

    }

    /**
     * Download the specified file
     *
     * @param File $file
     * @return BinaryFileResponse
     */
    public function download(File $file)
    {

        return response()->download(public_path() . '/storage/' . $file->path, $file->name);

    }


    /**
     * Remove the specified file from storage.
     *
     * @param File $file
     * @return Response
     * @throws
     */
    public function destroy(File $file)
    {
        if ($file->user_id !== auth()->id()) {

            abort(403, 'You do not have permission to delete this file');
        }


        try {
            Storage::disk('public')->delete($file->path);
            $file->delete();
            session()->flash('SuccessMassage', 'File Deleted Success');
            if (request()->wantsJson()) {
                dd(response([], 204));
            }
            return redirect(route('files.index'));

        } catch (Exception $error) {
//            throw new Exception('There File with this id');
//            $error_massage = $error->errorInfo[2];
            session()->flash('ErrorMassage', $error);
            return back();
        }

    }
}
