<?php
/**
 * A Magento 2 module named Magneto/EcommerceApi 
 *  
 */
namespace Magneto\EcommerceApi\Api;
 
/** 
 * Interface WishlistManagementInterface
 * @api
 */
interface WishlistManagementInterface
{

    /**
     * Return Wishlist items.
     *
     * @param int $customerId
     * @return array
     */
    public function getWishlistForCustomer($customerId); 

    /**
     * Return Added wishlist item.
     *
     * @param int $customerId
     * @param int $productId
     * @return array
     *
     */
    public function addWishlistForCustomer($customerId,$productId);
 
}



