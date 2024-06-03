import "./ListaGare.css";
import Accordion from "react-bootstrap/Accordion";
import ListGroup from "react-bootstrap/ListGroup";
import Button from "react-bootstrap/Button";
import { Link } from "react-router-dom";
import { redirect } from "react-router-dom";

export default function ListaGare({
  gare,
  mostraModifica = false,
  mostraElimina = false,
  mostraDisiscrivi = false,
  mostraStatistiche = false,
}) {
  async function elimina(id_gara) {
    const request = {
      method: "DELETE",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/elimina`,
      request
    );
    if (response.status == 401) return redirect("/login");
    window.location.reload();
  }

  async function disiscrivi(id_gara) {
    const request = {
      method: "DELETE",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/disiscriviConcorrente/${localStorage.getItem(
        "id_utente"
      )}`,
      request
    );
    if (response.status == 401) return redirect("/login");
    window.location.reload();
  }
  

  return (
    <>
      <Accordion>
        {gare.map((gara) => (
          <Accordion.Item eventKey={gara.id_gara} key={gara.id_gara}>
            <Accordion.Header>
              ID: {gara.id_gara} - Nome: {gara.nome}
            </Accordion.Header>
            <Accordion.Body>
              <ListGroup>
                <ListGroup.Item>
                  Concorrenti: {gara.concorrenti.length}
                  {gara.maxConcorrenti > 0 && "/" + gara.maxConcorrenti + ""}
                </ListGroup.Item>
                <ListGroup.Item>
                  Età minima: {gara.minEta == -1 ? "No" : gara.minEta + " anni"}
                </ListGroup.Item>
                <ListGroup.Item>
                  Chiusa: {gara.chiusa == 0 ? "No" : "Si"}
                </ListGroup.Item>
                <ListGroup.Item>
                  Organizzatori: {gara.organizzatori.length}
                </ListGroup.Item>
                {(mostraModifica || mostraElimina || mostraDisiscrivi) && (
                  <ListGroup.Item>
                    {mostraModifica && (
                      <Link to={`/gare/${gara.id_gara}/modifica`}>
                        <Button variant="primary" className="me-2">
                          Modifica
                        </Button>
                      </Link>
                    )}
                    {mostraElimina && (
                      <Button
                        variant="danger"
                        onClick={() => elimina(gara.id_gara)}
                        className="me-2"
                      >
                        Elimina
                      </Button>
                    )}
                    {mostraStatistiche && (
                      <Link to={`/gara/${gara.id_gara}`}>
                        <Button variant="primary" className="me-2">
                          Statistiche
                        </Button>
                      </Link>
                    )}
                    {mostraDisiscrivi && (
                      <Button
                        variant="primary"
                        onClick={() => disiscrivi(gara.id_gara)}
                        className="me-2"
                      >
                        Disiscriviti
                      </Button>
                    )}
                  </ListGroup.Item>
                )}
              </ListGroup>
            </Accordion.Body>
          </Accordion.Item>
        ))}
      </Accordion>
    </>
  );
}
