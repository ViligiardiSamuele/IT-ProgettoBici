import "./ListaGare.css";
import Accordion from "react-bootstrap/Accordion";
import ListGroup from "react-bootstrap/ListGroup";

export default function ListaGare({ gare }) {
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
                  Concorrenti: {gara.concorrenti.length}/{gara.maxConcorrenti}
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
              </ListGroup>
            </Accordion.Body>
          </Accordion.Item>
        ))}
      </Accordion>
    </>
  );
}
