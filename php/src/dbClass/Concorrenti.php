<?php

class Concorrenti implements JsonSerializable
{

    private $id_gara, $id_utenti;

    public function __construct($id_gara)
    {
        $this->recuperoDati();
    }

    private function recuperoDati()
    {
        //Recupero Concorreenti
        $stm = Database::getInstance()->prepare("
            select id_utente
            from Concorrenti
            where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        $id_utenti = [];
        foreach ($stm as $key => $value) {
            array_push($id_utenti, $value['id_utente']);
        }
    }

    function addConcorrenti($id_utenti)
    {
        foreach ($id_utenti as $id) {
            if (!in_array($id, $this->id_utenti)) {
                array_push($this->id_utenti, $id);
            }
        }
    }

    function updateOnDB()
    {
        //selezione gli id nel database
        $stm = Database::getInstance()->prepare("
            select id_utente
            from Concorrenti
            where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        $temp = [];
        foreach ($stm as $key => $value) {
            array_push($temp, $value['id_utente']);
        }

        foreach ($this->id_utenti as $id) {
            if (!in_array($id, $temp)) { //inserisce solo quelli nuovi
                $stm = Database::getInstance()->prepare("
                    insert into Concorrenti (id_gara, id_utente)
                    values (:id_gara, :id_utente)
                ");
                $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
                $stm->bindParam(":id_gara", $id, PDO::PARAM_INT);
                $stm->execute();
            }
        }
        return $stm;
    }

    public function jsonSerialize()
    {
        $attr = [];
        foreach (get_class_vars(get_class($this)) as $name => $value) {
            $attr[$name] = $this->{$name};
        }
        return $attr;
    }
}
