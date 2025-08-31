<?php

namespace Modules\Tracker\Tables;

use Illuminate\Support\Collection;

abstract class Table
{
    abstract protected function newQuery();

    abstract protected function getColumns();

    public static function make(){
        return (new static);
    }

    public static function render(){
        return self::make()->run();
    }
    private function hasMethod($methodName): bool
    {
        return method_exists($this, "get$methodName");
    }

    private function hasContentMethod($methodName){
        return method_exists($this, "get{$methodName}Content");
    }
    private function getMethod($methodName, $row){
        return $this->{"get$methodName"}($row);
    }

    private function getContentMethod($methodName, $row) {
        return $this->{"get{$methodName}Content"}($row);
    }

    private function getExecutableName($column){
        if (str_contains($column, '.')) {
            return implode("", array_map("ucfirst", explode('.', $column)));
        }
        return ucfirst($column);
    }
    private function getExecutableColumn($column, $row){
        $methodName = $this->getExecutableName($column);
        if ($this->hasMethod($methodName)) {
            return $this->getMethod($methodName, $row);
        }
        if($this->hasContentMethod($methodName)){
            return "<td>" . $this->getContentMethod($methodName, $row) . "</td>";
        }
    }

    private function getColumnOutput($column, $row){
        if (str_contains($column, '.')) {
            $value = \Arr::get($row->toArray(), $column);
        } else {
            $value = $row->{$column};
        }
        return "<td>" . $value . "</td>";
    }

    public function run(){
        $output = '';
        /** @var Collection $row */
        foreach($this->newQuery() as $row) {
            $output .= "<tr id='{$row->id}'>";
            foreach ($this->getColumns() as $column) {
                $executableContent = $this->getExecutableColumn($column, $row);
                if($executableContent){
                    $output .= $executableContent;
                    continue;
                }
                $output .= $this->getColumnOutput($column, $row);
            }
            $output .= "</tr>";
        }
        return $output;
    }
}
