<?php
namespace App\Controllers;

class jsonController{

    public function getJson($id)
    {
        $data = []; //DB::SELECT();



        return [
            "Header" =>$this->getHeader($data),
            "section" => $this->getSection(),
        ];
    }


    private function getHeader($data=null)
    {
        return [
            "template_id"   => $data['template_id'] ? $data['template_id']: NULL,
            "template"=>   "header1.vue",
            "name"=> "",
            "type"=> ""
        ];
    }


    private function getSection($data=null)
    {
        return [
            "template_id"   => $data['template_id'] ? $data['template_id']: NULL,
            "template"=>   "header1.vue",
            "name"=> [
                $this->getName($data['name'])
            ],
            "type"=> ""
        ];
    }


    private function getName($data=null)
    {
        return '';
    }
}