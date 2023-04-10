<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Http\Traits\OrdersTrait;
use App\Repositories\Interfaces\Files\FileRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Validator;

class FileService {

    use ApiResponse, OrdersTrait;
    /**
     * @var $fileRepository
     */
    protected $fileRepository;

    /**
     * order constructor.
     *
     * @param fileRepository $fileRepository
     */

    public function __construct(FileRepositoryInterface $fileRepository) {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Upload order temp files.
     * @param object $data
     * @return [json] \Illuminate\Http\Response
     */
    public function store($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $tempFileArr = [];
            $messages = [
                'order_files.required' => trans('messages.order_file.required'),
                'order_files.mimes' => trans('messages.order_file.mimes'),
            ];

            $validator = Validator::make($data->all(), [
                'order_files' => 'required',
                'order_files.*' => 'mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png|max:2048000',
            ], $messages);

            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            if ($data->hasfile('order_files')) {
                foreach ($data->file('order_files') as $key => $file) {
                    $uploadedFileSize = $file->getSize(); // in bytes
                    $calculatedFileSize = $this->fileSize($uploadedFileSize);
                    $fileType = $data->file_type;
                    $fileExtension = time() . '.' . $file->getClientOriginalExtension();
                    $realFname = $file->getClientOriginalName();
                    $uniqueFname = rand() . time() . "_" . $fileExtension;
                    $file->move(public_path('uploads'), $uniqueFname);
                    $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;

                    $fileData['doc_original_name'] = $realFname;
                    $fileData['doc_temp_name'] = $uniqueFname;
                    $fileData['url'] = $pulicUrlPath;
                    $fileData['file_size'] = $calculatedFileSize;
                    $fileData['file_type'] = $fileType;
                    $tempDocs = $this->fileRepository->store($fileData);

                    $tempFileArr[$key] = $fileData;
                    $tempFileArr[$key]['temp_doc_id'] = $tempDocs->id;
                }
                return $this->success($tempFileArr); // return temp file array
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * Delete order temp files.
     * @param object $data
     * @param int $id
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy($data, $id) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $data->merge(['file_id' => $data->route('fileId')]);
            $messages = ['file_id.required' => trans('messages.file.required')];
            $data->validate([
                'file_id' => 'required|exists:temp_docs,id',
            ], $messages);

            $fetchDoc = $this->fileRepository->show($id);
            $docAssignedName = $fetchDoc['doc_temp_name'];
            $fetchDoc = $this->fileRepository->destroy($id);
            $filePath = public_path('uploads') . '/' . $docAssignedName;
            unlink($filePath);
            return $this->success(['message' => trans('messages.file.deleted'), 'status' => 1]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * download file using file id.
     * @param object $data
     * @param int $id
     * @return [json] \Illuminate\Http\Response
     */
    public function downloadFile($data, $id) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $data->merge(['file_id' => $id]);
            $messages = ['file_id.required' => trans('messages.file.required')];

            $validator = Validator::make($data->all(), [
                'file_id' => 'required',
            ], $messages);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $fetchDoc = $this->fileRepository->show($id);
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

    /**
     * download all files using order id.
     * @param object $data
     * @param int $orderId
     * @return [json] \Illuminate\Http\Response
     */
    public function downloadAllFiles($data, $orderId) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $data->merge(['order_id' => $orderId]);
            $messages = ['order_id.required' => trans('messages.order_id.required')];
            $validator = Validator::make($data->all(), [
                'order_id' => 'required',
            ], $messages);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $order = $this->getOrderWithUser($orderId);
            if ($order && ($order->user->id == $user->id || $user->mdt == 1)) {
                $mdtId = !empty($order->mdt_id) ? $order->mdt_id : '';
                $code = !empty($order->user->code) ? $order->user->code : '';
                $orderCode = $this->getOrderCode($mdtId, $code, $orderId);
                if (empty($order->order_code)) {
                    $order->order_code = $orderCode;
                }
                $orderData = json_decode($order->order_data, true);
                $files = !empty($orderData['files']) ? $orderData['files'] : [];
                if (!empty($files[0])) {
                    $zip = new \ZipArchive();
                    $fileName = $order->order_code . ".zip";
                    if ($zip->open(public_path("orders/" . $fileName), \ZipArchive::CREATE) == true) {
                        foreach ($files as $file) {
                            $fileNewName = !empty($file['doc_temp_name']) ? $file['doc_temp_name'] : $file['file_new_name'];
                            $path = public_path('uploads/' . $fileNewName);
                            $relativeName = !empty($file['file_actual_name']) ? $file['file_actual_name'] : basename($path);
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
