import { useEffect, useState } from "react";
import "./FormIscrizione.css";
import Card from "react-bootstrap/Card";
import { redirect } from "react-router-dom";
import Form from "react-bootstrap/Form";
import Button from "react-bootstrap/esm/Button";

export default function FormIscrizione({ gare }) {
  const [user, setUser] = useState();
  const [gara, setGara] = useState();
  const [errorMsg, setErrorMsg] = useState("");

  async function getUserData() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/user`, request);
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    setUser(json);
  }

  async function onSubmit(event) {
    event.preventDefault();

    const request = {
      method: "POST",
      credentials: "include",
    };

    const response = await fetch(
      `http://localhost:8080/gare/iscriviti/${gara}`,
      request
    );
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    setUser(json);
  }

  useEffect(() => {
    getUserData();
  }, []);

  return (
    <>
      <h2>Iscriviti ad una gara</h2>
      <Card className="p-1">
        {errorMsg != "" ? (
          <p className="text-center">{errorMsg}</p>
        ) : (
          <p className="text-center">Seleziona una gara dove iscriverti</p>
        )}
        <Form onSubmit={onSubmit}>
          <Form.Select
            required
            name="gara"
            onChange={(e) => {
              setGara(e.target.value);
            }}
          >
            {gare.map((gara) => (
              <option value={gara.id_gara}>
                ID:{gara.id_gara} - {gara.nome}
              </option>
            ))}
          </Form.Select>
          <Button type="submit" className="mt-1">
            Iscriviti
          </Button>
        </Form>
      </Card>
    </>
  );
}
