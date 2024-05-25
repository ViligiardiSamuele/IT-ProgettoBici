<?php

class Utente implements JsonSerializable
{
    private $id_utente;
    protected $nome;
    protected $cognome;
    protected $nascita;
    protected $email;

    protected $iscrizioni = array();
    protected $gareGestite = array();

    public function __construct($id_utente)
    {
        $this->id_utente = $id_utente;
        $this->updateInfo();
    }

    public function updateInfo()
    {
        //Dati utente
        $stm = Database::getInstance()->prepare("
            select nome, cognome, nascita, email
            from Utenti
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $this->id_utente, PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetch(PDO::FETCH_ASSOC);
        $this->nome = $stm['nome'];
        $this->cognome = $stm['cognome'];
        $this->nascita = $stm['nascita'];
        $this->email = $stm['email'];

        //Iscrizioni
        $stm = Database::getInstance()->prepare("
            select id_gara
            from Concorrenti
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $this->id_utente, PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stm as $array) {
            foreach ($array as $value) {
                array_push($this->iscrizioni, (int) $value);
            }
        }

        //Gare gestite
        $stm = Database::getInstance()->prepare("
            select id_gara
            from Organizzatori
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $this->id_utente, PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stm as $array) {
            foreach ($array as $value) {
                array_push($this->gareGestite, (int) $value);
            }
        }
    }

    function getId_utente()
    {
        return $this->id_utente;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getCognome()
    {
        return $this->cognome;
    }

    function getNascita()
    {
        return $this->nascita;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getIscrizioni()
    {
        return $this->iscrizioni;
    }

    function getGareGestite()
    {
        return $this->gareGestite;
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
