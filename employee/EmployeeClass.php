<?php

class Employee 
{
    private $id;
    private $lastH;
    private $firstH;
    private $last;
    private $first;
    private $userName;
    private $flag;

    public function __construct($id, $lastH, $firstH, $last, $first, $userName, $flag)
    {
        $this->id       = $id;
        $this->lastH    = $lastH;
        $this->firstH   = $firstH;
        $this->last     = $last;
        $this->first    = $first;
        $this->userName = $userName;
        $this->flag     = $flag;
    }

    public function escape($obj)
    {
        return htmlspecialchars($obj, ENT_QUOTES, 'UTF-8');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHurigana()
    {
        return $this->escape($this->lastH . ' ' . $this->firstH);
    }

    public function getLastH()
    {
        return $this->escape($this->lastH);
    }

    public function getFirstH()
    {
        return $this->escape($this->firstH);
    }

    public function getName()
    {
        return $this->escape($this->last . ' ' . $this->first);
    }

    public function getLast()
    {
        return $this->escape($this->last);
    }

    public function getFirst()
    {
        return $this->escape($this->first);
    }

    public function getUserName()
    {
        return $this->escape($this->userName);
    }
    
    public function getFlag()
    {
        return $this->flag;
    } 
}