<?php
namespace Magneto\EcommerceApi\Model;
use Magneto\EcommerceApi\Api\EcommerceInterface;
 
class Ecommerce implements EcommerceInterface
{
    /** 
     * Returns greeting message to user
     *
     * @api
     * @param string $name Users name.
     * @return string Greeting message with users name.
     */
    public function name($name) {
        return "Hello, " . $name;
    }
} 