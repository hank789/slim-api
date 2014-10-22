<?php

namespace Ylc\AppBackendApi\Resource;


use Ylc\AppBackendApi\Resource;
class User extends Resource
{
    
    private $userService;

    /**
     * Get user service
     */
    public function init()
    {
        //$this->setUserService();
    }

    /**
     * @param null $id
     */
    public function get($id = null)
    {
         
        if ($id === null) {
            self::response(self::STATUS_NOT_FOUND);
            return;
        }

        $response = array('user' => 'tom');
        self::response(self::STATUS_OK, $response);
    }
    
    public function getUserService()
    {
        return $this->userService;
    }

    
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }
    
}