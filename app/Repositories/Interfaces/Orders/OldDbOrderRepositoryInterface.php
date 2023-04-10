<?php
namespace App\Repositories\Interfaces\Orders;
/*
 * Interface OldDbOrderRepositoryInterface
 * @package App\Repositories
 */
Interface OldDbOrderRepositoryInterface {
    public function allOrdersWithUsers();
    public function countOrderByStatusWithUserId($userId, $mdtId = null);
    public function deleteOrder($orderId);
    public function deleteUnlinkOrderFiles($orderId);
    public function getOrderList($data, $size, $userId, $mdtId = null);
    public function orderUploadFiles($data);
    public function storeOrder($data, $userData, $mdt);
    public function getOrder($id);
    public function updateOrder($data,$id);

}
?>