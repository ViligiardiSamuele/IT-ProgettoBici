import { useEffect, useState } from "react";
import "./FormModificaGara.css";
import Card from "react-bootstrap/Card";
import { redirect } from "react-router-dom";
import Form from "react-bootstrap/Form";
import Alert from "react-bootstrap/Alert";
import Button from "react-bootstrap/esm/Button";

export default function FormCreazioneGara({ id_gara }) {
  const [nome, setNome] = useState("");
  const [maxConcorrenti, setMaxConcorrenti] = useState("");
  const [minEta, setMinEta] = useState(-1);
  const [aperta, setAperta] = useState(true);

  const [hideControlMaxConcorrenti, setHideControlMaxConcorrenti] =
    useState(true);
  const [hideControlMinEta, setHideControlMinEta] = useState(true);
  const [msg, setMsg] = useState("");

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
    if(json['maxConcorrenti'] != -1)
      setHideControlMaxConcorrenti(false)
    setMaxConcorrenti(json["maxConcorrenti"]);
    if(json['minEta'] != -1)
      setHideControlMinEta(false)
    setMinEta(json["minEta"]);
    setAperta(!json["chiusa"]);
    console.log(json);
  }

  useEffect(() => {
    caricaInfo();
  }, []);

  async function onSubmit(event) {
    event.preventDefault();

    const request = {
      method: "POST",
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

    const response = await fetch(`http://localhost:8080/gare/crea`, request);
    const data = await response.json();
    if (response.status == 401) window.location = "/login";
    setMsg(data["msg"]);
  }

  return (
    <>
      <Card className="p-3">
        <Form onSubmit={onSubmit}>
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
          <Button type="submit" className="mt-2">
            Crea gara
          </Button>
        </Form>
        {msg != "" && (
          <Alert variant="info" className="mt-2">
            <p>
              <b>Info</b>: {msg}
            </p>
          </Alert>
        )}
      </Card>
    </>
  );
}
