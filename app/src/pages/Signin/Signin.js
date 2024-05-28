import "./Signin.css";

import Card from "react-bootstrap/Card";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { useState } from "react";
import { Link } from "react-router-dom";
import { Navigate } from "react-router-dom";
import Alert from "react-bootstrap/Alert";

export default function Signin() {
  const [email, setEmail] = useState("");
  const [cognome, setCognome] = useState("");
  const [nome, setNome] = useState("");
  const [nascita, setNascita] = useState("");
  const [password, setPassword] = useState("");
  const [password2, setPassword2] = useState("");

  const [mostraPassword, setMostraPassword] = useState(true);
  const [terminiAccettati, setTerminiAccettati] = useState(false);
  const [type, setType] = useState("password");
  const [msg, setMsg] = useState("");

  async function signin(event) {
    event.preventDefault();

    if (password != password2) {
      setMsg("Le password non sono uguali");
      return;
    }
    setMsg("");

    const request = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        email: email,
        password: password,
        nascita: nascita,
        email: email,
        password: password,
      }),
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/session/signin`,
      request
    );
    const data = await response.json();
    if (!response.ok && response.status != 200) {
      setMsg(data['msg']);
      return;
    }
    localStorage.setItem("id_utente", data.id_utente);
    window.location = "/";
  }

  return (
    <div className="App">
      <div className="mx-auto my-3" style={{ maxWidth: 450 }}>
        <div className="background rounded p-1">
          <Card className="p-3">
            <Form onSubmit={signin}>
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
                <Form.Label>Cognome</Form.Label>
                <Form.Control
                  type="text"
                  required
                  value={cognome}
                  onChange={(e) => {
                    setCognome(e.target.value);
                  }}
                />
              </Form.Group>

              <Form.Group className="mb-3">
                <Form.Label>Nome</Form.Label>
                <Form.Control
                  type="text"
                  required
                  value={nome}
                  onChange={(e) => {
                    setNome(e.target.value);
                  }}
                />
              </Form.Group>

              <Form.Group className="mb-3">
                <Form.Label>Data di Nascita</Form.Label>
                <Form.Control
                  type="date"
                  required
                  value={nascita}
                  onChange={(e) => {
                    setNascita(e.target.value);
                  }}
                  style={{ maxWidth: 150 }}
                  min="1900-01-01"
                  max={new Date().toISOString().split("T")[0]}
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
                <Form.Label>Conferma la Password</Form.Label>
                <Form.Control
                  type={type}
                  required
                  value={password2}
                  onChange={(e) => {
                    setPassword2(e.target.value);
                  }}
                />
              </Form.Group>

              <Form.Group className="mb-3">
                <Form.Check
                  type="checkbox"
                  label="Mostra le Password"
                  className="mt-2"
                  onChange={() => {
                    setMostraPassword(!mostraPassword);
                    if (mostraPassword) setType("text");
                    else setType("password");
                  }}
                />
              </Form.Group>

              <Form.Group className="mb-3">
                <Form.Check
                  type="checkbox"
                  label="Accetti i termini sotto riportati?"
                  className="mt-2"
                  required
                  onChange={() => {
                    setTerminiAccettati(!terminiAccettati);
                  }}
                />
              </Form.Group>
              {msg != "" && (
                <Alert variant="info" className="mt-2">
                  <p>
                    <b>Info</b>: {msg}
                  </p>
                </Alert>
              )}
              <Button variant="success" type="submit">
                Registrati
              </Button>
              <Link to="/login">
                <Button variant="primary" className="ms-2">
                  Accedi
                </Button>
              </Link>
              <Link to="/">
                <Button variant="secondary" className="ms-2">
                  Indietro
                </Button>
              </Link>
            </Form>
          </Card>
          <Card className="p-3 mt-1">
            <h4>Utilizzo dei dati, termini e condizioni</h4>
            <p>
              Il sito utilizza i dati forniti per consentire ai moderatori delle
              gare di applicare restrizioni, per esempio un limite di età.
              <ol>
                <li>
                  <b>Email:</b>
                  <br />
                  Nel caso sia necessaria una comunicazione importate sarà
                  utilizzato l'indirizzo email fornito al momento della
                  registrazione. Nel caso sia inserito un indirizzo email usa e
                  getta, e che quindi la comunicazione con l'utente sia
                  impossibilitata, ogni conseguenza sarà a carico dell'utente.
                </li>
                <li>
                  <b>Nome e Cognome:</b>
                  <br />
                  Vengono utilizzati per l'assegnazione del numero univoco di
                  ogni ciclista nel momento in cui avviene l'iscrizione ad una
                  gara.
                </li>
                <li>
                  <b>Data di Nascita:</b>
                  <br />
                  Viene confrontata con la minima età da rispettare per poter
                  partecipare ad una gara. L'età minima è decisa dai moderatori
                  della gara.
                </li>
              </ol>
            </p>
          </Card>
        </div>
      </div>
    </div>
  );
}
