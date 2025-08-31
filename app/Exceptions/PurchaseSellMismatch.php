<?php

namespace App\Exceptions;

use Exception;

class PurchaseSellMismatch extends Exception
{
    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @param  array  $guards
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {

        if ($request->ajax()) {
            return [
                'success' => 0,
                'msg' => $this->getMessage()
            ];
        } 
        throw new Exception($this->getMessage());
    }
}
