import "./ModificaGara.css";
import React, { useEffect, useState } from "react";
import { redirect } from "react-router-dom";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import Card from "react-bootstrap/Card";
import { useParams } from "react-router-dom";
import FormModificaGara from "../../components/FormModificaGara/FormModificaGara";


export default function ModificaGara() {
  const { id_gara } = useParams();

  async function salvaModifica() {
    const request = {
      method: "PUT",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare/modifica/${id_gara}`, request);
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
  }

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        {localStorage.getItem("id_utente") != null ? (
          <Row>
            <Col sm={12}>
              <h2>Modifica la gara {id_gara}</h2>
              <FormModificaGara id_gara={id_gara}/>
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
