import { useEffect, useState } from "react";
import "./UserInfo.css";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import Table from "react-bootstrap/Table";
import { redirect } from "react-router-dom";

export default function UserInfo() {
  const [user, setUser] = useState("");

  async function loadUserInfo() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/user`, request);
    if (response.status == 401) window.location = '/login';
    const json = await response.json();
    const data = {
      nome: json["nome"],
      cognome: json["cognome"],
      iscrizioni: json["iscrizioni"].length,
      gareGestite: json["gareGestite"].length,
    };
    setUser(data);
  }

  useEffect(() => {
    loadUserInfo();
  }, []);

  return (
    <>
      <Row>
        <Col sm={6} className="d-flex justify-content-start">
          <h3>
            Bentornato{" "}
            <span className="text-info">
              {user.cognome} {user.nome}
            </span>
          </h3>
        </Col>
        <Col sm={6} className="d-flex justify-content-end p-2">
          <Table>
            <thead>
              <tr>
                <th>Informazioni</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  {user.iscrizioni == 0 ? (
                    "Non sei iscrittto a nessuna gara"
                  ) : (
                    <>
                      Sei iscritto a {user.iscrizioni}{" "}
                      {user.iscrizioni > 1 ? "gare" : "gara"}
                    </>
                  )}
                </td>
              </tr>
              <tr>
                <td>
                  {user.gareGestite == 0 ? (
                    "Non sei iscrittto a nessuna gara"
                  ) : (
                    <>
                      Sei moderatore di {user.gareGestite}{" "}
                      {user.gareGestite > 1 ? "gare" : "gara"}
                    </>
                  )}
                </td>
              </tr>
            </tbody>
          </Table>
        </Col>
      </Row>
    </>
  );
}
