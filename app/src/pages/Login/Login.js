import "./Login.css";

import Card from "react-bootstrap/Card";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { useState } from "react";
import { Link } from "react-router-dom";
import Alert from "react-bootstrap/Alert";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [msg, setMsg] = useState("");
  const [mostraPassword, setMostraPassword] = useState(true);
  const [type, setType] = useState("password");

  async function login(event) {
    event.preventDefault();
    setMsg("");

    const request = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email: email, password: password }),
      credentials: "include",
    };

    try {
      const response = await fetch(
        `http://localhost:8080/session/login`,
        request
      );
      const data = await response.json();
      localStorage.setItem("id_utente", data.id_utente);
      window.location = "/";
    } catch (error) {
      console.error("Errore durante il login:", error);
      setMsg("Verifica di aver inserito correttamente email e password");
    }
  }

  return (
    <div className="App">
      <div
        className="position-absolute top-50 start-50 translate-middle w-100"
        style={{ maxWidth: 450 }}
      >
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
                  type={type}
                  required
                  value={password}
                  onChange={(e) => {
                    setPassword(e.target.value);
                  }}
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Check
                  type="checkbox"
                  label="Mostra la Password"
                  className="mt-2"
                  onChange={() => {
                    setMostraPassword(!mostraPassword);
                    if (mostraPassword) setType("text");
                    else setType("password");
                  }}
                />
              </Form.Group>
              {msg != "" && (
                <Alert variant="warning" className="mt-2">
                  <p>{msg}</p>
                </Alert>
              )}
              <Button variant="primary" type="submit">
                Accedi
              </Button>

              <Link to="/signin">
                <Button variant="success" className="ms-2">
                  Registrati
                </Button>
              </Link>
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
