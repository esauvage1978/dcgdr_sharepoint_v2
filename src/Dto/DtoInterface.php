<?php


namespace App\Dto;


interface DtoInterface
{
    public function getWordSearch();
    public function setWordSearch($wordSearch);

    public function getPage();
    public function setPage($page);
}