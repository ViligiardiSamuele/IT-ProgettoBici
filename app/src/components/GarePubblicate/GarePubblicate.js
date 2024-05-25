import { useEffect, useState } from "react";
import "./GarePubblicate.css";
import Accordion from "react-bootstrap/Accordion";
import ListGroup from 'react-bootstrap/ListGroup';

export default function GarePubblicate() {
  const [gare, setGare] = useState([]);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare`, request);
    const json = await response.json();
    setGare(json);
  }

  useEffect(() => {
    loadGare();
  }, []);

  return (
    <Accordion>
      {gare.map((gara) => (
        <Accordion.Item eventKey={gara.id_gara} key={gara.id_gara}>
          <Accordion.Header>Gara: {gara.id_gara} Nome: {gara.nome}</Accordion.Header>
          <Accordion.Body>
            <ListGroup>
              <ListGroup.Item>Massimo concorrenti: {gara.maxConcorrenti}</ListGroup.Item>
              <ListGroup.Item>Età minima: {gara.minEta == -1 ? "No" : gara.minEta + " anni"}</ListGroup.Item>
              <ListGroup.Item>Chiusa: {gara.chiusa == 0 ? "No" : "Si"}</ListGroup.Item>
              <ListGroup.Item>Concorrenti: {gara.concorrenti.join(", ")}</ListGroup.Item>
              <ListGroup.Item>Organizzatori: {gara.organizzatori.join(", ")}</ListGroup.Item>
            </ListGroup>
          </Accordion.Body>
        </Accordion.Item>
      ))}
    </Accordion>
  );
}
