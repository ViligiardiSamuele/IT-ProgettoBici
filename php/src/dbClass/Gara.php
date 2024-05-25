<?php

class Gara implements JsonSerializable
{
    private $id_gara;
    protected $nome;
    protected $maxConcorrenti;
    protected $minEta;
    protected $chiusa;

    protected $concorrenti = array();
    protected $organizzatori = array();

    public function __construct($id_gara)
    {
        $this->id_gara = $id_gara;
        $this->updateInfo();
    }

    public function updateInfo()
    {
        //Recupero dati gara
        $stm = Database::getInstance()->prepare("
            select *
            from Gare
            where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();
        $stm = $stm->fetch(PDO::FETCH_ASSOC);
        $this->nome = $stm['nome'];
        $this->maxConcorrenti = $stm['maxConcorrenti'];
        $this->minEta = $stm['minEta'];
        $this->chiusa = $stm['chiusa'];

        //Recupero concorrenti
        $stm = Database::getInstance()->prepare("
            select id_utente
            from Concorrenti
            where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stm as $array) {
            foreach ($array as $value) {
                array_push($this->concorrenti, (int) $value);
            }
        }

        //Recupero organizzatori
        $stm = Database::getInstance()->prepare("
            select id_utente
            from Organizzatori
            where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stm as $array) {
            foreach ($array as $value) {
                array_push($this->organizzatori, (int) $value);
            }
        }
    }

    public function jsonSerialize()
    {
        $attr = [];
        foreach (get_class_vars(get_class($this)) as $name => $value) {
            $attr[$name] = $this->{$name};
        }
        return $attr;
    }

    public function getIdGara()
    {
        return $this->id_gara;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getMaxConcorrenti()
    {
        return $this->maxConcorrenti;
    }

    public function getMinEta()
    {
        return $this->minEta;
    }

    public function getChiusa()
    {
        return $this->chiusa;
    }

    public function getConcorrenti()
    {
        return $this->concorrenti;
    }

    public function getOrganizzatori()
    {
        return $this->organizzatori;
    }
}
