<?php
namespace App\Repositories\Interfaces\Orders;

/*
 * Interface OrderTypeRepositoryInterface
 * @package App\Repositories
 */
interface OrderTypeRepositoryInterface
{
    public function orderTypes($whereIn = []);
    public function orderTypeTopics($id);
    public function store($data);
    public function update($data, $id);
    public function getOrderType($id);
    public function destroy($id);
}
