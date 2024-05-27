import { useEffect, useState } from "react";
import "./Iscrizioni.css";
import ListaGare from "../../components/ListaGare/ListaGare";
import FormIscrizione from "../../components/FormIscrizione/FormIscrizione";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import Card from "react-bootstrap/Card";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import { redirect } from "react-router-dom";

export default function Iscrizioni() {
  const [gareIscritto, setGareIscritto] = useState([]);
  const [gareAperte, setGareAperte] = useState([]);
  const [empty, setEmpty] = useState(false);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare/user`, request);
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    if (json.length > 0) setEmpty(false);
    else setEmpty(true);
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
        <Row>
          <Col sm={6}>
            <h2>Le tue iscrizioni</h2>
            {!empty ? (
              <ListaGare gare={gareIscritto} />
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
      </div>
    </div>
  );
}
