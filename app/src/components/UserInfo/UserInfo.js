import { useEffect, useState } from "react";
import "./UserInfo.css";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import Table from "react-bootstrap/Table";

export default function UserInfo() {
  const [user, setUser] = useState({
    nome: "samuele",
    cognome: "viligiardi",
    nIscrizioni: 2,
    nGareModerate: 1,
  });

  async function loadUserInfo() {
    const request = {
      method: "GET",
    };

    const response = await fetch(
      `http://localhost:8080/user/${localStorage.getItem("id_utente")}`,
      request
    );
    const json = response.json();
    console.log(json);
  }

  useEffect(() => {
    loadUserInfo();
  });

  return (
    <>
      <Row>
        <Col sm={8} className="d-flex justify-content-start">
          <h3>
            Bentornato{" "}
            <span className="text-info">
              {user.cognome} {user.nome}
            </span>
          </h3>
        </Col>
        <Col sm={4} className="d-flex justify-content-end p-2">
          <Table>
            <thead>
              <tr>
                <th>Informazioni</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  Iscritto a: {user.nIscrizioni}{" "}
                  {user.nIscrizioni > 1 ? "gare" : "gara"}
                </td>
              </tr>
              <tr>
                <td>Moderatore di: {user.nGareModerate} {user.nGareModerate > 1 ? "gare" : "gara"}</td>
              </tr>
            </tbody>
          </Table>
        </Col>
      </Row>
    </>
  );
}
