import { useEffect, useState } from "react";
import "./FormIscrizione.css";
import Card from "react-bootstrap/Card";
import { redirect } from "react-router-dom";
import Form from "react-bootstrap/Form";
import Alert from "react-bootstrap/Alert";
import Button from "react-bootstrap/esm/Button";

export default function FormIscrizione({ gare }) {
  const [gara, setGara] = useState(0);
  const [msg, setMsg] = useState("");

  async function onSubmit(event) {
    event.preventDefault();

    if (gara != 0) {
      const request = {
        method: "POST",
        credentials: "include",
      };

      const response = await fetch(
        `http://localhost:8080/gare/iscriviUtente/${gara}`,
        request
      );
      const data = await response.json();
      if (response.status == 401) return redirect("/login");
      setMsg(data["msg"]);
    }
  }

  return (
    <>
      <h2>Iscriviti ad una gara</h2>
      <Card className="p-3">
        <Form onSubmit={onSubmit}>
          <Form.Select
            required
            name="gara"
            onChange={(e) => {
              setGara(e.target.value);
            }}
          >
            <option selected value="0" key="0">
              Seleziona una gara
            </option>
            {gare.map((gara) => (
              <option value={gara.id_gara} key={gara.id_gara}>
                ID:{gara.id_gara} - {gara.nome}
              </option>
            ))}
          </Form.Select>
          <Button type="submit" className="mt-2">
            Iscriviti
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
