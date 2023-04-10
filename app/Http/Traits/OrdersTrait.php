<?php

namespace App\Http\Traits;
use App\Models\Order;

trait OrdersTrait {

    /**
     * generate order code
     */
    public static function getOrderCode($mdtId = null, $code = null, $orderId = null) {
        $date = date('ymd');
        $orderCodeEmptyFlag = false;
        if (!empty($orderId)) {
            $orderQuery = Order::where(['id' => $orderId]);
            $orderData = $orderQuery->first();
            if (empty($orderData->order_code)) {
                $orderCodeEmptyFlag = true;
                $date = date('ymd', strtotime($orderData->created_at));
            }
        }

        $count = Order::where('mdt_id', $mdtId)
            ->where(function ($q) {
                $q->whereNotNull('order_code')->orWhere('order_code', '<>', '');
            })
            ->whereDate('created_at', '=', $date)
            ->count();

        $count = sprintf("%02d", $count + 1);
        if (empty($mdtId) && empty($code)) {
            $code = !empty($code) ? $code : "ADH";
            $count = mt_rand(10, 100);
        }
        $orderCode = $code . "-" . $date . $count;
        if ($orderCodeEmptyFlag == true) {
            $orderQuery->update(['order_code' => $orderCode]);
        }

        return $orderCode;
    }
    /**
     * Formats filesize in human readable way.
     * @param file $file
     * @return [string] Formatted Filesize, e.g. "113.24 MB".
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

    /**
     * ger order with order creator
     * @param int $id
     * @return object $order
     *
     */

    public function getOrderWithUser($id) {
        $order = Order::where(['id' => $id])->with(['user' => function ($q) {
            $q->select(['id', 'code']);
        }])->first();
        return $order;
    }

}