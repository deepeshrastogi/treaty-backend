<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Traits\ApiResponse;
use App\Models\Order;
use App\Models\TempDocs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Validator;

class FilesController extends Controller {

    use ApiResponse;

    /**
     * Delete order temp files.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $fileId) {
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {
            $request->merge(['file_id' => $request->route('fileId')]);
            $messages = array('file_id.required' => trans('messages.file.required'));
            $request->validate(['file_id' => 'required|exists:temp_docs,id'], $messages);
            $docId = $request['file_id'];
            $fetchDoc = TempDocs::where('id', $docId)->first();
            $docAssignedName = $fetchDoc['doc_temp_name'];
            $fetchDoc = TempDocs::where('id', $docId)->delete();
            $filePath = public_path('uploads') . '/' . $docAssignedName;
            unlink($filePath);
            return $this->success(['message' => trans('messages.file.deleted'), 'status' => 1]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /*
     * Upload order temp files.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {
            $tempFileArr = [];
            $messages = [
                'order_files.required' => trans('messages.order_file.required'),
                'order_files.mimes' => trans('messages.order_file.mimes'),
            ];

            $validatedData = $request->validate([
                'order_files' => 'required',
                'order_files.*' => 'mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png|max:2048000',
            ]);

            if ($request->hasfile('order_files')) {
                foreach ($request->file('order_files') as $key => $file) {
                    $uploadedFileSize = $file->getSize(); // in bytes
                    $calculatedFileSize = $this->fileSize($uploadedFileSize);
                    $fileType = $request->file_type;
                    $fileExtension = time() . '.' . $file->getClientOriginalExtension();
                    $realFname = $file->getClientOriginalName();
                    $uniqueFname = rand() . time() . "_" . $fileExtension;
                    $file->move(public_path('uploads'), $uniqueFname);
                    $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;
                    $tempDocs = new TempDocs();
                    $tempDocs->doc_original_name = $realFname;
                    $tempDocs->doc_temp_name = $uniqueFname;
                    $tempDocs->url = $pulicUrlPath;
                    $tempDocs->file_size = $calculatedFileSize;
                    $tempDocs->file_type = $fileType;
                    $tempDocs->save(); // save temp file
                    $tempFileArr[$key]['doc_original_name'] = $realFname;
                    $tempFileArr[$key]['doc_temp_name'] = $uniqueFname;
                    $tempFileArr[$key]['url'] = $pulicUrlPath;
                    $tempFileArr[$key]['file_size'] = $calculatedFileSize;
                    $tempFileArr[$key]['file_type'] = $fileType;
                    $tempFileArr[$key]['temp_doc_id'] = $tempDocs->id;
                }
                return $this->success($tempFileArr); // return temp file array
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * Formats filesize in human readable way.
     *
     * @param file $file
     * @return string Formatted Filesize, e.g. "113.24 MB".
     */

    public function fileSize($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    /*
     * download file using file id.
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(Request $request, $fileId) {
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {
            $request->merge(['file_id' => $request->route('fileId')]);
            $messages = array('file_id.required' => trans('messages.file.required'));
            $validator = Validator::make($request->all(), ['file_id' => 'required'], $messages);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $fetchDoc = TempDocs::where('id', $request->file_id)->first();
            if ($fetchDoc) {
                $path = public_path("uploads/" . $fetchDoc->doc_temp_name); // get public path
                $fileName = $fetchDoc->doc_original_name;
                $mimeType = File::extension($path);
                $headers = [['Content-Type' => $mimeType]];
                return Response::download($path, $fileName, $headers);
            } else {
                return $this->error(['error' => [trans('messages.file.required')]], 200);
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /*
     * download all files using order id.
     * @return \Illuminate\Http\Response
     */
    public function downloadAllFiles(Request $request, $orderId) {
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {
            $request->merge(['order_id' => $request->route('orderId')]);
            $messages = array('order_id.required' => trans('messages.order_id.required'));
            $validator = Validator::make($request->all(), ['order_id' => 'required'], $messages);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $query = Order::where('id', $orderId);
            $order = $query->with(['user' => function ($q) {
                $q->select(['id', 'code']);
            }])->first();
            if ($order) {
                $mdtId = !empty($order->mdt_id) ? $order->mdt_id : '';
                $code = !empty($order->user->code) ? $order->user->code : '';
                $orderCode = Order::getOrderCode($mdtId, $code, $orderId);
                if (empty($order->order_code)) {
                    $order->order_code = $orderCode;
                }
                $orderData = json_decode($order->order_data, true);
                $files = !empty($orderData['files']) ? $orderData['files'] : [];
                if (!empty($files[0])) {
                    $zip = new \ZipArchive();
                    $fileName = $order->order_code . ".zip";
                    if ($zip->open(public_path("orders/" . $fileName), \ZipArchive::CREATE) == TRUE) {
                        foreach ($files as $key => $file) {
                            $path = public_path('uploads/' . $file['file_new_name']);
                            $relativeName = basename($path);
                            $zip->addFile($path, $relativeName);
                        }
                        $zip->close();
                    }
                    return response()->download(public_path('orders/' . $fileName));
                } else {
                    return $this->error(['error' => [trans('messages.order_id.required')]], 200);
                }
            } else {
                return $this->error(['error' => [trans('messages.order_id.required')]], 200);
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }
}

?>