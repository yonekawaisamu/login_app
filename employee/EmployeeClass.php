<?php

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
        return htmlspecialchars($this->last . ' ' . $this->first, ENT_QUOTES, 'UTF-8');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLast()
    {
        return htmlspecialchars($this->last, ENT_QUOTES, 'UTF-8');
    }

    public function getFirst()
    {
        return htmlspecialchars($this->first, ENT_QUOTES, 'UTF-8');
    }

    public function getUserName()
    {
        return htmlspecialchars($this->userName, ENT_QUOTES, 'UTF-8');
    }
    
    public function getFlag()
    {
        return $this->flag;
    } 
}