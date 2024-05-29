import "./GareCreate.css";
import React from "react";
import { useState, useEffect } from "react";
import { redirect } from "react-router-dom";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import ListaGare from "../../components/ListaGare/ListaGare";
import Card from "react-bootstrap/Card";
import FormCreazioneGara from "../../components/FormCreazioneGara/FormCreazioneGara";


export default function GareUtente() {

  const [gareCreate, setGareCreate] = useState([]);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare/gestite`, request);
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    setGareCreate(json);
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
              <h2>Le tue gare</h2>
              {gareCreate.length > 0 ? (
                <ListaGare gare={gareCreate} mostraOpzioni={true} />
              ) : (
                <Card className="text-center p-1">
                  Non sei moderatore di nessuna gara
                </Card>
              )}
            </Col>
            <Col sm={6}>
              <h2>Crea una nuova gara</h2>
              <FormCreazioneGara />
            </Col>
          </Row>
        ) : (
          <Card className="m-5 p-5">
            <h1 className="text-center">
              Accedi per visualizzare le gare moderate
            </h1>
          </Card>
        )}
      </div>
    </div>
  );
}
