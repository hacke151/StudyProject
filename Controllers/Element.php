<?php

namespace Controllers;

use Utils\Utils;

class Element {

    public $id;
    public $name;
    public $type;
    public $data;
    public $createDate;
    public $updateDate;
    public $sectionID;

    public static function getByID($id) {
        $stmt = Utils::getConnect()->prepare("SELECT * FROM elements WHERE elementID = :elementID");
        $stmt->execute(array("elementID" => $id));
        $res = $stmt->fetch();

        $element = new self($res["elementName"], $res["elementData"], $res["elementType"], $res["sectionID"]);
        $element->id = $id;
        $element->createDate = $res["elementCreateDate"];
        $element->updateDate = $res["elementUpdateDate"];

        return $element;
    }

    function __construct($elementName, $elementData, $elementType, $sectionID) {
        $this->sectionID = $sectionID;
        $this->name = $elementName;
        $this->data = $elementData;
        $this->type = $elementType;
    }

    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    private function insert() {
        $stmt = Utils::getConnect()->prepare("INSERT INTO elements (sectionID, elementName, elementData, elementType) VALUES (:sectionID, :elementName, :elementData, :elementType)");

        try {
            $stmt->execute(array('sectionID' => $this->sectionID,
                'elementName' => $this->name,
                'elementData' => $this->data,
                'elementType' => $this->type));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmt->errorInfo());
        }
        $this->id = Utils::getConnect()->lastInsertId();
    }

    private function update() {
        $stmt = Utils::getConnect()->prepare("UPDATE elements SET elementName = :elementName, elementData = :elementData, elementType = :elementType WHERE elementID = :elementID");

        try {
            $stmt->execute(array('elementID' => $this->id,
                'elementName' => $this->name,
                'elementData' => $this->data,
                'elementType' => $this->type));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmt->errorInfo());
        }
    }

    public static function delByID($id) {
        $stmt = Utils::getConnect()->prepare("DELETE FROM elements WHERE elementID = :ID");

        try {
            $stmt->execute(array('ID' => $id));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmt->errorInfo());
        }

        return false;
    }

    public static function getBySectionID($sectionID) {
        $stmt = Utils::getConnect()->prepare("SELECT * FROM elements WHERE sectionID = :sectionID");
        $stmt->execute(array('sectionID' => $sectionID));

        return $stmt->fetchAll();
    }
}
