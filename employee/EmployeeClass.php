<?php
//ユーザーshowで使用する案
class Employee 
{
    private $id;
    private $last;
    private $first;
    private $userName;
    private $flag;

    public function __construct($id, $last, $first, $userName, $flag)
    {
        $this->id       = $id;
        $this->last     = $last;
        $this->first    = $first;
        $this->userName = $userName;
        $this->flag     = $flag;
    }

    public function getName()
    {
        return $this->last . ' ' . $this->first;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLast()
    {
        return $this->last;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function getUserName()
    {
        return $this->userName;
    }
    
    public function getFlag()
    {
        return $this->flag;
    } 
}