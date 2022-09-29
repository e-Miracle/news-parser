<?php


namespace App\Message;


class NewsParse
{
    /**
     * @var object
     */
    private $load;

    /**
     * @return object
     */
    public function getLoad(): object
    {
        return $this->load;
    }

    public function __construct(object $load)
    {
        $this->load = $load;
    }
}