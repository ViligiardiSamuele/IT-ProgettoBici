import { useEffect, useState } from "react";
import "./Iscrizioni.css";
import ListaGare from "../../components/ListaGare/ListaGare";
import FormIscrizione from "../../components/FormIscrizione/FormIscrizione";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import Card from "react-bootstrap/Card";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";

export default function Iscrizioni() {
  const [gareIscritto, setGareIscritto] = useState([]);
  const [gareAperte, setGareAperte] = useState([]);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare/user`, request);
    if (response.status == 401) window.location = "/login";
    const json = await response.json();
    setGareIscritto(json);

    const response2 = await fetch(`http://localhost:8080/gare/aperte`, request);
    const json2 = await response2.json();
    setGareAperte(json2);
  }

  useEffect(() => {
    loadGare();
  }, []);

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        {localStorage.getItem("id_utente") != null ? (
          <Row>
            <Col sm={6}>
              <h2>Le tue iscrizioni</h2>
              {gareIscritto.length > 0 ? (
                <ListaGare
                  gare={gareIscritto}
                  mostraDisiscrivi={true}
                  mostraStatistiche={true}
                />
              ) : (
                <Card className="text-center p-1">
                  Non sei iscritto a nessuna gara
                </Card>
              )}
            </Col>
            <Col sm={6}>
              <FormIscrizione gare={gareAperte} />
            </Col>
          </Row>
        ) : (
          <Card className="m-5 p-5">
            <h1 className="text-center">
              Accedi per visualizzare le tue iscrizioni
            </h1>
          </Card>
        )}
      </div>
    </div>
  );
}
