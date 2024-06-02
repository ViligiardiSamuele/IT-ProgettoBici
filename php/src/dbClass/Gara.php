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
        $this->recuperoDati();
        $this->recuperoConcorrenti();
        $this->recuperoOrganizzatori();
    }

    private function recuperoDati()
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
    }

    private function recuperoConcorrenti()
    {
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
                array_push($this->concorrenti, new Utente((int) $value));
            }
        }
    }

    private function recuperoOrganizzatori()
    {
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
                array_push($this->organizzatori, new Utente((int) $value));
            }
        }
    }

    public function updateOnDB()
    {
        $stm = Database::getInstance()->prepare("
            UPDATE Gare
            SET nome = :nome, maxConcorrenti = :maxConcorrenti, minEta = :minEta, chiusa = :chiusa
            WHERE id_gara = :id_gara
        ");
        $stm->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stm->bindParam(":maxConcorrenti", $maxConcorrenti, PDO::PARAM_INT);
        $stm->bindParam(":minEta", $minEta, PDO::PARAM_STR);
        $stm->bindParam(":chiusa", $chiusa, PDO::PARAM_BOOL);
        $stm->bindParam(":id_gara", $id_gara, PDO::PARAM_INT);
        $stm->execute();
        return $stm;
    }

    public function eliminaGara()
    {
        $stm = Database::getInstance()->prepare("
            DELETE FROM Concorrenti
            WHERE id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();

        $stm = Database::getInstance()->prepare("
            DELETE FROM Organizzatori
            WHERE id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();

        $stm = Database::getInstance()->prepare("
            DELETE FROM Gare
            WHERE id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $this->id_gara, PDO::PARAM_INT);
        $stm->execute();
    }

    public function jsonSerialize()
    {
        $attr = [];
        foreach (get_class_vars(get_class($this)) as $name => $value) {
            $attr[$name] = $this->{$name};
        }
        return $attr;
    }

    /**
     * Get the value of id_gara
     */
    public function getIdGara()
    {
        return $this->id_gara;
    }

    /**
     * Set the value of id_gara
     */
    public function setIdGara($id_gara): self
    {
        $this->id_gara = $id_gara;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     */
    public function setNome($nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of maxConcorrenti
     */
    public function getMaxConcorrenti()
    {
        return $this->maxConcorrenti;
    }

    /**
     * Set the value of maxConcorrenti
     */
    public function setMaxConcorrenti($maxConcorrenti): self
    {
        $this->maxConcorrenti = $maxConcorrenti;

        return $this;
    }

    /**
     * Get the value of minEta
     */
    public function getMinEta()
    {
        return $this->minEta;
    }

    /**
     * Set the value of minEta
     */
    public function setMinEta($minEta): self
    {
        $this->minEta = $minEta;

        return $this;
    }

    /**
     * Get the value of chiusa
     */
    public function getChiusa()
    {
        return $this->chiusa;
    }

    /**
     * Set the value of chiusa
     */
    public function setChiusa($chiusa): self
    {
        $this->chiusa = $chiusa;

        return $this;
    }

    /**
     * Get the value of concorrenti
     */
    public function getConcorrenti()
    {
        return $this->concorrenti;
    }

    /**
     * Set the value of concorrenti
     */
    public function setConcorrenti($concorrenti): self
    {
        $this->concorrenti = $concorrenti;

        return $this;
    }

    /**
     * Get the value of organizzatori
     */
    public function getOrganizzatori()
    {
        return $this->organizzatori;
    }

    /**
     * Set the value of organizzatori
     */
    public function setOrganizzatori($organizzatori): self
    {
        $this->organizzatori = $organizzatori;

        return $this;
    }
}
