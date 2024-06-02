import { useEffect, useState } from "react";
import "./FormModificaGara.css";
import Card from "react-bootstrap/Card";
import { redirect } from "react-router-dom";
import Form from "react-bootstrap/Form";
import Alert from "react-bootstrap/Alert";
import Button from "react-bootstrap/esm/Button";
import Table from "react-bootstrap/Table";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";

export default function FormCreazioneGara({ id_gara }) {
  const [nome, setNome] = useState("");
  const [maxConcorrenti, setMaxConcorrenti] = useState("");
  const [minEta, setMinEta] = useState(-1);
  const [aperta, setAperta] = useState(true);
  const [concorrenti, setConcorrenti] = useState([]);
  const [organizzatori, setOrganizzatori] = useState([]);

  const [hideControlMaxConcorrenti, setHideControlMaxConcorrenti] =
    useState(true);
  const [hideControlMinEta, setHideControlMinEta] = useState(true);
  const [msg, setMsg] = useState("");

  const [emailAggiungiOrganizzatore, setEmailAggiungiOrganizzatore] =
    useState("");
  const [msgAggiungiOrganizzatore, setMsgAggiungiOrganizzatore] = useState("");

  async function caricaInfo() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}`,
      request
    );
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    setNome(json["nome"]);
    if (json["maxConcorrenti"] != -1) setHideControlMaxConcorrenti(false);
    setMaxConcorrenti(json["maxConcorrenti"]);
    if (json["minEta"] != -1) setHideControlMinEta(false);
    setMinEta(json["minEta"]);
    setAperta(json["chiusa"]);
    setConcorrenti(json["concorrenti"]);
    setOrganizzatori(json["organizzatori"]);
  }

  useEffect(() => {
    caricaInfo();
  }, []);

  async function onSubmit(event) {
    event.preventDefault();

    const request = {
      method: "PUT",
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        nome: nome,
        maxConcorrenti: {
          enable: !hideControlMaxConcorrenti,
          value: maxConcorrenti,
        },
        minEta: {
          enable: !hideControlMinEta,
          value: minEta,
        },
        aperta: aperta,
      }),
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/modifica`,
      request
    );
    const data = await response.json();
    if (response.status == 401) window.location = "/login";
    setMsg(data["msg"]);
  }

  //concorrenti
  async function deleteConcorrente(id_utente) {
    const request = {
      method: "DELETE",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/disiscriviConcorrente/${id_utente}`,
      request
    );
    setConcorrenti(
      concorrenti.filter((concorrente) => concorrente.id_utente !== id_utente)
    );
  }

  //organizzatori
  async function addOrganizzatore(event) {
    event.preventDefault();
    setMsgAggiungiOrganizzatore("");
    const request = {
      method: "POST",
      credentials: "include",
      body: JSON.stringify({
        email: emailAggiungiOrganizzatore,
      }),
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/aggiungiOrganizzatore`,
      request
    );
    const data = await response.json();
    if (response.status == 200) {
      setOrganizzatori((organizzatori) => [
        ...organizzatori,
        data["organizzatore"],
      ]);
    } else {
      const data = await response.json();
      setMsgAggiungiOrganizzatore(data["msg"]);
    }
  }

  async function deleteOrganizzatore(id_utente) {
    const request = {
      method: "DELETE",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/${id_gara}/rimuoviOrganizzatore/${id_utente}`,
      request
    );
    setOrganizzatori(
      organizzatori.filter(
        (organizzatore) => organizzatore.id_utente !== id_utente
      )
    );
  }

  return (
    <>
      <Card className="p-1" style={{ minWidth: 700 }}>
        <Card className="p-2">
          <Form onSubmit={onSubmit}>
            <Button type="submit" variant="success" className="mt-2">
              Salva modifiche
            </Button>
            {msg != "" ? (
              <Alert variant="info" className="my-2">
                <p>
                  <b>Info</b>: {msg}
                </p>
              </Alert>
            ) : (
              <br />
            )}
            <Form.Label>Nome</Form.Label>
            <Form.Control
              type="text"
              value={nome}
              required
              onChange={(e) => {
                setNome(e.target.value);
              }}
              placeholder=""
            />
            <Form.Check
              type="checkbox"
              label="Massimo concorrenti"
              checked={!hideControlMaxConcorrenti}
              className="mt-2"
              onChange={() => {
                setHideControlMaxConcorrenti(!hideControlMaxConcorrenti);
              }}
            />
            <Form.Control
              disabled={hideControlMaxConcorrenti}
              type="number"
              required={!hideControlMaxConcorrenti}
              min={"2"}
              placeholder="minimo 2 concorrenti"
              value={maxConcorrenti}
              onChange={(e) => {
                setMaxConcorrenti(e.target.value);
              }}
            />
            <Form.Check
              type="checkbox"
              label="Età minima"
              className="mt-2"
              checked={!hideControlMinEta}
              onChange={() => {
                setHideControlMinEta(!hideControlMinEta);
              }}
            />
            <Form.Control
              disabled={hideControlMinEta}
              type="number"
              required={!hideControlMinEta}
              min={"1"}
              placeholder=""
              value={minEta}
              onChange={(e) => {
                setMinEta(e.target.value);
              }}
            />
            <Form.Check
              type="checkbox"
              label={"Apri le iscrizioni"}
              checked={aperta}
              className="mt-2"
              value={aperta}
              onChange={(e) => {
                setAperta(!aperta);
              }}
            />
          </Form>
        </Card>
        <Card className="mt-2 p-2">
          <h3 className="text-center"><i>Organizzatori</i></h3>
          <Form
            onSubmit={addOrganizzatore}
            className="mx-auto"
            style={{ minWidth: 600 }}
          >
            <Row className="align-items-center">
              <Col>
                <Form.Control
                  type="email"
                  required
                  placeholder="Email dell'utente"
                  value={emailAggiungiOrganizzatore}
                  onChange={(e) => {
                    setEmailAggiungiOrganizzatore(e.target.value);
                  }}
                />
              </Col>
              <Col>
                <Button type="submit" variant="success" className="">
                  Aggiungi
                </Button>
              </Col>
            </Row>
            {msgAggiungiOrganizzatore != "" && (
              <Row>
                <Col className="mt-1">
                  <Alert variant="info">
                    <p>
                      <b>Info</b>: {msgAggiungiOrganizzatore}
                    </p>
                  </Alert>
                </Col>
              </Row>
            )}
          </Form>
          <Table striped style={{ maxWidth: "100%" }}>
            <thead>
              <tr>
                <th>ID</th>
                <th>Cognome</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Opzioni</th>
              </tr>
            </thead>
            <tbody>
              {organizzatori.map((organizzatore) => (
                <tr key={organizzatore.id_utente}>
                  <td>{organizzatore.id_utente}</td>
                  <td>{organizzatore.cognome}</td>
                  <td>{organizzatore.nome}</td>
                  <td>{organizzatore.email}</td>
                  <td>
                    <Button
                      variant="danger"
                      onClick={() =>
                        deleteOrganizzatore(organizzatore.id_utente)
                      }
                    >
                      Rimuovi
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        </Card>
        <Card className="mt-2 p-2">
          <h3 className="text-center"><i>Concorrenti</i></h3>
          <Table striped style={{ maxWidth: "100%" }}>
            <thead>
              <tr>
                <th>ID</th>
                <th>Cognome</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Nascita</th>
                <th>Opzioni</th>
              </tr>
            </thead>
            <tbody>
              {concorrenti.map((concorrente) => (
                <tr key={concorrente.id_utente}>
                  <td>{concorrente.id_utente}</td>
                  <td>{concorrente.cognome}</td>
                  <td>{concorrente.nome}</td>
                  <td>{concorrente.email}</td>
                  <td>{concorrente.nascita}</td>
                  <td>
                    <Button
                      variant="danger"
                      onClick={() => deleteConcorrente(concorrente.id_utente)}
                    >
                      Rimuovi
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
          {concorrenti.length == 0 && (
            <Alert variant="success">
              <b>Info</b>: Nessun concorrente è iscritto a questa gara.
            </Alert>
          )}
        </Card>
      </Card>
    </>
  );
}
