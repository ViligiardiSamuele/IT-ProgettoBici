import { useEffect, useState } from "react";
import "./UserInfo.css";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import Table from "react-bootstrap/Table";

export default function UserInfo() {
  const [user, setUser] = useState("");

  async function loadUserInfo() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/user`, request);
    const json = await response.json();
    const data = {
      nome: json["nome"],
      cognome: json["cognome"],
      iscizioni: json["iscrizioni"].length,
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
                  Sei iscritto a {user.iscizioni}{" "}
                  {user.iscizioni > 1 ? "gare" : "gara"}
                </td>
              </tr>
              <tr>
                <td>
                  Sei moderatore di {user.gareGestite}{" "}
                  {user.gareGestite > 1 ? "gare" : "gara"}
                </td>
              </tr>
            </tbody>
          </Table>
        </Col>
      </Row>
    </>
  );
}
