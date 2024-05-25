import { useEffect, useState } from "react";
import "./Iscrizioni.css";
import Accordion from "react-bootstrap/Accordion";
import ListGroup from "react-bootstrap/ListGroup";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import Card from "react-bootstrap/Card";

export default function Iscrizioni() {
  const [gare, setGare] = useState([]);
  const [empty, setEmpty] = useState(false);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare/user`, request);
    const json = await response.json();
    {
      json.length > 0 ? setEmpty(false) : setEmpty(true);
    }
    setGare(json);
  }

  useEffect(() => {
    loadGare();
  }, []);

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        {localStorage.getItem("id_utente") != null ? (
          <>
            {!empty ? (
              <>
                <h2>Le tue iscrizioni</h2>
                <Accordion>
                  {gare.map((gara) => (
                    <Accordion.Item eventKey={gara.id_gara} key={gara.id_gara}>
                      <Accordion.Header>
                        ID: {gara.id_gara} - Nome: {gara.nome}
                      </Accordion.Header>
                      <Accordion.Body>
                        <ListGroup>
                          <ListGroup.Item>
                            Concorrenti: {gara.concorrenti.length}/
                            {gara.maxConcorrenti}
                          </ListGroup.Item>
                          <ListGroup.Item>
                            Età minima:{" "}
                            {gara.minEta == -1 ? "No" : gara.minEta + " anni"}
                          </ListGroup.Item>
                          <ListGroup.Item>
                            Chiusa: {gara.chiusa == 0 ? "No" : "Si"}
                          </ListGroup.Item>
                          <ListGroup.Item>
                            Organizzatori: {gara.organizzatori.length}
                          </ListGroup.Item>
                        </ListGroup>
                      </Accordion.Body>
                    </Accordion.Item>
                  ))}
                </Accordion>
              </>
            ) : (
              <Card className="text-center p-1">
                Non sei iscritto a nessuna gara
              </Card>
            )}
          </>
        ) : (
          <Card className="m-5 p-5">
            <h1 className="text-center">Accedi per visualizzare le gare</h1>
          </Card>
        )}
      </div>
    </div>
  );
}
