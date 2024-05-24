<?php

class Utente
{
    private string $id_utente;
    private string $nome;
    private string $cognome;
    private string $nascita;
    private string $email;

    private string $iscrizioni;
    private string $gareGestite;

    public function __construct($id_utente)
    {
        $this->id_utente = $id_utente;
        $this->updateInfo();
    }

    public function updateInfo()
    {
        $stm = Database::getInstance()->prepare("
            select id_gara
            from Concorrenti
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $this->id_utente, PDO::PARAM_STR);
        $stm->execute();
        $stm = $stm->fetch(PDO::FETCH_ASSOC);
        $this->nome = $stm['nome'];
        $this->cognome = $stm['cognome'];
        $this->nascita = $stm['nascita'];
        $this->email = $stm['email'];

        $stm = Database::getInstance()->prepare("
            select nome, cognome, nascita, email
            from Utenti
            where id_utente = :id_utente
        ");

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
