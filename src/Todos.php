<?php

namespace Marto\Todos;

class Todos
{
    public $routeName = '';
    public $filePath = __DIR__.'/config/todos.json';
    public $data = [];
    public $fileData = '';

    public function __construct($routeName = null)
    {
        $this->routeName = $routeName;
    }

    public function ifFileExistOrCreate(){
        $this->fileData = $this->fileExist() ? '' : $this->fileCreate();
        return $this;
    }

    private function fileExist()
    {
        return \file_exists($this->filePath);
    }

    private function fileCreate()
    {
        \file_put_contents($this->filePath, '');
    }

    public function dataExistOrSetFirstPage()
    {
        \strlen(\file_get_contents($this->filePath)) > 0 ? $this->findRouteNameOrAppendIt() : $this->setFirstPage();
        return $this;
    }

    private function findRouteNameOrAppendIt()
    {
        \strpos(\file_get_contents($this->filePath), "\"".$this->routeName."\":") ? '' : \file_put_contents($this->filePath, \substr_replace(\file_get_contents($this->filePath), ",\"".$this->routeName."\":[]}", -1));
    }

    private function setFirstPage()
    {
        \file_put_contents($this->filePath, "{\"".$this->routeName."\":[]}");
    }

    public function getData()
    {
        return \file_get_contents($this->filePath);
    }

    public function storeData($data)
    {
        \file_put_contents($this->filePath, $data);
        return \file_get_contents($this->filePath);
    }
}