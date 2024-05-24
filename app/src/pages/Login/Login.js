import "./Login.css";

import Card from "react-bootstrap/Card";
import Container from "react-bootstrap/esm/Container";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { useState } from "react";
import { Link } from "react-router-dom";
import { Navigate } from "react-router-dom";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  async function login(event) {
    event.preventDefault();

    const request = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email: email, password: password }),
      credentials: "include",
    };

    try {
      const response = await fetch(`http://localhost:8080/login`, request);
      if (!response.ok) throw new Error("Network response was not ok");
      const data = await response.json();
      localStorage.setItem("id_utente", data.id_utente);
    } catch (error) {
      console.error("Errore durante il login:", error);
    }
    window.location.reload();
  }

  return (
    <div className="App">
      {localStorage.getItem("id_utente") != null && (
        <Navigate to="/" replace={true} />
      )}
      <div className="position-absolute top-50 start-50 translate-middle larghezza">
        <div className="background rounded p-1">
          <Card className="p-3">
            <Form onSubmit={login}>
              <Form.Group className="mb-3">
                <Form.Label>Email</Form.Label>
                <Form.Control
                  type="email"
                  placeholder="example@gmail.com"
                  required
                  value={email}
                  onChange={(e) => {
                    setEmail(e.target.value);
                  }}
                />
              </Form.Group>

              <Form.Group className="mb-3">
                <Form.Label>Password</Form.Label>
                <Form.Control
                  type="password"
                  required
                  value={password}
                  onChange={(e) => {
                    setPassword(e.target.value);
                  }}
                />
              </Form.Group>
              <Button variant="primary" type="submit">
                Accedi
              </Button>

              <Link to="/">
                <Button variant="secondary" className="ms-2">
                  Indietro
                </Button>
              </Link>
            </Form>
          </Card>
        </div>
      </div>
    </div>
  );
}
