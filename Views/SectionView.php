<?php

namespace Views;

use Controllers\Section,
    Controllers\Element,
    Utils\Utils;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class SectionView {

    function __autoload($class_name) {
        include $class_name . '.php';
    }

    public function edit($path, $id) {
        $section = Section::getByID($id);
        $formAction = "update";
        $btnName = "Изменить";
        $title = "Изменить раздел";
        include("Templates/sectionForm.html");
    }

    public function add($path, $id = null) {
        $formAction = "insert";
        $btnName = "Создать";
        $title = "Создать раздел";
        include("Templates/sectionForm.html");
    }

    public function update() {
        $sectionName = filter_input(INPUT_POST, 'sectionName');
        $sectionDescription = filter_input(INPUT_POST, 'sectionDescription');
        $section = new Section($sectionName, $sectionDescription, null);
        $section->id = filter_input(INPUT_POST, 'sectionID', FILTER_VALIDATE_INT);

        $res = $section->save();

        if (null == $res) {
            Utils::simplePage("Ошибка", $res);
            return false;
        }

        Utils::simplePage("Успех", "Запись успешно вставлена");
        return true;
    }

    public function insert() {
        $sectionParentID = filter_input(INPUT_POST, 'sectionParentID');

        if (!$sectionParentID) {
            $sectionParentID = null;
        }

        $sectionName = filter_input(INPUT_POST, 'sectionName');
        $sectionDescription = filter_input(INPUT_POST, 'sectionDescription');

        $section = new Section($sectionName, $sectionDescription, $sectionParentID);

        $res = $section->save();

        if (null == $res) {
            Utils::simplePage("Ошибка", $res);
            return false;
        }

        Utils::simplePage("Успех", "Запись успешно вставлена");
        return true;
    }

    public function delete($path, $sectionID) {
        $res = Section::delByID($sectionID);
        if (null == $res) {
            Utils::simplePage("Ошибка", $res);
            return false;
        }

        Utils::simplePage("Успех", "Запись успешно удалена");
        return true;
    }

    public function get() {
        $parentID = filter_input(INPUT_GET, 'id');
        $tab_count = filter_input(INPUT_GET, 'tab_count', FILTER_VALIDATE_INT);
        
        $sections = Section::getByParentID($parentID);
        
        $inner_tab_count = $tab_count + 1;
        $tabs = "";
        for ($i = 0; $i < $tab_count; $i++) {
            $tabs = $tabs . "&nbsp;&nbsp;";
        }

        foreach ($sections as $section) {
            $sectionName = $section['sectionName'];
            $sectionID = $section['sectionID'];
            $div_id = "section-" . $sectionID;

            include("Templates/sectionBlock.html");
        }

        if ($parentID) {
            $elements = Element::getBySectionID($parentID);
            if (count($elements)) {
                foreach($elements as $element){
                    include("Templates/elementBlock.html");
                }
            }
        }
    }

}
