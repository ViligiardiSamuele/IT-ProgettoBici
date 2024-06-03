<?php
session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller per la gestione delle gare
 */
class GaraController
{
    /**
     * Restituisce in JSON una gara
     */
    public function gara(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $response->getBody()->write(json_encode(new Gara($args['id'])));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    /**
     * Restituisce in JSON tutte le gare
     */
    public function gare(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            select id_gara
            from Gare
        ");
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    /**
     * Restituisce in JSON tutte le gare aperte
     */
    public function gareAperte(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            SELECT g.id_gara
            FROM Gare g
            LEFT JOIN Concorrenti c ON g.id_gara = c.id_gara AND c.id_utente = :id_utente
            WHERE g.chiusa = false AND c.id_utente IS NULL;
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    /**
     * Restituisce in JSON tutte le gare in cui l'utente è iscritto
     */
    public function gareIscritto(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            select id_gara
            from Concorrenti
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    /**
     * Restituisce in JSON tutte le gare in cui l'utente non è inscritto
     */
    public function utenteNonIscritto(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        $stm = Database::getInstance()->prepare("
            SELECT 
                g.id_gara
            FROM 
                Gare g
            LEFT JOIN 
                Concorrenti c ON g.id_gara = c.id_gara AND c.id_utente = :id_utente
            WHERE 
                c.id_utente IS NULL
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);

        $gare = [];
        foreach ($id_gare as $key => $value) {
            array_push($gare, new Gara($value['id_gara']));
        }

        $response->getBody()->write(json_encode($gare), JSON_PRETTY_PRINT);
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    /**
     * Iscrivi l'utente ad una gara
     */
    public function iscriviUtente(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired

        //Controllo esistenza gara
        $stm = Database::getInstance()->prepare("
                select nome from Gare where id_gara = :id_gara
        ");
        $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            //Controllo iscrizione già avvenuta
            $stm = Database::getInstance()->prepare("
                select *
                from Concorrenti
                where id_gara = :id_gara and id_utente = :id_utente
            ");
            $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
            $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
            $stm->execute();
            if ($stm->rowCount() == 0) {
                $gara = new Gara($args["id"]);
                $oggi = new DateTime();
                $utente = new Utente($_SESSION['id_utente']);
                if ($oggi->diff(new DateTime($utente->getNascita()))->y >= $gara->getMinEta()) {
                    $stm = Database::getInstance()->prepare("
                        INSERT INTO Concorrenti (id_gara, id_utente)
                        VALUES (:id_gara, :id_utente)
                    ");
                    $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
                    $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
                    $stm->execute();
                    if ($stm) {
                        $gara->updateInfo();
                        $response->getBody()->write(json_encode([
                            "msg" => "Iscrizione avvenuta con successo", "gara" => $gara
                        ], JSON_PRETTY_PRINT));
                        return $response->withHeader("Content-type", "application/json")->withStatus(200);
                    } else {
                        $response->getBody()->write(json_encode([
                            "msg" => "Errore nell'inserimento della registrazione"
                        ], JSON_PRETTY_PRINT));
                        return $response->withHeader("Content-type", "application/json")->withStatus(500);
                    }
                }
                $response->getBody()->write(json_encode([
                    "msg" => "La gara scelta richiede un' età minima di " . $gara->getMinEta() . " anni"
                ], JSON_PRETTY_PRINT));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
            $response->getBody()->write(json_encode([
                "msg" => "Sei già iscritto a questa gara"
            ], JSON_PRETTY_PRINT));
            return $response->withHeader("Content-type", "application/json")->withStatus(400);
        }
        $response->getBody()->write(json_encode([
            "msg" => "La gara (ID: " . $args['id'] . ") non esiste: Controlla di aver inserito correttamente l'ID"
        ], JSON_PRETTY_PRINT));
        return $response->withHeader("Content-type", "application/json")->withStatus(400);
    }

    /**
     * Restituisce in JSON tutte le gare create dall'utente
     */
    public function gareDellUtente(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"]))
            return $response->withStatus(401); //session expired
        $stm = Database::getInstance()->prepare("
            select id_gara
            from Organizzatori
            where id_utente = :id_utente
        ");
        $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
        $stm->execute();
        if ($stm) {
            if ($stm->rowCount() > 0) {
                $id_gare = $stm->fetchAll(PDO::FETCH_ASSOC);
                $gare = [];
                foreach ($id_gare as $key => $value) {
                    array_push($gare, new Gara($value['id_gara']));
                }
                $response->getBody()->write(json_encode($gare));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(["msg" => "Non sei moderatore di nessuna gara"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
        }
        $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
    }

    /**
     * Crea una gara
     */
    public function creaGara(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }

        $datiNuovaGara = json_decode($request->getBody()->getContents(), true);
        if (!$datiNuovaGara || !isset($datiNuovaGara['nome'])) {
            $response->getBody()->write(json_encode(["msg" => "Dati incompleti"]));
            return $response->withHeader("Content-type", "application/json")->withStatus(400);
        }

        try {
            $db = Database::getInstance();
            $stm = $db->prepare("
                SELECT id_gara
                FROM Gare
                WHERE nome = :nome
            ");
            $stm->bindParam(":nome", $datiNuovaGara['nome'], PDO::PARAM_STR);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                $disable = -1;

                // Inserimento in Gare
                $stm = $db->prepare("
                    INSERT INTO Gare (nome, maxConcorrenti, minEta, chiusa)
                    VALUES (:nome, :maxConcorrenti, :minEta, :chiusa)
                ");
                $stm->bindParam(":nome", $datiNuovaGara['nome'], PDO::PARAM_STR);

                $maxConcorrenti = $datiNuovaGara['maxConcorrenti']['enable'] ? $datiNuovaGara['maxConcorrenti']['value'] : $disable;
                $stm->bindParam(":maxConcorrenti", $maxConcorrenti, PDO::PARAM_INT);

                $minEta = $datiNuovaGara['minEta']['enable'] ? $datiNuovaGara['minEta']['value'] : $disable;
                $stm->bindParam(":minEta", $minEta, PDO::PARAM_INT);

                $chiusa = !$datiNuovaGara['aperta'];
                $stm->bindParam(":chiusa", $chiusa, PDO::PARAM_BOOL);

                $stm->execute();

                // Recupera ID gara appena inserita
                $id_gara = $db->lastInsertId();

                // Inserimento organizzatore in Organizzatori
                $stm = $db->prepare("
                    INSERT INTO Organizzatori (id_gara, id_utente)
                    VALUES (:id_gara, :id_utente)
                ");
                $stm->bindParam(":id_gara", $id_gara, PDO::PARAM_INT);
                $stm->bindParam(":id_utente", $_SESSION['id_utente'], PDO::PARAM_INT);
                $stm->execute();

                $response->getBody()->write(json_encode(["msg" => "Gara inserita correttamente"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(["msg" => "Esiste già una gara con lo stesso nome"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }

    /**
     * Modifica una gara
     */
    public function modificaGara(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }

        $datiGara = json_decode($request->getBody()->getContents(), true);
        if (!$datiGara || !isset($datiGara['nome'])) {
            $response->getBody()->write(json_encode(["msg" => "Dati incompleti"]));
            return $response->withHeader("Content-type", "application/json")->withStatus(400);
        }

        try {
            $disable = -1;

            $db = Database::getInstance();
            $stm = $db->prepare("
                UPDATE Gare
                SET nome = :nome, maxConcorrenti = :maxConcorrenti, minEta = :minEta, chiusa = :chiusa
                WHERE id_gara = :id_gara
            ");
            $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
            $stm->bindParam(":nome", $datiGara['nome'], PDO::PARAM_STR);

            $maxConcorrenti = $datiGara['maxConcorrenti']['enable'] ? $datiGara['maxConcorrenti']['value'] : $disable;
            $stm->bindParam(":maxConcorrenti", $maxConcorrenti, PDO::PARAM_INT);

            $minEta = $datiGara['minEta']['enable'] ? $datiGara['minEta']['value'] : $disable;
            $stm->bindParam(":minEta", $minEta, PDO::PARAM_INT);

            $chiusa = !$datiGara['aperta'];
            $stm->bindParam(":chiusa", $chiusa, PDO::PARAM_BOOL);

            $stm->execute();

            if ($stm) {
                $response->getBody()->write(json_encode(["msg" => "Gara aggiornata correttamente"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(["msg" => "Errore nell'aggiornamento dei dati"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }

    /**
     * Elimina una gara
     */
    public function eliminaGara(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }
        try {
            $utente = new Utente($_SESSION["id_utente"]);
            if ($utente->verificaSeModeraGara($args['id'])) {
                $gara = new Gara($args['id']);
                $gara->eliminaGara();
                $response->getBody()->write(json_encode(["msg" => "Gara eliminata correttamente"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(["msg" => "Operazione non consentita"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }

    /**
     * Disiscrivi un concorrente
     */
    public function disiscriviConcorrente(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }
        try {
            $stm = Database::getInstance()->prepare("
                DELETE FROM Concorrenti
                WHERE id_gara = :id_gara && id_utente = :id_utente
            ");
            $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
            $stm->bindParam(":id_utente", $args['id_utente'], PDO::PARAM_INT);
            $stm->execute();
            if ($stm) {
                $response->getBody()->write(json_encode(["msg" => "ok"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }

    /**
     * Rimuovi un organizzatore
     */
    public function rimuoviOrganizzatore(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }
        try {
            $stm = Database::getInstance()->prepare("
                DELETE FROM Organizzatori
                WHERE id_gara = :id_gara && id_utente = :id_utente
            ");
            $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
            $stm->bindParam(":id_utente", $args['id_utente'], PDO::PARAM_INT);
            $stm->execute();
            if ($stm) {
                $response->getBody()->write(json_encode(["msg" => "ok"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(200);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }

    /**
     * Aggiungi un organizzatore
     */
    public function aggiungiOrganizzatore(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION["id_utente"])) {
            return $response->withStatus(401); // session expired
        }
        $organizzatore = json_decode($request->getBody()->getContents(), true);
        try {
            $stm = Database::getInstance()->prepare("
                SELECT id_utente
                FROM Utenti
                WHERE email = :email
            ");
            $stm->bindParam(":email", $organizzatore['email'], PDO::PARAM_STR);
            $stm->execute();
            if ($stm->rowCount() > 0) {
                $id_utente = $stm->fetch(PDO::FETCH_ASSOC)['id_utente'];
                $stm = Database::getInstance()->prepare("
                    INSERT INTO Organizzatori (id_gara, id_utente)
                    VALUES (:id_gara, :id_utente)
                ");
                $stm->bindParam(":id_gara", $args['id'], PDO::PARAM_INT);
                $stm->bindParam(":id_utente", $id_utente, PDO::PARAM_INT);
                $stm->execute();
                if ($stm) {
                    $response->getBody()->write(json_encode(["organizzatore" => new Utente($id_utente)]));
                    return $response->withHeader("Content-type", "application/json")->withStatus(200);
                }
            } else {
                $response->getBody()->write(json_encode(["msg" => "L'email inserita non è presente nel database"]));
                return $response->withHeader("Content-type", "application/json")->withStatus(400);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(["msg" => "Errore nella connessione al database", "error" => $e->getMessage()]));
            return $response->withHeader("Content-type", "application/json")->withStatus(500);
        }
    }
}
