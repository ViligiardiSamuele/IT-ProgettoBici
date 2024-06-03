import { useEffect, useState } from "react";
import "./Gara.css";
import { useParams } from "react-router-dom";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import { redirect } from "react-router-dom";
import Card from "react-bootstrap/Card";
import Table from "react-bootstrap/Table";
import Alert from "react-bootstrap/Alert";
import ListGroup from "react-bootstrap/ListGroup";

export default function Gara() {
  const { id_gara } = useParams();
  const [gara, setGara] = useState([]);
  const [concorrenti, setConcorrenti] = useState([]);

  async function loadGare() {
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
    setGara(json);
    setConcorrenti(json["concorrenti"]);
  }

  useEffect(() => {
    loadGare();
  }, []);

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        <Card className="p-2">
          <Card className="p-2 mt-2 w-50 mx-auto border-0">
            <ListGroup variant="flush">
              <ListGroup.Item>
                ID: {gara.id_gara}
              </ListGroup.Item>
              <ListGroup.Item>
                Nome: {gara.nome}
              </ListGroup.Item>
              <ListGroup.Item>
                Concorrenti: {concorrenti.length} {gara.maxConcorrenti != -1 && `/ ${gara.maxConcorrenti}` }
              </ListGroup.Item>
            </ListGroup>
          </Card>
          <Card className="p-2 mt-2 w-75 mx-auto border-0">
            <h3 className="text-center">Concorrenti</h3>
            {concorrenti.length > 0 ? (
              <Table style={{ maxWidth: "100%" }}>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Cognome</th>
                    <th>Nome</th>
                  </tr>
                </thead>
                <tbody>
                  {concorrenti.map((concorrente) => (
                    <tr key={concorrente.id_utente}>
                      <td>{concorrente.id_utente}</td>
                      <td>{concorrente.cognome}</td>
                      <td>{concorrente.nome}</td>
                    </tr>
                  ))}
                </tbody>
              </Table>
            ) : (
              <Alert variant="success">
                <b>Info</b>: Nessun concorrente è iscritto a questa gara.
              </Alert>
            )}
          </Card>

          <Card className="p-2 mt-2"></Card>
        </Card>
      </div>
    </div>
  );
}
