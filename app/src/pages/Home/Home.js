import "./Home.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop.js";
import Button from "react-bootstrap/esm/Button.js";
import Card from "react-bootstrap/Card";
import UserInfo from "../../components/UserInfo/UserInfo.js";
import { useState } from "react";
import Row from "react-bootstrap/esm/Row.js";
import Col from "react-bootstrap/esm/Col.js";
import { Link } from "react-router-dom";

export default function Home() {
  const [key, setKey] = useState("tutte-le-gare");

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        {localStorage.getItem("id_utente") != null ? (
          <div className="m-2 mt-3 p-2 mx-auto" style={{ maxWidth: 1000 }}>
            <UserInfo />
            <Row style={{ maxWidth: 500 }} className="mx-auto">
              <Col md={6} className="mb-2">
                <Button variant="success">
                  <i className="bi bi-plus-circle"></i>
                  <> Crea una nuova gara</>
                </Button>
              </Col>
            </Row>
          </div>
        ) : (
          <Card className="m-5 p-5">
            <h1 className="text-center">
              Accedi per visualizzare la dashboard
            </h1>
          </Card>
        )}
      </div>
    </div>
  );
}
