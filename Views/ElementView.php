<?php

namespace Views;

use Controllers\Element,
    Utils\Utils;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class ElementView {

    function __autoload($class_name) {
        include $class_name . '.php';
    }

    public function edit($elementID) {
        $element = Element::getByID($elementID);

        $formAction = "update";
        $btnName = "Изменить";
        $title = "Изменить раздел";
        include("Templates/elementForm.html");
    }

    public function add($sectionID) {
        $element = new Element(null, null, null, $sectionID);
        $formAction = "insert";
        $btnName = "Создать";
        $title = "Создать раздел";
        include("Templates/elementForm.html");
    }

    public function update() {
        $elementID = filter_input(INPUT_POST, 'elementID', FILTER_VALIDATE_INT);
        $elementName = filter_input(INPUT_POST, 'elementName');
        $elementData = filter_input(INPUT_POST, 'elementData');
        $elementType = filter_input(INPUT_POST, 'elementType', FILTER_VALIDATE_INT);

        $element = new Element($elementName, $elementData, $elementType, null);
        $element->id = $elementID;

        $res = $element->save();
        if ($res) {
            Utils::simplePage("Ошибка", "Ошибка обновления: " . $res);
            return false;
        }
        Utils::simplePage("Успех", "Запись успешно изменена");
        return true;
    }

    public function insert() {
        $sectionID = filter_input(INPUT_POST, 'sectionID', FILTER_VALIDATE_INT);
        $elementName = filter_input(INPUT_POST, 'elementName');
        $elementData = filter_input(INPUT_POST, 'elementData');
        $elementType = filter_input(INPUT_POST, 'elementType', FILTER_VALIDATE_INT);

        $element = new Element($elementName, $elementData, $elementType, $sectionID);
        $res = $element->save();
        if ($res) {
            Utils::simplePage("Ошибка", "Ошибка создания записи: " . $res);
            return false;
        }

        Utils::simplePage("Успех", "Запись успешно создана");
        return true;
    }

    public function delete($elementID) {
        $res = Element::delByID($elementID);

        if ($res) {
            Utils::simplePage("Ошибка", "Ошибка удаления: " . $res);
            return false;
        }

        Utils::simplePage("Успех", "Запись успешно удалена");
        return true;
    }

}
