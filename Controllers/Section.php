<?php

namespace Controllers;

use Utils\Utils;

class Section {

    public $id;
    public $name;
    public $description;
    public $parentID;
    public $createDate;
    public $updateDate;

    function __construct($name, $description, $parentID) {
        $this->name = $name;
        $this->description = $description;
        $this->parentID = $parentID;
    }

    public static function getByID($id) {
        $stmt = Utils::getConnect()->prepare("SELECT * FROM sections WHERE sectionID = :sectionID");
        $stmt->execute(array('sectionID' => $id));
        $res = $stmt->fetch();

        $section = new self($res["sectionName"], $res["sectionDescription"], $res["sectionParentID"]);
        $section->id = $id;
        $section->createDate = $res["sectionCreateDate"];
        $section->updateDate = $res["sectionUpdateDate"];
        return $section;
    }

    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    private function insert() {
        $stmt = Utils::getConnect()->prepare("INSERT INTO sections (sectionParentID, sectionName, sectionDescription) VALUES (:sectionParentID, :sectionName, :sectionDescription)");

        try {
            $stmt->execute(array('sectionName' => $this->name,
                'sectionDescription' => $this->description,
                'sectionParentID' => $this->parentID));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmt->errorInfo());
        }
        $this->id = Utils::getConnect()->lastInsertId();
    }

    private function update() {
        $stmt = Utils::getConnect()->prepare("UPDATE sections SET sectionName = :sectionName, sectionDescription = :sectionDescription WHERE sectionID = :sectionID");

        try {
            $stmt->execute(array('sectionName' => $this->name,
                'sectionDescription' => $this->description,
                'sectionID' => $this->id));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmt->errorInfo());
        }
    }

    public static function delByID($id) {
        $stmtCntSec = Utils::getConnect()->prepare("SELECT COUNT(*) as cnt FROM sections WHERE sectionParentID = :sectionParentID");
        $stmtCntSec->execute(array('sectionParentID' => $id));

        if ($stmtCntSec->fetch()['cnt'] > 0) {
            return "Нельзя удалить раздел в котором имеются подразделы";
        }

        $stmtCntElm = Utils::getConnect()->prepare("SELECT COUNT(1) as cnt FROM elements WHERE sectionID = :sectionID");
        $stmtCntElm->execute(array('sectionID' => $id));

        if ($stmtCntElm->fetch()['cnt'] > 0) {
            return "Нельзя удалить раздел в котором имеются элементы";
        }

        $stmtDelSec = Utils::getConnect()->prepare("DELETE FROM sections WHERE sectionID = :sectionID");
        try {
            $stmtDelSec->execute(array('sectionID' => $id));
        } catch (Exception $ex) {
            return $ex . "\n" . print_r($stmtDelSec->errorInfo());
        }
    }

    public static function getByParentID($parentID) {
        if (!$parentID) {
            $stmt = Utils::getConnect()->prepare("SELECT * FROM sections WHERE sectionParentID is null");
        } else {
            $stmt = Utils::getConnect()->prepare("SELECT * FROM sections WHERE sectionParentID = :sectionParentID");
        }
        
        $stmt->execute(array('sectionParentID' => $parentID));
        
        return $stmt->fetchAll();
    }

}
